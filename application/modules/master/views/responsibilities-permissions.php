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
	overflow: hidden;
	height: 100%;
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
.perm-children { display: none; padding: 0.5rem 0.75rem 0.625rem 2rem; background: #fff; }
.perm-panel.open > .perm-children { display: block; }
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
	<span class="perm-hint">Check a main menu to expand and assign its submenu permissions.</span>
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

	$is_open = $any_child_checked || ($parent_checked && $has_children && $permissions_mode !== 'add');
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
(function($) {
	function syncParentState($panel) {
		var $parent = $panel.find('> .perm-panel-header .perm-parent');
		var $children = $panel.find('> .perm-children .perm-child-cb');
		if (!$children.length) {
			$parent.prop('indeterminate', false);
			return;
		}
		var total = $children.length;
		var checked = $children.filter(':checked').length;
		if (checked === 0) {
			$parent.prop('checked', false).prop('indeterminate', false);
		} else if (checked === total) {
			$parent.prop('checked', true).prop('indeterminate', false);
		} else {
			$parent.prop('checked', true).prop('indeterminate', true);
		}
	}

	function setOpen($panel, open) {
		var $toggle = $panel.find('> .perm-panel-header .perm-toggle i');
		if (!$panel.find('> .perm-children').length) return;
		if (open) {
			$panel.addClass('open');
			$toggle.removeClass('fa-chevron-right').addClass('fa-chevron-down');
		} else {
			$panel.removeClass('open');
			$toggle.removeClass('fa-chevron-down').addClass('fa-chevron-right');
		}
	}

	$(document).ready(function() {
		$('#perm-tree .perm-panel').each(function() { syncParentState($(this)); });

		$('#perm-tree').on('click', '.perm-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $panel = $(this).closest('.perm-panel');
			setOpen($panel, !$panel.hasClass('open'));
		});

		$('#perm-tree').on('change', '.perm-parent', function() {
			var $panel = $(this).closest('.perm-panel');
			var checked = $(this).prop('checked');
			var $children = $panel.find('> .perm-children .perm-child-cb');
			if ($children.length) {
				$children.prop('checked', checked);
				setOpen($panel, checked);
			}
			$(this).prop('indeterminate', false);
		});

		$('#perm-tree').on('change', '.perm-child-cb', function() {
			var $panel = $(this).closest('.perm-panel');
			var $parent = $panel.find('> .perm-panel-header .perm-parent');
			if ($parent.length && !$parent.hasClass('perm-virtual')) {
				if ($panel.find('> .perm-children .perm-child-cb:checked').length > 0) {
					$parent.prop('checked', true);
				}
			}
			syncParentState($panel);
			if ($(this).prop('checked')) { setOpen($panel, true); }
		});

		$('#perm-select-all').on('click', function() {
			$('#perm-tree .perm-parent, #perm-tree .perm-child-cb').prop('checked', true).prop('indeterminate', false);
			$('#perm-tree .perm-panel').each(function() { setOpen($(this), true); });
		});
		$('#perm-clear-all').on('click', function() {
			$('#perm-tree .perm-parent, #perm-tree .perm-child-cb').prop('checked', false).prop('indeterminate', false);
			$('#perm-tree .perm-panel').each(function() { setOpen($(this), false); });
		});
		$('#perm-expand-all').on('click', function() {
			$('#perm-tree .perm-panel').each(function() { setOpen($(this), true); });
		});
		$('#perm-collapse-all').on('click', function() {
			$('#perm-tree .perm-panel').each(function() { setOpen($(this), false); });
		});
	});
})(jQuery);
</script>
