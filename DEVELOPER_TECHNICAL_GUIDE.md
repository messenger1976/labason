# Water Billing System - Developer Technical Guide

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Code Structure](#code-structure)
3. [Database Schema](#database-schema)
4. [API Reference](#api-reference)
5. [Customization Guide](#customization-guide)
6. [Extension Points](#extension-points)
7. [Best Practices](#best-practices)

---

## Architecture Overview

### Technology Stack

- **Backend Framework**: CodeIgniter 3.x
- **Database**: MySQL 5.6+ / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **PDF Generation**: TCPDF
- **QR Code**: ciqrcode library
- **UI Framework**: Custom (Bootstrap-based)

### MVC Pattern Implementation

```
Request → Router → Controller → Model → Database
                ↓
              View ← Data
```

### Request Flow

1. User makes request via URL
2. CodeIgniter Router processes URL
3. Controller method is called
4. Controller loads Model(s)
5. Model queries Database
6. Controller loads View with data
7. View renders HTML response

---

## Code Structure

### Controller Structure

**Location**: `application/modules/master/controllers/`

**Standard Controller Pattern**:
```php
<?php
class ControllerName extends CI_Controller {
    // Page variables
    public $headerPage = '../../views/admin-includes/header';
    public $listPage = 'view_name';
    public $addPage = 'add_view';
    
    // Table name
    public $table_name = 'tbl_tablename';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('model_name', 'my_model');
        $this->load->library('form_validation');
        // ... other initializations
    }
    
    public function index() {
        // List view
    }
    
    public function add() {
        // Add functionality
    }
    
    public function edit($id) {
        // Edit functionality
    }
    
    public function delete($id) {
        // Delete functionality
    }
}
```

### Model Structure

**Location**: `application/modules/master/models/`

**Standard Model Pattern**:
```php
<?php
class ModelName_model extends CI_Model {
    public $table_name = 'tbl_tablename';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_all_records() {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_single_record($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row_array();
    }
    
    public function add_record($data) {
        return $this->db->insert($this->table_name, $data);
    }
    
    public function update_record($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }
    
    public function delete_record($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }
}
```

### View Structure

**Location**: `application/modules/master/views/`

**Standard View Pattern**:
- List view: `module_name.php`
- Add view: `module_name_add.php`
- Edit view: `module_name_edit.php`
- Search view: `module_name_search.php`
- AJAX view: `module_name_search _ajax.php`

---

## Database Schema

### Key Tables

#### Customer Management

**tbl_addcustomer**
```sql
- customer_id (PK)
- first_name
- last_name
- middle_name
- address
- meter_number
- zone (FK → tbl_zone)
- classification (FK → tbl_classification)
- customer_type (meter/monthly)
- status
- created_date
- updated_date
```

**tbl_addcustomer_reading**
```sql
- refno (PK)
- customer_id (FK)
- bp_id (FK → tbl_billing_period)
- month
- year
- reading
- previous_reading
- consumed
- amount
- penalty
- maintenance_fee
- customer_status
- update_date_time
```

**tbl_addmetercustomer**
```sql
- id (PK)
- customer_id (FK)
- month
- year
- invoice_id
- date
- amount
- userid
```

#### Billing

**tbl_billing_period**
```sql
- bp_id (PK)
- bp_period_month
- bp_period_year
- bp_zone_id
- bp_start_date
- bp_end_date
- bp_due_date
- bp_status
```

**tbl_feesplaning**
```sql
- id (PK)
- classification (FK)
- rate_per_unit
- maintenance_fee
- status
```

#### Zone & Classification

**tbl_zone**
```sql
- id (PK)
- zone
- status
```

**tbl_classification**
```sql
- class_id (PK)
- class_name
- class_cat_id (FK)
```

**tbl_classification_category**
```sql
- class_cat_id (PK)
- class_cat_name
```

#### Employee & User

**tbl_addemployee**
```sql
- id (PK)
- employee_name
- job_title_id (FK)
- status
```

**tbl_responsibilities_user**
```sql
- id (PK)
- username
- password
- employee_name
- usertype (superadmin/subadmin)
- status
```

**tbl_responsibilities**
```sql
- id (PK)
- role_name
- module_permissions (JSON or separate columns)
```

### Relationships

```
tbl_addcustomer (1) ──→ (N) tbl_addcustomer_reading
tbl_addcustomer (N) ──→ (1) tbl_zone
tbl_addcustomer (N) ──→ (1) tbl_classification
tbl_addcustomer_reading (N) ──→ (1) tbl_billing_period
tbl_addcustomer_reading (1) ──→ (0..1) tbl_addmetercustomer
tbl_classification (N) ──→ (1) tbl_classification_category
```

---

## API Reference

### URL Routing

**Pattern**: `/index.php/module/controller/method/parameters`

**Examples**:
```
GET  /master/addcustomer              → addcustomer::index()
GET  /master/addcustomer/add          → addcustomer::add()
POST /master/addcustomer/add          → addcustomer::add() [POST]
GET  /master/addcustomer/edit/123     → addcustomer::edit(123)
POST /master/addcustomer/edit/123     → addcustomer::edit(123) [POST]
GET  /master/addcustomer/delete/123   → addcustomer::delete(123)
```

### Common Controller Methods

#### List Records
```php
public function index() {
    $data['record'] = $this->my_model->get_all_records();
    $this->load->view($this->headerPage, $header);
    $this->load->view($this->listPage, $data);
}
```

#### Add Record
```php
public function add() {
    if($this->input->post('add')) {
        // Validation
        $result = $this->my_model->add_record($data);
        if($result) {
            redirect($this->listPage_redirect);
        }
    }
    $this->load->view($this->headerPage, $header);
    $this->load->view($this->addPage, $data);
}
```

#### Edit Record
```php
public function edit($id) {
    if($this->input->post('edit')) {
        $result = $this->my_model->update_record($id, $data);
        if($result) {
            redirect($this->listPage_redirect);
        }
    }
    $data['record'] = $this->my_model->get_single_record($id);
    $this->load->view($this->headerPage, $header);
    $this->load->view($this->editPage, $data);
}
```

#### Delete Record
```php
public function delete($id) {
    $result = $this->my_model->delete_record($id);
    if($result) {
        redirect($this->listPage_redirect);
    }
}
```

### AJAX Endpoints

**Pattern**: Controller methods that return JSON or HTML fragments

**Example**:
```php
public function search_ajax() {
    $zone = $this->input->post('zone');
    $status = $this->input->post('status');
    $data['record'] = $this->my_model->search_records($zone, $status);
    $this->load->view('module_search_ajax', $data);
}
```

### Common Helper Functions

**Location**: `application/helpers/common_helper.php`

**Functions**:
- `getMonthName($month_id)` - Get month name
- `formatCurrency($amount)` - Format currency
- `formatDate($date)` - Format date
- Other utility functions

---

## Customization Guide

### Adding a New Module

1. **Create Controller**
   - File: `application/modules/master/controllers/newmodule.php`
   - Extend `CI_Controller`
   - Implement standard CRUD methods

2. **Create Model**
   - File: `application/modules/master/models/newmodule_model.php`
   - Extend `CI_Model`
   - Implement data access methods

3. **Create Views**
   - List: `application/modules/master/views/newmodule.php`
   - Add: `application/modules/master/views/newmodule_add.php`
   - Edit: `application/modules/master/views/newmodule_edit.php`

4. **Add to Permissions**
   - Update `adminheader_model.php` module list
   - Update `responsibilities_model.php` module list

5. **Create Database Table**
   - Design table schema
   - Create migration or SQL script

### Modifying Existing Modules

1. **Backup First**: Always backup before modifications
2. **Update Controller**: Modify controller methods
3. **Update Model**: Modify data access logic
4. **Update Views**: Modify presentation layer
5. **Test Thoroughly**: Test all affected functionality

### Adding Custom Fields

**Example: Adding email field to customer**

1. **Database**:
   ```sql
   ALTER TABLE tbl_addcustomer ADD COLUMN email VARCHAR(255);
   ```

2. **Model**: No changes needed (uses generic methods)

3. **Controller**: Add to form validation
   ```php
   $this->form_validation->set_rules('email', 'Email', 'valid_email');
   ```

4. **View**: Add input field
   ```html
   <input type="email" name="email" value="<?php echo $record['email']; ?>">
   ```

### Customizing Reports

1. **Locate Report Controller**: `application/modules/master/controllers/reports.php`
2. **Modify Query**: Update model method
3. **Modify View**: Update report view template
4. **Test**: Generate report and verify output

### Adding PDF Templates

1. **Create View**: `application/modules/master/views/custom_pdf.php`
2. **Controller Method**:
   ```php
   public function generate_pdf() {
       $this->load->library('Pdf');
       $data = $this->my_model->get_data();
       $html = $this->load->view('custom_pdf', $data, true);
       $this->pdf->generate($html, 'filename.pdf');
   }
   ```

---

## Extension Points

### Hooks

**Location**: `application/config/hooks.php`

**Available Hooks**:
- `pre_system`
- `pre_controller`
- `post_controller_constructor`
- `post_controller`
- `display_override`
- `cache_override`
- `post_system`

### Libraries

**Custom Library Location**: `application/libraries/`

**Example**:
```php
// application/libraries/Custom_library.php
class Custom_library {
    public function custom_method() {
        // Implementation
    }
}

// Usage in controller
$this->load->library('custom_library');
$this->custom_library->custom_method();
```

### Helpers

**Custom Helper Location**: `application/helpers/`

**Example**:
```php
// application/helpers/custom_helper.php
function custom_function($param) {
    // Implementation
    return $result;
}

// Usage
$this->load->helper('custom');
$result = custom_function($param);
```

### Third-Party Libraries

**Location**: `application/third_party/`

**Usage**:
```php
$this->load->library('third_party/library_name');
```

---

## Best Practices

### Security

1. **Input Validation**: Always validate user input
   ```php
   $this->form_validation->set_rules('field', 'Label', 'required|trim');
   ```

2. **SQL Injection Prevention**: Use Query Builder
   ```php
   // Good
   $this->db->where('id', $id);
   
   // Bad
   $this->db->query("SELECT * FROM table WHERE id = $id");
   ```

3. **XSS Prevention**: Escape output
   ```php
   echo html_escape($user_input);
   ```

4. **CSRF Protection**: Enable in config
   ```php
   $config['csrf_protection'] = TRUE;
   ```

5. **Password Hashing**: Use PHP password_hash()
   ```php
   $hashed = password_hash($password, PASSWORD_DEFAULT);
   ```

### Code Organization

1. **Follow MVC**: Separate concerns
2. **DRY Principle**: Don't Repeat Yourself
3. **Naming Conventions**: Consistent naming
4. **Comments**: Document complex logic
5. **Error Handling**: Proper error handling

### Database

1. **Use Transactions**: For multiple operations
   ```php
   $this->db->trans_start();
   // Operations
   $this->db->trans_complete();
   ```

2. **Indexes**: Add indexes for frequently queried columns
3. **Foreign Keys**: Use foreign keys for data integrity
4. **Backup**: Regular backups

### Performance

1. **Query Optimization**: Optimize database queries
2. **Caching**: Use caching where appropriate
3. **Lazy Loading**: Load data only when needed
4. **Pagination**: Use pagination for large datasets

### Testing

1. **Unit Tests**: Test individual functions
2. **Integration Tests**: Test module interactions
3. **User Acceptance**: Test with real users
4. **Performance Tests**: Test under load

### Version Control

1. **Git**: Use version control
2. **Branches**: Use branches for features
3. **Commits**: Meaningful commit messages
4. **Tags**: Tag releases

---

## Common Patterns

### Search Functionality

```php
public function search() {
    $zone = $this->input->post('zone');
    $status = $this->input->post('status');
    
    $data['record'] = $this->my_model->search_records($zone, $status);
    $this->load->view('search_view', $data);
}
```

### Pagination

```php
$config['base_url'] = base_url('master/module/index');
$config['total_rows'] = $this->my_model->record_count();
$config['per_page'] = 20;
$this->pagination->initialize($config);

$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$data['record'] = $this->my_model->get_records($config['per_page'], $page);
```

### File Upload

```php
$config['upload_path'] = './images/upload';
$config['allowed_types'] = 'gif|jpg|png';
$config['max_size'] = 2048;
$this->load->library('upload', $config);

if($this->upload->do_upload('file')) {
    $data = $this->upload->data();
}
```

### Permission Check

```php
if($this->session->userdata('usertype') == 'subadmin') {
    $roleResponsible = $this->top_model->get_responsibilities();
    if(array_key_exists('module', $roleResponsible)) {
        $this->top_model->get_responsibilities_conditions($roleResponsible['module']);
    }
}
```

---

## Troubleshooting for Developers

### Debug Mode

Enable in `index.php`:
```php
define('ENVIRONMENT', 'development');
```

### Database Queries

View last query:
```php
echo $this->db->last_query();
```

### Error Logging

```php
log_message('error', 'Error message');
log_message('debug', 'Debug message');
log_message('info', 'Info message');
```

### Session Debug

```php
print_r($this->session->all_userdata());
```

---

## Resources

- **CodeIgniter Documentation**: https://codeigniter.com/user_guide/
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **TCPDF Documentation**: http://www.tcpdf.org/
- **jQuery Documentation**: https://api.jquery.com/

---

**Developer Guide v1.0**

