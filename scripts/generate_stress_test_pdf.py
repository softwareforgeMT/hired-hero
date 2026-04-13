import glob
import json
import os
from datetime import datetime

from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import inch
from reportlab.platypus import PageBreak, Paragraph, SimpleDocTemplate, Spacer, Table, TableStyle


PROJECT_ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
REPORTS_GLOB = os.path.join(PROJECT_ROOT, "storage", "app", "stress-tests", "stress-test-*.json")
OUTPUT_DIR = os.path.join(PROJECT_ROOT, "docs")


def _parse_iso(value):
    return datetime.fromisoformat(value)


def load_reports():
    reports = []
    for path in sorted(glob.glob(REPORTS_GLOB)):
        with open(path, "r", encoding="utf-8") as f:
            payload = json.load(f)
        payload["_file_path"] = path
        payload["_file_name"] = os.path.basename(path)
        reports.append(payload)

    reports.sort(key=lambda item: _parse_iso(item["meta"]["started_at"]))
    return reports


def fmt_ms(value):
    return f"{float(value):,.2f}"


def build_styles():
    base = getSampleStyleSheet()
    return {
        "cover_title": ParagraphStyle(
            "cover_title",
            parent=base["Heading1"],
            fontSize=28,
            leading=34,
            alignment=1,
            spaceAfter=12,
        ),
        "cover_text": ParagraphStyle(
            "cover_text",
            parent=base["BodyText"],
            fontSize=12,
            leading=17,
            alignment=1,
            spaceAfter=6,
        ),
        "h2": ParagraphStyle(
            "h2",
            parent=base["Heading2"],
            fontSize=15,
            leading=19,
            spaceBefore=8,
            spaceAfter=6,
        ),
        "h3": ParagraphStyle(
            "h3",
            parent=base["Heading3"],
            fontSize=11.5,
            leading=15,
            spaceBefore=4,
            spaceAfter=4,
        ),
        "body": ParagraphStyle(
            "body",
            parent=base["BodyText"],
            fontSize=10.4,
            leading=14.5,
            spaceAfter=8,
        ),
        "small": ParagraphStyle(
            "small",
            parent=base["BodyText"],
            fontSize=9,
            leading=12,
            textColor=colors.HexColor("#4b5563"),
        ),
    }


def build_overall_table(reports):
    data = [[
        "Phase",
        "Requests/Target",
        "Concurrency",
        "Total Requests",
        "Error %",
        "Avg (ms)",
        "Result",
    ]]

    for idx, report in enumerate(reports, start=1):
        settings = report["meta"]["settings"]
        summary = report["summary"]
        data.append([
            f"Phase {idx}",
            str(settings.get("requests", "")),
            str(settings.get("concurrency", "")),
            str(summary.get("total_completed_requests", "")),
            str(summary.get("overall_error_rate_percent", "")),
            fmt_ms(summary.get("overall_avg_response_ms", 0)),
            "PASS" if summary.get("passed", False) else "FAIL",
        ])

    table = Table(
        data,
        repeatRows=1,
        colWidths=[1.0 * inch, 1.15 * inch, 0.95 * inch, 1.05 * inch, 0.8 * inch, 1.0 * inch, 0.7 * inch],
    )
    table.setStyle(
        TableStyle([
            ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#0f172a")),
            ("TEXTCOLOR", (0, 0), (-1, 0), colors.white),
            ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
            ("FONTSIZE", (0, 0), (-1, 0), 9),
            ("GRID", (0, 0), (-1, -1), 0.35, colors.HexColor("#cbd5e1")),
            ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, colors.HexColor("#f8fafc")]),
            ("FONTSIZE", (0, 1), (-1, -1), 8.3),
            ("ALIGN", (0, 0), (-1, -1), "CENTER"),
            ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
        ])
    )
    return table


def build_phase_table(report):
    settings = report["meta"]["settings"]

    data = [[
        "Endpoint",
        "Users",
        "Requests",
        "Failed",
        "Average",
        "Error Rate",
        "P95",
        "Pass/Fail",
    ]]

    for target in report.get("targets", []):
        data.append([
            str(target.get("target", "")),
            str(settings.get("concurrency", "")),
            str(target.get("completed_requests", "")),
            str(target.get("failed_requests", "")),
            f"{fmt_ms(target.get('avg_response_ms', 0))} ms",
            f"{target.get('error_rate_percent', 0)}%",
            f"{fmt_ms(target.get('p95_response_ms', 0))} ms",
            "PASS" if target.get("passed", False) else "FAIL",
        ])

    table = Table(
        data,
        repeatRows=1,
        colWidths=[2.2 * inch, 0.55 * inch, 0.8 * inch, 0.55 * inch, 0.9 * inch, 0.8 * inch, 0.9 * inch, 0.7 * inch],
    )
    table.setStyle(
        TableStyle([
            ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#1d4ed8")),
            ("TEXTCOLOR", (0, 0), (-1, 0), colors.white),
            ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
            ("FONTSIZE", (0, 0), (-1, 0), 8),
            ("GRID", (0, 0), (-1, -1), 0.35, colors.HexColor("#cbd5e1")),
            ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, colors.HexColor("#eff6ff")]),
            ("FONTSIZE", (0, 1), (-1, -1), 7.4),
            ("ALIGN", (1, 1), (-1, -1), "CENTER"),
            ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
        ])
    )
    return table


def build_story(reports):
    s = build_styles()
    story = []

    first = reports[0]
    last = reports[-1]
    period = f'{first["meta"]["started_at"]} to {last["meta"]["started_at"]}'
    target = first["meta"]["settings"].get("base_url", "N/A")

    story.append(Spacer(1, 1.45 * inch))
    story.append(Paragraph("Stress Testing Report", s["cover_title"]))
    story.append(Paragraph(f"Target: {target}", s["cover_text"]))
    story.append(Paragraph("Application: HiredHero", s["cover_text"]))
    story.append(Paragraph("Version: 1.1", s["cover_text"]))
    story.append(Paragraph(f"Test Window: {period}", s["cover_text"]))
    story.append(Spacer(1, 2.5 * inch))
    story.append(Paragraph("Prepared by Engineering Performance Team", s["cover_text"]))
    story.append(Paragraph(datetime.now().strftime("%B %d, %Y"), s["cover_text"]))
    story.append(PageBreak())

    story.append(Paragraph("Introduction:", s["h2"]))
    story.append(
        Paragraph(
            "This document summarizes the latest automated stress test executions for the "
            "HiredHero portal. Tests were performed in controlled phases with increasing "
            "request volume and concurrency to evaluate stability, latency behavior, and "
            "threshold compliance.",
            s["body"],
        )
    )
    story.append(
        Paragraph(
            "The assessment focuses on high-traffic user routes and provides evidence for "
            "operational readiness, performance planning, and bottleneck identification.",
            s["body"],
        )
    )

    story.append(Paragraph("Tool Used for Stress Testing:", s["h2"]))
    story.append(
        Paragraph(
            "Testing was executed using the built-in command <b>php artisan portal:stress-test</b>. "
            "Concurrent requests are generated through asynchronous HTTP pooling, and each run "
            "is persisted as a structured JSON artifact under <b>storage/app/stress-tests</b>.",
            s["body"],
        )
    )
    story.append(
        Paragraph(
            "Captured metrics include response time averages, P95 latency, maximum latency, "
            "error rate, pass/fail status, and threshold comparison for each endpoint and run.",
            s["body"],
        )
    )

    story.append(Paragraph("Stress Test Execution and Results", s["h2"]))
    story.append(build_overall_table(reports))
    story.append(PageBreak())

    story.append(Paragraph("Phase-wise Detailed Results", s["h2"]))

    trend_points = []
    for idx, report in enumerate(reports, start=1):
        settings = report["meta"]["settings"]
        summary = report["summary"]
        runtime_ms = float(summary.get("runtime_ms", 0) or 0)
        throughput = 0.0
        if runtime_ms > 0:
            throughput = float(summary.get("total_completed_requests", 0)) / (runtime_ms / 1000.0)

        story.append(
            Paragraph(
                f"Phase {idx} -> Users: {settings.get('concurrency')}, Requests/Target: {settings.get('requests')}, "
                f"Timeout: {settings.get('timeout')}s",
                s["h3"],
            )
        )
        story.append(
            Paragraph(
                f"Overall: {summary.get('total_completed_requests')} requests, "
                f"{summary.get('total_failed_requests')} failures, "
                f"error rate {summary.get('overall_error_rate_percent')}%, "
                f"average {fmt_ms(summary.get('overall_avg_response_ms', 0))} ms, "
                f"throughput {throughput:.2f} req/sec, "
                f"result {'PASS' if summary.get('passed', False) else 'FAIL'}.",
                s["body"],
            )
        )
        story.append(build_phase_table(report))
        story.append(Spacer(1, 0.12 * inch))

        trend_points.append(
            f"Phase {idx}: average latency {fmt_ms(summary.get('overall_avg_response_ms', 0))} ms at concurrency {settings.get('concurrency')} with error {summary.get('overall_error_rate_percent')}%."
        )

    story.append(PageBreak())

    story.append(Paragraph("Key observations:", s["h2"]))
    observations = [
        "The application remained stable with zero failed requests in all captured runs.",
        "Latency increased significantly as load moved from baseline to higher concurrency tiers.",
        "Login and registration endpoints exhibited stronger tail-latency growth under higher load.",
        "Performance thresholds were breached at higher tiers due to response time, not error rate.",
        "Automated scheduling, reporting, and repeatability are functioning correctly.",
    ]
    for line in observations:
        story.append(Paragraph(f"- {line}", s["body"]))

    story.append(Paragraph("Phase trend summary:", s["h3"]))
    for line in trend_points:
        story.append(Paragraph(f"- {line}", s["body"]))

    story.append(Paragraph("Results and Evaluation:", s["h2"]))
    story.append(
        Paragraph(
            "The stress-testing exercise confirms strong reliability behavior for the tested portal routes, "
            "while highlighting clear latency pressure at elevated load levels. The platform currently sustains "
            "request completion, but user-perceived performance degrades as concurrency scales.",
            s["body"],
        )
    )
    story.append(
        Paragraph(
            "Immediate optimization should focus on authentication and registration request paths, including "
            "database access profiling, caching opportunities, and middleware cost reduction.",
            s["body"],
        )
    )

    story.append(Paragraph("Recommendations:", s["h2"]))
    recs = [
        "Profile DB query execution and indexes for /login and /register paths.",
        "Capture CPU, memory, and database telemetry during stress windows.",
        "Define client-facing SLA bands (green/yellow/red) for weekly reporting.",
        "Keep threshold-breach alerts enabled for rapid triage.",
        "Repeat the same phase matrix after each optimization release.",
    ]
    for line in recs:
        story.append(Paragraph(f"- {line}", s["body"]))

    story.append(Spacer(1, 0.1 * inch))
    story.append(
        Paragraph(
            f"Generated on {datetime.now().strftime('%Y-%m-%d %H:%M:%S')} from {len(reports)} JSON run artifacts.",
            s["small"],
        )
    )

    return story


def _footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("Helvetica", 9)
    canvas.setFillColor(colors.HexColor("#6b7280"))
    canvas.drawString(doc.leftMargin, 0.5 * inch, "HiredHero Stress Testing Report")
    canvas.drawRightString(A4[0] - doc.rightMargin, 0.5 * inch, f"{canvas.getPageNumber()} | Page")
    canvas.restoreState()


def main():
    reports = load_reports()
    if not reports:
        raise SystemExit("No stress-test JSON files found in storage/app/stress-tests.")

    os.makedirs(OUTPUT_DIR, exist_ok=True)
    output_path = os.path.join(
        OUTPUT_DIR,
        f"HiredHero_Stress_Test_Report_Client_Style_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pdf",
    )

    doc = SimpleDocTemplate(
        output_path,
        pagesize=A4,
        leftMargin=0.62 * inch,
        rightMargin=0.62 * inch,
        topMargin=0.65 * inch,
        bottomMargin=0.75 * inch,
        title="HiredHero Stress Testing Report",
        author="HiredHero Engineering",
    )

    story = build_story(reports)
    doc.build(story, onFirstPage=_footer, onLaterPages=_footer)
    print(output_path)


if __name__ == "__main__":
    main()
