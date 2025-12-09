<?php 
class mobilenotifications_model extends CI_Model {
	
	public $table_name = 'tbl_sms_notifications';
	public $table_settings = 'tbl_sms_settings';
	public $table_customer = 'tbl_addcustomer';
	public $table_customer_reading = 'tbl_addcustomer_reading';
	public $table_billing_period = 'tbl_billing_period';
	public $table_zone = 'tbl_zone';
	
	// Autoloading a system library using constructor method
	public function __construct() {
        parent::__construct();
    }
	
	/** Get ITEXMO API Settings **/
	public function get_sms_settings() {
		$this->db->select("*");
		$this->db->from($this->table_settings);
		$this->db->where('status', 1);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Update ITEXMO API Settings **/
	public function update_sms_settings($data) {
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$update_date = $dt_date->format("Y-m-d H:i:s");
		
		$update_data = array(
			'email' => isset($data['email']) ? $data['email'] : '',
			'api_code' => $data['api_code'],
			'api_password' => $data['api_password'],
			'sender_id' => isset($data['sender_id']) ? $data['sender_id'] : '',
			'updated_by' => $this->session->userdata('userid'),
			'updated_at' => $update_date
		);
		
		// Check if settings exist
		$existing = $this->get_sms_settings();
		if($existing) {
			$this->db->where('id', $existing['id']);
			$this->db->update($this->table_settings, $update_data);
			return $existing['id'];
		} else {
			$update_data['status'] = 1;
			$this->db->insert($this->table_settings, $update_data);
			return $this->db->insert_id();
		}
	}
	
	/** Get all notification records **/
	public function get_all_records($limit = '', $offset = '') {
		$this->db->select("sn.*, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name,
			c.customer_id as cust_id", FALSE);
		$this->db->from($this->table_name . ' as sn');
		$this->db->join($this->table_customer . ' as c', 'c.customer_id = sn.customer_id', 'left');
		$this->db->order_by('sn.created_at', 'DESC');
		
		if($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get notification by ID **/
	public function get_record_by_id($id) {
		$this->db->select("sn.*, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name,
			c.customer_id as cust_id", FALSE);
		$this->db->from($this->table_name . ' as sn');
		$this->db->join($this->table_customer . ' as c', 'c.customer_id = sn.customer_id', 'left');
		$this->db->where('sn.id', $id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Save notification log **/
	public function save_notification($data) {
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$created_date = $dt_date->format("Y-m-d H:i:s");
		
		$insert_data = array(
			'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : NULL,
			'mobile_number' => $data['mobile_number'],
			'message_type' => $data['message_type'],
			'message' => $data['message'],
			'status' => isset($data['status']) ? $data['status'] : 'pending',
			'itexmo_response' => isset($data['itexmo_response']) ? $data['itexmo_response'] : NULL,
			'itexmo_code' => isset($data['itexmo_code']) ? $data['itexmo_code'] : NULL,
			'sent_at' => isset($data['sent_at']) ? $data['sent_at'] : NULL,
			'created_by' => $this->session->userdata('userid'),
			'created_at' => $created_date
		);
		
		$this->db->insert($this->table_name, $insert_data);
		return $this->db->insert_id();
	}
	
	/** Update notification status **/
	public function update_notification_status($id, $status, $response = NULL, $code = NULL) {
		$dt_date = new DateTime("now", new DateTimeZone("Asia/Manila"));
		$update_date = $dt_date->format("Y-m-d H:i:s");
		
		$update_data = array(
			'status' => $status,
			'updated_at' => $update_date
		);
		
		if($status == 'sent') {
			$update_data['sent_at'] = $update_date;
		}
		
		if($response !== NULL) {
			$update_data['itexmo_response'] = $response;
		}
		
		if($code !== NULL) {
			$update_data['itexmo_code'] = $code;
		}
		
		$this->db->where('id', $id);
		$this->db->update($this->table_name, $update_data);
		return $this->db->affected_rows();
	}
	
	/** Get customers for billing statement **/
	public function get_customers_for_billing($billing_period_id = NULL, $zone_id = NULL) {
		$this->db->select("c.customer_id, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name,
			c.mobile1, c.mobile2,
			cr.amount, cr.penalty, cr.maintenance_fee,
			cr.month, cr.year,
			bp.bp_due_date", FALSE);
		$this->db->from($this->table_customer . ' as c');
		$this->db->join($this->table_customer_reading . ' as cr', 'cr.customer_id = c.customer_id', 'left');
		$this->db->join($this->table_billing_period . ' as bp', 'bp.bp_id = cr.bp_id', 'left');
		$this->db->where('(c.mobile1 IS NOT NULL AND c.mobile1 != "") OR (c.mobile2 IS NOT NULL AND c.mobile2 != "")');
		$this->db->where('c.status', 'active');
		
		if($billing_period_id) {
			$this->db->where('cr.bp_id', $billing_period_id);
		}
		
		if($zone_id) {
			$this->db->where('c.zone', $zone_id);
		}
		
		$this->db->group_by('c.customer_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get customers with due accounts **/
	public function get_due_accounts($days_before = 3) {
		$date = date('Y-m-d', strtotime("+$days_before days"));
		
		$this->db->select("c.customer_id, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name,
			c.mobile1, c.mobile2,
			cr.amount, cr.penalty, cr.maintenance_fee,
			cr.month, cr.year,
			bp.bp_due_date,
			DATEDIFF(bp.bp_due_date, CURDATE()) as days_until_due", FALSE);
		$this->db->from($this->table_customer . ' as c');
		$this->db->join($this->table_customer_reading . ' as cr', 'cr.customer_id = c.customer_id', 'left');
		$this->db->join($this->table_billing_period . ' as bp', 'bp.bp_id = cr.bp_id', 'left');
		$this->db->where('(c.mobile1 IS NOT NULL AND c.mobile1 != "") OR (c.mobile2 IS NOT NULL AND c.mobile2 != "")');
		$this->db->where('c.status', 'active');
		$this->db->where('cr.status', 0); // Unpaid
		$this->db->where('bp.bp_due_date <=', $date);
		$this->db->where('bp.bp_due_date >=', date('Y-m-d'));
		$this->db->group_by('c.customer_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get accounts for disconnection **/
	public function get_disconnection_accounts($days_overdue = 30) {
		$date = date('Y-m-d', strtotime("-$days_overdue days"));
		
		$this->db->select("c.customer_id, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name,
			c.mobile1, c.mobile2,
			cr.amount, cr.penalty, cr.maintenance_fee,
			cr.month, cr.year,
			bp.bp_due_date,
			DATEDIFF(CURDATE(), bp.bp_due_date) as days_overdue", FALSE);
		$this->db->from($this->table_customer . ' as c');
		$this->db->join($this->table_customer_reading . ' as cr', 'cr.customer_id = c.customer_id', 'left');
		$this->db->join($this->table_billing_period . ' as bp', 'bp.bp_id = cr.bp_id', 'left');
		$this->db->where('(c.mobile1 IS NOT NULL AND c.mobile1 != "") OR (c.mobile2 IS NOT NULL AND c.mobile2 != "")');
		$this->db->where('c.status', 'active');
		$this->db->where('cr.status', 0); // Unpaid
		$this->db->where('bp.bp_due_date <', $date);
		$this->db->group_by('c.customer_id');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get notification statistics **/
	public function get_notification_stats($date_from = NULL, $date_to = NULL) {
		$this->db->select("
			COUNT(*) as total_sent,
			SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as total_success,
			SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as total_failed,
			SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending,
			SUM(CASE WHEN message_type = 'billing_statement' THEN 1 ELSE 0 END) as billing_count,
			SUM(CASE WHEN message_type = 'due_account' THEN 1 ELSE 0 END) as due_count,
			SUM(CASE WHEN message_type = 'disconnection' THEN 1 ELSE 0 END) as disconnection_count
		");
		$this->db->from($this->table_name);
		
		if($date_from) {
			$this->db->where('created_at >=', $date_from);
		}
		
		if($date_to) {
			$this->db->where('created_at <=', $date_to . ' 23:59:59');
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Get notifications by date range **/
	public function get_notifications_by_date($date_from, $date_to) {
		$this->db->select("sn.*, 
			CONCAT(c.first_name, ' ', c.last_name) as customer_name", FALSE);
		$this->db->from($this->table_name . ' as sn');
		$this->db->join($this->table_customer . ' as c', 'c.customer_id = sn.customer_id', 'left');
		$this->db->where('sn.created_at >=', $date_from);
		$this->db->where('sn.created_at <=', $date_to . ' 23:59:59');
		$this->db->order_by('sn.created_at', 'DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get total count **/
	public function record_count() {
		return $this->db->count_all($this->table_name);
	}
	
	/** Get zones **/
	public function get_zones() {
		$this->db->select("*");
		$this->db->from($this->table_zone);
		$this->db->where('status', 1);
		$this->db->order_by('zone', 'ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Get billing periods **/
	public function get_billing_periods() {
		$this->db->select("bp.*, m.month_name");
		$this->db->from($this->table_billing_period . ' as bp');
		$this->db->join('tbl_months as m', 'm.month_id = bp.bp_period_month', 'left');
		$this->db->order_by('bp.bp_period_year DESC, bp.bp_period_month DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
}
?>

