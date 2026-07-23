# CSV Import Mapping for tbl_addcustomer

## CSV File Structure
The CSV file `labason-members-zone1.csv` has the following columns:

1. **last_name** - Contains full name in format "Last, First" or just "Last"
2. **customer_id** - Unique customer identifier (e.g., "11-7-12-00001")
3. **meter_number** - Water meter serial number
4. **meter_brand** - Brand of the meter (e.g., "Aqua Jet", "Ever", "E-Jet")
5. **date_installed** - Installation date in YYYY-MM-DD format
6. **zone** - Zone ID (integer, e.g., 12)

## Database Table: tbl_addcustomer

### CSV Column to Database Field Mapping

| CSV Column | Database Field | Type | Notes |
|------------|---------------|------|-------|
| last_name | first_name, last_name | VARCHAR | Parsed from "Last, First" format |
| customer_id | customer_id | VARCHAR | Direct mapping |
| meter_number | meter_number | VARCHAR | Direct mapping |
| meter_brand | meter_brand | VARCHAR | Direct mapping |
| date_installed | date_installed | DATE | Already in YYYY-MM-DD format |
| zone | zone | INT | Direct mapping (FK to tbl_zone) |

### Default Values (Not in CSV)

| Field | Default Value | Notes |
|-------|--------------|-------|
| account_id | 0 | Default account |
| middle_name | '' | Empty string |
| classification | 1 | Default classification ID |
| status | 1 | Active status |
| customer_type | 'metercustomer' | Default customer type |
| account_type | 1 | Default account type |
| billingplans | 1 | Default billing plan |
| create_date | Current date | Auto-generated |
| create_date_time | Current datetime | Auto-generated |

### Import Logic

1. **Name Parsing**: The `last_name` column is parsed to extract:
   - If format is "Last, First": 
     - `last_name` = "Last"
     - `first_name` = "First"
   - If no comma: 
     - `last_name` = entire string
     - `first_name` = empty string

2. **Date Handling**: 
   - CSV dates are already in YYYY-MM-DD format
   - No conversion needed, but validated

3. **Zone Validation**: 
   - Zone ID from CSV is validated against `tbl_zone` table
   - If zone doesn't exist, row is skipped with error

4. **Duplicate Handling**:
   - If `customer_id` already exists: **UPDATE** existing record
   - If `customer_id` doesn't exist: **INSERT** new record

5. **Required Fields**:
   - `customer_id` is required - rows without it are skipped
   - All other fields can be empty/null

## Import Script: import_customer_csv.php

The script:
- Reads CSV file from: `C:\Users\Admin\Documents\labason-members-zone1.csv`
- Connects to database: `labasonwd` on `localhost`
- Processes each row and imports/updates records
- Provides detailed statistics and error reporting

## Usage

Run the import script via command line:
```bash
C:\xampp3\php\php.exe import_customer_csv.php
```

Or access via web browser:
```
http://localhost/labason/import_customer_csv.php
```

