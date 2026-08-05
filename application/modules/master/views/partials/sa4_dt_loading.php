<?php
/**
 * Shared DataTable loading modal + styles/helpers.
 * Optional: $sa4_loading_label (default "Records")
 */
if (!isset($sa4_loading_label)) { $sa4_loading_label = 'Records'; }
$sa4_loading_id = isset($sa4_loading_id) ? $sa4_loading_id : 'datatable-loading-modal';
?>
<div id="<?php echo htmlspecialchars($sa4_loading_id); ?>" class="dt-loading-modal" style="display: none;" aria-live="polite" aria-busy="true">
	<div class="dt-loading-backdrop"></div>
	<div class="dt-loading-card panel shadow-3">
		<div class="panel-hdr bg-primary-600 bg-primary-gradient">
			<h2 class="text-white">Loading <span class="fw-300"><?php echo htmlspecialchars($sa4_loading_label); ?></span></h2>
		</div>
		<div class="panel-container show">
			<div class="panel-content text-center py-4 px-4">
				<div class="mb-3">
					<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<h5 class="mb-1 fw-500" id="dt-loading-title">Fetching records</h5>
				<p class="text-muted mb-3 fs-sm" id="dt-loading-subtitle">Please wait while we prepare the listing…</p>
				<div class="progress progress-lg mb-2">
					<div id="dt-loading-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary-500" role="progressbar" style="width: 8%;" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex justify-content-between fs-xs text-muted">
					<span>Event progress</span>
					<span id="dt-loading-percent">8%</span>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.dt-loading-modal { position: fixed; inset: 0; z-index: 1055; display: none; align-items: center; justify-content: center; }
	.dt-loading-modal.is-visible { display: flex !important; }
	.dt-loading-backdrop { position: absolute; inset: 0; background: rgba(33, 37, 41, 0.45); backdrop-filter: blur(3px); }
	.dt-loading-card { position: relative; z-index: 1; width: min(420px, calc(100vw - 2rem)); margin: 0; border: 0; overflow: hidden; }
	.dt-loading-card .panel-hdr { border-bottom: 0; }
	.dt-loading-card .progress { height: 1rem; border-radius: 999px; background: rgba(136, 106, 181, 0.15); overflow: hidden; }
	.dt-loading-card .progress-bar { transition: width 0.25s ease; border-radius: 999px; }
	.dataTables_wrapper.processing { opacity: 0.55; pointer-events: none; filter: grayscale(0.15); }
	.panel.panel-loading { position: relative; }
	.panel.panel-loading::before {
		content: ''; position: absolute; top: 0; left: 0; height: 3px; width: 100%; z-index: 5;
		background: linear-gradient(90deg, transparent, var(--theme-primary, #886ab5), transparent);
		background-size: 40% 100%; animation: dt-panel-shimmer 1.1s linear infinite;
	}
	@keyframes dt-panel-shimmer { 0% { background-position: -40% 0; } 100% { background-position: 140% 0; } }
</style>
