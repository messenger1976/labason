# Customer Management System Documentation

## Overview
This documentation covers the server-side pagination and customer login password management features implemented in the Customer Management module (`addcustomer`).

**Applicable to:**
- `waterbilling1` codebase
- `labason` codebase

---

## Table of Contents
1. [Server-Side Pagination](#server-side-pagination)
2. [Customer Login Password Management](#customer-login-password-management)
3. [Technical Implementation](#technical-implementation)
4. [Database Schema](#database-schema)
5. [API Endpoints](#api-endpoints)
6. [Troubleshooting](#troubleshooting)

---

## Server-Side Pagination

### Overview
The customer listing page now uses server-side pagination instead of loading all records at once. This significantly improves page load times, especially for large datasets.

### Features
- **Default Page Size**: 100 records per page
- **Page Size Options**: 10, 25, 50, 100 records
- **Search Functionality**: Real-time search across multiple fields
- **Sorting**: Click column headers to sort
- **Performance**: Only loads data for the current page

### How It Works
1. DataTables sends AJAX requests to the server with pagination parameters
2. Server processes the request and returns only the requested page of data
3. DataTables renders the data in the table
4. User can navigate between pages, change page size, or search

### User Interface
- **Search Box**: Located at the top-right of the table
- **Page Size Dropdown**: Located at the top-left (shows "10 25 50 100")
- **Pagination Controls**: Located at the bottom of the table
- **Sorting**: Click any column header to sort (except checkbox, S No, and Action columns)

---

## Customer Login Password Management

### Overview
Administrators can now set login passwords for customers directly from the customer listing page. This allows customers to log in to the system using their customer ID and password.

### Features
- **Password Icon**: Orange key icon in the action column
- **Modal Popup**: User-friendly form for password entry
- **Password Validation**: Minimum 3 characters, must match confirmation
- **MD5 Encryption**: Passwords are encrypted using MD5 (same as admin login)
- **Auto Column Creation**: Password column is automatically created if it doesn't exist

### How to Use

#### Setting a Customer Password
1. Navigate to the Customer Management page (`/master/addcustomer`)
2. Find the customer in the table
3. Click the orange key icon (🔑) in the Action column
4. A modal window will appear showing:
   - Customer ID (read-only)
   - Password field
   - Confirm Password field
5. Enter the desired password (minimum 3 characters)
6. Confirm the password
7. Click "Save Password"
8. A success message will appear and the modal will close automatically

#### Password Requirements
- Minimum 3 characters
- Must match the confirmation password
- Stored as MD5 hash in the database

---

## Technical Implementation

### File Structure

```
application/
├── modules/
│   └── master/
│       ├── controllers/
│       │   └── addcustomer.php          # Main controller
│       ├── models/
│       │   └── addcustomer_model.php    # Data access layer
│       └── views/
│           └── addcustomer.php          # User interface
```

### Controller Methods

#### `index()`
- **Purpose**: Loads the customer listing page
- **Changes**: No longer loads all records (uses empty array)
- **Location**: `application/modules/master/controllers/addcustomer.php`

#### `get_datatable_data()`
- **Purpose**: AJAX endpoint for DataTables server-side processing
- **Method**: POST
- **Parameters**:
  - `start`: Starting record index (for pagination)
  - `length`: Number of records to return
  - `search[value]`: Search term
  - `order[0][column]`: Column index to sort by
  - `order[0][dir]`: Sort direction (asc/desc)
  - `draw`: Draw counter for DataTables
- **Returns**: JSON response with:
  - `draw`: Draw counter
  - `recordsTotal`: Total number of records
  - `recordsFiltered`: Number of records after filtering
  - `data`: Array of customer records

#### `save_customer_password()`
- **Purpose**: Saves customer login password
- **Method**: POST
- **Parameters**:
  - `customer_id`: Customer database ID
  - `password`: Plain text password
- **Returns**: JSON response with:
  - `success`: Boolean indicating success/failure
  - `message`: Success or error message

### Model Methods

#### `get_paginated_records($start, $length, $search, $order_column, $order_dir)`
- **Purpose**: Retrieves paginated customer records with filtering and sorting
- **Parameters**:
  - `$start`: Starting record index (default: 0)
  - `$length`: Number of records to return (default: 10)
  - `$search`: Search term (default: '')
  - `$order_column`: Column to sort by (default: 'last_name')
  - `$order_dir`: Sort direction (default: 'asc')
- **Returns**: Array of customer records

#### `get_total_count($search)`
- **Purpose**: Gets total count of records (with optional search filter)
- **Parameters**:
  - `$search`: Search term (default: '')
- **Returns**: Integer count of records

#### `update_customer_password($customer_id, $password)`
- **Purpose**: Updates customer password in database
- **Parameters**:
  - `$customer_id`: Customer database ID
  - `$password`: MD5 encrypted password
- **Returns**: Boolean (true on success, false on failure)
- **Special Feature**: Automatically creates `password` column if it doesn't exist

### View Components

#### DataTables Configuration
```javascript
$('#dt_basic').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?php echo ADMIN_URL;?>addcustomer/get_datatable_data",
        "type": "POST"
    },
    "pageLength": 100,
    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
    // ... other configuration
});
```

#### Password Modal
- **HTML**: Bootstrap modal with form fields
- **JavaScript**: Handles form validation, AJAX submission, and error handling
- **Location**: Added before `footer.php` include in view file

---

## Database Schema

### Table: `tbl_addcustomer`

#### New Column: `password`
- **Type**: VARCHAR(255)
- **Null**: YES
- **Default**: NULL
- **Purpose**: Stores MD5 encrypted password for customer login
- **Auto-Creation**: Column is automatically created on first password save if it doesn't exist

#### SQL to Manually Add Column (if needed)
```sql
ALTER TABLE `tbl_addcustomer` ADD COLUMN `password` VARCHAR(255) NULL;
```

### Related Tables
- `tbl_zone`: Zone information (JOIN)
- `tbl_classification`: Customer classification (JOIN)
- `tbl_customer_type`: Customer type (JOIN)
- `tbl_feesplaning`: Billing plans (JOIN)

---

## API Endpoints

### Get DataTable Data
```
POST /master/addcustomer/get_datatable_data
```

**Request Body:**
```json
{
    "start": 0,
    "length": 100,
    "search": {
        "value": "search term"
    },
    "order": [{
        "column": 3,
        "dir": "asc"
    }],
    "draw": 1
}
```

**Response:**
```json
{
    "draw": 1,
    "recordsTotal": 500,
    "recordsFiltered": 25,
    "data": [
        [
            "<input type='checkbox'...>",
            "1",
            "CUST001",
            "Doe, John M",
            "<span>123 Main St</span>",
            "MTR001",
            "Zone A",
            "Residential",
            "<span class='label...'>Active</span>",
            "<div class='action-buttons'>...</div>"
        ]
    ]
}
```

### Save Customer Password
```
POST /master/addcustomer/save_customer_password
```

**Request Body:**
```json
{
    "customer_id": "123",
    "password": "customerpassword"
}
```

**Success Response:**
```json
{
    "success": true,
    "message": "Password saved successfully."
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Failed to save password. Please check the logs for details."
}
```

---

## Troubleshooting

### Issue: DataTable shows "No data available"
**Possible Causes:**
1. AJAX endpoint not accessible
2. Database connection issues
3. Model not loaded correctly

**Solutions:**
1. Check browser console for AJAX errors
2. Verify database connection in `config/database.php`
3. Check CodeIgniter logs in `application/logs/`

### Issue: Password save returns 500 error
**Possible Causes:**
1. Password column doesn't exist and auto-creation failed
2. Database permissions issue
3. Model method error

**Solutions:**
1. Manually add password column using SQL:
   ```sql
   ALTER TABLE `tbl_addcustomer` ADD COLUMN `password` VARCHAR(255) NULL;
   ```
2. Check database user permissions
3. Review error logs in `application/logs/log-YYYY-MM-DD.php`

### Issue: Search not working
**Possible Causes:**
1. Search parameter not being sent correctly
2. Database query error
3. Column name mismatch

**Solutions:**
1. Check browser Network tab to verify POST parameters
2. Review model `get_paginated_records` method
3. Verify column names in database match query

### Issue: Page loads slowly
**Possible Causes:**
1. Server-side pagination not enabled
2. Large number of JOINs
3. Missing database indexes

**Solutions:**
1. Verify DataTables configuration has `"serverSide": true`
2. Check query execution time in logs
3. Add indexes on frequently searched columns:
   ```sql
   CREATE INDEX idx_customer_id ON tbl_addcustomer(customer_id);
   CREATE INDEX idx_last_name ON tbl_addcustomer(last_name);
   ```

---

## Security Considerations

### Password Storage
- Passwords are encrypted using MD5 before storage
- Plain text passwords are never stored
- Password column allows NULL values (customers without passwords)

### Access Control
- Password management requires admin authentication
- Customer ID validation prevents unauthorized access
- AJAX endpoints should be protected by authentication middleware

### Recommendations
1. Consider upgrading from MD5 to bcrypt or Argon2 for better security
2. Implement password strength requirements
3. Add password reset functionality
4. Log password changes for audit purposes

---

## Future Enhancements

### Potential Improvements
1. **Password Reset**: Allow customers to reset their own passwords
2. **Password Strength Meter**: Visual indicator of password strength
3. **Bulk Password Generation**: Generate random passwords for multiple customers
4. **Password History**: Track password changes
5. **Two-Factor Authentication**: Add 2FA for customer logins
6. **Password Expiration**: Force password changes after certain period

### Performance Optimizations
1. Add database indexes on searchable columns
2. Implement caching for frequently accessed data
3. Optimize JOIN queries
4. Consider using database views for complex queries

---

## Version History

### Version 1.0 (Current)
- ✅ Server-side pagination implementation
- ✅ Default page size set to 100
- ✅ Customer login password management
- ✅ Password icon in action column
- ✅ Modal popup for password entry
- ✅ MD5 password encryption
- ✅ Auto column creation

---

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review CodeIgniter logs in `application/logs/`
3. Check browser console for JavaScript errors
4. Verify database schema matches documentation

---

## Code Locations

### Waterbilling1
- Controller: `C:\xampp2\htdocs\waterbilling1\application\modules\master\controllers\addcustomer.php`
- Model: `C:\xampp2\htdocs\waterbilling1\application\modules\master\models\addcustomer_model.php`
- View: `C:\xampp2\htdocs\waterbilling1\application\modules\master\views\addcustomer.php`

### Labason
- Controller: `C:\xampp2\htdocs\labason\application\modules\master\controllers\addcustomer.php`
- Model: `C:\xampp2\htdocs\labason\application\modules\master\models\addcustomer_model.php`
- View: `C:\xampp2\htdocs\labason\application\modules\master\views\addcustomer.php`

---

**Last Updated**: January 17, 2026
**Documentation Version**: 1.0
