#!/usr/bin/env python3
"""
Bulk SA4 hybrid migrator for labasonsandbox master views.
- List pages (jarviswidget + dt_basic): full pattern shell + SA4 DataTables
- Forms/reports (#main): chrome + panel wrap
Skips: already SA4, mobile*, backups, ajax-only fragments without #main chrome
"""
from __future__ import annotations

import re
import sys
from pathlib import Path

VIEWS = Path(r"c:\xampp\htdocs\labasonsandbox\application\modules\master\views")

SKIP_NAMES = {
    "addcustomer.php",
    "add_zone.php",
    "dashboard.php",
    "footer.php",
    "login.php",
    "forgot-password.php",
    "change-password.php",
    "change-username.php",
    "header.php",
    "include.php",
    "mobile_footer.php",
}

SKIP_PREFIXES = ("mobile_", "mobilenotifications")
SKIP_CONTAINS = ("05-aug", "04-aug", " Copy", "- Copy")

LIST_WAVE1 = {
    "classification.php",
    "classification_category.php",
    "amountrate.php",
    "feesplaning.php",
    "job_title.php",
    "addbillingperiod.php",
    "addexpensestype.php",
    "addaccountgroup.php",
    "addsubaccountgroup.php",
    "adminconfiguration.php",
    "global_settings.php",
    "web_settings.php",
    "responsibilities.php",
    "employee-logins.php",
    "manual_or_series.php",
    "database_backup.php",
    "main-category.php",
    "practise.php",
}

LIST_WAVE2 = {
    "addpaymentcustomer.php",
    "paymentmonthlycustomer.php",
    "addmetercustomerreading.php",
    "addexpenses.php",
    "addledger.php",
    "addjournalvoucher.php",
    "transaction.php",
    "leakingentry.php",
    "leakingentry_ledger.php",
    "leakingentrycorrection.php",
    "leakingentrycorrection_ledger.php",
    "or_correction.php",
    "technicalproblems.php",
    "addaccountmetercustomer.php",
    "addaccountmonthlycustomer.php",
    "addcustomer_search.php",
    "addcustomer_metersearch.php",
    "addcustomer_monthlysearch.php",
    "addcustomer_paidsearch.php",
    "addcustomer_unpaidsearch.php",
    "addcustomer_technicalsearch.php",
    "addcustomer_generatemetercustomer_search.php",
    "addmetercustomerreading_search.php",
    "addexpenses_search.php",
    "addledger_search.php",
    "addledger_payrolssearch.php",
}

LIST_WAVE3 = {
    "addemployee.php",
    "addemployee_search.php",
    "addemployee_payrolssearch.php",
    "payrols.php",
    "addassets.php",
    "addassets_search.php",
    "addshareholder.php",
    "addshareholder_search.php",
}


def should_skip(path: Path) -> bool:
    name = path.name
    if name in SKIP_NAMES:
        return True
    if name.startswith(SKIP_PREFIXES):
        return True
    for s in SKIP_CONTAINS:
        if s in name:
            return True
    if "/partials/" in str(path).replace("\\", "/"):
        return True
    return False


def is_printish(name: str) -> bool:
    n = name.lower()
    return (
        "print" in n
        or n.endswith("_printtopdf.php")
        or n.endswith("-print.php")
        or "invoice" in n and "print" in n
    )


def title_from_file(name: str) -> tuple[str, str, str]:
    base = re.sub(r"\.php$", "", name)
    base = re.sub(r"[_-]+", " ", base)
    words = [w.capitalize() for w in base.split() if w]
    nice = " ".join(words) if words else "Page"
    # icon heuristic
    icon = "fal fa-th-list"
    low = name.lower()
    if "employee" in low or "payrol" in low:
        icon = "fal fa-users"
    elif "expense" in low:
        icon = "fal fa-money-bill"
    elif "payment" in low or "ledger" in low or "journal" in low:
        icon = "fal fa-wallet"
    elif "customer" in low:
        icon = "fal fa-user-friends"
    elif "asset" in low:
        icon = "fal fa-boxes"
    elif "share" in low:
        icon = "fal fa-chart-pie"
    elif "report" in low or "aging" in low or "trial" in low or "statement" in low:
        icon = "fal fa-chart-bar"
    elif "setting" in low or "config" in low:
        icon = "fal fa-cog"
    elif "zone" in low:
        icon = "fal fa-map-marker-alt"
    elif "meter" in low or "reading" in low:
        icon = "fal fa-tachometer-alt"
    elif "classif" in low:
        icon = "fal fa-tags"
    return icon, "Manage", nice


def extract_breadcrumbs(html: str) -> list[tuple[str, str | None]]:
    """Return list of (label, href|None)."""
    m = re.search(r'<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>(.*?)</ol>', html, re.I | re.S)
    if not m:
        return [("Home", "<?php echo ADMIN_URL; ?>"), ("List", None)]
    items = []
    for li in re.findall(r"<li[^>]*>(.*?)</li>", m.group(1), re.I | re.S):
        a = re.search(r'<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)</a>', li, re.I | re.S)
        if a:
            label = re.sub(r"<[^>]+>", "", a.group(2)).strip()
            items.append((label or "Page", a.group(1)))
        else:
            label = re.sub(r"<[^>]+>", "", li).strip()
            if label:
                items.append((label, None))
    if not items:
        items = [("Home", "<?php echo ADMIN_URL; ?>"), ("Page", None)]
    return items


def breadcrumb_html(items: list[tuple[str, str | None]]) -> str:
    parts = ['\t<ol class="breadcrumb page-breadcrumb">']
    for i, (label, href) in enumerate(items):
        active = i == len(items) - 1
        safe_label = label
        if href and not active:
            parts.append(f'\t\t<li class="breadcrumb-item"><a href="{href}">{safe_label}</a></li>')
        else:
            parts.append(f'\t\t<li class="breadcrumb-item active">{safe_label}</li>')
    parts.append('\t\t<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>')
    parts.append("\t</ol>")
    return "\n".join(parts)


def extract_inner_content(html: str) -> str:
    """Extract useful body between widget content / form and before footer."""
    # Prefer widget-body content + siblings until end of jarviswidget
    m = re.search(
        r'(?:<!--\s*widget content\s*-->|<div class="widget-body[^"]*"[^>]*>)(.*?)(?:<!--\s*end widget content\s*-->|</div>\s*<!--\s*end widget div\s*-->)',
        html,
        re.I | re.S,
    )
    if m:
        return m.group(1).strip()

    # Form pages: panel / widget-body
    m = re.search(
        r'<div class="widget-body[^"]*"[^>]*>(.*?)</div>\s*</div>\s*</div>\s*<!--\s*end row',
        html,
        re.I | re.S,
    )
    if m:
        return m.group(1).strip()

    m = re.search(r'<div id="content"[^>]*>(.*?)</div>\s*<!--\s*END MAIN CONTENT', html, re.I | re.S)
    if m:
        inner = m.group(1)
        # strip sparks row / page-title row roughly keep from widget-grid
        m2 = re.search(r'(<section id="widget-grid".*)', inner, re.I | re.S)
        if m2:
            return m2.group(1).strip()
        return inner.strip()

    # Fallback: from first form or table
    m = re.search(r'(<(?:form|table|div class="row")[\s\S]+)', html, re.I)
    if m:
        chunk = m.group(1)
        # cut at footer include
        chunk = re.split(r"<\?php\s+include\(['\"]footer\.php['\"]\)", chunk, maxsplit=1)[0]
        return chunk.strip()
    return html


def extract_table_block(html: str) -> str | None:
    m = re.search(r'(<table[^>]*id=["\']dt_basic["\'][\s\S]*?</table>)', html, re.I)
    return m.group(1) if m else None


def extract_multi_delete_form(html: str) -> tuple[str | None, str]:
    """Return (action_url, extra_before_table_html)."""
    m = re.search(
        r'<form[^>]+action=["\']([^"\']*multi_delete[^"\']*)["\'][^>]*>([\s\S]*?)</form>',
        html,
        re.I,
    )
    if not m:
        return None, ""
    action = m.group(1)
    return action, ""


def extract_add_button(html: str) -> str | None:
    m = re.search(
        r'<a[^>]+href=["\']([^"\']+)["\'][^>]*>[\s\S]*?(?:Add|Create|New)[\s\S]*?</a>',
        html,
        re.I,
    )
    if m:
        return m.group(1)
    m = re.search(r"ADMIN_URL[^;]*?\}?\s*([a-z0-9_]+)/add/", html, re.I)
    return None


def strip_old_scripts(html: str) -> str:
    # Remove old datatables plugin scripts and huge boilerplate DT init
    html = re.sub(
        r'<script[^>]+src=["\'][^"\']*js/plugin/datatables[^"\']*["\'][^>]*>\s*</script>\s*',
        "",
        html,
        flags=re.I,
    )
    html = re.sub(
        r'<script[^>]+src=["\'][^"\']*datatable-responsive[^"\']*["\'][^>]*>\s*</script>\s*',
        "",
        html,
        flags=re.I,
    )
    html = re.sub(
        r"<!--\s*PAGE RELATED PLUGIN\(S\)\s*-->[\s\S]*?(?=<script(?![^>]+src=)|$)",
        "",
        html,
        flags=re.I,
    )
    # Remove google analytics boilerplate
    html = re.sub(r"<!--\s*Your GOOGLE ANALYTICS CODE Below\s*-->[\s\S]*$", "", html, flags=re.I)
    return html


def migrate_list(path: Path, html: str) -> str:
    icon, title, subtitle = title_from_file(path.name)
    crumbs = extract_breadcrumbs(html)
    table = extract_table_block(html)
    action, _ = extract_multi_delete_form(html)
    add_href = extract_add_button(html)

    if not table:
        # fall back to chrome wrap of inner content
        return migrate_chrome(path, html, full_kpi=True)

    # Modernize status labels a bit
    table = table.replace("label label-success", "badge badge-success")
    table = table.replace("label label-danger", "badge badge-danger")
    table = table.replace("label label-warning", "badge badge-warning")
    table = table.replace("label label-info", "badge badge-info")
    table = table.replace("arrowed-in arrowed-in-right", "")
    table = table.replace("arrowed", "")
    table = re.sub(r'\bclass=["\']ace["\']', 'class="ace"', table)
    if 'id="dt_basic"' in table and "table-bordered" not in table:
        table = table.replace(
            'id="dt_basic"',
            'id="dt_basic" class="table table-bordered table-hover table-striped w-100"',
            1,
        )
        table = re.sub(
            r'<table([^>]*id=["\']dt_basic["\'][^>]*)\s+class="[^"]*"',
            r'<table\1',
            table,
            count=1,
            flags=re.I,
        )
        # ensure class present once
        if "table table-bordered" not in table:
            table = re.sub(
                r"(<table[^>]*id=[\"']dt_basic[\"'])",
                r'\1 class="table table-bordered table-hover table-striped w-100"',
                table,
                count=1,
                flags=re.I,
            )

    panel_id = "panel-" + re.sub(r"[^a-z0-9]+", "-", path.stem.lower()).strip("-")
    entity = subtitle.lower()

    add_btn = ""
    if add_href:
        add_btn = f'''
									<div class="col-sm-6 col-md-6 text-right">
										<a href="{add_href}" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>'''

    delete_btn = ""
    form_open = ""
    form_close = ""
    if action:
        form_open = f'<form method="post" action="{action}" id="sa4-list-form">'
        form_close = "</form>"
        delete_btn = '''
									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>'''
        if not add_btn:
            delete_btn = delete_btn.replace("col-md-6", "col-md-12")

    toolbar_row = ""
    if delete_btn or add_btn:
        toolbar_row = f'''
								<div class="row mb-3 align-items-end">
{delete_btn}
{add_btn}
								</div>'''

    # Preserve deleteAllData script if custom
    custom_js = ""
    m_del = re.search(
        r"<script[^>]*>\s*function\s+deleteAllData\s*\(\)\s*\{[\s\S]*?\}\s*</script>",
        html,
        re.I,
    )
    # shared init already defines deleteAllData

    out = f'''<?php
	$sa4_page_icon = '{icon}';
	$sa4_page_title = '{title}';
	$sa4_page_subtitle = '{subtitle}';
	$sa4_loading_label = '{subtitle}';
	$sa4_dt_entity = '{entity}';
	$sa4_panel_id = '{panel_id}';
?>
<main id="js-page-content" role="main" class="page-content">
{breadcrumb_html(crumbs)}
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>

	<?php if ($this->session->flashdata('msg_succ')) {{ ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php }} ?>

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="{panel_id}" class="panel">
					<div class="panel-hdr">
						<h2>{subtitle} <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							{form_open}
{toolbar_row}
								{table}
							{form_close}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
</body>
</html>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
'''
    return out


def migrate_chrome(path: Path, html: str, full_kpi: bool = False) -> str:
    icon, title, subtitle = title_from_file(path.name)
    crumbs = extract_breadcrumbs(html)

    # Get content inside #content or after ribbon
    body = html
    # Drop duplicate document heads
    if re.search(r"<!DOCTYPE|<html", body, re.I):
        m = re.search(r'(<!--\s*MAIN PANEL\s*-->|<div id="main")', body, re.I)
        if m:
            body = body[m.start() :]

    body = strip_old_scripts(body)

    # Extract main content region
    content = extract_inner_content(body)

    # Clean jarviswidget wrappers to simpler markup
    content = re.sub(r'<article[^>]*>', '<div class="col-xl-12">', content, flags=re.I)
    content = re.sub(r"</article>", "</div>", content, flags=re.I)
    content = re.sub(
        r'<div class="jarviswidget[^"]*"[^>]*>',
        '<div class="panel">',
        content,
        flags=re.I,
    )
    content = re.sub(
        r"<header([^>]*)>([\s\S]*?)</header>",
        lambda m: '<div class="panel-hdr"><h2>'
        + re.sub(r"<[^>]+>", "", m.group(2)).strip()[:80]
        + "</h2>"
        + '<div class="panel-toolbar">'
        + '<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>'
        + '<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>'
        + "</div></div><div class=\"panel-container show\"><div class=\"panel-content\">",
        content,
        count=1,
        flags=re.I,
    )
    content = re.sub(r'<div class="jarviswidget-editbox">[\s\S]*?</div>', "", content, flags=re.I)
    content = re.sub(r'<div class="widget-body[^"]*"[^>]*>', "", content, flags=re.I)
    content = content.replace("btn-default", "btn-secondary")
    content = re.sub(r"\bcol-xs-(\d+)\b", r"col-\1", content)
    content = re.sub(r'\bclass="([^"]*)\bfa\b', lambda m: 'class="' + m.group(1).replace("fa ", "fal ").replace(" fa-", " fal-"), content)
    # simpler fa -> fal for icon classes
    content = re.sub(r'\bclass="fa\b', 'class="fal', content)
    content = re.sub(r"\bfa fa-", "fal fa-", content)

    # If no panel yet, wrap
    if 'class="panel"' not in content and "panel-hdr" not in content:
        content = f'''
	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>{subtitle} <span class="fw-300"><i>Form</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
{content}
					</div>
				</div>
			</div>
		</div>
	</div>'''

    kpi = ""
    if full_kpi:
        kpi = f"""<?php
	$sa4_page_icon = '{icon}';
	$sa4_page_title = '{title}';
	$sa4_page_subtitle = '{subtitle}';
?>
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>
"""
    else:
        kpi = f'''	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon {icon}"></i>
			{title} <span class="fw-300">{subtitle}</span>
		</h1>
	</div>
'''

    # Detect leftover custom scripts after footer in original
    scripts = []
    for sm in re.finditer(r"(<script(?![^>]+src=)[^>]*>[\s\S]*?</script>)", html, re.I):
        block = sm.group(1)
        # skip boilerplate DataTables / analytics
        if "ResponsiveDatatablesHelper" in block or "_gaq" in block:
            continue
        if "sDom" in block and "dt-toolbar" in block:
            continue
        if "dataTable(" in block and "pageSetUp" in block and len(block) > 1500:
            continue
        scripts.append(block)

    # If list-like with dt_basic still in content, add SA4 DT init
    dt_bits = ""
    if 'id="dt_basic"' in content or "id='dt_basic'" in content:
        panel_id = "panel-" + re.sub(r"[^a-z0-9]+", "-", path.stem.lower()).strip("-")
        content = f'<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">\n' + content
        dt_bits = f"""
<?php
	$sa4_loading_label = '{subtitle}';
	$sa4_dt_entity = '{subtitle.lower()}';
	$sa4_panel_id = '{panel_id}';
?>
<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
"""
        # ensure panel has id
        content = content.replace('class="panel"', f'id="{panel_id}" class="panel"', 1)

    out = f'''<main id="js-page-content" role="main" class="page-content">
{breadcrumb_html(crumbs)}
{kpi}
{content}
</main>
<?php include('footer.php'); ?>
</body>
</html>
{"".join(scripts)}
{dt_bits}
'''
    return out


def migrate_print(path: Path, html: str) -> str:
    """Light pass: ensure print pages don't pull old SA1 production CSS if they self-contain head."""
    html2 = html
    html2 = html2.replace("smartadmin-production.min.css", "sa4/css/app.bundle.css")
    html2 = html2.replace("smartadmin-production-plugins.min.css", "sa4/css/vendors.bundle.css")
    html2 = html2.replace("bootstrap.min.css", "sa4/css/vendors.bundle.css")
    # avoid double vendors if both replaced poorly - ok for light pass
    if "js-page-content" not in html2 and 'id="main"' in html2:
        # strip chrome only, keep printable content
        html2 = migrate_chrome(path, html2, full_kpi=False)
        # remove nav chrome for prints - already just content
    return html2


def classify(path: Path, html: str) -> str:
    name = path.name.lower()
    if is_printish(name):
        return "print"
    if "js-page-content" in html:
        return "skip"
    # ajax fragments without main chrome
    if ("_ajax" in name or " ajax" in name) and 'id="main"' not in html and "jarviswidget" not in html:
        return "ajax"
    if 'id="dt_basic"' in html and ("jarviswidget" in html or 'id="main"' in html):
        return "list"
    if 'id="main"' in html or "jarviswidget" in html:
        # form/report chrome
        if any(x in name for x in ("_add", "_edit", "_view", "-add", "-edit", "-view", "import", "generate")):
            return "form"
        if any(x in name for x in ("report", "aging", "arrears", "trial", "statement", "invoice", "qrcode", "balance", "monitor")):
            return "report"
        if 'id="dt_basic"' in html:
            return "list"
        return "form"
    return "skip"


def main() -> int:
    wave_filter = set(sys.argv[1:]) if len(sys.argv) > 1 else set()
    stats = {"list": 0, "form": 0, "report": 0, "print": 0, "ajax": 0, "skip": 0, "error": 0}

    files = sorted(VIEWS.glob("*.php"))
    for path in files:
        if should_skip(path):
            stats["skip"] += 1
            continue

        if wave_filter:
            # wave filters by filename sets or kinds
            kind_hint = None
            if "wave1" in wave_filter and path.name not in LIST_WAVE1:
                if path.name in LIST_WAVE2 or path.name in LIST_WAVE3:
                    continue
                # allow forms matching wave1 entities later
            if "wave1" in wave_filter:
                if path.name not in LIST_WAVE1:
                    continue
            if "wave2" in wave_filter and path.name not in LIST_WAVE2:
                continue
            if "wave3" in wave_filter and path.name not in LIST_WAVE3:
                continue
            if "forms" in wave_filter:
                pass  # handled by kind
            if "reports" in wave_filter:
                pass
            if "prints" in wave_filter:
                pass

        try:
            html = path.read_text(encoding="utf-8", errors="replace")
        except Exception as e:
            print(f"ERROR read {path.name}: {e}")
            stats["error"] += 1
            continue

        kind = classify(path, html)

        if wave_filter:
            if "forms" in wave_filter and kind != "form":
                continue
            if "reports" in wave_filter and kind != "report":
                continue
            if "prints" in wave_filter and kind != "print":
                continue
            if "lists" in wave_filter and kind != "list":
                continue

        if kind == "skip":
            stats["skip"] += 1
            continue
        if kind == "ajax":
            # light class modernization only
            new = html.replace("label label-success", "badge badge-success")
            new = new.replace("label label-danger", "badge badge-danger")
            new = new.replace("btn-default", "btn-secondary")
            if new != html:
                path.write_text(new, encoding="utf-8")
                stats["ajax"] += 1
                print(f"ajax-touch {path.name}")
            else:
                stats["skip"] += 1
            continue

        try:
            if kind == "list":
                new = migrate_list(path, html)
            elif kind == "print":
                new = migrate_print(path, html)
            else:
                new = migrate_chrome(path, html, full_kpi=False)
            path.write_text(new, encoding="utf-8")
            stats[kind] = stats.get(kind, 0) + 1
            print(f"{kind:6} {path.name}")
        except Exception as e:
            print(f"ERROR migrate {path.name}: {e}")
            stats["error"] += 1

    print("STATS", stats)
    return 0 if stats["error"] == 0 else 1


if __name__ == "__main__":
    raise SystemExit(main())
