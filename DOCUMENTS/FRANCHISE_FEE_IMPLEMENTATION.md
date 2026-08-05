# Franchise Fee Implementation - Documentation

## Overview
Implemented a dynamic Franchise Fee system that calculates 2% of the bill amount. For Senior Citizen (SC) accounts, the franchise fee is computed after the SC discount deduction. The system stores both the percentage value and the computed amount in the database for historical tracking.

## Date
- Initial Implementation: Current Date
- Latest Updates: Current Date
  - Edit page implementation
  - Payment customer add page enhancements
  - Monthly customer invoice enhancements
  - Cash payment module improvements

---

## Business Logic

### Franchise Fee Calculation Rules:
1. **Default Percentage:** 2% (configurable via Global Settings)
2. **Regular Accounts:** Franchise fee = 2% of unit price
3. **Senior Citizen Accounts (account_type = 3):** Franchise fee = 2% of (unit price - SC discount)
4. **Storage:** Both percentage and computed amount are stored in database

### Calculation Formula:
```
For Regular Accounts:
  franchise_fee_amount = unit_price × (franchise_fee_percentage / 100)

For Senior Citizen Accounts:
  franchise_fee_amount = (unit_price - sc_discount) × (franchise_fee_percentage / 100)
```

---

## Database Changes

### 1. Add Columns to `tbl_addcustomer_reading`

**SQL Script:**
```sql
-- Add franchise_fee_percent and franchise_fee_amount columns
ALTER TABLE `tbl_addcustomer_reading` 
ADD COLUMN `franchise_fee_percent` FLOAT DEFAULT 0.00 AFTER `maintenance_fee`,
ADD COLUMN `franchise_fee_amount` FLOAT DEFAULT 0.00 AFTER `franchise_fee_percent`;
```

**Column Details:**
- `franchise_fee_percent` - FLOAT, stores the percentage value used (e.g., 2.00)
- `franchise_fee_amount` - FLOAT, stores the computed amount

### 2. Add to Global Settings

**SQL Script:**
```sql
-- Add franchise fee percentage to global settings
INSERT INTO `tbl_global_settings` (`code`, `description`, `value`) 
VALUES ('FRANCHISE_FEE_PERCENTAGE', 'Franchise Fee Percentage (2% = 2.00)', 2.00);
```

**Action Required:**
- Execute both SQL scripts in your database

---

## Files Modified

### 1. Helper Functions
**File:** `application/helpers/common_helper.php`

**New Functions Added:**
- `get_franchise_fee_percentage()` - Retrieves franchise fee percentage from global settings
- `calculate_franchise_fee()` - Calculates franchise fee based on account type

**Updated Functions:**
- `createbalanceforward()` - Now includes franchise fee calculation

**Code Location:** After the `get_maintenance_fee()` function

---

### 2. Meter Customer Reading Model
**File:** `application/modules/master/models/addmetercustomerreading_model.php`

**New Methods Added:**
- `get_franchise_fee_percentage()` - Retrieves percentage from global settings

**Updated Methods:**
- `add_record()` - Calculates and stores franchise fee when creating new meter readings
  - Lines: ~43-100
  - Stores: `franchise_fee_percent` and `franchise_fee_amount`
  
- `update_meterreading()` - Calculates and updates franchise fee when updating readings
  - Lines: ~345-382
  - Updates: `franchise_fee_percent` and `franchise_fee_amount` in UPDATE query

---

### 3. Balance Forward Model
**File:** `application/modules/master/models/createbalanceforward_model.php`

**New Methods Added:**
- `get_franchise_fee_percentage()` - Retrieves percentage from global settings

**Updated Methods:**
- `process_single_customer()` - Calculates and stores franchise fee during balance forwarding
  - Lines: ~214-238
  - Stores: `franchise_fee_percent` and `franchise_fee_amount` in update array

---

### 4. JavaScript/Frontend Updates
**File:** `application/modules/master/views/addmetercustomerreading_search.php`

**Updated Event Handlers:**
- `$('#current_reading').on('blur')` - Calculates franchise fee when reading is entered
- `$('#sc_discount').on('blur')` - Recalculates franchise fee when discount changes
- `$('#maintenance_fee').on('blur')` - Recalculates franchise fee when maintenance fee changes

**New Fields Required in View:**
- `#franchise_fee_percent` - Hidden or display field for percentage
- `#franchise_fee_amount` - Display field for computed amount

---

### 5. Edit Page Implementation
**File:** `application/modules/master/models/addmetercustomerreading_model.php`

**Updated Methods:**
- `update_record()` - Calculates and stores franchise fee when updating meter readings via edit page
  - Lines: ~161-180
  - Retrieves customer account_type
  - Calculates franchise fee based on account type
  - Updates: `franchise_fee_percent` and `franchise_fee_amount` in UPDATE query

**File:** `application/modules/master/views/addmetercustomerreading_search.php`

**Updated Modal Form:**
- Added franchise fee fields to edit modal:
  - `#franchise_fee_percent` - Display field for percentage (readonly)
  - `#franchise_fee_amount` - Display field for computed amount (readonly)
- Updated save button to include franchise fee data in FormData

**File:** `application/modules/master/views/addmetercustomerreading_metersearch_ajax.php`

**Updated Table Display:**
- Added "Franchise Fee %" and "Franchise Fee Amt" columns to datatable
- Added franchise fee data attributes to edit button
- Updated JavaScript to populate franchise fee fields when modal opens

---

### 6. Payment Customer Add Page
**File:** `application/modules/master/models/addpaymentcustomer_model.php`

**Updated Methods:**
- `get_meter_reading_all_records()` - Added `franchise_fee_amount` to SELECT statement
  - Lines: ~196-217
  - Includes franchise fee data in query results

**File:** `application/modules/master/views/addpaymentcustomer_add _ajax.php`

**Updated Datatable:**
- Added "Franchise Fee" column header in table
- Added franchise fee amount display in table rows
- Displays formatted franchise fee amount (2 decimal places)

---

### 7. Monthly Customer Invoice Page
**File:** `application/modules/master/models/addpaymentcustomer_model.php`

**Updated Methods:**
- `get_single_record()` - Added franchise fee and due date fields
  - Lines: ~291-309
  - Added: `maintenance_fee`, `franchise_fee_percent`, `franchise_fee_amount`, `bp_id`, `bp_due_date`
  - Added LEFT JOIN to `tbl_billing_period` table

**File:** `application/modules/master/views/month_customer_invoice.php`

**Updated Invoice Display:**
- Added Maintenance Fee display in "Current Bill" column
- Added Franchise Fee display showing percentage and amount
- Added Due Date display in "Transaction Date" column
- Format: "Transaction Date: [date]" and "Due Date: [date]"

---

### 8. Cash Payment Module Enhancements
**File:** `application/modules/master/views/addpaymentcustomer_add.php`

**Modal Improvements:**
- **Transaction Date Field:**
  - Moved to appear immediately after OR/SI # field
  - Auto-populated with current date when popup opens
  - Format: `dd-mm-yyyy`

- **Automatic Penalty Recalculation:**
  - Added `recalculatePenaltyIfNeeded()` function
  - Triggers on transaction date blur/change
  - Compares transaction date with due date
  - Applies 10% penalty if transaction date > due date AND special_priviledge == 0
  - Recalculates grand total including penalty, VAT, leaking discount, and leaking balance

**Updated Event Handlers:**
- `$('#transdate').on('blur change')` - Recalculates penalty when transaction date changes
- `$('#vat_percent').on('blur')` - Calls penalty recalculation
- `$('#leaking_percent').on('blur')` - Calls penalty recalculation
- `$('#leaking_balance').on('blur')` - Calls penalty recalculation

**Hidden Fields Added:**
- `#due_date` - Stores billing period due date
- `#special_priviledge` - Stores customer special privilege status
- `#base_amount` - Stores base amount before penalty

**File:** `application/modules/master/views/addpaymentcustomer_add _ajax.php`

**Updated Table:**
- Added hidden fields for each billing record:
  - `due_date_<?php echo $i;?>` - Billing period due date
  - `special_priviledge_<?php echo $i;?>` - Customer special privilege
  - `base_amount_<?php echo $i;?>` - Base amount before penalty

---

## Implementation Details

### Calculation Flow

#### 1. When Adding New Meter Reading:
```
1. Get unit_price from cubic meter rate
2. Check if account_type == 3 (Senior Citizen)
3. If SC: Calculate SC discount (5% of unit_price)
4. Calculate franchise fee:
   - If SC: franchise_fee = (unit_price - sc_discount) × percentage / 100
   - If Regular: franchise_fee = unit_price × percentage / 100
5. Store both franchise_fee_percent and franchise_fee_amount
6. Add franchise_fee_amount to total_amount
```

#### 2. When Updating Meter Reading:
```
1. Retrieve existing reading data
2. Get unit_price and sc_discount
3. Get customer account_type
4. Calculate franchise fee based on account type
5. Update both franchise_fee_percent and franchise_fee_amount
6. Recalculate total_amount including franchise fee
```

#### 3. During Balance Forward:
```
1. Get previous billing period data
2. Retrieve unit_price and sc_discount from previous period
3. Get customer account_type
4. Calculate franchise fee based on account type
5. Store both franchise_fee_percent and franchise_fee_amount
```

---

## Cash Payment Module - Penalty Recalculation Feature

### Overview
The cash payment module now automatically recalculates penalty charges based on the transaction date relative to the billing period due date. This ensures accurate billing when payments are made after the due date.

### How It Works

1. **When Popup Opens:**
   - Transaction date is automatically set to current date
   - Due date, special privilege, and base amount are loaded from selected billing record

2. **When Transaction Date Changes:**
   - System compares transaction date with due date
   - If transaction date > due date AND customer has no special privilege:
     - Applies 10% penalty to base amount
     - Recalculates grand total with penalty included
   - If transaction date <= due date OR customer has special privilege:
     - No penalty applied
     - Uses base amount only

3. **Integration with Other Calculations:**
   - Penalty is calculated first
   - VAT is calculated on penalty-adjusted amount (if applicable)
   - Leaking discount is applied after penalty
   - Leaking balance is added after all deductions
   - Grand total includes all adjustments

### Code Example - Penalty Recalculation:

```javascript
function recalculatePenaltyIfNeeded() {
    var trans_date = $('#transdate').val();
    var due_date = $('#due_date').val();
    var special_priviledge = $('#special_priviledge').val();
    var base_amount = parseFloat($('#base_amount').val()) || 0;
    
    if(trans_date && due_date && base_amount > 0) {
        // Convert dates to comparable format
        var trans_date_parts = trans_date.split('-');
        var trans_date_formatted = trans_date_parts[2] + '-' + 
                                   trans_date_parts[1] + '-' + 
                                   trans_date_parts[0];
        var due_date_formatted = due_date.split(' ')[0];
        
        var penalty = 0;
        var new_amount = base_amount;
        
        // Check if penalty should be applied
        if(special_priviledge == 0 && trans_date_formatted > due_date_formatted) {
            penalty = (base_amount * 10) / 100;
            new_amount = base_amount + penalty;
        }
        
        // Update amount display
        $('#deepmala').text(new_amount.toFixed(2));
        $("#paid_total_amount").val(new_amount.toFixed(2));
        
        return new_amount;
    }
    return parseFloat($("#paid_total_amount").val() || 0);
}
```

### User Workflow:

1. User clicks "Unpaid" button on a billing record
2. Cash payment popup opens with:
   - OR/SI # field (auto-generated)
   - Transaction Date field (set to current date)
   - Current Bill Amount displayed
3. User can change transaction date if needed
4. System automatically:
   - Checks if date exceeds due date
   - Applies penalty if applicable
   - Updates all amounts accordingly
5. User enters payment details and processes payment

### Important Notes:

- **Special Privilege Accounts:** Customers with `special_priviledge != 0` are exempt from penalty charges
- **Penalty Rate:** Currently fixed at 10% of base amount (can be made configurable in future)
- **Date Format:** Transaction date uses `dd-mm-yyyy` format (datepicker format)
- **Due Date Format:** Due date from database is in `YYYY-MM-DD` format
- **Real-time Updates:** All calculations update immediately when transaction date changes

---

## Code Examples

### Helper Function Usage:
```php
// Get franchise fee percentage
$percentage = get_franchise_fee_percentage(); // Returns 2.00

// Calculate franchise fee
$franchise_data = calculate_franchise_fee($unit_price, $sc_discount, $account_type);
// Returns: array('percent' => '2.00', 'amount' => '10.50')
```

### Model Method Usage:
```php
// In model
$percentage = $this->get_franchise_fee_percentage();

if($account_type == 3) {
    $bill_amount = $unit_price - $sc_discount;
} else {
    $bill_amount = $unit_price;
}
$franchise_fee_amount = ($bill_amount * $percentage) / 100;

// Store in database
$data = array(
    'franchise_fee_percent' => number_format($percentage, 2, '.', ''),
    'franchise_fee_amount' => number_format($franchise_fee_amount, 2, '.', '')
);
```

### JavaScript Calculation:
```javascript
var franchise_fee_percentage = 2.00;
var bill_amount_for_franchise;

if($('#cust_type_id').val() == 3) {
    // SC: compute after SC deduction
    bill_amount_for_franchise = multiprice - discount;
} else {
    // Regular: compute on unit price
    bill_amount_for_franchise = multiprice;
}

var franchise_fee_amount = (bill_amount_for_franchise * franchise_fee_percentage) / 100;
total_amount += parseFloat(franchise_fee_amount);

$('#franchise_fee_percent').val(franchise_fee_percentage);
$('#franchise_fee_amount').val(amount_formatted(franchise_fee_amount));
```

---

## Database Schema Update

### `tbl_addcustomer_reading` Table:
```sql
+------------------------+-----------+------+-----+---------+-------+
| Field                  | Type      | Null | Key | Default | Extra |
+------------------------+-----------+------+-----+---------+-------+
| ...                    | ...       | ...  | ... | ...     | ...   |
| maintenance_fee        | float     | YES  |     | NULL    |       |
| franchise_fee_percent  | float     | YES  |     | 0.00    |       |  ← NEW
| franchise_fee_amount   | float     | YES  |     | 0.00    |       |  ← NEW
| ...                    | ...       | ...  | ... | ...     | ...   |
+------------------------+-----------+------+-----+---------+-------+
```

### `tbl_global_settings` Table:
```sql
+----+------------------------+----------------------------------+-------+
| id | code                   | description                      | value |
+----+------------------------+----------------------------------+-------+
| 1  | MAINTENANCE_FEE         | Water Meter Maintenance Fee      | 25.00 |
| 2  | FRANCHISE_FEE_PERCENTAGE| Franchise Fee Percentage (2%)   | 2.00  |  ← NEW
+----+------------------------+----------------------------------+-------+
```

---

## Bill Computation Flow

### Complete Bill Calculation:
```
1. unit_price = Get from cubic meter rate based on consumption
2. sc_discount = 0
   - If account_type == 3: sc_discount = unit_price × 5% / 100
3. bill_amount_after_discount = unit_price - sc_discount
4. maintenance_fee = Get from global settings (default: 25.00)
5. franchise_fee_amount = Calculate based on account type:
   - If SC: (unit_price - sc_discount) × franchise_fee_percentage / 100
   - If Regular: unit_price × franchise_fee_percentage / 100
6. total_amount = bill_amount_after_discount + maintenance_fee + franchise_fee_amount
7. penalty = Calculate if applicable:
   - If special_priviledge == '0': penalty = total_amount × 10% / 100
   - Final amount = total_amount + penalty
```

---

## Testing Checklist

### Database:
- [ ] Execute SQL to add `franchise_fee_percent` column
- [ ] Execute SQL to add `franchise_fee_amount` column
- [ ] Verify columns exist in `tbl_addcustomer_reading`
- [ ] Add `FRANCHISE_FEE_PERCENTAGE` to `tbl_global_settings`
- [ ] Verify default value is 2.00

### Functionality:
- [ ] Test adding new meter reading for regular account
- [ ] Verify franchise_fee_percent = 2.00 is stored
- [ ] Verify franchise_fee_amount is calculated correctly
- [ ] Test adding new meter reading for SC account
- [ ] Verify franchise fee is calculated after SC discount
- [ ] Test updating existing meter reading
- [ ] Verify franchise fee is recalculated correctly
- [ ] Test balance forward process
- [ ] Verify franchise fee is included in balance forward
- [ ] Test editing meter reading via search/edit page
- [ ] Verify franchise fee is displayed and saved correctly in edit modal

### Payment Customer Add Page:
- [ ] Verify franchise_fee_amount column appears in datatable
- [ ] Verify franchise fee amount displays correctly for each billing record
- [ ] Test payment process with franchise fee included

### Monthly Customer Invoice:
- [ ] Verify Maintenance Fee displays in invoice
- [ ] Verify Franchise Fee displays with percentage and amount
- [ ] Verify Due Date displays correctly
- [ ] Verify Transaction Date displays correctly
- [ ] Test invoice for both regular and SC accounts

### Cash Payment Module:
- [ ] Verify transaction date appears after OR/SI # field
- [ ] Verify transaction date auto-populates with current date
- [ ] Test penalty calculation when transaction date > due date
- [ ] Test penalty calculation when transaction date <= due date
- [ ] Verify penalty is NOT applied for special privilege accounts
- [ ] Verify penalty is applied correctly (10% of base amount)
- [ ] Test recalculation when VAT is changed
- [ ] Test recalculation when leaking discount is changed
- [ ] Test recalculation when leaking balance is changed
- [ ] Verify grand total updates correctly with all adjustments

### Edge Cases:
- [ ] Test with zero unit_price
- [ ] Test with very large unit_price
- [ ] Test with SC account having zero discount
- [ ] Test when global setting doesn't exist (fallback to 2.00)
- [ ] Test with different franchise fee percentages
- [ ] Test with missing due date
- [ ] Test with transaction date exactly equal to due date
- [ ] Test with transaction date before due date

### UI/Frontend:
- [ ] Verify franchise fee fields display in forms
- [ ] Verify franchise fee calculates correctly in JavaScript
- [ ] Test recalculation when SC discount changes
- [ ] Test recalculation when maintenance fee changes
- [ ] Verify franchise fee fields in edit modal are readonly
- [ ] Verify transaction date field is editable
- [ ] Verify date picker works correctly for transaction date

---

## Configuration

### Changing Franchise Fee Percentage:

1. **Via Global Settings UI:**
   - Navigate to: Settings → Global Settings
   - Find "FRANCHISE_FEE_PERCENTAGE"
   - Edit the value (e.g., change 2.00 to 3.00 for 3%)
   - Save

2. **Via Database:**
   ```sql
   UPDATE tbl_global_settings 
   SET value = 3.00 
   WHERE code = 'FRANCHISE_FEE_PERCENTAGE';
   ```

**Note:** Changes to the percentage will only affect new billings. Existing records retain their original percentage and amount.

---

## Data Migration

### For Existing Records:

If you need to backfill franchise fee for existing records:

```sql
-- Update existing records with franchise fee calculation
UPDATE tbl_addcustomer_reading acr
INNER JOIN tbl_addcustomer ac ON acr.customer_id = ac.customer_id
SET 
    acr.franchise_fee_percent = 2.00,
    acr.franchise_fee_amount = CASE 
        WHEN ac.account_type = 3 THEN 
            ((acr.unit_price - IFNULL(acr.sc_discount, 0)) * 2.00) / 100
        ELSE 
            (acr.unit_price * 2.00) / 100
    END
WHERE acr.franchise_fee_amount = 0 OR acr.franchise_fee_amount IS NULL;
```

---

## Reporting Considerations

### Reports That May Need Updates:

1. **Daily Reports:**
   - May need to include franchise_fee_amount in totals
   - Check: `adddailyreport_model.php`

2. **Statement of Account:**
   - May need to display franchise_fee_percent and franchise_fee_amount
   - Check: `statementofaccount_model.php`

3. **Payment Reports:**
   - May need to show franchise fee breakdown
   - Check: `addpaymentcustomer_model.php`

---

## Troubleshooting

### Common Issues:

1. **Franchise fee not calculating:**
   - Check if `FRANCHISE_FEE_PERCENTAGE` exists in `tbl_global_settings`
   - Verify account_type is being retrieved correctly
   - Check JavaScript console for errors

2. **Incorrect franchise fee for SC accounts:**
   - Verify SC discount is calculated before franchise fee
   - Check that account_type == 3 condition is working
   - Verify bill_amount_for_franchise calculation

3. **Franchise fee not saving:**
   - Verify database columns exist
   - Check INSERT/UPDATE queries include both fields
   - Verify data types match (FLOAT)

4. **JavaScript calculation errors:**
   - Check that `#cust_type_id` field exists
   - Verify `amount_formatted()` function is available
   - Check for JavaScript errors in browser console

---

## Rollback Instructions

If needed to rollback:

1. **Remove database columns:**
   ```sql
   ALTER TABLE `tbl_addcustomer_reading` 
   DROP COLUMN `franchise_fee_percent`,
   DROP COLUMN `franchise_fee_amount`;
   ```

2. **Remove from global settings:**
   ```sql
   DELETE FROM `tbl_global_settings` 
   WHERE `code` = 'FRANCHISE_FEE_PERCENTAGE';
   ```

3. **Revert code changes:**
   - Remove franchise fee calculations from models
   - Remove helper functions
   - Remove JavaScript calculations
   - Remove franchise fee from total_amount calculations

---

## Recent Updates Summary

### Update 1: Edit Page Implementation
- Added franchise fee calculation to meter reading edit functionality
- Updated edit modal to display franchise fee fields
- Ensured franchise fee is recalculated and saved when editing readings

### Update 2: Payment Customer Add Page
- Added franchise_fee_amount column to billing records datatable
- Displays franchise fee amount for each billing record
- Helps users see franchise fee before processing payment

### Update 3: Monthly Customer Invoice
- Added Maintenance Fee display in invoice
- Added Franchise Fee display (percentage and amount) in invoice
- Added Due Date display alongside Transaction Date
- Provides complete billing breakdown for customers

### Update 4: Cash Payment Module Enhancements
- **Transaction Date Improvements:**
  - Moved field to appear immediately after OR/SI # for better workflow
  - Auto-populates with current date when popup opens
  - Improves user experience and reduces data entry errors

- **Automatic Penalty Recalculation:**
  - System automatically checks if transaction date exceeds due date
  - Applies 10% penalty when payment is late (for non-special privilege accounts)
  - Recalculates in real-time when transaction date changes
  - Integrates with VAT, leaking discount, and leaking balance calculations
  - Ensures accurate billing amounts based on payment timing

### Technical Implementation Details:

**Penalty Calculation Logic:**
```javascript
if (transaction_date > due_date && special_priviledge == 0) {
    penalty = (base_amount * 10) / 100;
    new_amount = base_amount + penalty;
} else {
    new_amount = base_amount; // No penalty
}
```

**Date Comparison:**
- Transaction date format: `dd-mm-yyyy` (from datepicker)
- Due date format: `YYYY-MM-DD` (from database)
- Conversion handled in JavaScript for accurate comparison

**Integration Points:**
- Penalty recalculation triggers on transaction date change
- VAT calculation uses penalty-adjusted amount
- Leaking discount applied after penalty calculation
- Grand total includes all adjustments (penalty, VAT, discounts, balances)

---

## Future Enhancements

Possible improvements:

1. **Multiple Franchise Fee Rates:**
   - Different rates for different customer classifications
   - Different rates for different zones

2. **Franchise Fee History:**
   - Track when franchise fee percentage changes
   - Maintain audit trail

3. **Franchise Fee Reports:**
   - Total franchise fee collected per period
   - Franchise fee by customer type
   - Franchise fee trends

4. **Franchise Fee Exemptions:**
   - Ability to exempt certain customers
   - Exemption reasons tracking

5. **Penalty Configuration:**
   - Make penalty percentage configurable via Global Settings
   - Different penalty rates for different billing periods
   - Grace period configuration

6. **Payment Date Validation:**
   - Warn users when transaction date is significantly different from current date
   - Prevent future-dated transactions (if required)
   - Historical transaction date restrictions

---

## File Modification Summary

### Complete List of Modified Files:

1. **Database Tables:**
   - `tbl_addcustomer_reading` - Added `franchise_fee_percent` and `franchise_fee_amount` columns
   - `tbl_global_settings` - Added `FRANCHISE_FEE_PERCENTAGE` setting

2. **Helper Files:**
   - `application/helpers/common_helper.php` - Added `get_franchise_fee_percentage()` and `calculate_franchise_fee()` functions

3. **Model Files:**
   - `application/modules/master/models/addmetercustomerreading_model.php` - Added franchise fee to add/update methods
   - `application/modules/master/models/createbalanceforward_model.php` - Added franchise fee to balance forward process
   - `application/modules/master/models/addpaymentcustomer_model.php` - Added franchise fee and due date to queries

4. **View Files:**
   - `application/modules/master/views/addmetercustomerreading_search.php` - Added franchise fee fields to edit modal and JavaScript calculations
   - `application/modules/master/views/addmetercustomerreading_metersearch_ajax.php` - Added franchise fee columns to datatable
   - `application/modules/master/views/addpaymentcustomer_add.php` - Added penalty recalculation, transaction date handling, and auto-population
   - `application/modules/master/views/addpaymentcustomer_add _ajax.php` - Added franchise fee column and hidden fields for penalty calculation
   - `application/modules/master/views/month_customer_invoice.php` - Added maintenance fee, franchise fee, and due date display

5. **SQL Scripts:**
   - `sql/franchise_fee_database_changes.sql` - Database schema changes
   - `sql/tbl_global_settings.sql` - Global settings table creation (if needed)

---

## Related Documentation

- See `GLOBAL_SETTINGS_MODULE_CHANGES.md` for maintenance fee implementation
- See `DEVELOPER_TECHNICAL_GUIDE.md` for general system architecture

---

## Support

For issues or questions:
1. Check this documentation first
2. Review code comments in modified files
3. Check database for correct data
4. Verify global settings configuration

---

**End of Documentation**
