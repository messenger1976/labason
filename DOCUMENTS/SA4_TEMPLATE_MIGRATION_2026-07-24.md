# SmartAdmin 4 (SA4) Template Migration — labasonsandbox

**Date:** 2026-07-24  
**Branch:** `labasonsandbox`  
**Status:** Working tree changes (not committed as of end of day)  
**Purpose:** Reference guide for applying the same SA4 template upgrade to **waterbilling1**

---

## 1. Summary

Today’s work migrates the admin UI from **SmartAdmin 1.x / Bootstrap 3** to **SmartAdmin 4 / Bootstrap 4** (assets under `/sa4/`).

| Area | What changed |
|------|----------------|
| Shell | `header.php`, `navigation.php`, `footer.php`, `dashboard_header.php` |
| Assets | New untracked `sa4/` tree (`css/`, `js/`, `img/`, `webfonts/`, `media/`) |
| Bridge CSS | `sa4/css/legacy-bridge.css` — lets leftover SA1 markup survive inside SA4 |
| Views | ~202 master views wrapped/modernized to SA4 page chrome |
| Partials | New shared includes under `views/partials/` |
| Controllers | AJAX DataTables HTML for customers + payments restyled to SA4 badges/buttons |
| Dashboard | View heavily rewritten; controller/model touched on disk |
| Tools | Migration/repair scripts under `tools/` (dev helpers, not runtime) |

**Business logic was largely preserved.** This is primarily a template/layout/CSS/JS upgrade.

---

## 2. Files to copy / mirror into waterbilling1 (priority order)

### Phase A — Shell (do first)

1. Copy entire **`sa4/`** folder to waterbilling1 web root (same path: `/sa4/`).
2. Replace / merge:
   - `application/views/admin-includes/header.php`
   - `application/views/admin-includes/navigation.php`
   - `application/views/admin-includes/dashboard_header.php` (now just includes `header.php`)
   - `application/modules/master/views/footer.php`
3. Ensure logo/avatar paths exist (`img/pmroxas-logo.png`, `assets/avatars/avatar.png`) or update paths in navigation.

### Phase B — Shared partials

Copy:

```
application/modules/master/views/partials/sa4_kpi_subheader.php
application/modules/master/views/partials/sa4_dt_loading.php
application/modules/master/views/partials/sa4_dt_init.js.php
```

### Phase C — Views (bulk)

Either:

- **Copy migrated views** from labasonsandbox (fastest if waterbilling1 views match), or  
- **Re-run migrators** from `tools/` after adjusting hardcoded paths.

### Phase D — Controllers that emit HTML for DataTables

Merge carefully (not pure UI files):

- `application/modules/master/controllers/addcustomer.php` — status badges + action button groups
- `application/modules/master/controllers/addpaymentcustomer.php` — billing period POST filter + SA4 cell/action HTML

### Phase E — Dashboard

- `application/modules/master/views/dashboard.php` (major rewrite)
- Review `controllers/dashboard.php` + `models/dashboard_model.php` if present/changed locally

---

## 3. Shell architecture (SA4)

### 3.1 Header (`admin-includes/header.php`)

**Old:** SA1 CSS (`smartadmin-production*.css`), body classes like `smart-style-1 fixed-header`.

**New:**

- CSS: `sa4/css/vendors.bundle.css`, `app.bundle.css`, `skins/skin-master.css`, **`legacy-bridge.css`**
- Body: `mod-bg-1 mod-nav-link mod-skin-light header-function-fixed nav-function-fixed`
- Theme persistence via `localStorage.themeSettings`
- `$sa4 = base_url() . 'sa4/';`
- Spinner overlay kept (`showSpinner` / `hideSpinner`)

### 3.2 Navigation (`admin-includes/navigation.php`)

**Old:** `#left-panel` / SA1 menu markup.

**New SA4 structure:**

```html
<aside class="page-sidebar">
  <div class="page-logo">...</div>
  <nav id="js-primary-nav" class="primary-nav">
    <div class="nav-filter">...</div>
    <div class="info-card">...</div>
    <ul id="js-nav-menu" class="nav-menu">
      <li><a href="..."><i class="fal fa-..."></i><span class="nav-link-text">...</span></a></li>
    </ul>
  </nav>
</aside>
```

- Icons: Font Awesome Light (`fal fa-*`), not `fa fa-*`
- Permission checks (`$roleResponsible`) preserved
- Menu filter + user info card added

### 3.3 Footer (`master/views/footer.php`)

- Closes SA4 page wrapper
- `page-footer`, shortcut menu, `#js-color-profile` swatches (required by SA4 `app.bundle.js`)
- Scripts: `sa4/js/vendors.bundle.js`, `sa4/js/app.bundle.js`
- Includes a small harden patch for SA4 `rgb2hex` when color is undefined

### 3.4 `dashboard_header.php`

Collapsed to:

```php
<?php include __DIR__ . '/header.php'; ?>
```

---

## 4. View migration patterns

### 4.1 Target page chrome (every upgraded page)

Replace SA1 `#main` / `#ribbon` / `#content` with:

```php
<main id="js-page-content" role="main" class="page-content">
  <ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>">Home</a></li>
    <li class="breadcrumb-item active">Current</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block">
      <span class="js-get-date"></span>
    </li>
  </ol>

  <!-- subheader / KPIs -->
  <div class="subheader">...</div>
  <!-- or: include partials/sa4_kpi_subheader.php -->

  <div class="row">
    <div class="col-xl-12">
      <div class="panel" id="panel-...">
        <div class="panel-hdr">
          <h2>Title <span class="fw-300"><i>Listing</i></span></h2>
          <div class="panel-toolbar">
            <!-- Optional action buttons only (Add, Export, etc.). Panel chrome: pink close only (injected by smartPanel). -->
          </div>
        </div>
        <div class="panel-container show">
          <div class="panel-content">
            <!-- original table / form body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
```

### 4.2 Listing pages (DataTables)

Typical pattern after migration:

1. Set `$sa4_page_*` / `$sa4_dt_*` vars
2. Include `partials/sa4_kpi_subheader.php`
3. Panel + `#dt_basic` table
4. Include `partials/sa4_dt_loading.php` + `partials/sa4_dt_init.js.php`

Reference examples in labasonsandbox:

- **Hand-crafted / polished:** `addcustomer.php`, `add_zone.php`, `dashboard.php`, `addpaymentcustomer.php`
- **Bulk-migrated listing:** `addaccountgroup.php` (and most other list pages)

### 4.3 Form pages (add/edit)

- Outer chrome upgraded to SA4 `main` + breadcrumb + panel
- Inner form often still uses older BS3 patterns (`form-horizontal`, `input-group-addon`, `col-xs-*`)
- `legacy-bridge.css` makes these usable until forms are fully rewritten

Reference: `add_zone_add.php`

### 4.4 Class / markup renames applied by migrators

| Old (SA1 / BS3) | New (SA4 / BS4) |
|-----------------|-----------------|
| `label label-success` | `badge badge-success` |
| `label label-danger` | `badge badge-danger` |
| `btn-default` | `btn-secondary` |
| `col-xs-N` | `col-N` (bridge also maps xs) |
| `fa fa-*` | `fal fa-*` |
| `float-right` (preferred over `pull-right`) | used in places |
| Action icon clusters | `btn-group btn-group-sm` + `btn btn-outline-*` |

### 4.5 What was deferred / lightly touched

From inventory (`_sa4_inventory.txt`):

- **Mobile views** (`mobile_*`, `mobilenotifications*`) — deferred
- **Login / forgot password** — mostly skipped by bulk migrator; some password pages touched later
- **Print / PDF standalone** pages — chrome only or light cleanup
- **AJAX fragments** — class renames; not full redesign
- Backup/copy files (`*-05-aug`, `Copy.php`) — ignore

---

## 5. Controllers: DataTables HTML (important for waterbilling1)

Views are only half the story when rows are built in PHP controllers.

### `addcustomer.php` (AJAX list)

- Status: `badge badge-*-pill` instead of `label label-* arrowed`
- Actions: single `btn-group` with outline buttons + `fal` icons
- Removed old mobile dropdown action menu

### `addpaymentcustomer.php` (AJAX list)

- Billing period filter prefers **POST** `billing_period`, then session, then current
- Amounts / OR / dates wrapped in badges/spans for SA4 look
- Print/delete actions → outline button group
- `htmlspecialchars` on several cell values

When porting to waterbilling1, **diff these controllers carefully** — do not overwrite business query logic blindly.

---

## 6. Shared partials (API)

### `sa4_kpi_subheader.php`

Optional vars:

- `$sa4_page_icon` (default `fal fa-th-list`)
- `$sa4_page_title` / `$sa4_page_subtitle`

Computes income / expense / total customer via `$this->my_model` when available.

### `sa4_dt_init.js.php`

Optional vars:

- `$sa4_dt_entity` — loader label
- `$sa4_panel_id` — panel selector
- `$sa4_dt_export_cols` — export column indexes

Expects `#dt_basic` and loads SA4 DataTables + sparkline bundles.

### `sa4_dt_loading.php`

Loading modal/progress UI used by the init script.

---

## 7. `legacy-bridge.css` (keep until views are pure SA4)

Path: `sa4/css/legacy-bridge.css`

Key jobs:

- `#main`, `#ribbon`, `#content` layout inside SA4 wrapper
- Old `.panel` / `.jarviswidget` / `.well` / `.form-actions` styling
- `col-xs-*` → flex grid equivalents

Linked from header after skin CSS.

---

## 8. Migration tooling (labasonsandbox only)

Under `tools/` (paths currently hardcode `c:\xampp\htdocs\labasonsandbox`):

| Script | Role |
|--------|------|
| `sa4_migrate_views.js` / `.py` | Pass 1 — wrap list/form/report chrome |
| `sa4_migrate_pass2.js` | Pass 2 — leftover pages + ajax/print cleanup |
| `sa4_fix_dt_order.js` | DataTables column/order fixes |
| `sa4_wave_c_*.js` | Wave C polish / forms / repair / balance / verify |
| `restore_leakingentry_modal.js` | Restore leaking-entry modal from `tools/orig/` |
| `tools/orig/*` | Pre-migration backups of tricky pages |

Also created for inventory:

- `_sa4_inventory.txt` — categorization of all master views
- `_all_master_views.txt` — flat file list

For waterbilling1: either copy finished views, or retarget script `VIEWS` paths and re-run.

---

## 9. Recommended port order for waterbilling1

1. **Assets + shell** (`sa4/` + header/nav/footer) — verify login → dashboard loads.
2. **Partials** — empty listing page smoke-test with DT init.
3. **Flagship pages manually:** `dashboard`, `addcustomer`, `addpaymentcustomer`, `add_zone`, `leakingentry`.
4. **Bulk-copy or re-migrate** remaining list/form/report views.
5. **Controller HTML** for AJAX DataTables.
6. **Visual QA pass:** menus, modals, Select2, DataTables exports, print pages.
7. **Mobile / login** last (deferred in sandbox).

---

## 10. Smoke-test checklist

- [ ] Login redirects and SA4 skin loads (no missing `/sa4/` 404s)
- [ ] Sidebar filter + expand/collapse
- [ ] Theme/light-dark persistence (`localStorage`)
- [ ] Customer list AJAX + badges/actions
- [ ] Payment list + billing period filter + print buttons
- [ ] Add/edit forms submit (even if inner markup still “old”)
- [ ] Dashboard KPIs/charts
- [ ] Leaking entry modals (see `restore_leakingentry_modal.js` if broken)
- [ ] Footer shortcuts / logout
- [ ] Flash alerts (`alert-dismissible fade show`)

---

## 11. Known caveats / leftovers

- Many forms are **hybrid**: SA4 outer shell + old inner controls (intentional transitional state).
- Some list rows still keep a redundant mobile dropdown block from SA1 (migrator didn’t always strip it).
- Bulk migrator titles sometimes look crude (`Manage Addaccountgroup`) — polish titles/icons per page when porting flagship screens.
- `sa4/` and `tools/` are **untracked** in git as of this write-up — include them when committing or copying.
- Backup: `addpaymentcustomer_add.php.bak-layout`
- Old pattern matches still exist in places; new SA4 markers dominate (`js-page-content`, `panel-hdr`, `fal fa-*`).

---

## 12. Quick diff scope (2026-07-24 working tree)

```
Modified:
  application/views/admin-includes/{header,navigation,dashboard_header}.php
  application/modules/master/views/footer.php
  ~200 application/modules/master/views/*.php
  application/modules/master/controllers/{addcustomer,addpaymentcustomer}.php

Untracked (critical):
  sa4/
  application/modules/master/views/partials/
  tools/
  _sa4_inventory.txt
  _all_master_views.txt
```

No git commit was created for this migration on 2026-07-24; treat the working tree + this document as the source of truth until committed.

---

## 13. Reference “gold” files (study these first)

When updating waterbilling1, open these labasonsandbox files as templates:

1. `application/views/admin-includes/header.php`
2. `application/views/admin-includes/navigation.php`
3. `application/modules/master/views/footer.php`
4. `application/modules/master/views/addcustomer.php`
5. `application/modules/master/views/addpaymentcustomer.php`
6. `application/modules/master/views/dashboard.php`
7. `application/modules/master/views/add_zone.php`
8. `application/modules/master/views/partials/*`
9. `sa4/css/legacy-bridge.css`

---

*Generated as a wrap-up of labasonsandbox changes on 2026-07-24 for porting the SA4 template to waterbilling1.*
