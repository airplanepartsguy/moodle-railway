<?php
// 6-month recurring training reset.
// TurbineWorks operates recurring training every 6 months — more rigorous than
// ASA-100's annual minimum. This task implements that cadence by detecting
// completions that have aged past 180 days and resetting the affected users'
// progress in those courses, forcing re-completion.

namespace local_twu\task;

defined('MOODLE_INTERNAL') || die();

class recurring_reset extends \core\task\scheduled_task {

    /** Human-readable task name shown in Site Admin → Server → Scheduled tasks. */
    public function get_name(): string {
        return 'TurbineWorks: 6-month training recurrence reset';
    }

    /**
     * Run the recurrence reset.
     *
     * Scope: courses whose idnumber starts with "TWF4-" (Initial Training).
     * For each user with a course completion older than 180 days in such a
     * course, delete the user's activity completion records and the course
     * completion record so their progress is reset. The user remains enrolled
     * via cohort sync and can re-take the lessons and quiz.
     */
    public function execute(): void {
        global $DB;

        $cutoff = time() - (180 * DAYSECS); // 180 days ago

        // Find completions to reset:
        //   - In Initial Training courses (idnumber LIKE 'TWF4-%')
        //   - timecompleted is not NULL and is older than cutoff
        $expired = $DB->get_records_sql(
            "SELECT cc.id AS ccid, cc.userid, cc.course AS courseid,
                    cc.timecompleted, c.shortname, c.idnumber
               FROM {course_completions} cc
               JOIN {course} c ON c.id = cc.course
              WHERE c.idnumber LIKE 'TWF4-%'
                AND cc.timecompleted IS NOT NULL
                AND cc.timecompleted < :cutoff",
            ['cutoff' => $cutoff]
        );

        if (!$expired) {
            mtrace('[twu_recurring] no expired completions found');
            return;
        }

        $resetcount = 0;
        $userset = [];

        foreach ($expired as $row) {
            // Get all course modules in this course that participate in
            // completion (page lessons + quizzes — same set used at completion).
            $cmids = $DB->get_fieldset_sql(
                "SELECT cm.id
                   FROM {course_modules} cm
                   JOIN {modules} m ON m.id = cm.module
                  WHERE cm.course = :courseid
                    AND cm.completion > 0
                    AND m.name IN ('page', 'quiz')",
                ['courseid' => $row->courseid]
            );

            if ($cmids) {
                list($incmsql, $cmparams) = $DB->get_in_or_equal($cmids, SQL_PARAMS_NAMED, 'cm');
                // Delete activity completion rows for this user / these cms.
                $DB->delete_records_select(
                    'course_modules_completion',
                    "userid = :uid AND coursemoduleid $incmsql",
                    array_merge(['uid' => $row->userid], $cmparams)
                );
                // Delete any quiz attempts so the user can re-attempt fresh.
                $quizids = $DB->get_fieldset_sql(
                    "SELECT q.id
                       FROM {quiz} q
                       JOIN {course_modules} cm ON cm.instance = q.id
                       JOIN {modules} m ON m.id = cm.module AND m.name = 'quiz'
                      WHERE cm.course = :courseid AND cm.id $incmsql",
                    array_merge(['courseid' => $row->courseid], $cmparams)
                );
                if ($quizids) {
                    list($inquizsql, $quizparams) = $DB->get_in_or_equal($quizids, SQL_PARAMS_NAMED, 'quiz');
                    $DB->delete_records_select(
                        'quiz_attempts',
                        "userid = :uid AND quiz $inquizsql",
                        array_merge(['uid' => $row->userid], $quizparams)
                    );
                    $DB->delete_records_select(
                        'quiz_grades',
                        "userid = :uid AND quiz $inquizsql",
                        array_merge(['uid' => $row->userid], $quizparams)
                    );
                }
            }

            // Reset the criteria completions and the overall course completion.
            $DB->delete_records('course_completion_crit_compl', [
                'userid' => $row->userid,
                'course' => $row->courseid,
            ]);
            $DB->delete_records('course_completions', ['id' => $row->ccid]);

            mtrace(sprintf(
                '[twu_recurring] reset userid=%d in %s (completed %s)',
                $row->userid,
                $row->shortname,
                userdate($row->timecompleted, '%Y-%m-%d')
            ));

            $resetcount++;
            $userset[$row->userid] = true;
        }

        mtrace(sprintf(
            '[twu_recurring] reset %d completion(s) across %d user(s)',
            $resetcount,
            count($userset)
        ));
    }
}
