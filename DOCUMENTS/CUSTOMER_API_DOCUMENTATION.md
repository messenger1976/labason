# Customer Information API Documentation

## Overview

The Customer Information API provides RESTful endpoints for retrieving customer information from the `tbl_addcustomer` table. This API is designed for integration with mobile applications, third-party systems, and automated reporting tools.

**Base URL**: `/master/customer_api`

**Response Format**: All responses are returned in JSON format with the following structure:

```json
{
  "success": true,
  "message": "Operation message",
  "timestamp": "2024-01-15 10:30:00",
  "data": { /* Customer data */ }
}
```

---

## Table of Contents

1. [Authentication](#authentication)
2. [Endpoints](#endpoints)
   - [Get Customer Information](#1-get-customer-information)
   - [API Information](#2-api-information)
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

### 1. Get Customer Information

Retrieves complete customer information from the `tbl_addcustomer` table based on the customer ID.

**Endpoint**: `GET /master/customer_api/{customer_id}`

**Alternative Endpoints**:
- `GET /master/customer_api/customer/{customer_id}`
- `GET /master/customer_api?customer_id={customer_id}`

**Parameters**:
- `customer_id` (required) - Customer ID from the `tbl_addcustomer` table

**Response (Success)**:
```json
{
  "success": true,
  "message": "Customer information retrieved successfully",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "id": 1,
    "customer_id": "12345",
    "first_name": "John",
    "middle_name": "M",
    "last_name": "Doe",
    "gender": "Male",
    "DOB": "1990-01-15",
    "place_of_birth": "Manila",
    "city": "Manila",
    "state": "Metro Manila",
    "address": "123 Main Street",
    "mobile1": "09123456789",
    "mobile2": "09234567890",
    "line_number": "123456",
    "email_id": "john.doe@example.com",
    "customer_type": "metercustomer",
    "zone": 1,
    "zones": "Zone 1",
    "billingplans": 1,
    "billingplans_name": "Standard Plan",
    "referenceperson": "Jane Doe",
    "meter_number": "MTR001",
    "meter_brand": "Brand A",
    "meter_size": "1/2",
    "date_installed": "2020-01-15",
    "status": "1",
    "classification": 1,
    "account_type": 1,
    "membership_status": "Active",
    "special_priviledge": "0",
    "account_id": 1,
    "subName": "Account Name",
    "create_date": "2020-01-15",
    "create_date_time": "2020-01-15 10:00:00",
    "update_date_time": "2024-01-15 09:00:00"
  }
}
```

**Response (Customer Not Found)**:
```json
{
  "success": false,
  "message": "Customer not found",
  "timestamp": "2024-01-15 10:30:00",
  "data": null
}
```

**Response (Missing Parameter)**:
```json
{
  "success": false,
  "message": "Customer ID is required",
  "timestamp": "2024-01-15 10:30:00",
  "data": null
}
```

**cURL Examples**:
```bash
# Using URL path parameter
curl -X GET "http://yourdomain.com/master/customer_api/12345"

# Using alternative endpoint
curl -X GET "http://yourdomain.com/master/customer_api/customer/12345"

# Using query parameter
curl -X GET "http://yourdomain.com/master/customer_api?customer_id=12345"
```

**JavaScript Example**:
```javascript
// Using fetch API
fetch('/master/customer_api/12345')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Customer Info:', data.data);
      console.log('Customer Name:', data.data.first_name + ' ' + data.data.last_name);
      console.log('Address:', data.data.address);
      console.log('Zone:', data.data.zones);
    } else {
      console.error('Error:', data.message);
    }
  })
  .catch(error => console.error('Network error:', error));

// Using async/await
async function getCustomerInfo(customerId) {
  try {
    const response = await fetch(`/master/customer_api/${customerId}`);
    const result = await response.json();
    
    if (result.success) {
      return result.data;
    } else {
      throw new Error(result.message);
    }
  } catch (error) {
    console.error('Error fetching customer info:', error);
    return null;
  }
}

// Usage
const customerInfo = await getCustomerInfo('12345');
if (customerInfo) {
  console.log('Customer:', customerInfo);
}
```

**PHP Example**:
```php
<?php
function getCustomerInfo($customerId) {
    $url = "http://yourdomain.com/master/customer_api/{$customerId}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($result['success']) {
        return $result['data'];
    } else {
        return null;
    }
}

// Usage
$customerInfo = getCustomerInfo('12345');
if ($customerInfo) {
    echo "Customer Name: " . $customerInfo['first_name'] . " " . $customerInfo['last_name'] . "\n";
    echo "Address: " . $customerInfo['address'] . "\n";
    echo "Zone: " . $customerInfo['zones'] . "\n";
}
?>
```

**Python Example**:
```python
import requests
import json

def get_customer_info(customer_id):
    url = f"http://yourdomain.com/master/customer_api/{customer_id}"
    
    try:
        response = requests.get(url)
        response.raise_for_status()
        
        result = response.json()
        
        if result['success']:
            return result['data']
        else:
            print(f"Error: {result['message']}")
            return None
    except requests.exceptions.RequestException as e:
        print(f"Network error: {e}")
        return None

# Usage
customer_info = get_customer_info('12345')
if customer_info:
    print(f"Customer Name: {customer_info['first_name']} {customer_info['last_name']}")
    print(f"Address: {customer_info['address']}")
    print(f"Zone: {customer_info['zones']}")
```

---

### 2. API Information

Returns API documentation and endpoint information.

**Endpoint**: `GET /master/customer_api/info`

**Response**:
```json
{
  "success": true,
  "message": "API Information",
  "timestamp": "2024-01-15 10:30:00",
  "data": {
    "api_name": "Customer Information API",
    "version": "1.0.0",
    "description": "RESTful API for retrieving customer information from tbl_addcustomer table",
    "endpoints": {
      "GET /master/customer_api/{customer_id}": "Get customer information by customer ID",
      "GET /master/customer_api/customer/{customer_id}": "Get customer information by customer ID (alternative)",
      "GET /master/customer_api?customer_id={customer_id}": "Get customer information by customer ID (query parameter)",
      "GET /master/customer_api/info": "API information (this endpoint)"
    },
    "response_format": {
      "success": "boolean - Indicates if request was successful",
      "message": "string - Human-readable message",
      "timestamp": "string - ISO 8601 timestamp",
      "data": "object - Customer information from tbl_addcustomer table"
    },
    "example_request": "/master/customer_api/12345",
    "example_response": {
      "success": true,
      "message": "Customer information retrieved successfully",
      "timestamp": "2024-01-15 10:30:00",
      "data": {
        "id": 1,
        "customer_id": "12345",
        "first_name": "John",
        "last_name": "Doe"
      }
    }
  }
}
```

**cURL Example**:
```bash
curl -X GET "http://yourdomain.com/master/customer_api/info"
```

---

## Response Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success - Request completed successfully |
| 400 | Bad Request - Missing or invalid parameters |
| 404 | Not Found - Customer not found |
| 500 | Internal Server Error - Server error occurred |

---

## Data Models

### Customer Information Object

The customer information object contains all fields from the `tbl_addcustomer` table, plus additional computed fields:

**Core Fields**:
- `id` (integer) - Primary key ID
- `customer_id` (string) - Customer ID (unique identifier)
- `first_name` (string) - First name
- `middle_name` (string) - Middle name
- `last_name` (string) - Last name
- `gender` (string) - Gender
- `DOB` (date) - Date of birth (YYYY-MM-DD)
- `place_of_birth` (string) - Place of birth
- `city` (string) - City
- `state` (string) - State/Province
- `address` (string) - Full address
- `mobile1` (string) - Primary mobile number
- `mobile2` (string) - Secondary mobile number
- `line_number` (string) - Line number
- `email_id` (string) - Email address
- `customer_type` (string) - Type of customer (e.g., "metercustomer", "monthlycustomer")
- `zone` (integer) - Zone ID
- `billingplans` (integer) - Billing plan ID
- `referenceperson` (string) - Reference person
- `meter_number` (string) - Meter number
- `meter_brand` (string) - Meter brand
- `meter_size` (string) - Meter size
- `date_installed` (date) - Date installed (YYYY-MM-DD)
- `status` (string) - Status (usually "1" for active, "0" for inactive)
- `classification` (integer) - Classification ID
- `account_type` (integer) - Account type ID
- `membership_status` (string) - Membership status
- `special_priviledge` (string) - Special privilege flag
- `account_id` (integer) - Account ID
- `create_date` (date) - Creation date (YYYY-MM-DD)
- `create_date_time` (datetime) - Creation datetime (YYYY-MM-DD HH:MM:SS)
- `update_date_time` (datetime) - Last update datetime (YYYY-MM-DD HH:MM:SS)

**Computed/Joined Fields**:
- `zones` (string) - Zone name (from `tbl_zone` table)
- `billingplans_name` (string) - Billing plan name (from `tbl_feesplaning` table)
- `subName` (string) - Account name (from `tbl_subaccountgroup` table)

**Note**: The `password` field (if exists) may be included in the response. Consider removing it for security purposes in production.

---

## Examples

### Complete Integration Example (JavaScript)

```javascript
// Customer API Service
class CustomerAPI {
  constructor(baseUrl = '/master/customer_api') {
    this.baseUrl = baseUrl;
  }
  
  async getCustomer(customerId) {
    try {
      const response = await fetch(`${this.baseUrl}/${customerId}`);
      const result = await response.json();
      
      if (result.success) {
        return {
          success: true,
          data: result.data
        };
      } else {
        return {
          success: false,
          error: result.message
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message
      };
    }
  }
  
  async getCustomerByQuery(customerId) {
    try {
      const response = await fetch(`${this.baseUrl}?customer_id=${customerId}`);
      const result = await response.json();
      
      if (result.success) {
        return {
          success: true,
          data: result.data
        };
      } else {
        return {
          success: false,
          error: result.message
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message
      };
    }
  }
}

// Usage
const customerAPI = new CustomerAPI();

// Get customer information
customerAPI.getCustomer('12345')
  .then(result => {
    if (result.success) {
      const customer = result.data;
      console.log('Customer Name:', `${customer.first_name} ${customer.last_name}`);
      console.log('Address:', customer.address);
      console.log('Zone:', customer.zones);
      console.log('Mobile:', customer.mobile1);
      console.log('Email:', customer.email_id);
    } else {
      console.error('Error:', result.error);
    }
  });
```

### Complete Integration Example (PHP)

```php
<?php
class CustomerAPI {
    private $baseUrl;
    
    public function __construct($baseUrl = 'http://yourdomain.com/master/customer_api') {
        $this->baseUrl = $baseUrl;
    }
    
    public function getCustomer($customerId) {
        $url = $this->baseUrl . '/' . urlencode($customerId);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return array(
                'success' => false,
                'error' => 'Network error: ' . $error
            );
        }
        
        $result = json_decode($response, true);
        
        if ($result && isset($result['success']) && $result['success']) {
            return array(
                'success' => true,
                'data' => $result['data']
            );
        } else {
            return array(
                'success' => false,
                'error' => isset($result['message']) ? $result['message'] : 'Unknown error'
            );
        }
    }
}

// Usage
$customerAPI = new CustomerAPI();
$result = $customerAPI->getCustomer('12345');

if ($result['success']) {
    $customer = $result['data'];
    echo "Customer Name: " . $customer['first_name'] . " " . $customer['last_name'] . "\n";
    echo "Address: " . $customer['address'] . "\n";
    echo "Zone: " . $customer['zones'] . "\n";
    echo "Mobile: " . $customer['mobile1'] . "\n";
    echo "Email: " . $customer['email_id'] . "\n";
} else {
    echo "Error: " . $result['error'] . "\n";
}
?>
```

### Complete Integration Example (Python)

```python
import requests
import json

class CustomerAPI:
    def __init__(self, base_url='http://yourdomain.com/master/customer_api'):
        self.base_url = base_url
    
    def get_customer(self, customer_id):
        url = f"{self.base_url}/{customer_id}"
        
        try:
            response = requests.get(url)
            response.raise_for_status()
            
            result = response.json()
            
            if result.get('success'):
                return {
                    'success': True,
                    'data': result['data']
                }
            else:
                return {
                    'success': False,
                    'error': result.get('message', 'Unknown error')
                }
        except requests.exceptions.RequestException as e:
            return {
                'success': False,
                'error': f'Network error: {str(e)}'
            }

# Usage
customer_api = CustomerAPI()
result = customer_api.get_customer('12345')

if result['success']:
    customer = result['data']
    print(f"Customer Name: {customer['first_name']} {customer['last_name']}")
    print(f"Address: {customer['address']}")
    print(f"Zone: {customer['zones']}")
    print(f"Mobile: {customer['mobile1']}")
    print(f"Email: {customer['email_id']}")
else:
    print(f"Error: {result['error']}")
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
6. **Handle null/empty values** in response data gracefully

### Error Handling Example (JavaScript)

```javascript
async function getCustomerWithErrorHandling(customerId) {
  try {
    const response = await fetch(`/master/customer_api/${customerId}`);
    
    // Check HTTP status
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await response.json();
    
    // Check API success flag
    if (!result.success) {
      throw new Error(result.message || 'Unknown error');
    }
    
    return result.data;
  } catch (error) {
    console.error('Error fetching customer:', error);
    
    // Handle specific error cases
    if (error.message.includes('404')) {
      console.error('Customer not found');
    } else if (error.message.includes('400')) {
      console.error('Invalid request');
    } else {
      console.error('Network or server error');
    }
    
    return null;
  }
}
```

---

## Rate Limiting

Currently, there are no rate limits implemented. Consider implementing rate limiting for production use to prevent abuse.

---

## Versioning

Current API Version: **1.0.0**

Future versions may be accessed via URL versioning:
- `/master/customer_api/v1/{customer_id}`
- `/master/customer_api/v2/{customer_id}`

---

## Security Considerations

1. **Password Field**: The API may return the `password` field if it exists in the database. Consider:
   - Removing the password field from responses
   - Implementing field filtering
   - Using separate endpoints for sensitive data

2. **Authentication**: Currently, the API does not require authentication. For production:
   - Implement API key authentication
   - Use OAuth2 or JWT tokens
   - Implement IP whitelisting if needed

3. **Input Validation**: Always validate and sanitize `customer_id` input:
   - Check for SQL injection attempts
   - Validate format/length
   - Use parameterized queries (handled by CodeIgniter)

4. **HTTPS**: Always use HTTPS in production to encrypt data in transit

---

## Support

For issues, questions, or feature requests, please contact the development team.

---

## Changelog

### Version 1.0.0 (2024-01-15)
- Initial release
- Customer information endpoint
- API information endpoint
- Support for multiple endpoint formats (path parameter, query parameter)

---

## License

This API is part of the Water Billing System. All rights reserved.
