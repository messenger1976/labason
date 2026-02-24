# Billing and Payment Computation Guide

This document describes how billing amounts, franchise tax, penalty (Amt after due date), and VAT are computed in the labasonsandbox water billing application.

---

## Table of Contents

1. [Meter Reading / Billing Computation](#1-meter-reading--billing-computation)
2. [Franchise Tax](#2-franchise-tax)
3. [Amt Before Due Date (Total Amount)](#3-amt-before-due-date-total-amount)
4. [Amt After Due Date (Penalty)](#4-amt-after-due-date-penalty)
5. [VAT (Payment Flow)](#5-vat-payment-flow)
6. [Where Each Computation Lives](#6-where-each-computation-lives)

---

## 1. Meter Reading / Billing Computation

### Consumed

- **Formula:** `consumed = current_reading - previous_reading`
- Consumed is the number of cubic meters used for the period.

### Current Bill (Unit Price)

- **Source:** Server lookup via `addmetercustomerreading/get_cubic_meter_price/`.
- **Inputs:** `cubic_meter_reading` (consumed), `customer_id`.
- **Backend:** Model `get_cust_class_id()` gets customer classification; `get_unit_price(consumed, classification)` looks up `tbl_amountrate` by `cubic_meter` and `classification_id`, and returns **`per_unit`** (the rate for that consumption tier).
- **Result:** Current Bill = `per_unit` (formatted).

### Senior Citizen (SC) Discount

- **Rule:** 5% discount only if **customer type = 3 (Senior Citizen)** AND **consumed ≤ 30** cubic meters.
- **Formula:** `sc_discount = current_bill × 5%` when eligible; otherwise `0`.
- If consumption exceeds 30 cubic meters, SC discount is not applied.

---

## 2. Franchise Tax

### Franchise Tax Percentage

- **Source:** From form field `#franchise_fee_percent`, or default from PHP `$franchise_fee_percentage` (often `2.00`), or from global settings `tbl_global_settings` where `code = 'FRANCHISE_FEE_PERCENTAGE'` (model: `get_franchise_fee_percentage()`). Default fallback: **2.00%**.

### Franchise Tax Amount

- **Business rule:** Franchise tax is **always** computed on the **current bill** (unit price), **before** any senior citizen discount.
- **Formula:** `franchise_fee_amount = current_bill × (franchise_fee_percent / 100)`
- Senior citizen discount is applied when computing the **total**, but franchise tax is based on the current bill only.

---

## 3. Amt Before Due Date (Total Amount)

- **Formula:**  
  `total_amount = current_bill - sc_discount + maintenance_fee + franchise_fee_amount`
- This is the amount the customer pays if paid on or before the due date.

---

## 4. Amt After Due Date (Penalty)

### Rule

- If **special privilege = '0'** (no privilege): apply 10% penalty.
- If customer has special privilege: **Amt after due date = Amt before due date** (no penalty).

### Penalty Formula (When No Special Privilege)

- **10% is applied only to** `(current_bill - sc_discount)`, **not** to maintenance fee or franchise tax.
- **Formula:**  
  `penalty_base = current_bill - sc_discount`  
  `amount_after_due_date = (penalty_base × 1.10) + maintenance_fee + franchise_fee_amount`
- So: penalty = 10% of the discounted bill; maintenance and franchise are added without penalty.

### Summary Table

| Condition                    | Amt after due date |
|----------------------------|--------------------|
| Special privilege = '0'    | `(current_bill - sc_discount) × 1.10 + maintenance_fee + franchise_fee_amount` |
| Special privilege ≠ '0'    | Same as Amt before due date |

---

## 5. VAT (Payment Flow)

VAT is used in the **payment** flow (Add Payment), not in meter-reading or billing. It appears on the Add Payment form as **VAT %** and **VAT Amount** (often labeled “VAT Discount”).

### Base Amount for VAT

- **When `total_total_amount != 0`:** Base = `total_total_amount` (minus `leaking_amount` if any).
- **When `total_total_amount == 0`:** Base = **`paid_total_amount`** (minus `leaking_amount` if any).  
  `paid_total_amount` is the amount due for the bill and **may already include penalty** if the transaction date is after the due date.

### VAT Amount Formula

- **Formula:** `vat_amount = base_amount × (vat_percent / 100)`
- VAT is treated as a **deduction** from the amount due.

### Grand Total After VAT

- **Formula:** `grand_total = base_amount - vat_amount + leaking_balance`
- So: amount due minus VAT, plus any leaking balance.

### When There Is Already a Penalty

- When the user pays **after the due date** (and has no special privilege), `recalculatePenaltyIfNeeded()` sets:  
  `paid_total_amount = base_amount + (base_amount × 10%)`.
- The VAT % blur handler calls `recalculatePenaltyIfNeeded()` first, then uses the **current** `paid_total_amount` as the base for VAT.
- **Therefore:** When there is already a penalty, **VAT is computed on the amount that already includes the penalty** (the “Amt after due date”), not on the pre-penalty amount.
- **Order:** Penalty is applied first (into `paid_total_amount`), then VAT is calculated on that penalized amount, then grand total = penalized amount − VAT + leaking_balance.

---

## 6. Where Each Computation Lives

| Feature              | Location (labasonsandbox) |
|----------------------|---------------------------|
| Edit popup (Meter Reading Correction) | `application/modules/master/views/addmetercustomerreading_search.php` — `#myModal`, `recalculateFranchiseFeeAndTotals()` |
| Add Billing Period popup              | Same file — `#addBillingModal`, `recalculateFranchiseFeeAndTotalsAdd()` |
| sync_import (billing period import)   | `application/modules/master/models/addmetercustomerreading_model.php` — `update_meterreading()` |
| Mobile dashboard billing              | `application/modules/master/views/mobile_dashboard.php` — `compute_all()` success callback |
| Franchise tax % (default)             | `addmetercustomerreading_model.php` — `get_franchise_fee_percentage()` (from `tbl_global_settings`) |
| VAT computation                       | `application/modules/master/views/addpaymentcustomer_add.php` — `#vat_percent` blur handler |
| Penalty on payment (transaction date) | Same file — `recalculatePenaltyIfNeeded()`, `#transdate` blur/change |

---

*Document generated from billing and payment logic in the labasonsandbox codebase. Last updated: February 2025.*
