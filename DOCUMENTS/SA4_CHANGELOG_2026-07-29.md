# SA4 UI Polish Changelog — labasonsandbox

**Period:** 2026-07-24 → 2026-07-30  
**Project:** Labason Water District admin (`labasonsandbox`)  
**Base migration guide:** [`SA4_TEMPLATE_MIGRATION_2026-07-24.md`](./SA4_TEMPLATE_MIGRATION_2026-07-24.md)  
**Reference layout:** Customers list (`/master/addcustomer`) — KPI header, SA4 panel, outline action buttons, DataTables, Bootstrap datepicker, SweetAlert2  

This document records **all UI polish and related fixes** after the initial SA4 shell migration. Business logic was preserved unless noted.

---

## 1. Goals of this pass

1. Make listing / form / report pages look like the **Customers** gold standard.
2. Standardize the KPI subheader to **Income / Expense / Total Customer**.
3. Fix meter income totals to use `SUM(grand_total)` (not `pay_amount`) wherever touched.
4. Replace legacy SA1 confirm/delete UX with **SweetAlert2**.
5. Finish hybrid pages that still had SA1 inner markup (forms, modals, action images).

---

## 2. Shared infrastructure

| File | Role |
|------|------|
| `views/partials/sa4_kpi_subheader.php` | Shared KPI header (Income / Expense / Total Customer) |
| `views/partials/sa4_dt_loading.php` | DataTables loading overlay |
| `views/partials/sa4_dt_init.js.php` | Shared SA4 DataTables + export buttons init |
| `views/partials/sa4_swal_delete.js.php` | Global SweetAlert2 delete interceptor + helpers |
| `views/footer.php` | Loads SA4 bundles; includes `sa4_swal_delete.js.php` after `app.bundle.js` |

### SweetAlert2 notes

- Uses **SweetAlert2 v9** semantics: confirm result is `result.value` (not `result.isConfirmed` from v10+).
- Helpers: `sa4ConfirmAction`, `sa4SwalConfirmed`, `deleteAllData()`.
- Global click interceptor handles common delete links/buttons.

### KPI income rule

When a page/model computes Income for the subheader:

- Meter billing: `SUM(grand_total)`  
- Monthly billing: `SUM(paidamount)` (unchanged)  
- Prefer `common_model` patterns where possible.

---

## 3. Standard page patterns

### Listing page

- Breadcrumb + 3-block KPI subheader  
- SA4 `panel` + `panel-hdr` + DataTable (`#dt_basic` or equivalent)  
- Status: `badge badge-*-pill`  
- Actions: `btn-group btn-group-sm` + `btn btn-outline-*` + `fal` icons  
- Optional: Copy / CSV / Excel / PDF / Print / Refresh buttons  

### Form page (add / edit)

- Same KPI header  
- Clean SA4 panel, usually **2-column** `form-group` / `form-label`  
- Bootstrap datepicker input groups (`dd-mm-yyyy`)  
- Cancel (secondary) + Submit (primary)  

### Report / search page

- Same KPI header  
- Filter form in SA4 panel (2-column)  
- Search / Print / Export kept  
- Results in clean DataTable or AJAX fragment  

---

## 4. Pages polished (by area)

### 4.1 Customers

| URL | View(s) | What changed |
|-----|---------|--------------|
| `/master/addcustomer` | `addcustomer.php` | Gold listing; SA4 modals shell (View / Edit / Password); datepicker assets for edit modal |
| `/master/addcustomer/add/` | `addcustomer_add.php` | Full SA4 2-col form; datepickers; AJAX ID/email/mobile + regenerate ID preserved |
| *(View popup)* | `addcustomer_view_modal.php` | Fixed BS4 header; correct Address/City/Mobile mapping; photo + name header; status badge |
| *(Edit popup)* | `addcustomer_edit_modal.php` | Full SA4 form rewrite; datepickers; billing-plan show/hide fixed; AJAX update contract preserved |

**Modal header rule (BS4):** title first, close button second (legacy BS3 order broke alignment).

---

### 4.2 Zones

| URL | View(s) / model | What changed |
|-----|-----------------|--------------|
| `/master/add_zone` | `add_zone.php`, `add_zone_model.php` | KPI header matched Customers (removed Total Zones / Active); income uses `grand_total` |

---

### 4.3 Master data / settings

| URL | View(s) | What changed |
|-----|---------|--------------|
| `/master/classification_category` | `classification_category.php` (+ add/edit) | Listing + forms; SweetAlert delete; KPI; income fix in model |
| `/master/classification` | `classification.php` (+ add/edit) | Same pattern; income → `SUM(grand_total)` |
| `/master/amountrate` | `amountrate.php`, `_add`, `_edit` | Listing keeps **server-side AJAX** DataTable (not only `sa4_dt_init`); SA4 badges/status/edit in controller; add/edit forms polished |
| `/master/global_settings` | `global_settings.php`, `global_settings-edit.php` | KPI + Edit-only list; clean edit form |
| `/master/addcustomer/adminconfiguration` | `adminconfiguration.php`, `_edit` | View/edit SA4 panels |
| Change username / password | `change-username.php`, `change-password.php` | SA4 form panels |
| `/master/database_backup` | `database_backup.php` | KPI listing layout |
| `/master/leakingentrycorrection` | `leakingentrycorrection.php` | KPI + SA4 DataTable + outline actions |

---

### 4.4 Employees / roles

| URL | View(s) | What changed |
|-----|---------|--------------|
| `/master/addemployee` | `addemployee.php` | Labels cleaned (“Employees”); same shared KPI/DT pattern |
| `/master/job_title` | `job_title.php` | KPI listing |
| `/master/employee-logins` | list + add/edit | SA4 polish |
| `/master/responsibilities` | list + add/edit/view/permissions | SA4 polish |

---

### 4.5 Technical problems

| URL | View(s) | What changed |
|-----|---------|--------------|
| `/master/technicalproblems` | `technicalproblems.php` | KPI; DataTable; outline Edit/Delete; Add link fixed to `technicalproblems/add/` |
| `/master/technicalproblems/add/` | `technicalproblems_add.php` | KPI; 2-col form; Select2 customer; datepicker; submit fixed |
| `/master/technicalproblems/edit/...` | `technicalproblems_edit.php` | SA4 form + message modal header |

---

### 4.6 Billing / meter / OR / balance

| URL / area | View(s) | What changed |
|------------|---------|--------------|
| Billing period | `addbillingperiod.php` (+ add/import/ajax) | SA4 listing/forms; AJAX delete + SweetAlert |
| Meter reading | `addmetercustomerreading*` | SA4 search/edit modals |
| OR correction | `or_correction.php`, `_edit` | SA4 polish |
| Manual OR series | `manual_or_series.php` | SA4 polish |
| Create balance forward | `createbalanceforward.php` (+ ajax) | SA4 polish |
| Leaking entry | `leakingentry.php`, ledger views | SA4 panel/modals where touched |

---

### 4.7 Reports & monitors

| URL | View(s) | What changed |
|-----|---------|--------------|
| `/master/adddailyreport` | `adddailyreport_add.php` (+ ajax) | KPI; 2-col form; Search/Print/Export; income `grand_total` |
| `/master/adddailyreportnogrouping` | `adddailyreportnogrouping_*` | Same layout (no zone/cashier filters) |
| `/master/reports/monthly_billing_report` | `monthly_billing_report*` | KPI; 2-col filters; clean results + category table |
| `/master/reports/customer_report` | `customer_report*` | KPI; checkbox; status badges in results |
| `/master/reports/aging_ar_report` | `aging_ar_report*` | KPI; datepicker; Display/Print/Export |
| `/master/reports/arrears_monitoring_report` | `arrears_monitoring_report*` | KPI; datepicker; Update Arrears kept |
| `/master/reports/customer_payment_monitoring_report` | `customer_payment_monitoring_report*` | KPI; Status/Zone filters; pagination; fixed duplicated footer/scripts |
| `/master/customerbalancemonitor` | `customerbalancemonitor*` | KPI; checkboxes; batch load + progress; Display restored |
| `/master/reports/monthly_income_report_analytic` | `monthly_income_report_analytic.php` | KPI; Month/Year form; chart/export kept |

---

### 4.8 Mobile notifications / web settings / profile / auth

| URL / area | View(s) | What changed |
|------------|---------|--------------|
| Mobile notifications | `mobilenotifications.php`, dashboard, settings | SA4 panels + modals |
| Web settings | `web_settings.php`, `_edit` | SA4 polish |
| Profile | `profile.php` (+ controller/model as needed) | SA4 polish |
| Login / forgot password | `login.php`, `forgot-password.php` | Light SA4 / branding touch |

---

## 5. Controllers & models touched (non-view)

These emit HTML for AJAX DataTables or compute KPIs — merge carefully into waterbilling1:

### Controllers

- `addcustomer.php` — list badges/actions; `view_ajax` / `edit_ajax` / `update_ajax` still used by modals  
- `addpaymentcustomer.php` — billing period filter + SA4 cell HTML  
- `amountrate.php` — DataTable cell HTML (badges/status/edit); do **not** replace with client-only `sa4_dt_init` alone  
- `addbillingperiod.php` — AJAX delete integration  
- `addmetercustomerreading.php` — modal/search related  
- `profile.php` — as needed for profile UI  

### Models (income / KPI fixes to `grand_total` or shared totals)

Examples touched in this wave:

- `add_zone_model.php`  
- `adddailyreport_model.php`  
- `classification_model.php` / `classification_category_model.php`  
- `amountrate_model.php`  
- `technicalproblems_model.php`  
- `job_title_model.php`, `addemployee_model.php`, `employee_logins_model.php`  
- `web_settings_model.php`, `mobilenotifications_model.php`  
- `createbalanceforward_model.php`, `or_correction_model.php`, `manual_or_series_model.php`  
- `addbillingperiod_model.php`  
- Prefer aligning remaining models with `common_model.php` (`SUM(grand_total)`).

---

## 6. Bug fixes worth remembering

| Issue | Fix |
|-------|-----|
| View Customer modal title/`×` stuck on the right | BS4 modal-header order: `<h5>` then `.close` |
| View modal City showed Mobile value | Map fields correctly: Address→`address`, City→`city`, Mobile→`mobile1` |
| Edit modal legacy addons / broken layout | Rewrote to SA4 2-col form + bootstrap datepicker |
| Edit modal billing plans show/hide inverted / empty wrapper | Wrap `#showcustomers_modal` around billing plans; show for monthly, hide for meter |
| SweetAlert confirm never navigated | Use Swal **v9** `result.value`, not `isConfirmed` |
| Zones header had 4 KPIs unlike Customers | Reduced to Income / Expense / Total Customer |
| Amount Rate “Delete Selected” | Removed broken control where applicable |
| Technical Problems Add button wrong URL | Point to `technicalproblems/add/` |
| Customer Payment Monitoring broken scripts | Removed duplicated footer/script markup |
| Customer Balance Monitor Display missing | Restored JS + batch loader |

---

## 7. Field / contract preservation (do not break)

When restyling forms/modals, keep:

- POST field names (`special_priviledge` spelling included)  
- Hidden IDs (`id`, `original_customer_id`)  
- AJAX endpoints (`view_ajax`, `edit_ajax`, `update_ajax`, uniqueness checks, ID regenerate)  
- Server-side DataTables for Amount Rate  
- Report Search / Print / Export behaviors  

---

## 8. Smoke-test checklist (polish pass)

- [ ] `/master/addcustomer` — list, View modal, Edit modal, Password modal, Add page  
- [ ] `/master/add_zone` — header matches Customers KPIs; income matches  
- [ ] Classification Category / Classification / Amount Rate — CRUD + delete confirm  
- [ ] Technical Problems — list, add, edit  
- [ ] Daily / Monthly / Aging / Arrears / Payment monitoring / Balance monitor reports  
- [ ] Global Settings, Admin Configuration, Change Username/Password  
- [ ] Delete actions show SweetAlert and proceed on confirm  
- [ ] Datepickers open and format `dd-mm-yyyy`  
- [ ] No missing `/sa4/` asset 404s  

---

## 9. Porting notes for waterbilling1

1. Keep using [`SA4_TEMPLATE_MIGRATION_2026-07-24.md`](./SA4_TEMPLATE_MIGRATION_2026-07-24.md) for shell + bulk migration.  
2. Use this changelog as the **second wave**: polish flagship + master + reports.  
3. Copy polished views/partials from labasonsandbox, then **diff controllers/models** (do not overwrite query logic blindly).  
4. Gold references after this pass:
   - `addcustomer.php` + `addcustomer_add.php` + view/edit modals  
   - `add_zone.php` (header parity)  
   - `partials/sa4_*.php`  
   - A polished report e.g. `adddailyreport_add.php`  

---

## 10. Related commits (labasonsandbox)

Approximate history covering template work:

| Commit | Message |
|--------|---------|
| `8e4256b` | Update admin template |
| `6ab1046` | Update favicon and login logo |
| `0a40acc` | Update admin panel template |
| `50157bc` | Update admin panel template (includes customer add/modals + zone header) |

Working tree may still contain uncommitted polish depending on deploy method; treat live sandbox + this document as the checklist until everything is committed.

---

*Document generated 2026-07-30 to catalog SA4 polish changes in labasonsandbox for reference and waterbilling1 porting.*
