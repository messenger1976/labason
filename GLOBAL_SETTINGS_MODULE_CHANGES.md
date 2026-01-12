# Global Settings Module - Implementation Notes

## Overview
Created a new Global Settings module under the Settings Menu that allows admin users to dynamically manage system-wide settings, starting with the Maintenance Fee setup. This replaces hardcoded maintenance fee values throughout the system.

## Date
Implementation completed on: Current Date

---

## Changes Made

### 1. Database Changes

#### New Table: `tbl_global_settings`
**File:** `tbl_global_settings.sql`

**Table Structure:**
- `id` - INT(11) AUTO_INCREMENT PRIMARY KEY
- `code` - VARCHAR(100) NOT NULL UNIQUE
- `description` - TEXT
- `value` - FLOAT NOT NULL DEFAULT 0

**Default Data:**
- Pre-inserted record with code `MAINTENANCE_FEE`, description `Water Meter Maintenance Fee`, and default value `25.00`

**Action Required:**
- Execute the SQL script `tbl_global_settings.sql` in your database

---

### 2. New Module Files Created

#### Model
**File:** `application/modules/master/models/global_settings_model.php`
- CRUD operations for global settings
- Methods: `get_all_records()`, `get_single_record()`, `get_setting_by_code()`, `add_record()`, `update_record()`, `delete_record()`
- Includes duplicate checking for code field

#### Controller
**File:** `application/modules/master/controllers/global_settings.php`
- Admin-only access control (redirects non-admin users)
- Standard CRUD operations: `index()`, `view()`, `add()`, `edit()`, `delete()`, `multi_delete()`
- Form validation and error handling

#### Views
**Files:**
- `application/modules/master/views/global_settings.php` - List view with DataTables
- `application/modules/master/views/global_settings-edit.php` - Edit form view

---

### 3. Navigation Menu Updates

#### Desktop Navigation
**File:** `application/views/admin-includes/navigation.php`

**Changes:**
- Added "Global Settings" menu item under Settings menu
- Only visible to admin users (`usertype == 'admin'`)
- Active state detection for `global_settings` segment

**Location:** Settings menu → Global Settings (first item, admin only)

#### Mobile Navigation
**File:** `application/views/admin-includes/mobile_navigation.php`

**Changes:**
- Added "Global Settings" menu item under Settings menu
- Only visible to admin users
- Active state detection for `global_settings` segment

---

### 4. Helper Function Updates

#### Common Helper
**File:** `application/helpers/common_helper.php`

**New Function Added:**
```php
get_maintenance_fee()
```
- Retrieves maintenance fee value from `tbl_global_settings` table
- Returns formatted value (2 decimal places)
- Fallback to '25.00' if setting doesn't exist

**Updated Function:**
- `createbalanceforward()` - Now uses `get_maintenance_fee()` instead of hardcoded '25.00'

---

### 5. Model Updates for Dynamic Maintenance Fee

#### Balance Forward Model
**File:** `application/modules/master/models/createbalanceforward_model.php`

**New Method Added:**
```php
get_maintenance_fee()
```
- Retrieves maintenance fee from global settings
- Returns formatted value with fallback

**Updated Method:**
- `process_single_customer()` - Now uses `get_maintenance_fee()` instead of hardcoded '25.00'
- Line 210: Changed from `'maintenance_fee' => '25.00'` to dynamic retrieval

#### Meter Customer Reading Model
**File:** `application/modules/master/models/addmetercustomerreading_model.php`

**New Method Added:**
```php
get_maintenance_fee()
```
- Retrieves maintenance fee from global settings
- Returns formatted value with fallback

**Updated Methods:**
- `add_record()` - Now includes `maintenance_fee` field in insert data
  - Uses posted value if available, otherwise retrieves from global settings
  - Line 88: Added `'maintenance_fee' => $maintenance_fee` to `$set_data` array

**Note:** `update_meterreading()` already uses stored maintenance_fee from database records, so no changes needed there.

---

## Files Modified Summary

1. ✅ `tbl_global_settings.sql` - **NEW** (Database table creation script)
2. ✅ `application/modules/master/models/global_settings_model.php` - **NEW**
3. ✅ `application/modules/master/controllers/global_settings.php` - **NEW**
4. ✅ `application/modules/master/views/global_settings.php` - **NEW**
5. ✅ `application/modules/master/views/global_settings-edit.php` - **NEW**
6. ✅ `application/views/admin-includes/navigation.php` - **MODIFIED**
7. ✅ `application/views/admin-includes/mobile_navigation.php` - **MODIFIED**
8. ✅ `application/helpers/common_helper.php` - **MODIFIED**
9. ✅ `application/modules/master/models/createbalanceforward_model.php` - **MODIFIED**
10. ✅ `application/modules/master/models/addmetercustomerreading_model.php` - **MODIFIED**

---

## Hardcoded Values Replaced

### Before:
- `'maintenance_fee' => '25.00'` (hardcoded in multiple locations)

### After:
- Dynamic retrieval from `tbl_global_settings` table using code `MAINTENANCE_FEE`
- Fallback to '25.00' if setting doesn't exist (backward compatibility)

### Locations Updated:
1. ✅ `createbalanceforward_model.php` - Line 210
2. ✅ `common_helper.php` - Line 203 (in `createbalanceforward()` function)
3. ✅ `addmetercustomerreading_model.php` - Line 88 (in `add_record()` function)

---

## Access Control

- **Module Access:** Admin users only
- **Controller Check:** Redirects non-admin users to `master/page/`
- **Menu Visibility:** Only shown to users with `usertype == 'admin'`

---

## Usage Instructions

### For Administrators:

1. **Access Global Settings:**
   - Navigate to: Settings → Global Settings
   - URL: `/master/global_settings`

2. **Edit Maintenance Fee:**
   - Click edit icon on the Maintenance Fee row
   - Update the value field
   - Click Save

3. **Add New Settings:**
   - Currently, the module is set up for Maintenance Fee only
   - Can be extended to add more global settings in the future

### For Developers:

**To retrieve maintenance fee in code:**
```php
// Using helper function
$maintenance_fee = get_maintenance_fee();

// Using model method
$this->load->model('global_settings_model', 'settings_model');
$setting = $this->settings_model->get_setting_by_code('MAINTENANCE_FEE');
$maintenance_fee = $setting->value;
```

---

## Testing Checklist

- [ ] Execute SQL script to create table
- [ ] Verify Global Settings menu appears for admin users
- [ ] Verify Global Settings menu is hidden for non-admin users
- [ ] Test editing maintenance fee value
- [ ] Verify balance forward process uses new maintenance fee
- [ ] Verify new meter readings use new maintenance fee
- [ ] Test with different maintenance fee values
- [ ] Verify fallback to 25.00 if setting doesn't exist

---

## Future Enhancements

The module is designed to be extensible. Additional global settings can be added:
- Tax rates
- Penalty percentages
- Discount rates
- Other system-wide configuration values

Simply add new records to `tbl_global_settings` with appropriate codes and retrieve them using the model methods.

---

## Notes

- The maintenance fee is stored as FLOAT in the database
- Display formatting uses 2 decimal places
- Code field is case-insensitive (stored as uppercase)
- Code field must be unique
- All existing functionality remains backward compatible with fallback values

---

## Rollback Instructions

If needed to rollback:

1. Remove menu items from navigation files
2. Revert model changes to use hardcoded '25.00'
3. Remove helper function `get_maintenance_fee()`
4. Drop table: `DROP TABLE IF EXISTS tbl_global_settings;`

---

**End of Documentation**
