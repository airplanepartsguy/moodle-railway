"""Generate branded placeholder course title cards for TurbineWorks University.

Run from the repo root to (re)generate the 8 PNG cards used as course
overview images. The Moodle bootstrap (local_twu/cli/bootstrap.php) attaches
these to each course's overviewfiles filearea after creating the course.

Replace the output PNGs later with photorealistic AI-generated images if
desired; the bootstrap will pick up whatever's on disk at the same filename.
"""

from __future__ import annotations

from pathlib import Path
from PIL import Image, ImageDraw, ImageFont

OUT_DIR = Path(__file__).parent.parent / "local_twu" / "assets" / "courses"
OUT_DIR.mkdir(parents=True, exist_ok=True)

# TurbineWorks brand
NAVY = (13, 34, 64)
NAVY_LIGHT = (28, 58, 100)
GOLD = (255, 200, 0)
WHITE = (255, 255, 255)
SHADOW = (0, 0, 0, 80)

W, H = 1280, 720

COURSES = [
    ("TWF4-1", "Unapproved Parts &\nCounterfeit Materials", "FAA AC 21-29D"),
    ("TWF4-2", "Receiving and\nShipping Inspection",        "ASA-100 §6"),
    ("TWF4-3", "ASA-100\nFamiliarization",                  "Standard Overview"),
    ("TWF4-4", "Parts and\nWarehousing",                    "ASA-100 §7"),
    ("TWF4-5", "Recordkeeping",                             "ASA-100 §8"),
    ("TWF4-6", "FAA AC 00-56",                              "Accreditation Framework"),
    ("TWF4-7", "ESD Handling",                              "ANSI/ESD S20.20"),
    ("TWF4-8", "Hazmat\nIdentification",                    "49 CFR / IATA DGR"),
]


def load_font(size: int) -> ImageFont.FreeTypeFont:
    """Try a few common Windows + cross-platform fonts; fall back to default."""
    candidates = [
        "C:/Windows/Fonts/segoeuib.ttf",   # Segoe UI Bold
        "C:/Windows/Fonts/arialbd.ttf",    # Arial Bold
        "C:/Windows/Fonts/arial.ttf",
        "arial.ttf",
        "DejaVuSans-Bold.ttf",
    ]
    for path in candidates:
        try:
            return ImageFont.truetype(path, size)
        except (OSError, IOError):
            continue
    return ImageFont.load_default()


def render_card(idnumber: str, title: str, subtitle: str, out_path: Path) -> None:
    """Render a plain navy-gradient background with a gold corner accent.

    No text is drawn on the image itself — the theme (Snap, etc.) overlays the
    course title and breadcrumbs at render time. Baking text into the image
    creates a doubled-text mess when the theme overlays its own labels on top.
    """
    # Diagonal gradient: NAVY -> NAVY_LIGHT, top-left to bottom-right.
    img = Image.new("RGB", (W, H), NAVY)
    px = img.load()
    for y in range(H):
        for x in range(W):
            t = (x + y) / (W + H)
            r = int(NAVY[0] + (NAVY_LIGHT[0] - NAVY[0]) * t)
            g = int(NAVY[1] + (NAVY_LIGHT[1] - NAVY[1]) * t)
            b = int(NAVY[2] + (NAVY_LIGHT[2] - NAVY[2]) * t)
            px[x, y] = (r, g, b)

    draw = ImageDraw.Draw(img, "RGBA")

    # Gold corner accent (top-right triangle) — visual identifier, no text
    draw.polygon([(W, 0), (W, 220), (W - 220, 0)], fill=GOLD)

    # Thin gold strip along the bottom — subtle TurbineWorks branding
    draw.rectangle([(0, H - 12), (W, H)], fill=GOLD)

    img.save(out_path, "PNG", optimize=True)
    print(f"  wrote {out_path.name}  (plain background, title overlaid by theme)")


def main() -> None:
    print(f"Rendering {len(COURSES)} course cards into {OUT_DIR}")
    for idnumber, title, subtitle in COURSES:
        out = OUT_DIR / f"{idnumber}.png"
        render_card(idnumber, title, subtitle, out)
    print("done.")


if __name__ == "__main__":
    main()
