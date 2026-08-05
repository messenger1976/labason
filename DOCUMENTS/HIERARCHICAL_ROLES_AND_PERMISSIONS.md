# Hierarchical Roles & Permissions

**Audience:** System Administrators, Developers, AI Agents, Future Maintainers  
**Owner role:** Platform / Billing System Maintainer  
**Lifecycle:** maintained  
**Related architecture:** Water Billing System — Admin authorization model  
**Last validated:** 2026-07-23  
**Evidence:** CONFIRMED — CODE + DATABASE SCHEMA  
**Systems:** `waterbilling1`, `labasonsandbox`

---

## Purpose and scope

This document explains the **hierarchical Roles & Responsibilities** permission model used by the Labason / Water Billing admin panel. It records architectural intent, how permissions map to the sidebar navigation, how they are stored and enforced, and how operators should assign roles to sub-admins.

It does **not** cover:

- Employee Login account creation (except role assignment linkage)
- Setting menu items (Change Username / Password — always available)
- Mobile navigation overhaul
- Legacy ecommerce permission library (`application/libraries/permission.php`)

---

## Audience

| Reader | When to use this |
|--------|------------------|
| Admin / HR | Creating or editing roles (e.g. Cashier/Teller) |
| Developer | Adding a new menu item that must be permission-gated |
| AI Agent | Before changing auth, navigation, or responsibilities UI |
| Auditor | Understanding who can access which Admin/Finance modules |

---

## Core concepts

### Authority split

| Layer | Role |
|-------|------|
| **This document (Company Knowledge)** | Architectural intent and verified behaviour |
| **Source code + live DB schema** | Implementation truth — what actually runs |

Claims below are labelled **verified** unless marked otherwise.

### Users and roles

| Concept | Storage | Meaning |
|---------|---------|---------|
| Primary Admin | `tbl_admin_details` (`usertype` = `admin`) | Full access; bypasses most permission checks |
| Sub-admin | `tbl_responsibilities_user` | Employee login account linked to one role via `role_id` |
| Role | `tbl_responsibilities` | Named permission set (one column per module key, typically `0` / `1`) |

Permissions are **not** stored in the session as a map. On each request for a sub-admin, `$roleResponsible` is loaded from the role row and used by navigation and controllers.

### Hierarchical UI (target doctrine = current implementation)

The Roles Add / Edit / View screens present permissions as a **sidebar-aligned tree**:

1. **Main menu** card (e.g. Admin, Finance, Customers)
2. Checking a main menu **expands** its submenu and **selects all children**
3. Unchecking a main menu **collapses** and **clears** children
4. Individual children can be toggled; parent stays checked when any child remains selected
5. Parents with only some children checked show an **indeterminate** state
6. Toolbar: Select All / Clear All / Expand All / Collapse All

**Verified:** Submenu listings under Admin mirror `application/views/admin-includes/navigation.php`.

### Parent gate vs child keys

For **Admin**:

- Parent key `admin` MUST be `1` for the Admin section to appear in the sidebar.
- Child keys (e.g. `responsibilities`, `employee_logins`, `or_correction`) control individual Admin submenu links.
- A role with `admin = 1` but all children `0` sees an empty Admin section (parent visible, no items).

For groups without a saved parent key (Customers, Finance, Reports), the main-menu checkbox is **virtual** (UI only) and does not POST a column; only child keys are saved.

---

## Permission tree (sidebar map)

| Main menu | Parent key (saved) | Child keys |
|-----------|-------------------|------------|
| Dashboard | `dashboard` | — |
| Customers | *(virtual)* | `addcustomer`, `add_zone` |
| Finance | *(virtual)* | `addmetercustomerreading`, `addpaymentcustomer`, `leakingentry`, `feesplaning`, `paymentmonthlycustomer`, `metersearch`, `monthlysearch`, `generatemetercustomer_search`, `income_reportsearch`, `paidsearch`, `unpaidsearch` |
| Employee | `addemployee` | `payrols`, `payrolssearch` (nav payroll often commented; Job Title uses same parent gate) |
| Expenses | `addexpenses` | `bsearch` (Accounting menu also gated by `addexpenses`) |
| Reports | *(virtual)* | `adddailyreport`, `customerbalancemonitor` |
| Assets | `addassets` | — |
| Ledger | `addledger` | — |
| Technical Problems | `technicalproblems` | `technicalsearch` |
| Admin Address | `web_settings` | — |
| Mobile Notifications | `mobile_notifications` | — |
| Admin | `admin` | `responsibilities`, `employee_logins`, `manual_or_series`, `addbillingperiod`, `createbalanceforward`, `or_correction`, `meter_reading_correction`, `leaking_entry_correction`, `database_backup`, `classification_category`, `classification`, `amountrate` |
| Setting | — | Not assignable (admin-only Global Settings / Admin Configuration; username/password always shown) |

---

## How to apply

### Operator — create or edit a role

1. Sign in as **admin** (or a sub-admin with Admin → Roles & Responsibilities).
2. Open **Admin → Roles & Responsibilities**.
3. **Add** a new role or **Edit** an existing one.
4. Enter **Role Name**.
5. Use the permission tree:
   - Check a main menu (e.g. **Admin**) to expand and select all submenu items, **or**
   - Expand via the chevron and tick only the needed children.
6. Save (**Add** / **Edit**).
7. Assign the role under **Admin → Employee Login** (`role_id` + `role_name`).

### Developer — add a new permission-gated menu item

1. Choose a stable **permission key** (snake_case, matching controller/module naming where possible).
2. Add the key to:
   - `responsibilities::module_name()` (labels)
   - `responsibilities::module_groups()` (hierarchy placement)
   - `responsibilities_model::module_name()` (defaults `0` for update/clear)
   - `adminheader_model::module_name()` (load into `$roleResponsible`)
3. Add a column on `tbl_responsibilities` (VARCHAR(10) DEFAULT `'0'`). Prefer the idempotent script under `sql/add_hierarchical_permission_columns.sql` as a pattern.
4. Gate the nav item in `application/views/admin-includes/navigation.php` with:

   ```php
   array_key_exists('your_key', $roleResponsible) && $roleResponsible['your_key'] == 1
   || $this->session->userdata('usertype') == 'admin'
   ```

5. Optionally enforce in the controller via `adminheader_model::get_responsibilities_conditions(...)`.
6. Update this document and re-validate.

### Database migration

Script (safe to re-run):

- `sql/add_hierarchical_permission_columns.sql`

Apply to the active database (`waterbilling1` and/or `labasonsandbox` per environment config).

**Note:** New columns default to `0`. Existing roles that previously relied on ungated Admin children (e.g. OR Correction when only `admin = 1`) MUST be re-edited to grant those child keys explicitly.

---

## Data flow

```text
Employee Login (tbl_responsibilities_user.role_id)
        │
        ▼
tbl_responsibilities  (one column per permission key = 0|1)
        │
        ▼
adminheader_model::get_responsibilities()
        │
        ▼
$header / $roleResponsible  ──►  navigation.php gates
                              ──►  controller conditions
```

Form POST remains compatible: checked boxes submit as `module[]` = permission key; unchecked keys are written as `0` on update via `array_diff_key` against model defaults.

---

## Key files (verified)

| Area | Path |
|------|------|
| Controller | `application/modules/master/controllers/responsibilities.php` |
| Model (CRUD + defaults) | `application/modules/master/models/responsibilities_model.php` |
| Model (runtime load) | `application/modules/master/models/adminheader_model.php` |
| Permission tree partial | `application/modules/master/views/responsibilities-permissions.php` |
| Add / Edit / View | `responsibilities-add.php`, `responsibilities-edit.php`, `responsibilities-view.php` |
| Sidebar gates | `application/views/admin-includes/navigation.php` |
| Migration SQL | `sql/add_hierarchical_permission_columns.sql` |
| Tables | `tbl_responsibilities`, `tbl_responsibilities_user` |

---

## Responsibilities

| Role | Duty |
|------|------|
| System Admin | Define roles; grant least privilege; re-check roles after schema upgrades |
| Developer | Keep `module_name()`, `module_groups()`, DB columns, and nav gates in sync |
| Document owner | Update this file when permission architecture or keys change |
| AI Agent | Read this before changing auth/nav; verify against code; do not invent keys |

---

## Examples

### Cashier / Teller (typical)

- Dashboard
- Finance children needed for billing/collection (e.g. Meter Bills, Paid/Unpaid Search)
- Reports → Daily Reports and/or Customer Balance Monitor as required
- **Do not** grant Admin → Database Backup / Roles & Responsibilities unless intentional

### Office Admin with setup duties

- Admin parent + selected children: Setup Schedule Billing Period, Manual OR Series, Classification, Per Unit Value
- Exclude correction tools and Database Backup if not required

---

## Anti-patterns

| Avoid | Why |
|-------|-----|
| Granting only child Admin keys without `admin = 1` | Admin section never appears |
| Adding a nav link without a Roles checkbox key | Cannot be granted from UI |
| Adding a checkbox key without a DB column | Save/load fails or silently drops the field |
| Updating only the controller `module_name()` and not the model / header model | Update clears wrong set; runtime `$roleResponsible` incomplete |
| Relying on “any subadmin” open gates in Reports without reviewing | Over-permission vs least privilege |
| Using legacy `application/libraries/permission.php` | Unrelated ecommerce permission tables; not this billing flow |

---

## Related documents

- Project manuals: [WATER_BILLING_SYSTEM_MANUAL.md](WATER_BILLING_SYSTEM_MANUAL.md), [DEVELOPER_TECHNICAL_GUIDE.md](DEVELOPER_TECHNICAL_GUIDE.md), [QUICK_REFERENCE_GUIDE.md](QUICK_REFERENCE_GUIDE.md)
- Related modules: [DATABASE_BACKUP_MODULE_README.md](DATABASE_BACKUP_MODULE_README.md), [MOBILE_NOTIFICATIONS_MODULE_README.md](MOBILE_NOTIFICATIONS_MODULE_README.md)
- Index: [README.md](README.md)
- Company Knowledge usage doctrine: consult architectural docs before non-trivial auth changes; verify claims in code

---

## Implementation status

| Item | Status |
|------|--------|
| Hierarchical permission UI (add/edit/view) | Implemented |
| `module_groups()` sidebar mirror | Implemented |
| New Admin/Finance permission columns | Migrated on `waterbilling1` + `labasonsandbox` |
| Nav gates for previously ungated Admin/Finance items | Implemented |
| Setting menu permissions | Out of scope (unchanged) |
| Mobile nav full parity | Out of scope |

---

## Confidence footer

| Domain | Confidence |
|--------|------------|
| Business intent | 85 |
| Architecture | 90 |
| Implementation | 90 |
| Database | 90 |
| Ops | 75 |
| Security | 70 |

**Unresolved / watch:**

- Some Reports items still allow broad `subadmin` visibility alongside `adddailyreport` — review if tightening is required.
- Existing production roles may need a one-time re-grant of newly gated Admin children after upgrade.

**Last validated:** 2026-07-23

---

## Maintenance

| Trigger | Action |
|---------|--------|
| New sidebar menu item | Update keys, groups, DB column, nav gate, this document |
| Permission UX change | Update Core concepts + How to apply; bump Last validated |
| Supersede this edition | Move prior file to `DOCUMENTS/ARCHIVE/` and leave a stub link here |

**Owner:** Platform / Billing System Maintainer  
**Review cadence:** After each auth or navigation change affecting sub-admins
