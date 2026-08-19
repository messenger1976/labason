<?php
/**
 * Hierarchical Roles & Permissions checkbox tree.
 * Expects: $module_groups, $modules_name, $permissions_mode ('add'|'edit'|'view')
 * Optional: $record (edit/view)
 */
$permissions_mode = isset($permissions_mode) ? $permissions_mode : 'add';
$is_view = ($permissions_mode === 'view');
$record = (isset($record) && is_array($record)) ? $record : array();
$module_groups = (isset($module_groups) && is_array($module_groups)) ? $module_groups : array();
$modules_name = (isset($modules_name) && is_array($modules_name)) ? $modules_name : array();
$disabled_attr = $is_view ? 'disabled="disabled"' : '';

if (!function_exists('resp_is_checked')) {
	function resp_is_checked($key, $record, $mode) {
		if ($mode === 'add') {
			return false;
		}
		return (isset($record[$key]) && (string) $record[$key] === '1');
	}
}
?>
<style>
.perm-toolbar { margin: 0 0 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
.perm-hint { color: #6c757d; font-size: 0.8125rem; margin-left: 0.25rem; }
.perm-grid { display: flex; flex-wrap: wrap; margin: 0 -0.5rem; }
.perm-card { width: 100%; padding: 0 0.5rem; margin-bottom: 0.875rem; }
@media (min-width: 992px) { .perm-card { width: 50%; } }
@media (min-width: 1200px) { .perm-card { width: 33.333%; } }
.perm-panel {
	background: #f8f9fa;
	border: 1px solid #e9ecef;
	border-radius: 0.25rem;
	overflow: visible;
	height: auto;
}
.perm-panel-header {
	padding: 0.625rem 0.75rem;
	background: #eee;
	border-bottom: 1px solid #e0e0e0;
	display: flex;
	align-items: center;
	gap: 0.5rem;
	user-select: none;
}
.perm-panel-header.has-children { cursor: pointer; }
.perm-panel-header.no-children { cursor: default; }
.perm-panel-header label {
	margin: 0;
	font-weight: 700;
	font-size: 0.875rem;
	cursor: pointer;
	flex: 1;
}
.perm-panel-header .perm-toggle { color: #555; width: 18px; text-align: center; }
.perm-panel.open > .perm-panel-header { background: #e8f0e8; border-bottom-color: #cfe0cf; }
.perm-children,
.perm-children { display: none; padding: 0.5rem 0.75rem 0.625rem 2rem; background: #fff; }
.perm-panel.open > .perm-children,
.perm-panel.open > .perm-children,
.perm-panel.open .perm-children,
.perm-panel.open .perm-children { display: block; }
.perm-child { padding: 0.3rem 0; border-bottom: 1px dashed #eee; }
.perm-child:last-child { border-bottom: 0; }
.perm-child label { margin: 0; font-weight: normal; font-size: 0.8125rem; cursor: pointer; }
.perm-child input[type="checkbox"],
.perm-panel-header input[type="checkbox"] { margin-right: 0.375rem; vertical-align: middle; }
</style>

<div class="perm-toolbar">
	<?php if (!$is_view) { ?>
	<button type="button" class="btn btn-xs btn-success" id="perm-select-all"><i class="fal fa-check-square mr-1"></i> Select All</button>
	<button type="button" class="btn btn-xs btn-secondary" id="perm-clear-all"><i class="fal fa-square mr-1"></i> Clear All</button>
	<?php } ?>
	<button type="button" class="btn btn-xs btn-primary" id="perm-expand-all"><i class="fal fa-plus-square mr-1"></i> Expand All</button>
	<button type="button" class="btn btn-xs btn-secondary" id="perm-collapse-all"><i class="fal fa-minus-square mr-1"></i> Collapse All</button>
	<span class="perm-hint">Click the group name or the arrow to expand (or use Expand All). Then tick AR Adjustment under Accounting.</span>
</div>

<div class="perm-grid" id="perm-tree">
<?php foreach ($module_groups as $group) {
	$group_id = $group['id'];
	$parent_key = isset($group['parent_key']) ? $group['parent_key'] : null;
	$children = isset($group['children']) ? $group['children'] : array();
	$has_children = !empty($children);
	$icon = isset($group['icon']) ? $group['icon'] : 'fa-folder';

	$parent_checked = false;
	$any_child_checked = false;
	$all_children_checked = $has_children;

	if ($parent_key) {
		$parent_checked = resp_is_checked($parent_key, $record, $permissions_mode);
	}
	foreach ($children as $child_key) {
		$child_on = resp_is_checked($child_key, $record, $permissions_mode);
		if ($child_on) {
			$any_child_checked = true;
		} else {
			$all_children_checked = false;
		}
	}
	if (!$has_children) {
		$all_children_checked = $parent_checked;
	}

	$virtual_checked = false;
	if (!$parent_key && $has_children) {
		$virtual_checked = $all_children_checked && $any_child_checked;
		$parent_checked = $virtual_checked || $any_child_checked;
	}

	$is_open = $has_children;
	$panel_class = 'perm-panel' . ($is_open && $has_children ? ' open' : '');
	$header_class = 'perm-panel-header' . ($has_children ? ' has-children' : ' no-children');
?>
	<div class="perm-card">
		<div class="<?php echo $panel_class; ?>" data-group="<?php echo htmlspecialchars($group_id); ?>">
			<div class="<?php echo $header_class; ?>">
				<?php if ($has_children) { ?>
					<span class="perm-toggle"><i class="fal <?php echo $is_open ? 'fa-chevron-down' : 'fa-chevron-right'; ?>"></i></span>
				<?php } else { ?>
					<span class="perm-toggle"><i class="fal fa-circle" style="font-size:6px;vertical-align:middle;"></i></span>
				<?php } ?>

				<?php if ($parent_key) { ?>
					<label>
						<input type="checkbox"
							class="perm-parent"
							id="module<?php echo htmlspecialchars($parent_key); ?>"
							name="module[]"
							value="<?php echo htmlspecialchars($parent_key); ?>"
							data-group="<?php echo htmlspecialchars($group_id); ?>"
							<?php echo resp_is_checked($parent_key, $record, $permissions_mode) ? 'checked' : ''; ?>
							<?php echo $disabled_attr; ?> />
						<i class="fal <?php echo htmlspecialchars(str_replace('fa-', 'fa-', $icon)); ?>"></i>
						<?php echo htmlspecialchars($group['label']); ?>
					</label>
				<?php } else { ?>
					<label>
						<input type="checkbox"
							class="perm-parent perm-virtual"
							data-group="<?php echo htmlspecialchars($group_id); ?>"
							<?php echo ($virtual_checked || ($any_child_checked && $all_children_checked)) ? 'checked' : ''; ?>
							<?php echo $disabled_attr; ?> />
						<i class="fal <?php echo htmlspecialchars($icon); ?>"></i>
						<?php echo htmlspecialchars($group['label']); ?>
					</label>
				<?php } ?>
			</div>

			<?php if ($has_children) { ?>
			<div class="perm-children">
				<?php foreach ($children as $child_key) {
					$child_label = isset($modules_name[$child_key]) ? $modules_name[$child_key] : $child_key;
				?>
				<div class="perm-child">
					<label>
						<input type="checkbox"
							class="perm-child-cb"
							id="module<?php echo htmlspecialchars($child_key); ?>"
							name="module[]"
							value="<?php echo htmlspecialchars($child_key); ?>"
							data-group="<?php echo htmlspecialchars($group_id); ?>"
							<?php echo resp_is_checked($child_key, $record, $permissions_mode) ? 'checked' : ''; ?>
							<?php echo $disabled_attr; ?> />
						<?php echo htmlspecialchars($child_label); ?>
					</label>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
</div>

<script type="text/javascript">
(function () {
	function onReady(fn) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', fn);
		} else {
			fn();
		}
	}

	function childBox(panel) {
		if (!panel) return null;
		var kids = panel.children;
		for (var i = 0; i < kids.length; i++) {
			if (kids[i].classList && (kids[i].classList.contains('perm-children') || kids[i].classList.contains('perm-children'))) {
				return kids[i];
			}
		}
		return panel.querySelector('.perm-children, .perm-children');
	}

	function setOpen(panel, open) {
		if (!panel || !childBox(panel)) return;
		var icon = panel.querySelector('.perm-panel-header .perm-toggle i');
		if (open) {
			panel.classList.add('open');
			if (icon) {
				icon.classList.remove('fa-chevron-right');
				icon.classList.add('fa-chevron-down');
			}
		} else {
			panel.classList.remove('open');
			if (icon) {
				icon.classList.remove('fa-chevron-down');
				icon.classList.add('fa-chevron-right');
			}
		}
	}

	function syncParent(panel) {
		var parent = panel.querySelector('.perm-panel-header .perm-parent');
		var box = childBox(panel);
		if (!parent || !box) return;
		var cbs = box.querySelectorAll('.perm-child-cb');
		if (!cbs.length) return;
		var checked = 0;
		for (var i = 0; i < cbs.length; i++) {
			if (cbs[i].checked) checked++;
		}
		parent.indeterminate = (checked > 0 && checked < cbs.length);
		parent.checked = (checked === cbs.length);
		if (checked === 0) parent.checked = false;
	}

	onReady(function () {
		var tree = document.getElementById('perm-tree');
		if (!tree) return;
		var panels = tree.querySelectorAll('.perm-panel');
		for (var p = 0; p < panels.length; p++) syncParent(panels[p]);

		tree.addEventListener('click', function (e) {
			var header = e.target.closest ? e.target.closest('.perm-panel-header.has-children') : null;
			if (!header || !tree.contains(header)) return;
			if (e.target && e.target.closest && e.target.closest('input[type="checkbox"]')) return;
			e.preventDefault();
			var panel = header.closest('.perm-panel');
			setOpen(panel, !panel.classList.contains('open'));
		});

		tree.addEventListener('change', function (e) {
			var t = e.target;
			if (!t || !t.classList) return;
			if (t.classList.contains('perm-parent')) {
				var panel = t.closest('.perm-panel');
				var box = childBox(panel);
				if (box) {
					var cbs = box.querySelectorAll('.perm-child-cb');
					for (var i = 0; i < cbs.length; i++) cbs[i].checked = t.checked;
					setOpen(panel, t.checked);
				}
				t.indeterminate = false;
			}
			if (t.classList.contains('perm-child-cb')) {
				var panel2 = t.closest('.perm-panel');
				syncParent(panel2);
				if (t.checked) setOpen(panel2, true);
			}
		});

		function allPanels(open) {
			var list = tree.querySelectorAll('.perm-panel');
			for (var i = 0; i < list.length; i++) setOpen(list[i], open);
		}

		var expand = document.getElementById('perm-expand-all');
		var collapse = document.getElementById('perm-collapse-all');
		var selectAll = document.getElementById('perm-select-all');
		var clearAll = document.getElementById('perm-clear-all');
		if (expand) expand.addEventListener('click', function () { allPanels(true); });
		if (collapse) collapse.addEventListener('click', function () { allPanels(false); });
		if (selectAll) selectAll.addEventListener('click', function () {
			var inputs = tree.querySelectorAll('.perm-parent, .perm-child-cb');
			for (var i = 0; i < inputs.length; i++) { inputs[i].checked = true; inputs[i].indeterminate = false; }
			allPanels(true);
		});
		if (clearAll) clearAll.addEventListener('click', function () {
			var inputs = tree.querySelectorAll('.perm-parent, .perm-child-cb');
			for (var i = 0; i < inputs.length; i++) { inputs[i].checked = false; inputs[i].indeterminate = false; }
			allPanels(false);
		});
	});
})();
</script>
