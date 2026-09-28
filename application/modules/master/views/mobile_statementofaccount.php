<?php
/**
 * Mobile Statement of Account (mobile-first cards + lazy scroll)
 *
 * Rendered inside the mobile shell (mobile_header + mobile_navigation).
 * Data comes from mobile_statementofaccount/get_soa/{customer_id} which reuses
 * the same statementofaccount_model logic as the public SOA page.
 */
?>
<style>
	/* ---------------------------------------------------------------
	   Mobile SOA — scoped styles (prefix .soa-m-)
	   Card-based, thumb-friendly, no horizontal scroll.
	   --------------------------------------------------------------- */
	:root { --soa-top: 49px; }

	.soa-m-wrap {
		padding: 8px 8px calc(96px + env(safe-area-inset-bottom, 0px)) 8px;
		max-width: 720px;
		margin: 0 auto;
		font-size: 14px;
		-webkit-text-size-adjust: 100%;
	}
	.soa-m-card {
		background: #fff;
		border-radius: 14px;
		box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
		margin-bottom: 10px;
		overflow: hidden;
	}
	.soa-m-pad { padding: 12px; }

	/* ---- Picker ---- */
	.soa-m-picker-title {
		display: flex; align-items: center; gap: 8px;
		font-weight: 700; color: #24405c; font-size: 15px; margin-bottom: 8px;
	}
	.soa-m-picker-title i { color: #3276b1; }
	.soa-m-hint { color: #6b7b8c; font-size: 12px; margin-top: 6px; }
	.select2-container { width: 100% !important; }

	.soa-m-recent { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
	.soa-m-chip-btn {
		border: 1px solid #cfd8e3; background: #f4f7fb; color: #24405c;
		border-radius: 999px; padding: 6px 12px; font-size: 12px; font-weight: 600;
		min-height: 34px; line-height: 1;
	}
	.soa-m-chip-btn:active { background: #e3ecf7; }

	.soa-m-empty { text-align: center; color: #dfe8f3; padding: 34px 14px; }
	.soa-m-empty i { font-size: 40px; display: block; margin-bottom: 10px; opacity: .8; }
	.soa-m-empty span { font-size: 14px; }

	.soa-m-error {
		background: #fdecef; border-left: 4px solid #d9534f; color: #8b1e2b;
		border-radius: 10px; padding: 12px; margin-bottom: 10px; font-weight: 600;
	}

	/* ---- Sticky summary ---- */
	.soa-m-summary {
		position: sticky; top: var(--soa-top); z-index: 30;
		background: linear-gradient(135deg, #3276b1 0%, #24405c 100%);
		color: #fff; border-radius: 14px; margin-bottom: 10px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
		overflow: hidden;
	}
	.soa-m-sum-head { padding: 10px 12px 8px; display: flex; align-items: flex-start; gap: 10px; }
	.soa-m-sum-name { font-weight: 700; font-size: 15px; line-height: 1.25; flex: 1; }
	.soa-m-sum-sub { font-size: 11.5px; opacity: .88; margin-top: 3px; line-height: 1.35; }
	.soa-m-badge {
		display: inline-block; border-radius: 999px; padding: 3px 9px;
		font-size: 10.5px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase;
	}
	.soa-m-badge.active { background: #1dc9b7; color: #04302b; }
	.soa-m-badge.disconnected { background: #d9534f; color: #fff; }
	.soa-m-badge.deactive { background: #f0ad4e; color: #4a3200; }
	.soa-m-badge.offline { background: #4a4a4a; color: #ffe; }

	.soa-m-balance {
		padding: 10px 12px 12px;
		display: flex; align-items: flex-end; justify-content: space-between; gap: 10px;
		background: rgba(0, 0, 0, .16);
	}
	.soa-m-balance .lbl { font-size: 11px; text-transform: uppercase; letter-spacing: .4px; opacity: .85; }
	.soa-m-balance .amt { font-size: 24px; font-weight: 800; line-height: 1.1; margin-top: 2px; }
	.soa-m-balance.settled .amt { color: #9ff5e7; }
	.soa-m-balance.due .amt { color: #ffd9a0; }
	.soa-m-due { text-align: right; font-size: 11.5px; line-height: 1.4; opacity: .95; }
	.soa-m-due strong { font-size: 13px; }

	.soa-m-sum-actions {
		display: flex; gap: 6px; padding: 0 8px 10px; flex-wrap: wrap;
	}
	.soa-m-sum-actions .btn {
		flex: 1 1 auto; min-height: 40px; border-radius: 10px; font-weight: 700; font-size: 12.5px;
		display: inline-flex; align-items: center; justify-content: center; gap: 5px;
		background: rgba(255, 255, 255, .16); border: 1px solid rgba(255, 255, 255, .35); color: #fff;
	}
	.soa-m-sum-actions .btn:active { background: rgba(255, 255, 255, .3); }
	.soa-m-updated { font-size: 10.5px; opacity: .8; padding: 0 12px 10px; }

	/* ---- Filters ---- */
	.soa-m-toolbar { display: flex; flex-direction: column; gap: 8px; }
	.soa-m-chips { display: flex; gap: 6px; overflow-x: auto; padding-bottom: 2px; -webkit-overflow-scrolling: touch; }
	.soa-m-chips::-webkit-scrollbar { display: none; }
	.soa-m-chip {
		flex: 0 0 auto; border: 1px solid #cfd8e3; background: #fff; color: #40566d;
		border-radius: 999px; padding: 7px 14px; font-size: 12.5px; font-weight: 700; min-height: 36px;
	}
	.soa-m-chip .cnt { opacity: .6; font-weight: 600; }
	.soa-m-chip.on { background: #3276b1; border-color: #3276b1; color: #fff; }
	.soa-m-chip.on .cnt { opacity: .85; }
	.soa-m-search { position: relative; }
	.soa-m-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #8a9aab; }
	.soa-m-search input {
		width: 100%; height: 42px; border: 1px solid #cfd8e3; border-radius: 10px;
		padding: 0 12px 0 34px; font-size: 16px; background: #fff; color: #24405c;
	}
	.soa-m-search input:focus { outline: none; border-color: #3276b1; box-shadow: 0 0 0 3px rgba(50, 118, 177, .18); }

	/* ---- Ledger cards ---- */
	.soa-m-entry { border-left: 4px solid #cfd8e3; }
	.soa-m-entry.type-billing { border-left-color: #d9534f; }
	.soa-m-entry.type-payment { border-left-color: #1dc9b7; }
	.soa-m-entry.type-adjustment { border-left-color: #f0ad4e; }
	.soa-m-entry.short { background: #fff6f8; }
	.soa-m-entry-top { display: flex; align-items: center; gap: 10px; padding: 11px 12px; cursor: pointer; }
	.soa-m-ic {
		flex: 0 0 34px; height: 34px; border-radius: 9px;
		display: flex; align-items: center; justify-content: center;
		background: #eef3f9; color: #47627f; font-size: 15px;
	}
	.type-billing .soa-m-ic { background: #fdecef; color: #b53a3a; }
	.type-payment .soa-m-ic { background: #e3f7f4; color: #12897b; }
	.type-adjustment .soa-m-ic { background: #fdf3e3; color: #a5731c; }
	.soa-m-entry-main { flex: 1; min-width: 0; }
	.soa-m-entry-desc {
		font-weight: 700; color: #24405c; font-size: 13.5px; line-height: 1.3;
		overflow: hidden; text-overflow: ellipsis; display: -webkit-box;
		-webkit-line-clamp: 2; -webkit-box-orient: vertical;
	}
	.soa-m-entry-meta { font-size: 11.5px; color: #7b8b9c; margin-top: 2px; }
	.soa-m-entry-amt { text-align: right; flex: 0 0 auto; }
	.soa-m-entry-amt .val { font-weight: 800; font-size: 14px; }
	.soa-m-entry-amt .val.debit { color: #b53a3a; }
	.soa-m-entry-amt .val.credit { color: #12897b; }
	.soa-m-entry-amt .bal { font-size: 10.5px; color: #7b8b9c; margin-top: 2px; }
	.soa-m-chev { color: #9fb0c2; font-size: 13px; transition: transform .2s ease; }
	.soa-m-entry.open .soa-m-chev { transform: rotate(180deg); }

	.soa-m-entry-body { display: none; padding: 4px 12px 12px; border-top: 1px dashed #e2e9f1; }
	.soa-m-entry.open .soa-m-entry-body { display: block; }
	.soa-m-dl { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 10px; margin-top: 8px; }
	.soa-m-dl .k { font-size: 10.5px; color: #7b8b9c; text-transform: uppercase; letter-spacing: .3px; }
	.soa-m-dl .v { font-size: 13px; color: #24405c; font-weight: 700; }
	.soa-m-note {
		margin-top: 9px; background: #fdecef; border-left: 3px solid #d9534f; color: #8b1e2b;
		border-radius: 6px; padding: 7px 9px; font-size: 12px; font-weight: 600;
	}
	.soa-m-note.pay { background: #e8f7f5; border-left-color: #1dc9b7; color: #0d6b60; }
	.soa-m-note.warn { background: #fdf3e3; border-left-color: #f0ad4e; color: #8a6116; }

	.soa-m-loadmore { text-align: center; padding: 12px; }
	.soa-m-loadmore .btn {
		min-height: 42px; border-radius: 10px; font-weight: 700; font-size: 13px;
		background: #fff; border: 1px solid #cfd8e3; color: #40566d; width: 100%;
	}
	.soa-m-sentinel { height: 1px; }

	.soa-m-trend { display: flex; align-items: center; gap: 10px; }
	.soa-m-trend .lbl { font-size: 11.5px; color: #7b8b9c; font-weight: 600; }
	.soa-m-trend .spark { flex: 1; text-align: right; }

	/* ---- Signatures ---- */
	.soa-m-sign-toggle {
		width: 100%; background: #fff; border: 1px solid #cfd8e3; border-radius: 10px;
		min-height: 42px; font-weight: 700; color: #40566d; font-size: 13px;
	}
	.soa-m-sign { display: none; padding-top: 10px; }
	.soa-m-sign.on { display: block; }
	.soa-m-sign-row { margin-bottom: 12px; }
	.soa-m-sign-row .lbl { font-size: 10.5px; color: #7b8b9c; text-transform: uppercase; letter-spacing: .3px; }
	.soa-m-sign-row .nm { font-weight: 700; color: #24405c; font-size: 13px; border-top: 1px solid #24405c; margin-top: 18px; padding-top: 4px; }
	.soa-m-sign-row .ti { font-size: 11px; color: #6b7b8c; margin-top: 2px; }

	/* ---- Skeleton loader ---- */
	.soa-m-skel { border-radius: 14px; background: #fff; padding: 12px; margin-bottom: 10px; }
	.soa-m-skel .ln {
		height: 12px; border-radius: 6px; margin-bottom: 9px;
		background: linear-gradient(90deg, #eef3f9 25%, #e2eaf3 37%, #eef3f9 63%);
		background-size: 400% 100%; animation: soaShim 1.2s ease-in-out infinite;
	}
	@keyframes soaShim { 0% { background-position: 100% 0; } 100% { background-position: 0 0; } }

	@media (max-width: 360px) {
		.soa-m-balance .amt { font-size: 21px; }
		.soa-m-sum-name { font-size: 14px; }
	}
</style>

<!-- MAIN PANEL -->
<div id="main" role="main" style="margin-left:0px;">
	<div id="content">
		<div class="soa-m-wrap">

			<!-- Customer picker -->
			<div class="soa-m-card soa-m-pad">
				<div class="soa-m-picker-title">
					<i class="fa fa-file-text-o" aria-hidden="true"></i>
					<span>Statement of Account</span>
				</div>
				<select id="soa_customer" aria-label="Search customer"></select>
				<div class="soa-m-hint">
					<i class="fa fa-info-circle"></i> Search by name, customer ID or meter number.
				</div>
				<div class="soa-m-recent" id="soa_recent"></div>
			</div>

			<!-- Empty state -->
			<div class="soa-m-empty" id="soa_empty">
				<i class="fa fa-search" aria-hidden="true"></i>
				<span>Pick a customer to view their statement of account.</span>
			</div>

			<!-- Error -->
			<div class="soa-m-error" id="soa_error" style="display:none;"></div>

			<!-- Loading skeleton -->
			<div id="soa_loading" style="display:none;">
				<div class="soa-m-skel">
					<div class="ln" style="width:60%;"></div>
					<div class="ln" style="width:85%;"></div>
					<div class="ln" style="width:45%; margin-bottom:0;"></div>
				</div>
				<div class="soa-m-skel">
					<div class="ln" style="width:90%;"></div>
					<div class="ln" style="width:70%; margin-bottom:0;"></div>
				</div>
				<div class="soa-m-skel">
					<div class="ln" style="width:80%;"></div>
					<div class="ln" style="width:55%; margin-bottom:0;"></div>
				</div>
			</div>

			<!-- Result -->
			<div id="soa_result" style="display:none;">

				<!-- Sticky summary -->
				<div class="soa-m-summary" id="soa_summary">
					<div class="soa-m-sum-head">
						<div style="flex:1; min-width:0;">
							<div class="soa-m-sum-name" id="soa_name">—</div>
							<div class="soa-m-sum-sub" id="soa_meta"></div>
						</div>
						<span class="soa-m-badge active" id="soa_status_badge" style="display:none;"></span>
					</div>
					<div class="soa-m-balance" id="soa_balance_box">
						<div>
							<div class="lbl">Outstanding balance</div>
							<div class="amt" id="soa_balance">₱0.00</div>
						</div>
						<div class="soa-m-due" id="soa_due"></div>
					</div>
					<div class="soa-m-sum-actions">
						<button type="button" class="btn" id="soa_act_print"><i class="fa fa-print"></i> Print</button>
						<button type="button" class="btn" id="soa_act_pdf"><i class="fa fa-file-pdf-o"></i> PDF</button>
						<button type="button" class="btn" id="soa_act_share"><i class="fa fa-share-alt"></i> Share</button>
						<button type="button" class="btn" id="soa_act_call" style="display:none;"><i class="fa fa-phone"></i> Call</button>
						<button type="button" class="btn" id="soa_act_sms" style="display:none;"><i class="fa fa-comment"></i> SMS</button>
					</div>
					<div class="soa-m-updated" id="soa_updated"></div>
				</div>

				<!-- Toolbar: filters + search + trend -->
				<div class="soa-m-card soa-m-pad">
					<div class="soa-m-toolbar">
						<div class="soa-m-trend">
							<span class="lbl">Balance trend</span>
							<span class="spark"><span id="soa_spark"></span></span>
						</div>
						<div class="soa-m-chips" id="soa_chips"></div>
						<div class="soa-m-search">
							<i class="fa fa-filter"></i>
							<input type="text" id="soa_filter_text" placeholder="Filter this statement…" autocomplete="off">
						</div>
					</div>
				</div>

				<!-- Ledger -->
				<div id="soa_ledger"></div>
				<div class="soa-m-sentinel" id="soa_sentinel"></div>
				<div class="soa-m-loadmore" id="soa_loadmore" style="display:none;">
					<button type="button" class="btn" id="soa_loadmore_btn">
						<i class="fa fa-angle-down"></i> Show older transactions
					</button>
				</div>

				<!-- Totals + signatures -->
				<div class="soa-m-card soa-m-pad">
					<div class="soa-m-dl">
						<div>
							<div class="k">Total billed</div>
							<div class="v" id="soa_tot_debit">₱0.00</div>
						</div>
						<div>
							<div class="k">Total paid</div>
							<div class="v" id="soa_tot_credit">₱0.00</div>
						</div>
					</div>
					<div style="margin-top:12px;">
						<button type="button" class="soa-m-sign-toggle" id="soa_sign_toggle">
							<i class="fa fa-pencil"></i> Show signatories
						</button>
						<div class="soa-m-sign" id="soa_sign"></div>
					</div>
				</div>

			</div><!-- /#soa_result -->
		</div>
	</div>
</div>
<!-- END MAIN PANEL -->

<?php include('mobile_footer.php'); ?>

<script type="text/javascript">
(function () {
	'use strict';

	var SOA_CFG = {
		ajax:  '<?php echo ADMIN_URL; ?>mobile_statementofaccount/',
		print: '<?php echo ADMIN_URL; ?>statementofaccount/index/',
		pdf:   '<?php echo ADMIN_URL; ?>statementofaccount/pdf/'
	};

	var CHUNK = 15;          // cards rendered per pass (lazy scroll)
	var CACHE_PREFIX = 'soa_cache_';
	var RECENT_KEY = 'soa_recent';
	var LAST_KEY = 'soa_last_customer';
	var CURRENT = null;      // last loaded payload
	var FILTER = 'all';
	var TEXT = '';
	var FILTERED = [];
	var RENDERED = 0;
	var LOADING = false;

	function peso(n) {
		n = Number(n) || 0;
		return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}
	function esc(s) {
		return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function fmtDate(d) {
		if (!d) { return ''; }
		var p = String(d).split('-');
		if (p.length === 3) { return p[2] + '-' + p[1] + '-' + p[0]; }
		return d;
	}
	function shortTime(ts) {
		try {
			return new Date(ts).toLocaleString('en-PH', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
		} catch (e) { return ''; }
	}
	function groupOf(type) {
		if (type === 'billing') { return 'billing'; }
		if (type === 'payment' || type === 'leaking_payment') { return 'payment'; }
		return 'adjustment';
	}
	function iconOf(type) {
		if (type === 'billing') { return 'fa-file-text-o'; }
		if (type === 'payment' || type === 'leaking_payment') { return 'fa-check-circle-o'; }
		return 'fa-sliders';
	}
	function labelOf(type) {
		if (type === 'billing') { return 'Billing'; }
		if (type === 'payment') { return 'Payment'; }
		if (type === 'leaking_payment') { return 'Leaking payment'; }
		if (type === 'leaking_discount') { return 'Leaking discount'; }
		if (type === 'adjustment') { return 'Adjustment'; }
		return type || 'Entry';
	}

	// ---------------------------------------------------------------- storage
	function cacheKey(id) { return CACHE_PREFIX + id; }
	function saveCache(id, payload) {
		try {
			localStorage.setItem(cacheKey(id), JSON.stringify({ t: Date.now(), d: payload }));
			localStorage.setItem(LAST_KEY, id);
		} catch (e) { /* storage full / disabled — offline cache is best-effort */ }
	}
	function readCache(id) {
		try {
			var raw = localStorage.getItem(cacheKey(id));
			if (!raw) { return null; }
			var obj = JSON.parse(raw);
			return (obj && obj.d) ? obj : null;
		} catch (e) { return null; }
	}
	function pushRecent(id, name) {
		try {
			var list = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]');
			list = list.filter(function (r) { return r.id !== id; });
			list.unshift({ id: id, name: name, t: Date.now() });
			list = list.slice(0, 6);
			localStorage.setItem(RECENT_KEY, JSON.stringify(list));
		} catch (e) { /* ignore */ }
		renderRecent();
	}
	function renderRecent() {
		var $box = $('#soa_recent').empty();
		var list = [];
		try { list = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]'); } catch (e) { list = []; }
		if (!list.length) { return; }
		$box.append('<span class="soa-m-hint" style="flex-basis:100%;margin:0 0 2px;">Recently viewed</span>');
		list.forEach(function (r) {
			$('<button type="button" class="soa-m-chip-btn"></button>')
				.text((r.name || r.id).substring(0, 26))
				.attr('data-cid', r.id)
				.appendTo($box);
		});
	}

	// ---------------------------------------------------------------- rendering
	function renderSummary(p) {
		var c = p.customer || {};
		var s = p.summary || {};

		$('#soa_name').text(c.name || c.customer_id || '—');
		var meta = [];

		var where = [];
		if (c.address) { where.push(esc(c.address)); }
		if (c.zone) { where.push('Zone ' + esc(c.zone)); }
		meta.push('ID ' + esc(c.customer_id) + (c.meter_number ? ' · Meter ' + esc(c.meter_number) : ''));
		if (where.length) { meta.push(where.join(' · ')); }
		$('#soa_meta').html(meta.join('<br>'));

		var $badge = $('#soa_status_badge').removeClass('active disconnected deactive').show();
		var st = Number(c.status);
		if (st === 1) { $badge.addClass('active').text(c.status_label || 'Active'); }
		else if (st === 2) { $badge.addClass('disconnected').text(c.status_label || 'Disconnected'); }
		else { $badge.addClass('deactive').text(c.status_label || 'De-Active'); }

		var settled = !!s.is_settled;
		$('#soa_balance_box').removeClass('settled due').addClass(settled ? 'settled' : 'due');
		$('#soa_balance').text(peso(s.balance));

		var due = [];
		if (s.latest_period) {
			due.push('<div>Latest bill <strong>' + esc(s.latest_period) + '</strong></div>');
		}
		if (s.latest_due_date) {
			due.push('<div>Due ' + esc(fmtDate(s.latest_due_date)) + '</div>');
		}
		if (!settled && s.penalty_projected && s.penalty_amount > 0) {
			due.push('<div>After due ≈ <strong>' + peso(s.after_due) + '</strong></div>');
			due.push('<div style="opacity:.8;">incl. est. ' + esc(s.penalty_rate) + '% penalty</div>');
		} else if (settled) {
			due.push('<div style="color:#9ff5e7;font-weight:700;">Fully paid — thank you!</div>');
		}
		$('#soa_due').html(due.join(''));

		$('#soa_tot_debit').text(peso(s.total_debit));
		$('#soa_tot_credit').text(peso(s.total_credit));

		var mobile1 = (c.mobile1 || '').replace(/[^\d+]/g, '');
		if (mobile1) {
			$('#soa_act_call, #soa_act_sms').show().data('phone', mobile1);
		} else {
			$('#soa_act_call, #soa_act_sms').hide().data('phone', '');
		}

		renderSignatures(p.signatures || {});
		renderSpark(p.ledger || []);
	}

	function renderSignatures(sig) {
		var rows = [
			['Prepared by', sig.prepared_name, sig.prepared_title],
			['Verified correct', sig.verified_name, sig.verified_title],
			['Approved', sig.approved_name, sig.approved_title]
		];
		var html = '';
		rows.forEach(function (r) {
			if (!r[1]) { return; }
			html += '<div class="soa-m-sign-row">' +
				'<div class="lbl">' + esc(r[0]) + '</div>' +
				'<div class="nm">' + esc(String(r[1]).toUpperCase()) + '</div>' +
				(r[2] ? '<div class="ti">' + esc(r[2]) + '</div>' : '') +
				'</div>';
		});
		$('#soa_sign').html(html);
	}

	function renderSpark(ledger) {
		var chrono = ledger.slice().reverse();
		var values = [];
		for (var i = 0; i < chrono.length; i++) {
			values.push(Number(chrono[i].balance) || 0);
		}
		if (values.length > 24) { values = values.slice(values.length - 24); }
		var $sp = $('#soa_spark');
		$sp.empty();
		if (!values.length || typeof $.fn.sparkline === 'undefined') { return; }
		$sp.sparkline(values, {
			type: 'line', width: '100%', height: '30px',
			lineColor: '#3276b1', fillColor: 'rgba(50,118,177,.16)',
			spotColor: '#24405c', minSpotColor: '#1dc9b7', maxSpotColor: '#d9534f',
			highlightSpotColor: '#24405c', lineWidth: 1.6, spotRadius: 2
		});
	}

	function detailRows(e) {
		var rows = [];
		function add(k, v) {
			if (v !== null && v !== undefined && v !== '' && v !== 0 && v !== '0') { rows.push([k, v]); }
		}
		if (groupOf(e.type) === 'billing') {
			if (e.previous_reading !== undefined && e.reading !== undefined) {
				add('Reading', e.previous_reading + ' → ' + e.reading);
			}
			add('Consumed', (e.consumed !== '' && e.consumed != null ? e.consumed + ' cu.m' : ''));
			add('Unit price / bill', e.unit_price);
			if (Number(e.sc_discount) > 0) { add('SC discount', peso(e.sc_discount)); }
			if (Number(e.arrears) > 0) { add('Arrears on bill', peso(e.arrears)); }
			if (Number(e.maintenance_fee) > 0) { add('Maintenance fee', peso(e.maintenance_fee)); }
			if (Number(e.penalty) > 0) { add('Penalty', peso(e.penalty)); }
			add('Due date', e.due_date ? fmtDate(e.due_date) : '');
		} else {
			add('Entry type', labelOf(e.type));
			add('Reference', e.refno);
		}
		return rows;
	}

	function entryCard(e) {
		var g = groupOf(e.type);
		var short = !!e.is_shortfall;
		var cls = 'soa-m-card soa-m-entry type-' + g + (short ? ' short' : '');
		var amt, amtCls;
		if (Number(e.credit) > 0) { amt = peso(e.credit); amtCls = 'credit'; }
		else { amt = peso(e.debit); amtCls = 'debit'; }

		var rows = detailRows(e);
		var dl = '';
		if (rows.length) {
			dl = '<div class="soa-m-dl">';
			rows.forEach(function (r) {
				dl += '<div><div class="k">' + esc(r[0]) + '</div><div class="v">' + esc(r[1]) + '</div></div>';
			});
			dl += '</div>';
		}

		var note = '';
		if (short) {
			note = '<div class="soa-m-note"><i class="fa fa-exclamation-triangle"></i> Underpaid — shortfall ' +
				peso(e.shortfall) + ' carried as balance</div>';
		} else if (g === 'payment') {
			note = '<div class="soa-m-note pay"><i class="fa fa-check"></i> Payment posted</div>';
		} else if (g === 'adjustment') {
			note = '<div class="soa-m-note warn"><i class="fa fa-sliders"></i> Accounting adjustment</div>';
		}

		return '<div class="' + cls + '">' +
			'<div class="soa-m-entry-top" role="button" tabindex="0">' +
				'<span class="soa-m-ic"><i class="fa ' + iconOf(e.type) + '"></i></span>' +
				'<div class="soa-m-entry-main">' +
					'<div class="soa-m-entry-desc">' + esc(e.description) + '</div>' +
					'<div class="soa-m-entry-meta">' + esc(fmtDate(e.date)) +
						(e.refno ? ' · Ref ' + esc(e.refno) : '') + '</div>' +
				'</div>' +
				'<div class="soa-m-entry-amt">' +
					'<div class="val ' + amtCls + '">' + amt + '</div>' +
					'<div class="bal">Bal ' + peso(e.balance) + '</div>' +
				'</div>' +
				'<i class="fa fa-chevron-down soa-m-chev"></i>' +
			'</div>' +
			'<div class="soa-m-entry-body">' + dl + note + '</div>' +
		'</div>';
	}

	function computeFiltered() {
		var ledger = (CURRENT && CURRENT.ledger) ? CURRENT.ledger : [];
		var q = TEXT.toLowerCase();
		FILTERED = ledger.filter(function (e) {
			if (FILTER !== 'all' && groupOf(e.type) !== FILTER) { return false; }
			if (!q) { return true; }
			var hay = (e.description || '') + ' ' + (e.refno || '') + ' ' + (e.date || '') + ' ' + labelOf(e.type);
			return hay.toLowerCase().indexOf(q) !== -1;
		});
		RENDERED = 0;
		$('#soa_ledger').empty();
	}

	function renderChips() {
		var ledger = (CURRENT && CURRENT.ledger) ? CURRENT.ledger : [];
		var counts = { all: ledger.length, billing: 0, payment: 0, adjustment: 0 };
		ledger.forEach(function (e) { counts[groupOf(e.type)]++; });

		var defs = [
			['all', 'All', counts.all],
			['billing', 'Billing', counts.billing],
			['payment', 'Payments', counts.payment],
			['adjustment', 'Adjustments', counts.adjustment]
		];
		var html = '';
		defs.forEach(function (d) {
			html += '<button type="button" class="soa-m-chip' + (FILTER === d[0] ? ' on' : '') +
				'" data-f="' + d[0] + '">' + d[1] + ' <span class="cnt">' + d[2] + '</span></button>';
		});
		$('#soa_chips').html(html);
	}

	function renderNextChunk() {
		if (RENDERED >= FILTERED.length) {
			$('#soa_loadmore').hide();
			return false;
		}
		var end = Math.min(RENDERED + CHUNK, FILTERED.length);
		var html = '';
		for (var i = RENDERED; i < end; i++) { html += entryCard(FILTERED[i]); }
		$('#soa_ledger').append(html);
		RENDERED = end;

		if (RENDERED >= FILTERED.length) {
			$('#soa_loadmore').hide();
			if (!FILTERED.length) {
				$('#soa_ledger').html(
					'<div class="soa-m-card soa-m-pad" style="text-align:center;color:#7b8b9c;">' +
					'<i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>' +
					'No transactions match this view.</div>'
				);
			}
		} else {
			$('#soa_loadmore').show();
		}
		return RENDERED < FILTERED.length;
	}

	function renderAll(p, stale) {
		CURRENT = p;
		$('#soa_empty').hide();
		$('#soa_error').hide();
		$('#soa_result').show();
		renderSummary(p);
		renderChips();
		computeFiltered();
		renderNextChunk();

		var when = (p && p.summary && p.summary.generated_at) ? p.summary.generated_at : '';
		if (stale) {
			$('#soa_updated').html('<span class="soa-m-badge offline">Offline</span> Showing saved copy — ' + esc(when));
		} else {
			$('#soa_updated').text('Updated ' + when);
		}
	}

	function showError(msg) {
		$('#soa_error').text(msg).show();
	}

	// ---------------------------------------------------------------- data load
	function loadSoa(id, opts) {
		opts = opts || {};
		if (!id || LOADING) { return; }
		LOADING = true;
		$('#soa_error').hide();

		var cached = readCache(id);
		if (cached) {
			// Instant render from the device cache, then refresh in the background
			// (no skeleton / spinner, so an offline reload still shows content).
			renderAll(cached.d, true);
		} else {
			$('#soa_result').hide();
			$('#soa_empty').hide();
			$('#soa_loading').show();
			if (window.showSpinner) { window.showSpinner(); }
		}

		$.ajax({
			url: SOA_CFG.ajax + 'get_soa/' + encodeURIComponent(id),
			type: 'GET',
			dataType: 'json',
			cache: false,
			success: function (resp) {
				if (!resp || resp.success !== true) {
					if (!cached) {
						$('#soa_loading').hide();
						$('#soa_result').hide();
						$('#soa_empty').show();
						showError((resp && resp.message) ? resp.message : 'Unable to load the statement.');
					} else {
						$('#soa_updated').html('<span class="soa-m-badge offline">Offline</span> Showing saved copy.');
					}
					return;
				}
				$('#soa_loading').hide();
				saveCache(id, resp);
				pushRecent(id, (resp.customer && resp.customer.name) || id);
				renderAll(resp, false);
			},
			error: function () {
				$('#soa_loading').hide();
				if (cached) {
					renderAll(cached.d, true);
				} else {
					$('#soa_result').hide();
					$('#soa_empty').show();
					showError('Network error. Connect to the internet and try again.');
				}
			},
			complete: function () {
				LOADING = false;
				if (window.hideSpinner) { window.hideSpinner(); }
			}
		});
	}

	// ---------------------------------------------------------------- init
	$(document).ready(function () {
		// Keep the sticky summary below the fixed app header.
		var hdr = document.getElementById('header');
		if (hdr) {
			document.documentElement.style.setProperty('--soa-top', hdr.offsetHeight + 'px');
		}

		// Searchable customer picker (server-side search).
		if ($.fn.select2) {
			$('#soa_customer').select2({
				placeholder: 'Search customer (name, ID, meter #)',
				allowClear: true,
				width: '100%',
				minimumInputLength: 2,
				ajax: {
					url: SOA_CFG.ajax + 'search_customers',
					dataType: 'json',
					delay: 300,
					cache: true,
					data: function (params) { return { q: params.term, page: params.page || 1 }; },
					processResults: function (data, params) {
						params.page = params.page || 1;
						return {
							results: (data && data.results) ? data.results : [],
							pagination: { more: !!(data && data.pagination && data.pagination.more) }
						};
					}
				}
			});

			$('#soa_customer').on('select2:select', function (e) {
				var d = (e.params && e.params.data) ? e.params.data : {};
				if (d.id) { loadSoa(d.id); }
			});
			$('#soa_customer').on('select2:unselect', function () {
				CURRENT = null;
				$('#soa_result').hide();
				$('#soa_empty').show();
			});
		}

		// Recently viewed chips
		$('#soa_recent').on('click', '.soa-m-chip-btn', function () {
			loadSoa($(this).attr('data-cid'));
		});
		renderRecent();

		// Filters
		$('#soa_chips').on('click', '.soa-m-chip', function () {
			FILTER = $(this).attr('data-f') || 'all';
			renderChips();
			computeFiltered();
			renderNextChunk();
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});

		var textTimer = null;
		$('#soa_filter_text').on('input', function () {
			var val = $(this).val();
			clearTimeout(textTimer);
			textTimer = setTimeout(function () {
				TEXT = String(val || '').trim();
				computeFiltered();
				renderNextChunk();
			}, 180);
		});

		// Expand / collapse a transaction card
		$('#soa_ledger').on('click', '.soa-m-entry-top', function () {
			$(this).closest('.soa-m-entry').toggleClass('open');
		});
		$('#soa_ledger').on('keypress', '.soa-m-entry-top', function (e) {
			if (e.which === 13 || e.which === 32) {
				e.preventDefault();
				$(this).closest('.soa-m-entry').toggleClass('open');
			}
		});

		// Lazy scroll
		$('#soa_loadmore_btn').on('click', function () { renderNextChunk(); });
		if ('IntersectionObserver' in window) {
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (en) {
					if (en.isIntersecting && $('#soa_result').is(':visible')) { renderNextChunk(); }
				});
			}, { rootMargin: '200px 0px' });
			io.observe(document.getElementById('soa_sentinel'));
		}

		// Signatures toggle
		$('#soa_sign_toggle').on('click', function () {
			var $s = $('#soa_sign').toggleClass('on');
			$(this).html($s.hasClass('on')
				? '<i class="fa fa-pencil"></i> Hide signatories'
				: '<i class="fa fa-pencil"></i> Show signatories');
		});

		// Quick actions
		$('#soa_act_print').on('click', function () {
			if (!CURRENT || !CURRENT.customer) { return; }
			window.open(SOA_CFG.print + encodeURIComponent(CURRENT.customer.customer_id), '_blank');
		});
		$('#soa_act_pdf').on('click', function () {
			if (!CURRENT || !CURRENT.customer) { return; }
			window.open(SOA_CFG.pdf + encodeURIComponent(CURRENT.customer.customer_id), '_blank');
		});
		$('#soa_act_share').on('click', function () {
			if (!CURRENT || !CURRENT.customer) { return; }
			var c = CURRENT.customer, s = CURRENT.summary || {};
			var text = 'Statement of Account\n' + c.name + '\nID: ' + c.customer_id +
				(c.meter_number ? ' · Meter: ' + c.meter_number : '') +
				'\nOutstanding balance: ' + peso(s.balance) +
				((s.latest_period) ? '\nLatest bill: ' + s.latest_period : '') +
				((s.latest_due_date) ? ' due ' + fmtDate(s.latest_due_date) : '');
			if (navigator.share) {
				navigator.share({ title: 'Statement of Account', text: text }).catch(function () {});
			} else if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(function () {
					if ($.smallBox) {
						$.smallBox({ title: 'Copied', content: 'Statement summary copied to clipboard.', color: '#739E73', timeout: 4000, icon: 'fa fa-check' });
					}
				});
			}
		});
		$('#soa_act_call').on('click', function () {
			var p = $(this).data('phone');
			if (p) { window.location.href = 'tel:' + p; }
		});
		$('#soa_act_sms').on('click', function () {
			var p = $(this).data('phone');
			if (p) { window.location.href = 'sms:' + p; }
		});

		// Offline / online feedback
		window.addEventListener('offline', function () {
			$('#soa_error').text('You are offline. Showing the last saved statement.').show();
		});
		window.addEventListener('online', function () {
			$('#soa_error').hide();
			var last = null;
			try { last = localStorage.getItem(LAST_KEY); } catch (e) { last = null; }
			if (last) { loadSoa(last, { force: true }); }
		});

		// Restore the last viewed statement instantly (offline-friendly).
		var lastId = null;
		try { lastId = localStorage.getItem(LAST_KEY); } catch (e) { lastId = null; }
		if (lastId && readCache(lastId)) {
			var c = readCache(lastId);
			renderAll(c.d, true);
			loadSoa(lastId, { force: true });
		}
	});
})();
</script>
