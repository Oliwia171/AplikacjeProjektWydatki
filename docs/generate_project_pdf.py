#!/usr/bin/env python3
"""Generate the expanded network project PDF from the Markdown source.

Requires reportlab:
    python3 -m pip install reportlab
"""

from __future__ import annotations

import re
import sys
import textwrap
from pathlib import Path

from reportlab.lib.pagesizes import A4
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.pdfgen import canvas


ROOT = Path(__file__).resolve().parent
DEFAULT_SOURCE = ROOT / "ProjektSieci_rozszerzony.md"
DEFAULT_OUTPUT = ROOT / "ProjektSieci_rozszerzony.pdf"
PAGE_BREAK = "<!-- PAGE_BREAK -->"


def register_fonts() -> dict[str, str]:
    font_dir = Path("/usr/share/fonts/truetype/dejavu")
    fonts = {
        "regular": ("DocRegular", font_dir / "DejaVuSans.ttf"),
        "bold": ("DocBold", font_dir / "DejaVuSans-Bold.ttf"),
        "mono": ("DocMono", font_dir / "DejaVuSansMono.ttf"),
    }

    for _, (name, path) in fonts.items():
        if not path.exists():
            raise FileNotFoundError(f"Missing font file: {path}")
        pdfmetrics.registerFont(TTFont(name, str(path)))

    return {key: value[0] for key, value in fonts.items()}


def strip_markdown(text: str) -> str:
    text = text.replace("**", "")
    text = text.replace("`", "")
    return text.strip()


def draw_wrapped(
    pdf: canvas.Canvas,
    text: str,
    *,
    x: float,
    y: float,
    width: float,
    font_name: str,
    font_size: int,
    line_height: int,
    indent: int = 0,
) -> float:
    text = strip_markdown(text)
    if not text:
        return y - line_height

    avg_char_width = font_size * (0.62 if "Mono" in font_name else 0.52)
    max_chars = max(20, int((width - indent) / avg_char_width))
    wrapped = textwrap.wrap(
        text,
        width=max_chars,
        break_long_words=False,
        replace_whitespace=False,
    ) or [""]

    pdf.setFont(font_name, font_size)
    for index, line in enumerate(wrapped):
        pdf.drawString(x + (indent if index else 0), y, line)
        y -= line_height
    return y


def render_page(
    pdf: canvas.Canvas,
    page_text: str,
    page_number: int,
    total_pages: int,
    fonts: dict[str, str],
) -> None:
    page_width, page_height = A4
    left = 50
    right = page_width - 50
    top = page_height - 52
    bottom = 58
    y = top
    in_code = False

    for raw_line in page_text.strip().splitlines():
        line = raw_line.rstrip()

        if line.strip() == "```text":
            in_code = True
            y -= 4
            continue
        if line.strip() == "```":
            in_code = False
            y -= 6
            continue

        if not line.strip():
            y -= 7
            continue

        if in_code or line.lstrip().startswith("|"):
            y = draw_wrapped(
                pdf,
                line,
                x=left,
                y=y,
                width=right - left,
                font_name=fonts["mono"],
                font_size=8,
                line_height=11,
            )
        elif line.startswith("# "):
            y -= 6
            y = draw_wrapped(
                pdf,
                line[2:],
                x=left,
                y=y,
                width=right - left,
                font_name=fonts["bold"],
                font_size=15,
                line_height=19,
            )
            y -= 5
        elif line.startswith("## "):
            y -= 2
            y = draw_wrapped(
                pdf,
                line[3:],
                x=left,
                y=y,
                width=right - left,
                font_name=fonts["bold"],
                font_size=11,
                line_height=15,
            )
        elif re.match(r"^\d+\.\s", line.strip()) or line.strip().startswith("- "):
            y = draw_wrapped(
                pdf,
                line,
                x=left,
                y=y,
                width=right - left,
                font_name=fonts["regular"],
                font_size=9,
                line_height=12,
                indent=14,
            )
        else:
            y = draw_wrapped(
                pdf,
                line,
                x=left,
                y=y,
                width=right - left,
                font_name=fonts["regular"],
                font_size=9,
                line_height=12,
            )

        if y < bottom:
            raise RuntimeError(f"Page {page_number} content does not fit on one page.")

    pdf.setFont(fonts["regular"], 8)
    pdf.drawCentredString(page_width / 2, 32, f"-- {page_number} z {total_pages} --")
    pdf.showPage()


def main() -> int:
    source = Path(sys.argv[1]) if len(sys.argv) > 1 else DEFAULT_SOURCE
    output = Path(sys.argv[2]) if len(sys.argv) > 2 else DEFAULT_OUTPUT

    pages = source.read_text(encoding="utf-8").split(PAGE_BREAK)
    if len(pages) != 11:
        raise RuntimeError(f"Expected 11 pages, found {len(pages)}.")

    fonts = register_fonts()
    pdf = canvas.Canvas(str(output), pagesize=A4)
    pdf.setTitle("Logika przydzielania adresów IP - DHCP")
    pdf.setAuthor("Oliwia Kwasek")

    for index, page in enumerate(pages, start=1):
        render_page(pdf, page, index, len(pages), fonts)

    pdf.save()
    print(f"Generated {output} ({len(pages)} pages)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
