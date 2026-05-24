FROM moodlehq/moodle-php-apache:8.3-bookworm

# Clone the Moodle 4.5 LTS stable branch (tracks latest patch releases) and remove the .git directory to reduce image size
RUN git clone --depth 1 -b MOODLE_405_STABLE https://github.com/moodle/moodle.git /var/www/html \
 && rm -rf /var/www/html/.git

# Bundle plugins TurbineWorks University needs out of the box:
#   - mod_customcert: branded PDF certificates with verification serials (ASA-100 audit evidence)
#   - theme_moove:   modern navy-friendly theme (replaces dated default Boost UI)
RUN git clone --depth 1 -b v4.4.9 https://github.com/markn86/moodle-mod_customcert.git /var/www/html/mod/customcert \
 && rm -rf /var/www/html/mod/customcert/.git \
 && git clone --depth 1 -b MOODLE_405_STABLE https://github.com/willianmano/moodle-theme_moove.git /var/www/html/theme/moove \
 && rm -rf /var/www/html/theme/moove/.git \
 && git clone --depth 1 -b MR-4.5 https://github.com/open-lms-open-source/moodle-theme_snap.git /var/www/html/theme/snap \
 && rm -rf /var/www/html/theme/snap/.git

# Copy the local_twu plugin (TurbineWorks University bootstrap + CLI helpers)
COPY local_twu/ /var/www/html/local/twu/

RUN chown -R www-data:www-data /var/www/html

# Install and configure the runtime entrypoint
COPY railway-entrypoint.sh /usr/local/bin/railway-entrypoint.sh
RUN chmod +x /usr/local/bin/railway-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/railway-entrypoint.sh"]
