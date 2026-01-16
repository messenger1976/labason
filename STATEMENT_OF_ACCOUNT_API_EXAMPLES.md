# Statement of Account API - Usage Examples

Based on the page: `http://labason.com/master/statementofaccount/index/11-7-12-01262`

## API Endpoint

**Base URL:** `http://labason.com/master/statementofaccount_api`

**Customer ID:** `11-7-12-01262`

## Available Endpoints

### 1. Get Complete Statement of Account
Returns customer info, ledger entries, and current balance.

**URL:** 
- `GET /master/statementofaccount_api/get/11-7-12-01262`
- `GET /master/statementofaccount_api/get?customer_id=11-7-12-01262`

### 2. Get Customer Information Only
**URL:** 
- `GET /master/statementofaccount_api/customer/11-7-12-01262`
- `GET /master/statementofaccount_api/customer?customer_id=11-7-12-01262`

### 3. Get Ledger Entries Only
**URL:** 
- `GET /master/statementofaccount_api/ledger/11-7-12-01262`
- `GET /master/statementofaccount_api/ledger?customer_id=11-7-12-01262`

### 4. Get Current Balance Only
**URL:** 
- `GET /master/statementofaccount_api/balance/11-7-12-01262`
- `GET /master/statementofaccount_api/balance?customer_id=11-7-12-01262`

## Example API Calls

### cURL (Command Line)

```bash
# Get complete statement of account
curl -X GET "http://labason.com/master/statementofaccount_api/get/11-7-12-01262"

# Or with query parameter
curl -X GET "http://labason.com/master/statementofaccount_api/get?customer_id=11-7-12-01262"

# Get customer info only
curl -X GET "http://labason.com/master/statementofaccount_api/customer/11-7-12-01262"

# Get ledger entries only
curl -X GET "http://labason.com/master/statementofaccount_api/ledger/11-7-12-01262"

# Get current balance only
curl -X GET "http://labason.com/master/statementofaccount_api/balance/11-7-12-01262"
```

### JavaScript (Fetch API)

```javascript
// Get complete statement of account
fetch('http://labason.com/master/statementofaccount_api/get/11-7-12-01262')
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
    if (data.success) {
      console.log('Customer:', data.data.customer_info);
      console.log('Current Balance:', data.data.current_balance);
      console.log('Ledger Entries:', data.data.ledger_entries);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });

// Using async/await
async function getStatementOfAccount(customerId) {
  try {
    const response = await fetch(`http://labason.com/master/statementofaccount_api/get/${customerId}`);
    const data = await response.json();
    
    if (data.success) {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error fetching statement:', error);
    throw error;
  }
}

// Usage
getStatementOfAccount('11-7-12-01262')
  .then(statement => {
    console.log('Customer Info:', statement.customer_info);
    console.log('Current Balance:', statement.current_balance);
    console.log('Total Entries:', statement.entry_count);
  });
```

### PHP (cURL)

```php
<?php
// Get complete statement of account
$customer_id = '11-7-12-01262';
$url = "http://labason.com/master/statementofaccount_api/get/{$customer_id}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

if ($data['success']) {
    echo "Customer: " . $data['data']['customer_info']['customer_id'] . "\n";
    echo "Current Balance: PHP " . number_format($data['data']['current_balance'], 2) . "\n";
    echo "Total Entries: " . $data['data']['entry_count'] . "\n";
} else {
    echo "Error: " . $data['message'] . "\n";
}
?>
```

### PHP (file_get_contents)

```php
<?php
$customer_id = '11-7-12-01262';
$url = "http://labason.com/master/statementofaccount_api/get/{$customer_id}";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    ]
]);

$response = file_get_contents($url, false, $context);
$data = json_decode($response, true);

if ($data['success']) {
    $customer = $data['data']['customer_info'];
    $balance = $data['data']['current_balance'];
    $entries = $data['data']['ledger_entries'];
    
    echo "Customer ID: " . $customer['customer_id'] . "\n";
    echo "Name: " . strtoupper($customer['last_name'] . ', ' . $customer['first_name']) . "\n";
    echo "Current Balance: PHP " . number_format($balance, 2) . "\n";
    echo "Total Transactions: " . count($entries) . "\n";
}
?>
```

### jQuery (AJAX)

```javascript
// Get complete statement of account
$.ajax({
    url: 'http://labason.com/master/statementofaccount_api/get/11-7-12-01262',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
        if (data.success) {
            console.log('Customer Info:', data.data.customer_info);
            console.log('Current Balance:', data.data.current_balance);
            console.log('Ledger Entries:', data.data.ledger_entries);
            
            // Display customer info
            $('#customer-id').text(data.data.customer_info.customer_id);
            $('#customer-name').text(
                data.data.customer_info.last_name + ', ' + 
                data.data.customer_info.first_name
            );
            $('#current-balance').text('PHP ' + 
                parseFloat(data.data.current_balance).toFixed(2));
        } else {
            alert('Error: ' + data.message);
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
    }
});
```

### Python (requests)

```python
import requests
import json

# Get complete statement of account
customer_id = '11-7-12-01262'
url = f'http://labason.com/master/statementofaccount_api/get/{customer_id}'

response = requests.get(url)
data = response.json()

if data['success']:
    customer_info = data['data']['customer_info']
    current_balance = data['data']['current_balance']
    ledger_entries = data['data']['ledger_entries']
    
    print(f"Customer ID: {customer_info['customer_id']}")
    print(f"Name: {customer_info['last_name']}, {customer_info['first_name']}")
    print(f"Current Balance: PHP {current_balance:,.2f}")
    print(f"Total Entries: {len(ledger_entries)}")
    
    # Print first 5 ledger entries
    for entry in ledger_entries[:5]:
        print(f"\nDate: {entry['date']}")
        print(f"Type: {entry['type']}")
        print(f"Description: {entry['description']}")
        print(f"Debit: {entry['debit']}, Credit: {entry['credit']}")
        print(f"Balance: {entry['balance']}")
else:
    print(f"Error: {data['message']}")
```

## Response Format

### Success Response

```json
{
    "success": true,
    "message": "Statement of account retrieved successfully",
    "timestamp": "2026-01-17 10:30:00",
    "data": {
        "customer_info": {
            "customer_id": "11-7-12-01262",
            "first_name": "John",
            "last_name": "Doe",
            "middle_name": "M",
            "address": "123 Main Street",
            "meter_number": "12345",
            "zone": "Zone 1",
            "class_name": "Residential",
            "cust_type_name": "Regular"
        },
        "ledger_entries": [
            {
                "date": "2026-01-15",
                "type": "billing",
                "refno": "REF001",
                "description": "Billing - January 2026",
                "debit": 1500.00,
                "credit": 0,
                "balance": 1500.00,
                "reading": "100",
                "previous_reading": "90",
                "consumed": "10",
                "penalty": 0
            },
            {
                "date": "2026-01-10",
                "type": "payment",
                "refno": "OR-12345",
                "description": "Payment - OR# OR-12345",
                "debit": 0,
                "credit": 1000.00,
                "balance": 500.00
            }
        ],
        "current_balance": 500.00,
        "entry_count": 2,
        "generated_at": "2026-01-17 10:30:00"
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Customer not found",
    "timestamp": "2026-01-17 10:30:00"
}
```

## HTTP Status Codes

- `200` - Success
- `400` - Bad Request (missing customer_id)
- `404` - Customer not found

## Notes

- All responses are in JSON format
- Customer ID format: `11-7-12-01262` (zone-branch-customer format)
- Dates are in `Y-m-d` format
- Amounts are in PHP (Philippine Peso)
- Ledger entries are sorted by date (newest first)
- Running balance is calculated automatically
