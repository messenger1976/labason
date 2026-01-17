# Statement of Account API Documentation

## Overview

The Statement of Account API provides RESTful endpoints for retrieving customer billing information, ledger entries, and account balances. This API is designed for integration with mobile applications, third-party systems, and automated reporting tools.

**Base URL**: `/master/statementofaccount_api`

**Response Format**: All responses are returned in JSON format with the following structure:

```json
{
  "success": true,
  "message": "Operation message",
  "timestamp": "2024-01-15 10:30:00",
  "data": { /* Response data */ }
}
```

---

## Table of Contents

1. [Authentication](#authentication)
2. [Endpoints](#endpoints)
   - [Get Complete Statement](#1-get-complete-statement-of-account)
   - [Get Customer Information](#2-get-customer-information)
   - [Get Ledger Entries](#3-get-ledger-entries)
   - [Get Current Balance](#4-get-current-balance)
   - [Search Customer](#5-search-customer)
   - [Reset Password](#6-reset-password)
   - [API Information](#7-api-information)
3. [Response Codes](#response-codes)
4. [Data Models](#data-models)
5. [Examples](#examples)
6. [Error Handling](#error-handling)

---

## Authentication

Currently, the API does not require authentication. However, it is recommended to implement authentication for production use.

**Note**: Consider implementing API key authentication or OAuth2 for production deployments.

---

## Endpoints

### 1. Get Complete Statement of Account

Retrieves the complete statement of account for a customer, including customer information, all ledger entries, and current balance.

**Endpoint**: `GET /master/statementofaccount_api/get/{customer_id}`

**Alternative**: `GET /master/statementofaccount_api/get?customer_id={customer_id}`

**Parameters**:
- `customer_id` (required) - Customer ID

**Response**:
```json
{
  "success": true,
  "message": "Statement of account retrieved successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "customer_info": {
      "customer_id": "12345",
      "customer_name": "John Doe",
      "address": "123 Main St",
      "zone": "Zone 1",
      "class_name": "Residential",
      "cust_type_name": "Regular",
      /* ... other customer fields ... */
    },
    "ledger_entries": [
      {
        "date": "2024-01-15",
        "type": "billing",
        "refno": "REF001",
        "description": "Billing - January 2024",
        "debit": 1500.00,
        "credit": 0,
        "balance": 1500.00,
        "reading": "1000",
        "previous_reading": "950",
        "consumed": "50",
        "unit_price": "30.00",
        "penalty": 0,
        "due_date": "2024-02-15"
      },
      {
        "date": "2024-01-20",
        "type": "payment",
        "refno": "OR12345",
        "description": "Payment - OR# OR12345",
        "debit": 0,
        "credit": 1500.00,
        "balance": 0.00
      }
    ],
    "current_balance": 0.00,
    "entry_count": 2,
    "generated_at": "2024-01-15 10:30:00"
  }
}
```

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/get/12345"
```

**JavaScript Example**:
```javascript
fetch('/master/statementofaccount_api/get/12345')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

### 2. Get Customer Information

Retrieves only the customer information without ledger entries.

**Endpoint**: `GET /master/statementofaccount_api/customer/{customer_id}`

**Alternative**: `GET /master/statementofaccount_api/customer?customer_id={customer_id}`

**Parameters**:
- `customer_id` (required) - Customer ID

**Response**:
```json
{
  "success": true,
  "message": "Customer information retrieved successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "customer_id": "12345",
    "customer_name": "John Doe",
    "address": "123 Main St",
    "zone": "Zone 1",
    "class_name": "Residential",
    "cust_type_name": "Regular",
    /* ... other customer fields ... */
  }
}
```

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/customer/12345"
```

---

### 3. Get Ledger Entries

Retrieves only the ledger entries (billings and payments) for a customer.

**Endpoint**: `GET /master/statementofaccount_api/ledger/{customer_id}`

**Alternative**: `GET /master/statementofaccount_api/ledger?customer_id={customer_id}`

**Parameters**:
- `customer_id` (required) - Customer ID

**Response**:
```json
{
  "success": true,
  "message": "Ledger entries retrieved successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "ledger_entries": [
      {
        "date": "2024-01-15",
        "type": "billing",
        "refno": "REF001",
        "description": "Billing - January 2024",
        "debit": 1500.00,
        "credit": 0,
        "balance": 1500.00,
        "reading": "1000",
        "previous_reading": "950",
        "consumed": "50",
        "unit_price": "30.00",
        "penalty": 0,
        "due_date": "2024-02-15"
      },
      {
        "date": "2024-01-20",
        "type": "payment",
        "refno": "OR12345",
        "description": "Payment - OR# OR12345",
        "debit": 0,
        "credit": 1500.00,
        "balance": 0.00
      }
    ],
    "entry_count": 2,
    "generated_at": "2024-01-15 10:30:00"
  }
}
```

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/ledger/12345"
```

---

### 4. Get Current Billing Balance

Retrieves only the current billing balance for a customer. The current billing balance is calculated as **Total Debit - Total Credit** from all ledger entries.

**Endpoint**: `GET /master/statementofaccount_api/balance/{customer_id}`

**Alternative**: `GET /master/statementofaccount_api/balance?customer_id={customer_id}`

**Parameters**:
- `customer_id` (required) - Customer ID

**Response**:
```json
{
  "success": true,
  "message": "Current balance retrieved successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "customer_id": "12345",
    "current_balance": 1500.00,
    "balance_formatted": "1,500.00",
    "generated_at": "2024-01-15 10:30:00"
  }
}
```

**Note**: The `current_balance` field represents the **Current Billing Balance**, which is calculated as the sum of all debit amounts minus the sum of all credit amounts from the customer's ledger entries.

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/balance/12345"
```

---

### 5. Search Customer

Searches for a customer by ID and returns minimal information if found.

**Endpoint**: `GET /master/statementofaccount_api/search?customer_id={customer_id}`

**Alternative**: `POST /master/statementofaccount_api/search` (with `customer_id` in request body)

**Parameters**:
- `customer_id` (required) - Customer ID (can be passed as query parameter or POST data)

**Response (Customer Found)**:
```json
{
  "success": true,
  "message": "Customer found",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "exists": true,
    "customer_id": "12345",
    "customer_name": "John Doe",
    "zone": "Zone 1",
    "address": "123 Main St"
  }
}
```

**Response (Customer Not Found)**:
```json
{
  "success": false,
  "message": "Customer not found",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "exists": false
  }
}
```

**cURL Example (GET)**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/search?customer_id=12345"
```

**cURL Example (POST)**:
```bash
curl -X POST "http://yourdomain.com/master/statementofaccount_api/search" \
  -d "customer_id=12345"
```

---

### 6. Reset Password

Resets the customer's login password. This endpoint allows customers to change their password through the API.

**Endpoint**: `POST /master/statementofaccount_api/reset_password`

**Method**: POST only

**Parameters** (POST data):
- `customer_id` (required, string) - Customer ID
- `password` (required, string) - New password (minimum 3 characters)
- `current_password` (optional, string) - Current password for verification (if provided, will be validated before reset)

**Request Body Example**:
```json
{
  "customer_id": "12345",
  "password": "newpassword123"
}
```

**Request Body with Current Password Verification**:
```json
{
  "customer_id": "12345",
  "current_password": "oldpassword",
  "password": "newpassword123"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Password reset successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "customer_id": "12345",
    "password_reset_at": "2024-01-15 10:30:00",
    "message": "Password reset successfully"
  }
}
```

**Response (Error - Missing Parameters)**:
```json
{
  "success": false,
  "message": "Customer ID is required",
  "timestamp": "2024-01-15 10:30:00"
}
```

**Response (Error - Invalid Password)**:
```json
{
  "success": false,
  "message": "Password must be at least 3 characters long",
  "timestamp": "2024-01-15 10:30:00"
}
```

**Response (Error - Customer Not Found)**:
```json
{
  "success": false,
  "message": "Customer not found",
  "timestamp": "2024-01-15 10:30:00"
}
```

**Response (Error - Incorrect Current Password)**:
```json
{
  "success": false,
  "message": "Current password is incorrect",
  "timestamp": "2024-01-15 10:30:00"
}
```

**cURL Example**:
```bash
curl -X POST "http://yourdomain.com/master/statementofaccount_api/reset_password" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": "12345",
    "password": "newpassword123"
  }'
```

**cURL Example (with Current Password Verification)**:
```bash
curl -X POST "http://yourdomain.com/master/statementofaccount_api/reset_password" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": "12345",
    "current_password": "oldpassword",
    "password": "newpassword123"
  }'
```

**JavaScript Example**:
```javascript
// Reset password without current password verification
fetch('/master/statementofaccount_api/reset_password', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    customer_id: '12345',
    password: 'newpassword123'
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Password reset successfully');
    console.log('Reset at:', data.data.password_reset_at);
  } else {
    console.error('Error:', data.message);
  }
});

// Reset password with current password verification
async function resetPasswordWithVerification(customerId, currentPassword, newPassword) {
  try {
    const response = await fetch('/master/statementofaccount_api/reset_password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        customer_id: customerId,
        current_password: currentPassword,
        password: newPassword
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      return { success: true, message: data.message };
    } else {
      return { success: false, message: data.message };
    }
  } catch (error) {
    return { success: false, message: 'Network error: ' + error.message };
  }
}

// Usage
resetPasswordWithVerification('12345', 'oldpassword', 'newpassword123')
  .then(result => {
    if (result.success) {
      console.log('Password reset successful');
    } else {
      console.error('Password reset failed:', result.message);
    }
  });
```

**PHP Example**:
```php
<?php
function resetCustomerPassword($customerId, $newPassword, $currentPassword = null) {
    $url = "http://yourdomain.com/master/statementofaccount_api/reset_password";
    
    $data = array(
        'customer_id' => $customerId,
        'password' => $newPassword
    );
    
    if ($currentPassword !== null) {
        $data['current_password'] = $currentPassword;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($result['success']) {
        return array('success' => true, 'message' => $result['message']);
    } else {
        return array('success' => false, 'message' => $result['message']);
    }
}

// Usage
$result = resetCustomerPassword('12345', 'newpassword123');
if ($result['success']) {
    echo "Password reset successfully\n";
} else {
    echo "Error: " . $result['message'] . "\n";
}

// With current password verification
$result = resetCustomerPassword('12345', 'newpassword123', 'oldpassword');
?>
```

**Python Example**:
```python
import requests
import json

def reset_customer_password(customer_id, new_password, current_password=None):
    url = "http://yourdomain.com/master/statementofaccount_api/reset_password"
    
    data = {
        'customer_id': customer_id,
        'password': new_password
    }
    
    if current_password:
        data['current_password'] = current_password
    
    response = requests.post(
        url,
        json=data,
        headers={'Content-Type': 'application/json'}
    )
    
    result = response.json()
    
    if result['success']:
        return {'success': True, 'message': result['message']}
    else:
        return {'success': False, 'message': result['message']}

# Usage
result = reset_customer_password('12345', 'newpassword123')
if result['success']:
    print("Password reset successfully")
else:
    print(f"Error: {result['message']}")

# With current password verification
result = reset_customer_password('12345', 'newpassword123', 'oldpassword')
```

**Security Notes**:
- Passwords are encrypted using MD5 (same as the login system)
- Minimum password length is 3 characters
- If `current_password` is provided, it will be verified before allowing the reset
- The password reset is immediate and does not require email verification
- Consider implementing additional security measures (rate limiting, CAPTCHA) for production use

---

### 7. API Information

Returns API documentation and endpoint information.

**Endpoint**: `GET /master/statementofaccount_api/` or `GET /master/statementofaccount_api/info`

**Response**:
```json
{
  "success": true,
  "message": "API Information",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "api_name": "Statement of Account API",
    "version": "1.0.0",
    "description": "RESTful API for retrieving customer statement of account information",
    "endpoints": {
      "GET /master/statementofaccount_api/get/{customer_id}": "Get complete statement of account",
      "GET /master/statementofaccount_api/customer/{customer_id}": "Get customer information only",
      "GET /master/statementofaccount_api/ledger/{customer_id}": "Get ledger entries only",
      "GET /master/statementofaccount_api/balance/{customer_id}": "Get current balance only",
      "GET /master/statementofaccount_api/search?customer_id={customer_id}": "Search customer by ID",
      "POST /master/statementofaccount_api/reset_password": "Reset customer password",
      "GET /master/statementofaccount_api/": "API information (this endpoint)"
    }
  }
}
```

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/statementofaccount_api/"
```

---

## Response Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success - Request completed successfully |
| 400 | Bad Request - Missing or invalid parameters |
| 401 | Unauthorized - Incorrect current password (for reset_password endpoint) |
| 404 | Not Found - Customer not found |
| 405 | Method Not Allowed - Wrong HTTP method used |
| 500 | Internal Server Error - Server error occurred |

---

## Data Models

### Customer Information Object

```json
{
  "customer_id": "string",
  "customer_name": "string",
  "address": "string",
  "zone": "string",
  "class_name": "string",
  "cust_type_name": "string",
  /* Additional fields from tbl_addcustomer table */
}
```

### Ledger Entry Object

```json
{
  "date": "YYYY-MM-DD",
  "type": "billing|payment",
  "refno": "string",
  "description": "string",
  "debit": "number",
  "credit": "number",
  "balance": "number",
  "reading": "string (for billing entries)",
  "previous_reading": "string (for billing entries)",
  "consumed": "string (for billing entries)",
  "unit_price": "string (for billing entries)",
  "penalty": "number (for billing entries)",
  "penalty_included": "boolean (for billing entries)",
  "due_date": "YYYY-MM-DD (for billing entries)"
}
```

**Ledger Entry Types**:
- `billing` - Water billing/reading entry (debit)
- `payment` - Payment entry (credit)

**Important Notes**:

1. **Billing Entry Debit Amount**:
   - If a payment exists for the billing period AND the payment date is after the due date, the `debit` field will contain the `penalty` amount from `tbl_addcustomer_reading`.
   - Otherwise, the `debit` field will contain the `amount` from `tbl_addcustomer_reading`.
   - The `penalty_included` field indicates whether the penalty amount is being used.

2. **Payment Entry Description**:
   - Payment descriptions include billing period information in the format: `Payment - OR# {or_number} (Billing Period: {month_name} {year})`
   - For payments covering multiple billing periods, all periods are listed.

3. **Running Balance**:
   - The `balance` field represents the running balance calculated chronologically (oldest to newest).
   - Entries are sorted by date descending (newest first) for display, but balances are calculated from oldest to newest.

---

## Examples

### Complete Integration Example (JavaScript)

```javascript
// Get complete statement of account
async function getStatementOfAccount(customerId) {
  try {
    const response = await fetch(`/master/statementofaccount_api/get/${customerId}`);
    const result = await response.json();
    
    if (result.success) {
      console.log('Customer:', result.data.customer_info);
      console.log('Current Balance:', result.data.current_balance);
      console.log('Ledger Entries:', result.data.ledger_entries);
      return result.data;
    } else {
      console.error('Error:', result.message);
      return null;
    }
  } catch (error) {
    console.error('Network error:', error);
    return null;
  }
}

// Get current balance only
async function getCurrentBalance(customerId) {
  try {
    const response = await fetch(`/master/statementofaccount_api/balance/${customerId}`);
    const result = await response.json();
    
    if (result.success) {
      return result.data.current_balance;
    }
    return null;
  } catch (error) {
    console.error('Error:', error);
    return null;
  }
}

// Search customer
async function searchCustomer(customerId) {
  try {
    const response = await fetch(`/master/statementofaccount_api/search?customer_id=${customerId}`);
    const result = await response.json();
    
    if (result.success && result.data.exists) {
      return result.data;
    }
    return null;
  } catch (error) {
    console.error('Error:', error);
    return null;
  }
}

// Usage
getStatementOfAccount('12345');
getCurrentBalance('12345');
searchCustomer('12345');
```

### PHP Example

```php
<?php
// Get statement of account
function getStatementOfAccount($customerId) {
    $url = "http://yourdomain.com/master/statementofaccount_api/get/{$customerId}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data['success']) {
        return $data['data'];
    }
    return null;
}

// Get current balance
function getCurrentBalance($customerId) {
    $url = "http://yourdomain.com/master/statementofaccount_api/balance/{$customerId}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data['success']) {
        return $data['data']['current_balance'];
    }
    return null;
}

// Usage
$statement = getStatementOfAccount('12345');
$balance = getCurrentBalance('12345');
?>
```

### Python Example

```python
import requests
import json

def get_statement_of_account(customer_id):
    url = f"http://yourdomain.com/master/statementofaccount_api/get/{customer_id}"
    response = requests.get(url)
    data = response.json()
    
    if data['success']:
        return data['data']
    return None

def get_current_balance(customer_id):
    url = f"http://yourdomain.com/master/statementofaccount_api/balance/{customer_id}"
    response = requests.get(url)
    data = response.json()
    
    if data['success']:
        return data['data']['current_balance']
    return None

# Usage
statement = get_statement_of_account('12345')
balance = get_current_balance('12345')
```

---

## Error Handling

All API endpoints return standardized error responses:

### Missing Parameter Error (400)

```json
{
  "success": false,
  "message": "Customer ID is required",
  "timestamp": "2024-01-15 10:30:00",
  "data": null
}
```

### Customer Not Found Error (404)

```json
{
  "success": false,
  "message": "Customer not found",
  "timestamp": "2024-01-15 10:30:00",
  "data": null
}
```

### Best Practices

1. **Always check the `success` field** before processing data
2. **Handle HTTP status codes** appropriately
3. **Implement retry logic** for network errors
4. **Cache responses** when appropriate to reduce API calls
5. **Validate customer_id** before making API calls

---

## Rate Limiting

Currently, there are no rate limits implemented. Consider implementing rate limiting for production use to prevent abuse.

---

## Versioning

Current API Version: **1.0.0**

Future versions may be accessed via URL versioning:
- `/master/statementofaccount_api/v1/get/{customer_id}`
- `/master/statementofaccount_api/v2/get/{customer_id}`

---

## Support

For issues, questions, or feature requests, please contact the development team.

---

## Changelog

### Version 1.2.0 (2026-01-17)
- **Updated Current Billing Balance Calculation**: Changed from running balance to Total Debit - Total Credit
- **Enhanced Penalty Logic**: Billing entries now use penalty amount if payment is made after due date, otherwise use original amount
- **Improved Balance Calculation**: Running balance is now calculated chronologically (oldest to newest) for accuracy
- **Payment Description Enhancement**: Payment entries now include billing period information
- **Entry Sorting**: Payments appear before billings when entries have the same date
- **API Response Updates**: Added `penalty_included` field to billing entries

### Version 1.1.0 (2026-01-17)
- Added reset password endpoint
- Password reset with optional current password verification
- Enhanced security for password operations

### Version 1.0.0 (2024-01-15)
- Initial release
- Complete statement of account endpoint
- Customer information endpoint
- Ledger entries endpoint
- Current billing balance endpoint
- Customer search endpoint
- API information endpoint

---

## License

This API is part of the Water Billing System. All rights reserved.

