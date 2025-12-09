# Water Billing System - Complete User Manual & Documentation

## Table of Contents

1. [System Overview](#system-overview)
2. [Installation & Setup](#installation--setup)
3. [System Architecture](#system-architecture)
4. [User Roles & Permissions](#user-roles--permissions)
5. [Modules & Features](#modules--features)
6. [User Guide](#user-guide)
7. [Database Structure](#database-structure)
8. [Technical Documentation](#technical-documentation)
9. [Troubleshooting](#troubleshooting)
10. [Appendix](#appendix)

---

## System Overview

### What is the Water Billing System?

The Water Billing System is a comprehensive web-based application designed for managing water utility billing operations. It is built using **CodeIgniter PHP Framework** and provides a complete solution for:

- Customer management (Meter-based and Monthly customers)
- Billing and invoicing
- Payment processing
- Meter reading management
- Financial reporting
- Employee and payroll management
- Expense tracking
- Technical problem tracking
- Database backup and restore

### Key Features

- **Dual Customer Types**: Supports both meter-based and monthly fixed-rate customers
- **Automated Billing**: Automatic calculation of water consumption, penalties, and fees
- **Multi-Zone Management**: Organize customers by geographic zones
- **Role-Based Access Control**: Granular permissions for different user roles
- **Comprehensive Reporting**: Multiple report types including billing, aging AR, and customer reports
- **PDF Generation**: Generate invoices, receipts, and reports in PDF format
- **Mobile Support**: Mobile-responsive interface and mobile app features
- **Database Backup**: Automated database backup and restore functionality

### System Requirements

- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: PHP 5.6+ (PHP 7.x recommended)
- **Database**: MySQL 5.6+ or MariaDB 10.0+
- **Extensions**: 
  - mysqli
  - GD Library (for image processing)
  - mbstring
  - openssl
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)

---

## Installation & Setup

### Step 1: Download and Extract

1. Download the Water Billing System files
2. Extract to your web server directory (e.g., `htdocs/waterbilling1` for XAMPP)

### Step 2: Database Configuration

1. Create a MySQL database:
   ```sql
   CREATE DATABASE waterbilling1 CHARACTER SET utf8 COLLATE utf8_general_ci;
   ```

2. Import the database schema:
   - Locate the SQL file in `mydatabases/crazy_waterbillingsystem (2).sql`
   - Import it using phpMyAdmin or MySQL command line

3. Configure database connection:
   - Open `application/config/database.php`
   - Update the following settings:
   ```php
   $db['default']['hostname'] = 'localhost';
   $db['default']['username'] = 'root';  // Your MySQL username
   $db['default']['password'] = '';       // Your MySQL password
   $db['default']['database'] = 'waterbilling1';
   ```

### Step 3: Base URL Configuration

1. Open `application/config/config.php`
2. Update the base URL:
   ```php
   $config['base_url'] = 'http://localhost/waterbilling1/';
   ```
   Or for production:
   ```php
   $config['base_url'] = 'https://yourdomain.com/';
   ```

### Step 4: File Permissions

Set appropriate permissions:
```bash
chmod 755 application/cache
chmod 755 application/logs
chmod 755 backups
chmod 755 images/upload
```

### Step 5: Initial Login

1. Access the system: `http://localhost/waterbilling1/`
2. Default credentials (check with system administrator)
3. Change default password immediately after first login

---

## System Architecture

### Framework

- **Framework**: CodeIgniter 3.x
- **Architecture**: MVC (Model-View-Controller)
- **Database**: MySQL/MariaDB
- **PDF Library**: TCPDF

### Directory Structure

```
waterbilling1/
├── application/
│   ├── config/          # Configuration files
│   ├── controllers/     # Base controllers
│   ├── core/            # Core system files
│   ├── helpers/         # Helper functions
│   ├── libraries/       # Custom libraries
│   ├── models/          # Base models
│   ├── modules/         # Modular controllers, models, views
│   │   └── master/      # Main application module
│   │       ├── controllers/
│   │       ├── models/
│   │       └── views/
│   └── views/           # Base views
├── assets/              # CSS, JS, images
├── backups/             # Database backups
├── images/              # Uploaded images
├── system/              # CodeIgniter system files
└── index.php            # Entry point
```

### MVC Pattern

- **Models**: Data access layer (`application/modules/master/models/`)
- **Views**: Presentation layer (`application/modules/master/views/`)
- **Controllers**: Business logic layer (`application/modules/master/controllers/`)

---

## User Roles & Permissions

### User Types

1. **Super Admin**
   - Full system access
   - No permission restrictions
   - Can manage all modules

2. **Sub Admin**
   - Limited access based on assigned role
   - Permissions controlled by Responsibilities module
   - Can have different access levels per module

### Permission System

The system uses a granular permission system:

- **List (l)**: View records
- **Add (a)**: Create new records
- **Edit (e)**: Modify existing records
- **Delete (d)**: Remove records

### Setting Up Roles

1. Navigate to: **Master → Responsibilities**
2. Click "Add" to create a new role
3. Select modules and assign permissions
4. Assign the role to users

---

## Modules & Features

### 1. Dashboard

**Location**: `master/dashboard`

**Features**:
- Overview statistics (total customers, payments, expenses)
- Monthly sales charts
- Zone-wise customer distribution
- Recent transactions
- Technical problems summary
- Leaking balance summary

**Key Metrics Displayed**:
- Total Customers (Meter + Monthly)
- Total Expenses
- Total Payments (Meter + Monthly)
- Technical Problems (Total/Solved)
- Monthly Sales (Paid/Unpaid)

### 2. Customer Management

**Location**: `master/addcustomer`

**Features**:
- Add/Edit/Delete customers
- Support for two customer types:
  - **Meter Customers**: Billed based on water consumption
  - **Monthly Customers**: Fixed monthly billing
- Customer classification system
- Zone assignment
- Customer search and filtering
- Customer profile with photo upload
- QR code generation for customer accounts

**Customer Information**:
- Personal details (name, address, contact)
- Meter number
- Zone assignment
- Classification (residential, commercial, etc.)
- Customer type (meter/monthly)
- Status (active/inactive)

**Search Options**:
- Meter customer search
- Monthly customer search
- Paid customer search
- Unpaid customer search
- Technical problems search
- Income report search

### 3. Zone Management

**Location**: `master/add_zone`

**Features**:
- Create and manage geographic zones
- Assign customers to zones
- Zone-based reporting and filtering

### 4. Billing Period Management

**Location**: `master/addbillingperiod`

**Features**:
- Create billing periods (month/year combinations)
- Set due dates for each period
- Zone-specific billing periods
- Import billing periods from CSV
- Activate/deactivate billing periods

### 5. Meter Reading

**Location**: `master/addmetercustomerreading`

**Features**:
- Record meter readings
- Automatic consumption calculation
- Automatic billing amount calculation
- Support for CSV import
- Reading history tracking
- Status management (active, inactive, no reading)

**Reading Process**:
1. Select billing period
2. Enter current meter reading
3. System calculates:
   - Previous reading
   - Consumption (current - previous)
   - Amount based on rate structure
   - Penalties (if applicable)
   - Maintenance fees

### 6. Payment Processing

#### Meter Customer Payments

**Location**: `master/addpaymentcustomer`

**Features**:
- Record payments for meter customers
- Generate invoices/receipts
- Payment history tracking
- Partial payment support
- OR (Official Receipt) number generation
- Print payment receipts

#### Monthly Customer Payments

**Location**: `master/paymentmonthlycustomer`

**Features**:
- Record payments for monthly customers
- Generate monthly invoices
- Payment tracking
- Bulk payment generation

### 7. Fees Planning

**Location**: `master/feesplaning`

**Features**:
- Define billing plans
- Set rates per unit of consumption
- Maintenance fee configuration
- Classification-based pricing
- Support for tiered pricing

### 8. Amount Rate Management

**Location**: `master/amountrate`

**Features**:
- Configure per-unit water rates
- Set rates by classification
- Update rate structures
- Historical rate tracking

### 9. Reports

**Location**: `master/reports`

#### Available Reports:

1. **Daily Reports**
   - Daily collection summary
   - Payment details by date
   - Zone-wise collections

2. **Monthly Billing Report**
   - Billing summary by period
   - Filter by zone and status
   - Payment status tracking
   - Export to PDF

3. **Customer Report**
   - Complete customer listing
   - Filter by zone and status
   - Customer details export

4. **Aging AR Report**
   - Accounts receivable aging
   - Outstanding balances by age
   - 30/60/90/120/150+ days buckets
   - Filter by zone and status

5. **Leaking AR Report**
   - Leaking account balances
   - Outstanding leaking entries
   - Filter by zone

**Report Features**:
- Filter by zone, status, date range
- Export to PDF
- Print functionality
- Prepared by/Verified by/Approved by fields

### 10. Employee Management

**Location**: `master/addemployee`

**Features**:
- Add/Edit/Delete employees
- Employee profile management
- Job title assignment
- Employee login tracking
- Employee search

### 11. Payroll Management

**Location**: `master/payrols`

**Features**:
- Employee payroll processing
- Salary calculation
- Payroll history
- Payroll reports
- Print payroll slips

### 12. Expense Management

**Location**: `master/addexpenses`

**Features**:
- Record expenses
- Expense categorization
- Expense type management
- Expense search and filtering
- Expense reports
- Balance sheet integration

**Expense Types**:
- Operating expenses
- Maintenance expenses
- Administrative expenses
- Other expenses

### 13. Assets Management

**Location**: `master/addassets`

**Features**:
- Track company assets
- Asset valuation
- Asset search
- Asset reports

### 14. Technical Problems

**Location**: `master/technicalproblems`

**Features**:
- Log technical issues
- Track problem status
- Assign to employees
- Problem resolution tracking
- Search and filter problems

### 15. Leaking Entry

**Location**: `master/leakingentry`

**Features**:
- Record leaking accounts
- Track leaking balances
- Leaking account reports
- Leaking AR reports

### 16. Statement of Account

**Location**: `master/statementofaccount`

**Features**:
- Generate customer statements
- View billing history
- Payment history
- Outstanding balances
- Print statements

### 17. OR Correction

**Location**: `master/or_correction`

**Features**:
- Correct official receipt entries
- Edit payment records
- Maintain audit trail

### 18. Database Backup

**Location**: `master/database_backup`

**Features**:
- Create database backups
- List all backups
- Download backups
- Restore from backup
- Delete backups
- Backup history tracking

**Usage**:
1. Click "Create Backup" to generate a new backup
2. Download backups for off-site storage
3. Restore from backup when needed (WARNING: Overwrites current database)
4. Delete old backups to free space

### 19. Responsibilities (Role Management)

**Location**: `master/responsibilities`

**Features**:
- Create user roles
- Assign module permissions
- Set permission levels (List/Add/Edit/Delete)
- Assign roles to users
- Role-based access control

### 20. Web Settings

**Location**: `master/web_settings`

**Features**:
- Configure system settings
- Company information
- Contact details
- System preferences

### 21. Account Management

**Location**: `master/change_password` and `master/change_username`

**Features**:
- Change user password
- Change username
- Security settings

### 22. Accounting Modules

#### Account Groups
**Location**: `master/addaccountgroup`

#### Sub Account Groups
**Location**: `master/addsubaccountgroup`

#### Ledger
**Location**: `master/addledger`

#### Journal Voucher
**Location**: `master/addjournalvoucher`

#### Trial Balance
**Location**: `master/trialbalance`

#### Transaction
**Location**: `master/transaction`

### 23. Mobile App Features

**Location**: `master/mobile_dashboard`, `master/mobile_tickets`

**Features**:
- Mobile-optimized dashboard
- Mobile ticket management
- Mobile-responsive views

---

## User Guide

### Getting Started

1. **Login**
   - Navigate to the system URL
   - Enter username and password
   - Click "Login"

2. **Dashboard Overview**
   - After login, you'll see the dashboard
   - Review key metrics and statistics
   - Access modules from the navigation menu

### Adding a New Customer

1. Navigate to **Master → Add Customer**
2. Click "Add" button
3. Fill in customer information:
   - Personal details (First Name, Last Name, Middle Name)
   - Address
   - Contact information
   - Meter number (for meter customers)
   - Zone selection
   - Classification
   - Customer type (Meter/Monthly)
4. Upload customer photo (optional)
5. Click "Save"

### Recording Meter Reading

1. Navigate to **Master → Meter Customer Reading**
2. Select billing period
3. Select zone (optional, for filtering)
4. Click "Add Reading" or search for customer
5. Enter current meter reading
6. System automatically calculates:
   - Consumption
   - Amount due
   - Penalties (if applicable)
7. Save the reading

### Processing Payment

#### For Meter Customers:

1. Navigate to **Master → Meter Customer Bills**
2. Search for customer or select from list
3. Click "Add Payment"
4. Enter payment details:
   - Payment amount
   - Payment date
   - Payment method
5. System generates OR number
6. Click "Save"
7. Print receipt if needed

#### For Monthly Customers:

1. Navigate to **Master → Monthly Customer Bills**
2. Select customer
3. Generate bill for billing period
4. Record payment
5. Print invoice

### Generating Reports

1. Navigate to **Master → Reports**
2. Select report type:
   - Daily Reports
   - Monthly Billing Report
   - Customer Report
   - Aging AR Report
   - Leaking AR Report
3. Set filters:
   - Zone
   - Status
   - Date range (if applicable)
4. Click "Search" or "Generate"
5. View results
6. Export to PDF or print

### Managing Billing Periods

1. Navigate to **Master → Billing Period**
2. Click "Add" to create new period
3. Enter:
   - Month
   - Year
   - Due date
   - Zone (if zone-specific)
4. Activate the period
5. Use for billing operations

### Setting Up Fees/Rates

1. Navigate to **Master → Fees Planning** or **Amount Rate**
2. Click "Add"
3. Configure:
   - Rate per unit
   - Maintenance fees
   - Classification-based rates
4. Save configuration

### Database Backup

1. Navigate to **Master → Database Backup**
2. Click "Create Backup"
3. Wait for backup generation
4. Download backup file
5. Store securely

**To Restore**:
1. Select a backup from the list
2. Click restore icon
3. Confirm restoration
4. **WARNING**: This will overwrite current database

---

## Database Structure

### Core Tables

#### Customer Tables
- `tbl_addcustomer` - Customer master data
- `tbl_addmetercustomer` - Meter customer payments
- `tbl_monthlycustomer` - Monthly customer payments
- `tbl_addcustomer_reading` - Meter readings

#### Billing Tables
- `tbl_billing_period` - Billing periods
- `tbl_feesplaning` - Fee plans
- `tbl_classification` - Customer classifications
- `tbl_classification_category` - Classification categories

#### Zone & Location
- `tbl_zone` - Zone master data

#### Employee & Payroll
- `tbl_addemployee` - Employee data
- `tbl_payrols` - Payroll records
- `tbl_jobtitle` - Job titles
- `tbl_responsibilities_user` - User accounts
- `tbl_responsibilities` - Role definitions

#### Financial
- `tbl_addexpenses` - Expenses
- `tbl_addexpensestype` - Expense types
- `tbl_addassets` - Assets
- `tbl_addledger` - Ledger entries
- `tbl_addjournalvoucher` - Journal vouchers

#### System
- `tbl_websettings` - System settings
- `tbl_months` - Month master data
- `tbl_database_backups` - Backup records

#### Other
- `tbl_technicalproblems` - Technical issues
- `tbl_leakingentry` - Leaking entries
- `tbl_addshareholder` - Shareholders
- `tbl_addaccountgroup` - Account groups
- `tbl_addsubaccountgroup` - Sub account groups

### Key Relationships

- Customer → Zone (Many-to-One)
- Customer → Classification (Many-to-One)
- Customer Reading → Billing Period (Many-to-One)
- Customer Reading → Customer (Many-to-One)
- Payment → Customer Reading (One-to-One)
- Employee → Job Title (Many-to-One)
- User → Responsibilities (Many-to-One)

---

## Technical Documentation

### URL Structure

The system uses CodeIgniter's URL routing:

```
http://domain.com/index.php/module/controller/method/parameters
```

**Examples**:
- Dashboard: `/master/dashboard`
- Customer List: `/master/addcustomer`
- Add Customer: `/master/addcustomer/add`
- Edit Customer: `/master/addcustomer/edit/{id}`
- Reports: `/master/reports/monthly_billing_report`

### Controllers

All controllers are located in `application/modules/master/controllers/`

**Naming Convention**:
- Controller files: lowercase with underscores (e.g., `addcustomer.php`)
- Controller classes: PascalCase (e.g., `class addcustomer extends CI_Controller`)

### Models

All models are located in `application/modules/master/models/`

**Naming Convention**:
- Model files: lowercase with underscores + `_model` suffix (e.g., `addcustomer_model.php`)
- Model classes: PascalCase + `_model` suffix (e.g., `class Addcustomer_model extends CI_Model`)

### Views

All views are located in `application/modules/master/views/`

**Naming Convention**:
- View files: lowercase with underscores (e.g., `addcustomer.php`, `addcustomer_add.php`)

### Common Helpers

- `common_helper.php` - Common utility functions
- `common` - Loaded in controllers for common operations

### Libraries

- **Pdf**: TCPDF library for PDF generation
- **ciqrcode**: QR code generation
- **form_validation**: Form validation
- **pagination**: Pagination support

### Session Management

The system uses CodeIgniter sessions:
- User ID: `$this->session->userdata('userid')`
- Username: `$this->session->userdata('username')`
- User Type: `$this->session->userdata('usertype')`

### File Uploads

Upload configuration:
- Path: `./images/upload`
- Allowed types: `gif|jpg|png`
- Max size: 10000 KB
- Max dimensions: 1024x768

### PDF Generation

Uses TCPDF library:
- Location: `application/libraries/Pdf.php`
- Views for PDF: Located in views folder with `printtopdf` or `print` in name

### AJAX Operations

AJAX views are identified by `_ajax` suffix:
- Example: `addcustomer_search _ajax.php`
- Called via jQuery AJAX from main views

### CSV Import

CSV import functionality available for:
- Meter readings
- Billing periods
- Customer data

Location: `csv_import.php`, `import.php`, `import_data.php`

---

## Troubleshooting

### Common Issues

#### 1. Database Connection Error

**Problem**: Cannot connect to database

**Solutions**:
- Check `application/config/database.php` settings
- Verify MySQL service is running
- Confirm database name, username, password
- Check database user permissions

#### 2. 404 Page Not Found

**Problem**: Pages return 404 error

**Solutions**:
- Check `.htaccess` file (if using mod_rewrite)
- Verify `application/config/config.php` base_url
- Check `application/config/routes.php`
- Ensure Apache mod_rewrite is enabled

#### 3. Permission Denied Errors

**Problem**: Access denied to modules

**Solutions**:
- Check user role and permissions
- Verify user is assigned correct role
- Check Responsibilities module settings
- Ensure user type is correct (superadmin/subadmin)

#### 4. File Upload Fails

**Problem**: Cannot upload images/files

**Solutions**:
- Check `images/upload` directory permissions (755)
- Verify PHP upload_max_filesize and post_max_size
- Check file type restrictions
- Ensure directory exists and is writable

#### 5. PDF Generation Errors

**Problem**: PDFs not generating

**Solutions**:
- Check TCPDF library is loaded
- Verify write permissions
- Check PHP memory limit
- Review error logs

#### 6. Session Expired

**Problem**: Frequent logouts

**Solutions**:
- Check session configuration in `application/config/config.php`
- Increase session expiration time
- Check server session settings
- Verify cookies are enabled

#### 7. AJAX Not Working

**Problem**: AJAX requests failing

**Solutions**:
- Check browser console for JavaScript errors
- Verify jQuery is loaded
- Check AJAX view files exist
- Verify controller methods return correct data

#### 8. Backup/Restore Issues

**Problem**: Backup or restore fails

**Solutions**:
- Check `backups/` directory permissions
- Verify database user has backup/restore privileges
- Check PHP execution time limits
- Ensure sufficient disk space

### Error Logs

Check error logs in:
- `application/logs/` - Application logs
- Server error logs (Apache/Nginx)
- PHP error logs

### Debug Mode

To enable debug mode:
1. Open `index.php`
2. Change: `define('ENVIRONMENT', 'production');` to `define('ENVIRONMENT', 'development');`
3. Errors will be displayed (disable in production!)

---

## Appendix

### A. Module List Reference

| Module | Controller | Description |
|--------|-----------|-------------|
| Dashboard | dashboard | Main dashboard |
| Customer | addcustomer | Customer management |
| Zone | add_zone | Zone management |
| Billing Period | addbillingperiod | Billing period setup |
| Meter Reading | addmetercustomerreading | Meter reading entry |
| Meter Payment | addpaymentcustomer | Meter customer payments |
| Monthly Payment | paymentmonthlycustomer | Monthly customer payments |
| Fees Planning | feesplaning | Fee plan configuration |
| Amount Rate | amountrate | Rate management |
| Reports | reports | Report generation |
| Employee | addemployee | Employee management |
| Payroll | payrols | Payroll processing |
| Expenses | addexpenses | Expense tracking |
| Assets | addassets | Asset management |
| Technical Problems | technicalproblems | Issue tracking |
| Leaking Entry | leakingentry | Leaking account management |
| Statement | statementofaccount | Customer statements |
| OR Correction | or_correction | Receipt correction |
| Database Backup | database_backup | Backup/restore |
| Responsibilities | responsibilities | Role management |
| Web Settings | web_settings | System settings |

### B. Status Codes

**Customer Status**:
- `1` - Active
- `0` - Inactive
- `2` - Suspended

**Payment Status**:
- `1` - Paid
- `0` - Unpaid
- `99` - All

**Reading Status**:
- `1` - Active/Read
- `0` - No reading
- `2` - Inactive
- `3` - No consumption
- `4` - Has reading

### C. File Upload Limits

Default PHP settings (may need adjustment):
- `upload_max_filesize`: 2M (recommend 10M)
- `post_max_size`: 8M (recommend 20M)
- `max_execution_time`: 30 seconds
- `memory_limit`: 128M

### D. Database Backup Schedule

**Recommended Schedule**:
- Daily backups for production
- Weekly backups for development
- Monthly archive backups
- Store backups off-site

### E. Security Best Practices

1. **Change Default Passwords**: Immediately after installation
2. **Regular Updates**: Keep PHP and MySQL updated
3. **Backup Regularly**: Automated daily backups
4. **Access Control**: Limit admin access
5. **HTTPS**: Use SSL in production
6. **File Permissions**: Restrict file permissions
7. **Input Validation**: All user inputs validated
8. **SQL Injection**: Use prepared statements (CodeIgniter Query Builder)
9. **XSS Protection**: Output escaping enabled
10. **Session Security**: Secure session configuration

### F. Support Contacts

For technical support or questions:
- Check system logs first
- Review this documentation
- Contact system administrator
- Check CodeIgniter documentation: https://codeigniter.com/user_guide/

### G. Version Information

- **Framework**: CodeIgniter 3.x
- **PHP Version**: 5.6+ (7.x recommended)
- **MySQL Version**: 5.6+
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)

### H. Quick Reference Commands

**Database Backup** (via command line):
```bash
mysqldump -u username -p database_name > backup.sql
```

**Database Restore** (via command line):
```bash
mysql -u username -p database_name < backup.sql
```

**Check PHP Version**:
```bash
php -v
```

**Check MySQL Version**:
```bash
mysql --version
```

---

## Document Version

- **Version**: 1.0
- **Last Updated**: 2025
- **Author**: System Documentation
- **Status**: Complete

---

## License & Copyright

This documentation is provided for the Water Billing System. All rights reserved.

---

**End of Documentation**

