# Senior Citizen Discount Implementation Documentation

**Date Created:** January 29, 2026  
**Modified By:** AI Assistant  
**Version:** 1.0

---

## Table of Contents

1. [Overview](#overview)
2. [Business Rule](#business-rule)
3. [Implementation Details](#implementation-details)
4. [Files Modified](#files-modified)
5. [Technical Specifications](#technical-specifications)
6. [Testing Guidelines](#testing-guidelines)
7. [Code Examples](#code-examples)

---

## Overview

This documentation describes the implementation of a business rule that restricts senior citizen discount eligibility based on water consumption. The discount is only applicable when consumption does not exceed 30 cubic meters per billing period.

### Purpose

To prevent abuse of the senior citizen discount privilege for excessive water consumption while maintaining fair pricing for legitimate senior citizen customers.

### Scope

This change affects all billing calculation points in the system:
- Backend API endpoints
- Web-based edit forms
- Mobile dashboard interface

---

## Business Rule

### Rule Statement

**Senior citizens cannot avail the 5% discount if their water consumption exceeds 30 cubic meters per billing period.**

### Conditions

The senior citizen discount (5%) is applied **ONLY** when **ALL** of the following conditions are met:

1. Customer account type is Senior Citizen (`account_type == 3`)
2. Consumption (current_reading - previous_reading) ≤ 30 cubic meters

### Discount Calculation

- **If eligible:** Discount = (Unit Price × 5) / 100
- **If not eligible:** Discount = 0

### Franchise Fee Impact

Franchise fee (2% of bill amount) calculation is also affected:
- **If SC discount applies:** Franchise fee calculated on (Unit Price - SC Discount)
- **If SC discount does NOT apply:** Franchise fee calculated on full Unit Price

---

## Implementation Details

### Logic Flow

```
1. Calculate consumption = current_reading - previous_reading
2. Check if customer is senior citizen (account_type == 3)
3. If senior citizen AND consumption <= 30:
   - Apply 5% discount
   - Calculate franchise fee on (unit_price - discount)
4. Else:
   - No discount (discount = 0)
   - Calculate franchise fee on full unit_price
5. Calculate total amount including maintenance fee and franchise fee
```

### Key Changes

1. **Consumption Check Added:** All discount calculations now verify consumption ≤ 30 cubic meters
2. **Franchise Fee Logic Updated:** Franchise fee calculation respects the discount eligibility
3. **Consistent Implementation:** Same logic applied across all calculation points

---

## Files Modified

### 1. Backend Model: `addmetercustomerreading_model.php`

**Location:** `application/modules/master/models/addmetercustomerreading_model.php`

**Function:** `update_meterreading()`

**Changes:**
- Added consumption check (`$consumed <= 30`) in discount calculation
- Updated franchise fee calculation to respect consumption limit
- Added comprehensive documentation comments

**Lines Modified:** 489-542

**Key Code:**
```php
// Senior Citizen Discount Calculation
$discount = 0;
if($customerinfo[0]['account_type']==3 && $consumed <= 30){
    $discount = ($cubicmeter_rate->per_unit * 5)/100;
}

// Franchise Fee Calculation
if($customerinfo[0]['account_type']==3 && $consumed <= 30){
    $bill_amount_for_franchise = $cubicmeter_rate->per_unit - $discount;
} else {
    $bill_amount_for_franchise = $cubicmeter_rate->per_unit;
}
```

---

### 2. Web Edit Form: `addmetercustomerreading_search.php`

**Location:** `application/modules/master/views/addmetercustomerreading_search.php`

**Function:** `recalculateFranchiseFeeAndTotals()` and SC discount calculation

**Changes:**
- Updated `recalculateFranchiseFeeAndTotals()` to check consumption
- Modified SC discount calculation when current reading changes
- Added consumption validation before applying discount

**Lines Modified:** 569-730

**Key Code:**
```javascript
// Senior Citizen Discount Calculation
var consumed = parseFloat($('#consumed').val() || 0);
if($('#cust_type_id').val()==3 && consumed <= 30){
    var discount = (multiprice * 5)/100;
    $('#sc_discount').val(amount_formatted(discount));
} else {
    $('#sc_discount').val(amount_formatted(0));
}

// Franchise Fee Calculation
if($('#cust_type_id').val()==3 && consumed <= 30){
    bill_amount_for_franchise = multiprice - discount;
} else {
    bill_amount_for_franchise = multiprice;
}
```

---

### 3. Mobile Dashboard: `mobile_dashboard.php`

**Location:** `application/modules/master/views/mobile_dashboard.php`

**Function:** `compute_all()`

**Changes:**
- Added consumption check in discount calculation
- Updated franchise fee calculation logic
- Added comprehensive documentation comments

**Lines Modified:** 566-633

**Key Code:**
```javascript
// Senior Citizen Discount Calculation
var discount = 0;
var consumed = parseFloat(difer) || 0;
if($('#cust_type_id').val()==3 && consumed <= 30){
    discount = (multiprice * 5)/100;
}

// Franchise Fee Calculation
var bill_amount_for_franchise;
if($('#cust_type_id').val()==3 && consumed <= 30){
    bill_amount_for_franchise = multiprice - discount;
} else {
    bill_amount_for_franchise = multiprice;
}
```

---

## Technical Specifications

### Variables Used

| Variable | Type | Description |
|---------|------|-------------|
| `consumed` | Integer/Float | Difference between current_reading and previous_reading |
| `account_type` | Integer | Customer account type (3 = Senior Citizen) |
| `discount` | Float | Senior citizen discount amount |
| `multiprice` | Float | Unit price per cubic meter |
| `bill_amount_for_franchise` | Float | Amount used for franchise fee calculation |

### Calculation Formula

#### Senior Citizen Discount
```
IF (account_type == 3 AND consumed <= 30):
    discount = (unit_price × 5) / 100
ELSE:
    discount = 0
```

#### Franchise Fee
```
IF (account_type == 3 AND consumed <= 30):
    bill_amount_for_franchise = unit_price - discount
ELSE:
    bill_amount_for_franchise = unit_price

franchise_fee_amount = (bill_amount_for_franchise × franchise_fee_percentage) / 100
```

#### Total Amount
```
total_amount = unit_price - discount + maintenance_fee + franchise_fee_amount
```

---

## Testing Guidelines

### Test Cases

#### Test Case 1: Senior Citizen with Consumption ≤ 30
- **Input:**
  - Account Type: 3 (Senior Citizen)
  - Previous Reading: 100
  - Current Reading: 125
  - Consumption: 25 cubic meters
- **Expected Result:**
  - Discount Applied: Yes (5% of unit price)
  - Franchise Fee: Calculated on (unit_price - discount)

#### Test Case 2: Senior Citizen with Consumption > 30
- **Input:**
  - Account Type: 3 (Senior Citizen)
  - Previous Reading: 100
  - Current Reading: 135
  - Consumption: 35 cubic meters
- **Expected Result:**
  - Discount Applied: No (discount = 0)
  - Franchise Fee: Calculated on full unit_price

#### Test Case 3: Regular Customer (Any Consumption)
- **Input:**
  - Account Type: 1 or 2 (Regular Customer)
  - Previous Reading: 100
  - Current Reading: 130
  - Consumption: 30 cubic meters
- **Expected Result:**
  - Discount Applied: No (discount = 0)
  - Franchise Fee: Calculated on full unit_price

#### Test Case 4: Senior Citizen with Exactly 30 Cubic Meters
- **Input:**
  - Account Type: 3 (Senior Citizen)
  - Previous Reading: 100
  - Current Reading: 130
  - Consumption: 30 cubic meters
- **Expected Result:**
  - Discount Applied: Yes (5% of unit price)
  - Franchise Fee: Calculated on (unit_price - discount)

#### Test Case 5: Senior Citizen with Exactly 31 Cubic Meters
- **Input:**
  - Account Type: 3 (Senior Citizen)
  - Previous Reading: 100
  - Current Reading: 131
  - Consumption: 31 cubic meters
- **Expected Result:**
  - Discount Applied: No (discount = 0)
  - Franchise Fee: Calculated on full unit_price

### Testing Locations

1. **Backend API:** `addbillingperiod/sync_import`
2. **Web Edit Form:** `master/addmetercustomerreading/edit` (popup)
3. **Mobile Dashboard:** `master/mobile_dashboard`

---

## Code Examples

### PHP Backend Example

```php
/**
 * Senior Citizen Discount Calculation
 * Date Modified: January 29, 2026
 * Modified By: AI Assistant
 */
$consumed = $data['current_reading'] - $data['previous_reading'];
$discount = 0;
if($customerinfo[0]['account_type']==3 && $consumed <= 30){
    $discount = ($cubicmeter_rate->per_unit * 5)/100;
}

// Franchise Fee Calculation
if($customerinfo[0]['account_type']==3 && $consumed <= 30){
    $bill_amount_for_franchise = $cubicmeter_rate->per_unit - $discount;
} else {
    $bill_amount_for_franchise = $cubicmeter_rate->per_unit;
}
$franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;
```

### JavaScript Frontend Example

```javascript
/**
 * Senior Citizen Discount Calculation
 * Date Modified: January 29, 2026
 * Modified By: AI Assistant
 */
var consumed = parseFloat($('#consumed').val() || 0);
var discount = 0;
if($('#cust_type_id').val()==3 && consumed <= 30){
    discount = (multiprice * 5)/100;
    $('#sc_discount').val(amount_formatted(discount));
} else {
    $('#sc_discount').val(amount_formatted(0));
}

// Franchise Fee Calculation
var bill_amount_for_franchise;
if($('#cust_type_id').val()==3 && consumed <= 30){
    bill_amount_for_franchise = multiprice - discount;
} else {
    bill_amount_for_franchise = multiprice;
}
var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
```

---

## Impact Analysis

### Affected Features

1. **Billing Calculation:** All billing calculations now respect the consumption limit
2. **Discount Application:** Senior citizen discount only applies within consumption limit
3. **Franchise Fee:** Franchise fee calculation adjusted based on discount eligibility
4. **Total Amount:** Final bill amount reflects the correct discount application

### User Impact

- **Senior Citizens:** Will not receive discount if consumption exceeds 30 cubic meters
- **System Administrators:** Need to be aware of the new business rule
- **Billing Staff:** Should verify consumption before applying discounts

---

## Maintenance Notes

### Future Considerations

1. **Configurable Limit:** Consider making the 30 cubic meter limit configurable via admin settings
2. **Notification:** Consider adding notifications when discount is not applied due to consumption
3. **Reporting:** May want to track discount applications vs. denials for reporting purposes

### Code Maintenance

- All calculation logic includes standard comments with date, author, and purpose
- Business rule is clearly documented in code comments
- Consistent implementation across all calculation points

---

## Related Documentation

- `WATER_BILLING_SYSTEM_MANUAL.md` - General system documentation
- `MOBILE_DASHBOARD_PWA_README.md` - Mobile dashboard documentation
- `STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md` - API documentation

---

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 29, 2026 | AI Assistant | Initial implementation of senior citizen discount consumption limit |

---

## Contact & Support

For questions or issues related to this implementation, please refer to:
- System Administrator
- Development Team
- Business Rules Documentation

---

**End of Documentation**
