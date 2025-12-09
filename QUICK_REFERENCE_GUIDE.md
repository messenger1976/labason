# Water Billing System - Quick Reference Guide

## Common Tasks

### Login
- URL: `/master` or `/`
- Enter username and password
- Click "Login"

### Add New Customer
1. **Master → Add Customer → Add**
2. Fill customer details
3. Select zone and classification
4. Choose customer type (Meter/Monthly)
5. Save

### Record Meter Reading
1. **Master → Meter Customer Reading**
2. Select billing period
3. Search/Select customer
4. Enter current reading
5. System calculates amount automatically
6. Save

### Process Payment (Meter Customer)
1. **Master → Meter Customer Bills**
2. Search customer
3. Click "Add Payment"
4. Enter amount and date
5. System generates OR number
6. Save and print receipt

### Process Payment (Monthly Customer)
1. **Master → Monthly Customer Bills**
2. Select customer
3. Generate bill for period
4. Record payment
5. Print invoice

### Generate Monthly Billing Report
1. **Master → Reports → Monthly Billing Report**
2. Select billing period
3. Select zone (optional)
4. Select status (All/Paid/Unpaid)
5. Click "Search"
6. Export to PDF if needed

### Generate Aging AR Report
1. **Master → Reports → Aging AR Report**
2. Enter "As of Date"
3. Select zone (optional)
4. Select status
5. Click "Search"
6. Export to PDF

### Create Database Backup
1. **Master → Database Backup**
2. Click "Create Backup"
3. Wait for completion
4. Download backup file

### Create Billing Period
1. **Master → Billing Period → Add**
2. Select month and year
3. Set due date
4. Select zone (if zone-specific)
5. Activate period
6. Save

### Search Customer
- **Master → Add Customer**
- Use search filters:
  - Meter customer search
  - Monthly customer search
  - Paid/Unpaid search
  - Technical problems search

### Print Invoice/Receipt
- After payment, click "Print" button
- Or navigate to customer invoice page
- Select billing period
- Click "Print"

### Add Employee
1. **Master → Add Employee → Add**
2. Fill employee details
3. Assign job title
4. Save

### Record Expense
1. **Master → Add Expenses → Add**
2. Select expense type
3. Enter amount and description
4. Select date
5. Save

### Log Technical Problem
1. **Master → Technical Problems → Add**
2. Enter problem description
3. Assign to employee
4. Set status
5. Save

### Change Password
1. **Master → Change Password**
2. Enter current password
3. Enter new password
4. Confirm new password
5. Save

## URL Shortcuts

| Task | URL |
|------|-----|
| Dashboard | `/master/dashboard` |
| Customer List | `/master/addcustomer` |
| Add Customer | `/master/addcustomer/add` |
| Meter Reading | `/master/addmetercustomerreading` |
| Meter Payment | `/master/addpaymentcustomer` |
| Monthly Payment | `/master/paymentmonthlycustomer` |
| Reports | `/master/reports` |
| Billing Period | `/master/addbillingperiod` |
| Employee | `/master/addemployee` |
| Expenses | `/master/addexpenses` |
| Database Backup | `/master/database_backup` |
| Settings | `/master/web_settings` |

## Keyboard Shortcuts

- **Ctrl + S**: Save (in forms)
- **Ctrl + P**: Print current page
- **Esc**: Close modals
- **Enter**: Submit forms

## Status Codes Quick Reference

| Code | Meaning |
|------|---------|
| 0 | Inactive/Unpaid |
| 1 | Active/Paid |
| 2 | Suspended |
| 3 | No consumption |
| 4 | Has reading |
| 99 | All/Any |

## Common Filters

### Zone Filter
- All Zones: `0` or empty
- Specific Zone: Zone ID (1, 2, 3, etc.)

### Status Filter
- All: `99` or empty
- Paid: `1`
- Unpaid: `0`
- Inactive: `2`

## Report Types

1. **Daily Report**: Daily collection summary
2. **Monthly Billing Report**: Billing by period
3. **Customer Report**: Customer listing
4. **Aging AR Report**: Outstanding balances by age
5. **Leaking AR Report**: Leaking account balances

## File Locations

- **Customer Photos**: `images/upload/`
- **Backups**: `backups/`
- **Logs**: `application/logs/`
- **Exports**: Downloads folder

## Contact Support

For issues:
1. Check error logs
2. Review main manual
3. Contact system administrator

---

**Quick Reference v1.0**

