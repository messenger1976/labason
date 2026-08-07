# AR Adjustment Module (Accounts Receivable Adjustment)

**Date:** 2026-08-07  
**Project:** labasonsandbox  
**Status:** Implemented  
**Menu:** Accounting → **AR Adjustment**  
**URL:** `/master/aradjustment`  
**Voucher series:** `ADJ-#####` (`tbl_doc_series_number.doc_name = 'AR_ADJ'`)

> Adjusts customer Statement of Account (SOA) balances with audited credit/debit memos, without faking meter payments or Official Receipts. Posted entries also write balanced GL lines to `tbl_transactions`.

---

## 1. Purpose

Use AR Adjustment when SOA shows an imbalance that should **not** be fixed through cashier collections, for example:

- Underpayment shortfall carried forward (e.g. bill ₱277.20, paid ₱252.00 → **₱25.20**)
- Billing error / overbill that needs a credit note
- Uncollectible balance write-off
- Underbill catch-up via debit memo

**Do not use** for normal payments (use OR / Payment modules) or leak-specific A/R (use Leaking Entry).

---

## 2. Workflow (Maker–Checker)

```
Maker creates Draft  →  Approver Posts  →  SOA + GL updated
                              ↓
                         Approver Voids  →  reversing GL; removed from SOA
```

| Status | Code | Meaning |
|--------|------|---------|
| Draft | `1` | Editable / deletable by maker |
| Posted | `2` | Appears on SOA; GL pair posted |
| Void | `3` | Excluded from SOA; reversing GL posted |

**Permissions**

| Responsibility column | Who | Actions |
|-----------------------|-----|---------|
| `ar_adjustment` | Billing / Accounting maker | List, view, add/edit draft, delete draft |
| `ar_adjustment_approve` | Accountant / Finance / Admin | Post, void |
| Admin `usertype` | System admin | Bypass (full access) |

Suggested role matrix:

| Role | `ar_adjustment` | `ar_adjustment_approve` |
|------|-----------------|-------------------------|
| Billing Officer | Yes | No |
| Accounting Staff | Yes | No |
| Accountant / Finance | Yes | Yes |
| General Manager / Admin | Yes | Yes |
| Cashier / Meter Reader | No | No |

Assign under **Roles & Responsibilities** (Accounting group). Migration enables both flags for role `id = 1`.

---

## 3. Adjustment types & SOA direction

| Type (`adj_type`) | SOA effect (`adj_direction`) | Typical use |
|-------------------|------------------------------|-------------|
| `credit_note` | Credit ↓ balance | Overbill / credit note |
| `billing_correction` | Credit ↓ balance | Period shortfall treated as billing fix |
| `write_off` | Credit ↓ balance | Uncollectible shortfall (recommended for ₱25.20 case) |
| `debit_memo` | Debit ↑ balance | Underbill / additional charge |

Amount is always stored **positive**; direction is derived from type.

---

## 4. Chart of Accounts (recommended)

Seed script: [`sql/ar_adjustment_coa_seed.sql`](../sql/ar_adjustment_coa_seed.sql)  
Safe to re-run (inserts only if ledger name missing).

### GL pairing guide

| Adjustment type | Debit | Credit |
|-----------------|-------|--------|
| Credit Note | Sales Returns and Allowances - Billing Credits | Accounts Receivable - Water Customers |
| Billing Correction | Sales Returns and Allowances - Billing Credits | Accounts Receivable - Water Customers |
| Write-off | Bad Debts Expense - AR Write-off | Accounts Receivable - Water Customers |
| Debit Memo | Accounts Receivable - Water Customers | Billing Adjustment Income |

### Ledgers seeded (labasonsandbox)

| Ledger name | Account group / subgroup |
|-------------|--------------------------|
| Accounts Receivable - Water Customers | Assets → ACCOUNTS RECEIVABLE |
| Water Sales - Metered Billing | P&L Income → Water Sales |
| Sales Returns and Allowances - Billing Credits | P&L Income → SALES RETURNS & ALLOWANCES |
| Bad Debts Expense - AR Write-off | P&L Expenses → INDIRECT EXPENSES |
| Billing Adjustment Income | P&L Income → INDIRECT INCOME |
| Cash in Bank | Assets → CASH & BANK BALANCE |

Also renamed duplicate subgroup **Water Sales** (id 26) → **SALES RETURNS & ALLOWANCES**.

The Add/Edit form **auto-fills** Dr/Cr when Adjustment Type changes (override allowed).

**Example — Sulatre ₱25.20 Dec 2025 shortfall**

1. Type: **Write-off**  
2. Customer: `11-7-12-01041`  
3. Amount: `25.20`  
4. Period: Month **12** / Year **2025**  
5. Dr: Bad Debts Expense - AR Write-off · Cr: Accounts Receivable - Water Customers  
6. Save Draft → Post  

Result: SOA running balance → **0.00**; pink underpaid highlight for that period clears when credit covers the shortfall.

---

## 5. Setup / migrations

Run on the target database (order matters):

```bash
# 1) Module table, doc series, permissions
mysql -u root labasonsandbox < sql/ar_adjustment_migration.sql

# 2) Recommended COA ledgers
mysql -u root labasonsandbox < sql/ar_adjustment_coa_seed.sql
```

Or via phpMyAdmin: execute both SQL files.

**Notes**

- If `ALTER TABLE ... ADD COLUMN ar_adjustment` fails because columns already exist, skip those `ALTER` lines and keep the `CREATE TABLE` / series / Admin `UPDATE`.
- After migration, confirm Admin role has both permissions, or grant them in Roles & Responsibilities.

---

## 6. Files

| Layer | Path |
|-------|------|
| Controller | `application/modules/master/controllers/aradjustment.php` |
| Model | `application/modules/master/models/aradjustment_model.php` |
| Views | `aradjustment.php`, `aradjustment_add.php`, `aradjustment_edit.php`, `aradjustment_view.php` |
| SOA model | `application/modules/master/models/statementofaccount_model.php` (posted adjustments) |
| SOA view | `application/modules/master/views/statementofaccount.php` (highlight includes adjustments) |
| Nav | `application/views/admin-includes/navigation.php` (Accounting menu) |
| Permissions registry | `adminheader_model.php`, `responsibilities.php`, `responsibilities_model.php` |
| SQL | `sql/ar_adjustment_migration.sql`, `sql/ar_adjustment_coa_seed.sql` |

### Controller endpoints

| Method | Path | Notes |
|--------|------|-------|
| `index` | `/master/aradjustment` | List |
| `add` | `/master/aradjustment/add` | Create draft |
| `edit/{id}` | Draft only | |
| `view/{id}` | Detail + Post/Void actions | |
| `post/{id}` | Requires approve permission | |
| `void_entry/{id}` | Requires approve permission | |
| `delete/{id}` | Draft only | |
| `search_customer` | AJAX JSON lookup | CI2-compatible `WHERE (... OR ...)` |

---

## 7. Data model — `tbl_ar_adjustment`

| Column | Notes |
|--------|-------|
| `adj_id`, `adj_no` | PK; unique voucher `ADJ-#####` |
| `customer_id` | Links to `tbl_addcustomer.customer_id` |
| `adj_type`, `adj_direction`, `adj_amount` | Type / SOA side / positive amount |
| `adj_date` | Transaction date |
| `month`, `year`, `bp_id`, `reading_refno` | Optional bill-period link (drives SOA period highlight) |
| `reason` (required), `remarks` | Audit text |
| `dr_ledger_id`, `cr_ledger_id` | Required before post |
| `status` | 1 draft / 2 posted / 3 void |
| `created_by*`, `posted_by*`, `voided_by*` + timestamps | Audit trail |

Does **not** insert fake rows into `tbl_addmetercustomer`.

---

## 8. SOA & GL integration

### Statement of Account

On `get_customer_ledger()`:

- Loads **posted** adjustments for the customer
- Emits ledger lines with `type = adjustment`
- Description example: `AR Adjustment ADJ-00012 - Write-off (Period 12/2025) — …`
- Credit adjustments reduce period shortfall; debit adjustments increase it
- Underpaid (pink) highlighting clears when payments + discounts + **credit adjustments** cover the bill for that period

Voided adjustments are excluded.

### General Ledger (`tbl_transactions`)

On **Post**, inserts two rows:

- `tableName = ar_adjustment`
- `voucherNo = adj_no`
- Debit line + Credit line (balanced)

On **Void**, posts reversing GL pair; original rows are retained for audit (not silently deleted).

---

## 9. Accounting menu visibility

Accounting sidebar shows when the user has **any** of:

- `addexpenses`
- `addledger`
- `ar_adjustment`

(or Admin usertype)

---

## 10. Out of scope

- Changing cashier / OR payment screens  
- Auto-creating adjustments from SOA highlight (manual encode with reason)  
- Bulk migration of historical underpayments  

---

## 11. Quick test checklist

1. Run both SQL migrations on the target DB.  
2. Login as Admin (or role with both permissions).  
3. Open **Accounting → AR Adjustment → Add**.  
4. Search customer (e.g. `sulatre` → `11-7-12-01041`).  
5. Choose **Write-off**, amount `25.20`, period 12/2025, reason filled; confirm Dr/Cr auto-fill.  
6. Save Draft → View → **Post**.  
7. Open customer SOA: balance **0.00**, Dec 2025 no longer underpaid-highlighted.  
8. (Optional) Void and confirm SOA restores shortfall and reversing GL exists.

---

## 12. Related documents

- [STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md](STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md)  
- [BILLING_AND_PAYMENT_COMPUTATION.md](BILLING_AND_PAYMENT_COMPUTATION.md)  
- [HIERARCHICAL_ROLES_AND_PERMISSIONS.md](HIERARCHICAL_ROLES_AND_PERMISSIONS.md)  

**Last validated:** 2026-08-07 (labasonsandbox local DB + module UI)
