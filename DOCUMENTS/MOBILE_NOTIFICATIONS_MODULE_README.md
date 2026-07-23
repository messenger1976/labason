# Mobile Notifications Module - ITEXMO API Integration

This module provides comprehensive mobile notification functionality using the ITEXMO API for sending SMS notifications to customers regarding billing statements, due accounts, disconnection notices, and custom messages.

## Features

- **Billing Statement Notifications**: Automatically send billing statements to customers
- **Due Account Alerts**: Notify customers about upcoming due dates
- **Disconnection Notices**: Send urgent notifications for overdue accounts
- **Custom Messages**: Send personalized messages to individual customers
- **Analytics Dashboard**: Track SMS delivery statistics and success rates
- **Notification History**: View all sent messages with status tracking
- **ITEXMO API Integration**: Full integration with ITEXMO SMS gateway

## Installation

### 1. Database Setup

Run the SQL script to create the necessary tables:

```sql
-- Execute sql/mobile_notifications_table.sql
```

This will create:
- `tbl_sms_notifications` - Stores all SMS notification logs
- `tbl_sms_settings` - Stores ITEXMO API configuration
- Adds `mobile_notifications` column to `tbl_responsibilities` table

### 2. ITEXMO API Setup

1. Visit [https://www.itexmo.com](https://www.itexmo.com)
2. Register for an account or log in
3. Navigate to API Settings in your dashboard
4. Copy your API Code and API Password
5. Go to **Mobile Notifications > Settings** in the admin panel
6. Enter your ITEXMO credentials and save

### 3. Module Access

The module is automatically added to the admin menu under "Mobile Notifications". Access it via:
- **Notifications**: Main page for sending notifications
- **Settings**: Configure ITEXMO API credentials
- **Analytics Dashboard**: View SMS statistics and reports

## Usage

### Sending Billing Statements

1. Navigate to **Mobile Notifications**
2. Click **"Billing Statements"** button
3. Select the billing period
4. Optionally select a specific zone
5. Click **"Send Notifications"**

The system will:
- Find all customers with mobile numbers for the selected billing period
- Generate personalized messages with bill amount and due date
- Send SMS via ITEXMO API
- Log all notifications with status

### Sending Due Account Notifications

1. Click **"Due Accounts"** button
2. Set the number of days before due date (default: 3 days)
3. Click **"Send Notifications"**

The system will notify customers whose bills are due within the specified days.

### Sending Disconnection Notices

1. Click **"Disconnection Notices"** button
2. Set the number of days overdue (default: 30 days)
3. Click **"Send Notifications"**

The system will send urgent notifications to customers with overdue accounts.

### Sending Custom Messages

1. Click **"Custom Message"** button
2. Select a customer (optional) or enter mobile number manually
3. Type your message (max 160 characters)
4. Click **"Send Message"**

### Viewing Analytics

1. Navigate to **Mobile Notifications > Analytics Dashboard**
2. Select date range using the filter
3. View statistics including:
   - Total sent messages
   - Success rate
   - Failed messages
   - Message type breakdown
   - Daily message chart

## Message Types

- **billing_statement**: Regular billing notifications
- **due_account**: Upcoming due date reminders
- **disconnection**: Overdue account warnings
- **custom**: Custom messages sent manually

## Notification Status

- **pending**: Message queued but not sent
- **sent**: Message successfully sent via ITEXMO
- **failed**: Message failed to send (check ITEXMO response code)

## ITEXMO Response Codes

The system handles the following ITEXMO response codes:

- **0**: Success
- **1**: Invalid Number
- **2**: Number prefix not supported
- **3**: Invalid API Code
- **4**: Maximum Message per day reached
- **5**: Maximum allowed characters for message reached
- **6**: System Offline
- **7**: Expired API Code
- **8**: iTexMo Error
- **9**: Invalid function parameters
- **10**: Recipient's number is blocked
- **11**: Recipient's number is invalid
- **12**: Invalid sender ID
- **13**: Sender ID not allowed

## Files Created

### Controllers
- `application/modules/master/controllers/mobilenotifications.php`

### Models
- `application/modules/master/models/mobilenotifications_model.php`

### Views
- `application/modules/master/views/mobilenotifications.php` - Main notifications page
- `application/modules/master/views/mobilenotifications_settings.php` - Settings page
- `application/modules/master/views/mobilenotifications_dashboard.php` - Analytics dashboard

### Database
- `sql/mobile_notifications_table.sql` - Database schema

## Files Modified

- `application/views/admin-includes/navigation.php` - Added menu items
- `application/modules/master/models/adminheader_model.php` - Added module to permissions
- `application/modules/master/controllers/dashboard.php` - Added SMS statistics
- `application/modules/master/models/dashboard_model.php` - Added SMS table reference

## Permissions

The module uses the `mobile_notifications` permission in the responsibilities system. To grant access:

1. Go to **Admin > Roles & Responsibilities**
2. Edit the role
3. Enable `mobile_notifications` permission
4. Save

## API Integration Details

The module uses ITEXMO's HTTP API endpoint:
- **URL**: `https://www.itexmo.com/php_api/api.php`
- **Method**: POST
- **Parameters**:
  - `1`: Mobile number
  - `2`: Message text
  - `3`: API Code
  - `passwd`: API Password
  - `6`: Sender ID (optional)

## Troubleshooting

### Messages Not Sending

1. Check ITEXMO API credentials in Settings
2. Verify you have sufficient credits in your ITEXMO account
3. Check the notification log for error codes
4. Ensure mobile numbers are in correct format (digits only)

### High Failure Rate

1. Verify mobile numbers are valid and active
2. Check if numbers are blocked in ITEXMO
3. Ensure API code hasn't expired
4. Verify sender ID is registered

### Dashboard Not Showing Data

1. Ensure notifications have been sent
2. Check date range filter
3. Verify database connection

## Support

For ITEXMO API issues, contact ITEXMO support at [https://www.itexmo.com](https://www.itexmo.com)

For module issues, check the notification logs and ITEXMO response codes in the notification details.

## Notes

- Mobile numbers are automatically cleaned (spaces, dashes removed)
- Messages are limited to 160 characters for standard SMS
- All notifications are logged for audit purposes
- The system uses customer's `mobile1` field first, falls back to `mobile2`
- Only customers with active status and valid mobile numbers receive notifications

