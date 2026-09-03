<?php 
class addpaymentcustomer_model extends CI_Model {
	public $table_name = 'tbl_addmetercustomer';
    public $table_customername = 'tbl_addcustomer';
	public $table_amount = 'tbl_amountrate';
	public $table_customers = 'tbl_addcustomer';
	public $table_address = 'tbl_web_settings';
	public $table_monthly = 'tbl_monthlycustomer';
	public $table_expenses = 'tbl_addexpenses';
	public $table_payrol = 'tbl_payrols';
	public $table_customer = 'tbl_addcustomer';
	public $table_ledger ='tbl_ledgers';
	public $table_transactions ='tbl_transactions';
	public $table_billing_period ='tbl_billing_period';
	public $table_doc_series_number ='tbl_doc_series_number';
	public $table_zone ='tbl_zone';
	public $table_meter_reading ='tbl_addcustomer_reading';
	public $table_users ='tbl_responsibilities_user';
	public $table_leaking_ledger ='tbl_leaking_ledger';
	public $table_leaking_ledger_details ='tbl_leaking_ledger_details';

	/** Set by controller when posting multiple payments in one batch (one OR for all lines). */
	public $batch_or_number = null;

	// Autoloading a system library usin constructor method
	public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Manila');
    }

	/** Current datetime string in Philippines timezone (Y-m-d H:i:s). */
	private function manila_now() {
		return (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');
	}

	/** Format unix timestamp as date string in Philippines timezone (Y-m-d). */
	private function manila_date_from_timestamp($timestamp) {
		$dt = new DateTime('@' . (int) $timestamp);
		$dt->setTimezone(new DateTimeZone('Asia/Manila'));
		return $dt->format('Y-m-d');
	}

	/**
	 * Highest OR number already recorded for this teller (for seeding a new series row).
	 */
	private function max_or_for_user($user_id) {
		$user_id = (int) $user_id;
		if ($user_id <= 0) {
			return 0;
		}
		$this->db->select_max('or_number', 'm');
		$this->db->where('userid', $user_id);
		$r = $this->db->get($this->table_name)->row();
		if ($r && isset($r->m) && $r->m !== null && $r->m !== '') {
			return (int) $r->m;
		}
		return 0;
	}

	/**
	 * Ensure tbl_doc_series_number has an OR row for this teller (doc_name=OR, teller_user_id).
	 * Seeds doc_series_num from max(or_number) for that user so numbering continues after migration.
	 */
	public function ensure_or_series_row($user_id) {
		$user_id = (int) $user_id;
		if ($user_id <= 0) {
			return false;
		}
		if (!$this->db->field_exists('teller_user_id', $this->table_doc_series_number)) {
			return false;
		}
		$this->db->where('doc_name', 'OR');
		$this->db->where('teller_user_id', $user_id);
		$q = $this->db->get($this->table_doc_series_number);
		if ($q->num_rows() > 0) {
			return true;
		}
		$seed = $this->max_or_for_user($user_id);
		$insert = array(
			'doc_name' => 'OR',
			'doc_series_num' => $seed,
			'teller_user_id' => $user_id,
		);
		if (!$this->db->insert($this->table_doc_series_number, $insert)) {
			$this->db->where('doc_name', 'OR');
			$this->db->where('teller_user_id', $user_id);
			return $this->db->get($this->table_doc_series_number)->num_rows() > 0;
		}
		return true;
	}

	/**
	 * Next OR for display (non-atomic). Uses teller-specific series when column exists.
	 */
	public function get_or_preview_for_user($user_id) {
		$user_id = (int) $user_id;
		if ($user_id <= 0 || !$this->db->field_exists('teller_user_id', $this->table_doc_series_number)) {
			$this->db->select('doc_series_num');
			$this->db->from($this->table_doc_series_number);
			$this->db->where('doc_id', 1);
			$row = $this->db->get()->row_array();
			$n = isset($row['doc_series_num']) ? (int) $row['doc_series_num'] + 1 : 1;
			return $n;
		}
		$this->ensure_or_series_row($user_id);
		$this->db->select('doc_series_num');
		$this->db->from($this->table_doc_series_number);
		$this->db->where('doc_name', 'OR');
		$this->db->where('teller_user_id', $user_id);
		$row = $this->db->get()->row_array();
		if (!$row) {
			return 1;
		}
		return (int) $row['doc_series_num'] + 1;
	}

	/**
	 * Atomically reserve the next OR for this teller (standalone transaction).
	 * Used for batch payments where several rows share one OR.
	 */
	public function allocate_next_or_atomic($user_id) {
		$user_id = (int) $user_id;
		if (!$this->db->field_exists('teller_user_id', $this->table_doc_series_number) || $user_id <= 0) {
			$this->db->trans_begin();
			$q = $this->db->query(
				'SELECT doc_id, doc_series_num FROM ' . $this->table_doc_series_number . ' WHERE doc_id = 1 FOR UPDATE'
			);
			$row = $q->row();
			if (!$row) {
				$this->db->trans_rollback();
				return false;
			}
			$next = (int) $row->doc_series_num + 1;
			$this->db->where('doc_id', 1);
			$this->db->update($this->table_doc_series_number, array('doc_series_num' => $next));
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return false;
			}
			$this->db->trans_commit();
			return $next;
		}
		$this->db->trans_begin();
		$this->ensure_or_series_row($user_id);
		$q = $this->db->query(
			'SELECT doc_id, doc_series_num FROM ' . $this->table_doc_series_number . ' WHERE doc_name = ? AND teller_user_id = ? FOR UPDATE',
			array('OR', $user_id)
		);
		$row = $q->row();
		if (!$row) {
			$this->db->trans_rollback();
			return false;
		}
		$next = (int) $row->doc_series_num + 1;
		$this->db->where('doc_id', (int) $row->doc_id);
		$this->db->update($this->table_doc_series_number, array('doc_series_num' => $next));
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}
		$this->db->trans_commit();
		return $next;
	}

	/**
	 * True if this OR is already used for the current teller (same rules as check_or_number).
	 */
	public function is_or_number_taken($or_number) {
		$or_number = (int) $or_number;
		if ($or_number <= 0) {
			return true;
		}
		$this->db->select('id');
		$this->db->from($this->table_name);
		$this->db->where('CAST(or_number AS UNSIGNED)=', $or_number);
		$uid = $this->session->userdata('userid');
		if ($uid !== null && $uid !== '' && (int) $uid > 0) {
			$this->db->where('userid', (int) $uid);
		}
		$q = $this->db->get();
		return $q->num_rows() > 0;
	}

	/**
	 * After manual/batch save, keep doc_series_num at least as high as the OR used (no full transaction).
	 */
	public function sync_or_series_max_after_posted($user_id, $posted_or) {
		$posted_or = (int) $posted_or;
		$user_id = (int) $user_id;
		if ($posted_or <= 0) {
			return false;
		}
		if ($this->db->field_exists('teller_user_id', $this->table_doc_series_number) && $user_id > 0) {
			$this->ensure_or_series_row($user_id);
			$this->db->select('doc_id, doc_series_num');
			$this->db->from($this->table_doc_series_number);
			$this->db->where('doc_name', 'OR');
			$this->db->where('teller_user_id', $user_id);
			$row = $this->db->get()->row();
			if (!$row) {
				return false;
			}
			$new_max = max((int) $row->doc_series_num, $posted_or);
			$this->db->where('doc_id', (int) $row->doc_id);
			return $this->db->update($this->table_doc_series_number, array('doc_series_num' => $new_max));
		}
		$this->db->select('doc_id, doc_series_num');
		$this->db->from($this->table_doc_series_number);
		$this->db->where('doc_id', 1);
		$row = $this->db->get()->row();
		if (!$row) {
			return false;
		}
		$new_max = max((int) $row->doc_series_num, $posted_or);
		$this->db->where('doc_id', 1);
		return $this->db->update($this->table_doc_series_number, array('doc_series_num' => $new_max));
	}

	    // to get all ledgers
	public function fetchLedger(){
		$this->db->select("*");
		$this->db->from($this->table_ledger);
		//$this->db->where('id','1');
		//$this->db->where('account_id','5');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
	/** In Function Get all records from select table **/
    
	 public function get_all_records($billing_period='') {
        $this->db->select($this->table_name.".*,SUM(".$this->table_name.".amount) as gross_amount,".$this->table_customername.".*,".$this->table_name.".id as id");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_name.".customer_id = ".$this->table_customername.".customer_id", 'left');
		if($billing_period!=''){
			$billperiod = explode(' ',$billing_period);
			$this->db->where($this->table_name.'.month',$billperiod[0]);
			$this->db->where($this->table_name.'.year',$billperiod[1]);
		}
		
		$this->db->order_by($this->table_name.'.id','desc');
		$this->db->group_by($this->table_name.'.invoice_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }
	
	 public function get_amountrate() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->order_by('id','desc');
		$this->db->where('id','1');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row_array();
		return $result;
    }

    public function get_unitvalue() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }	
	public function addaccount($data)
	{
		$result = $this->db->insert('tbl_account', $data); 
		return $result;
	}
	 public function get_address() {
        $this->db->select("*");
		$this->db->from($this->table_address);
		$this->db->where('id','1');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }		
	
	public function get_addcustomer() {
        $this->db->select("*");
		$this->db->from($this->table_customername);
		$this->db->order_by('id','desc');
		$this->db->where("customer_type",'metercustomer');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	public function select_getoldmeter($id) {
		
        /*$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
		*/
		$sql = "SELECT ac.id as addcustomer_id, ac.customer_id, ac.mobile1, ac.mobile2, ac.email_id, am.id as addmetercustomer_id,
		        am.customer_id, am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount, 
				am.total, am.status as addmetercustomer_status, am.date 
		        FROM `tbl_addmetercustomer` am 
				LEFT JOIN `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				WHERE ac.customer_id = '$id' OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' OR ac.email_id = '$id'
				ORDER BY addmetercustomer_id DESC LIMIT 1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
		
		/*$this->db->select('tbl_addmetercustomer.* , tbl_addcustomer.id as tbid , tbl_addmetercustomer.status as newstatus');
		$this->db->join('tbl_addcustomer', 'tbl_addcustomer.customer_id = tbl_addmetercustomer.customer_id');
		$this->db->get('tbl_addmetercustomer');
		$this->db->order_by('tbl_addmetercustomer.id','desc');
		$this->db->or_where("tbl_addcustomer.customer_id",$id);
		$this->db->or_where("tbl_addcustomer.mobile1",$id);
		$this->db->or_where("tbl_addcustomer.mobile2",$id);
		$this->db->or_where("tbl_addcustomer.email_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
		*/
		
    }	
	public function check_customer_id($id){
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where('customer_id',$id);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
	}
	
	public function get_addcustomer_add_all_records($id){
		$sql = "SELECT ac.id AS addcustomer_id, ac.customer_id, ac.first_name, ac.middle_name, ac.last_name, 
		        ac.mobile1, ac.mobile2, ac.email_id, ac.address, am.id AS addmetercustomer_id, am.customer_id, 
				am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount,
				am.total, am.status AS addmetercustomer_status, am.date, am.month, am.year, tac.customer_id, tac.month, 
				tac.reading, tac.year
				FROM  `tbl_addmetercustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_addcustomer_reading` tac ON am.customer_id = tac.customer_id
				WHERE  ac.customer_id = '$id'
                ORDER BY addmetercustomer_id DESC LIMIT 0,1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function get_addcustomer_count_all_records($id, $mon_id){
		$sql = "SELECT ac.id AS addcustomer_id, ac.customer_id, ac.first_name, ac.middle_name, ac.last_name, 
		        ac.mobile1, ac.mobile2, ac.email_id, ac.address, am.id AS addmetercustomer_id, am.customer_id, 
				am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount,
				am.total, am.status AS addmetercustomer_status, am.date, am.month, am.year, tac.customer_id, tac.month, 
				tac.reading, tac.year
				FROM  `tbl_addmetercustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_addcustomer_reading` tac ON am.customer_id = tac.customer_id
				WHERE  am.month = '$mon_id' AND ac.customer_id = '$id' OR ac.mobile1 = '$id' OR ac.mobile2 = '$id' 
				OR ac.email_id = '$id'
                ORDER BY tac.month DESC LIMIT 0,1
				";
		$query = $this->db->query($sql);
		$result = $query->num_rows();
		return $result;
	}
	
	public function get_addcustomer_show_all_records($id, $mon_id,$year=''){
		$sql = "SELECT ac.id AS addcustomer_id, ac.customer_id, ac.first_name, ac.middle_name, ac.last_name, 
		        ac.mobile1, ac.mobile2, ac.email_id, ac.address, am.id AS addmetercustomer_id, am.customer_id, 
				am.oldmeter, am.aftermeter, am.consumedunits, am.per_unit, am.amount, am.balance, am.pay_amount,
				am.total, am.status AS addmetercustomer_status, am.date, am.month, am.year, tac.customer_id, tac.month, 
				tac.reading, tac.year
				FROM  `tbl_addmetercustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_addcustomer_reading` tac ON am.customer_id = tac.customer_id
				WHERE  am.month = '$mon_id' AND ac.customer_id = '$id' AND am.year = '$year'
                ORDER BY tac.month DESC LIMIT 0,1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function get_meter_reading_all_records($id){
		$id = $this->db->escape_str(trim((string) $id));
		$paid_create_date_select = $this->db->field_exists('create_date_time', 'tbl_addmetercustomer')
			? 'pay.create_date_time AS paid_create_date_time'
			: 'NULL AS paid_create_date_time';

		$sql = "SELECT ac.id AS meter_id, ac.customer_id, ac.reading, ac.month, ac.year, ac.date, ac.amount, am.customer_id,
		        am.mobile1, am.mobile2, am.email_id, tm.month_name, am.status, ac.previous_reading, ac.consumed, am.special_priviledge,
				ac.sc_discount,
				ac.unit_price,
				ac.bp_id AS bp_id,
				ac.penalty,
				ac.maintenance_fee,
				ac.franchise_fee_amount,
				ac.refno,
				bp.*,
				pay.id AS payment_id,
				pay.amount AS paid_bill_amount,
				pay.pay_amount AS paid_pay_amount,
				pay.grand_total AS paid_grand_total,
				pay.leaking_amount AS paid_leaking_amount,
				pay.or_number AS paid_or_number,
				pay.date AS trans_date,
				{$paid_create_date_select},
				pay.balance AS paid_balance
				FROM  `tbl_addcustomer_reading` ac
				LEFT JOIN  `tbl_addcustomer` am ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_months` tm ON ac.month = tm.month_id
				LEFT JOIN  `tbl_billing_period` bp ON ac.bp_id = bp.bp_id
				LEFT JOIN  `tbl_addmetercustomer` pay ON pay.customer_id = am.customer_id AND pay.month = ac.month AND pay.year = ac.year
				WHERE ac.month = tm.month_id
                AND am.customer_id = '{$id}'
				ORDER BY meter_id DESC
				";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function get_second_meter($id){
		$sql = "SELECT ac.id AS meter_id, ac.customer_id, ac.reading, ac.month, ac.year, ac.date, am.customer_id, 
		        am.mobile1, am.mobile2, am.email_id, tm.month_name
				FROM  `tbl_addcustomer_reading` ac
				LEFT JOIN  `tbl_addcustomer` am ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_months` tm ON ac.month = tm.month_id
				WHERE ac.month = tm.month_id
                AND  am.customer_id = '$id' OR am.mobile1 = '$id' OR am.mobile2 = '$id' 
				OR am.email_id = '$id'
				ORDER BY ac.id DESC LIMIT 1,1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function collectinfo($id){
		$sql = "SELECT ac.id AS meter_id, ac.customer_id, ac.reading, ac.month, ac.year, ac.date, am.customer_id, 
		        am.mobile1, am.mobile2, am.email_id, tm.month_name, tm.month_id
				FROM  `tbl_addcustomer_reading` ac
				LEFT JOIN  `tbl_addcustomer` am ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_months` tm ON ac.month = tm.month_id
				WHERE ac.month = tm.month_id
                AND  am.customer_id = '$id' OR am.mobile1 = '$id' OR am.mobile2 = '$id' 
				OR am.email_id = '$id'
				ORDER BY meter_id DESC LIMIT 0,1
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function month_collectinfo($id){
		$sql = "SELECT ac.id AS meter_id, ac.customer_id, ac.reading, ac.month, ac.year, ac.date, am.customer_id, 
		        am.mobile1, am.mobile2, am.email_id, tm.month_name
				FROM  `tbl_addcustomer_reading` ac
				LEFT JOIN  `tbl_addcustomer` am ON ac.customer_id = am.customer_id
				LEFT JOIN  `tbl_months` tm ON ac.month = tm.month_id
				WHERE  am.customer_id = '$id'
				ORDER BY meter_id DESC LIMIT 0,1 
				";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function get_metercustomer_add_all_records($id,$mon_id,$year_id){
		$sql = "SELECT ac.id AS addcustomer_id, ac.customer_id, am.id AS addmetercustomer_id, am.customer_id, 
		        am.date, am.month, am.year, ac.mobile1, ac.mobile2, ac.email_id, am.amount, am.per_unit, am.or_number, am.date as trans_date, am.create_date_time
				FROM  `tbl_addmetercustomer` am
				LEFT JOIN  `tbl_addcustomer` ac ON ac.customer_id = am.customer_id
                WHERE am.month = '$mon_id' AND am.year = '$year_id' AND ac.customer_id = '$id'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	public function select_getoldmeter_count($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->num_rows();
		return $result;
    }	
	
	
	
 	/** In Function Get single records for edit view purpose from select table **/
    public function get_single_record($id='') {
        $this->db->select($this->table_name.".*,".$this->table_zone.".zone as zonename, ".$this->table_meter_reading.".unit_price,".$this->table_meter_reading.".penalty,
		".$this->table_meter_reading.".amount,
		".$this->table_meter_reading.".sc_discount,
		".$this->table_meter_reading.".maintenance_fee,
		".$this->table_meter_reading.".franchise_fee_percent,
		".$this->table_meter_reading.".franchise_fee_amount,
		".$this->table_meter_reading.".bp_id,
		".$this->table_billing_period.".bp_due_date,
		".$this->table_users.".employee_name
		");
		$this->db->from($this->table_name);
		$this->db->join($this->table_meter_reading,$this->table_name.'.customer_id='.$this->table_meter_reading.'.customer_id AND '.$this->table_name.'.month='.$this->table_meter_reading.'.month AND '.$this->table_name.'.year='.$this->table_meter_reading.'.year','left');
		$this->db->join($this->table_customers,$this->table_name.'.customer_id='.$this->table_customers.'.customer_id','left');
		$this->db->join($this->table_zone,$this->table_customers.'.zone='.$this->table_zone.'.id','left');
		$this->db->join($this->table_billing_period,$this->table_meter_reading.'.bp_id='.$this->table_billing_period.'.bp_id','left');
		$this->db->join($this->table_users,$this->table_name.'.userid='.$this->table_users.'.id','left');
		if($id != ''){
			$this->db->where($this->table_name.".id",$id);
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->row_array();
		}
		return $result;
    }
  	/** In Function Add Check Exits records for select table **/
	public function exit_details($exit_data) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }
	
  	/** In Function Edit Check Exits records  for select table**/
	public function exit_id($exit_data,$local_id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where($exit_data);
		$query = $this->db->get();
		$result = $query->num_rows();
		if($result > 0){
			$this->db->select("*");
			$this->db->from($this->table_name);
			$this->db->where($exit_data);
			$query = $this->db->get();
			$result = $query->row_array();
			if($result['id'] == $local_id){
				$result ='0';
			}
		}else{
			$result =1;
		}
		return $result;
    }
	
	public function get_dollar_value() {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
	public function get_meter_value($cubmeter_read) {
        $this->db->select("*");
		$this->db->from($this->table_amount);
		$this->db->where('id',1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
	
	public function get_lastdata($id) {
        $this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$this->db->where("customer_id",$id);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result_array();
		return $result;
    }	
	
  	/** In Function Add records for select table **/
	public function add_record(){  //print_r($this->input->post);exit;
		$id = trim($this->input->post('customer_id'));
		$getData=$this->select_getoldmeter($id);
		$trans_date = strtotime($this->input->post('transdate'));
		$leaking_id = $this->input->post('leaking_id');
		$leaking_balance_prev_bal = $this->input->post('leaking_balance');
		
		//echo 'Customer ID:'.$this->input->post('name');
		//exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		        $balance = $getData[0]['balance'];
			}else{
			//$balance = $getData[0]['total'];
			   $balance = $getData[0]['balance'];
            
			} 
			
		}
		if(empty($getData)){
		
			$balance = 0;
		}		
		
		$leaking_balance_pay = $this->input->post('paid_total_amount')=='' ? 0 : $this->input->post('paid_total_amount');
		$consumedunits=$this->input->post('current_reading')-$this->input->post('oldmeter');
		//$meterdollar = $this->get_dollar_value();
		//$amount=$meterdollar['per_unit']*$consumedunits;
		$amount=$this->input->post('paid_total_amount');
		$total_amount = $amount + $balance;
		$total_balance = $total_amount - $this->input->post('pay_amount');
		$total=$amount+$total_balance-$this->input->post('pay_amount');
		
		$allamount = $this->input->post('paid_total_amount');
		$payamount = $this->input->post('pay_amount');
		$minusamount = $allamount - $payamount;
		
		$final_total_amount = $this->input->post('final_total_amount');
		$pay_amount = $this->input->post('pay_amount');
		$remaining = $allamount - $pay_amount;
		$change_amount = $this->input->post('change_amount');
		$leaking_payment = 0;
		$leaking_balance_total = 0;
		$leaking_current_balance_total = 0;

		if($this->input->post('leaking_balance')>0){
			//if($change_amount>0){
			$leaking_payment =$this->input->post('leaking_balance');
			$leaking_balance_total = $this->input->post('leaking_balance_total');
			$leaking_current_balance_total = $leaking_balance_total - $leaking_payment;
			//}
			
		}

		$user_id = (int) $this->session->userdata('userid');
		$posted_or = (int) $this->input->post('or_num');
		if ($posted_or <= 0) {
			return false;
		}

		$this->db->trans_begin();

		$series_row = null;
		if ($this->db->field_exists('teller_user_id', $this->table_doc_series_number) && $user_id > 0) {
			if (!$this->ensure_or_series_row($user_id)) {
				$this->db->trans_rollback();
				return false;
			}
			$q = $this->db->query(
				'SELECT doc_id, doc_series_num FROM ' . $this->table_doc_series_number . ' WHERE doc_name = ? AND teller_user_id = ? FOR UPDATE',
				array('OR', $user_id)
			);
			$series_row = $q->row();
		} else {
			$q = $this->db->query(
				'SELECT doc_id, doc_series_num FROM ' . $this->table_doc_series_number . ' WHERE doc_id = 1 FOR UPDATE'
			);
			$series_row = $q->row();
		}
		if (!$series_row) {
			$this->db->trans_rollback();
			return false;
		}

		if ($this->is_or_number_taken($posted_or)) {
			$this->db->trans_rollback();
			return false;
		}

		$or_num = $posted_or;

	    $set_data = array(
			'customer_id' => trim($id),
			'ledger_id' => $this->input->post('ledger_id'),
			'invoice_id' => $this->input->post('time_format'),
			'name' => $this->input->post('fullname'),
			'oldmeter' => $this->input->post('oldmeter'),
			'aftermeter' => $this->input->post('current_reading'),
			'per_unit' => $this->input->post('paid_total_amount'),
			'consumedunits' => $consumedunits,
			'amount' => $this->input->post('paid_total_amount'),
			'balance' => $change_amount,
			'total' => $allamount,
			'pay_amount' => $this->input->post('pay_amount'),
			'currency' => $this->input->post('currency'),
			'month' => $this->input->post('month'),
			'year' => $this->input->post('year'),
			'status' => $this->input->post('status_id'),
			'or_number' => $or_num,
			'vat_percent' => $this->input->post('vat_percent'),
			'vat_amount' => $this->input->post('vat_amount'),
			'leaking_percent' => $this->input->post('leaking_percent'),
			'leaking_amount' => $this->input->post('leaking_amount'),
			'leaking_payment' => $leaking_payment,
			'leaking_prev_balance' => $leaking_balance_total,
			'leaking_cur_balance' => $leaking_current_balance_total,
			'grand_total' => $this->input->post('grand_total'),
			'date' => $this->manila_date_from_timestamp($trans_date),
			'create_date_time' => $this->manila_now(),
			'userid' => $this->session->userdata('userid'),
			'username' => $this->session->userdata('username'),
		);
		$result = $this->db->insert($this->table_name, $set_data); //print_r($result1); exit;
		if (!$result) {
			$this->db->trans_rollback();
			return false;
		}
		// save data(first entry) on transaction table
		$lastId = $this->db->insert_id(); 

		$set_data4 = array(
			'customer_billing_id' => $lastId,
			'status' => 1,
			'or_number' => $or_num,
			'update_date_time' => $this->manila_now(),
		);
		$this->db->where('customer_id',trim($id));
		$this->db->where('month',$this->input->post('month'));
		$this->db->where('year',$this->input->post('year'));
		$this->db->update($this->table_meter_reading, $set_data4); //print_r($result2); //exit;

		$set_data2 = array(
			'tableName' => 'addmetercustomer',
			'transaction_id' => $lastId,
			'ledger_id' => $this->input->post('ledger_id'),
			'ledger_id_for' => 'customer_id',
			'debit' => $this->input->post('grand_total'),//$allamount,
			'date' => $this->manila_date_from_timestamp($trans_date),
			'create_date_time' => $this->manila_now(),
			'update_date_time' => $this->manila_now(),
		);
		$result2 = $this->db->insert($this->table_transactions, $set_data2); //print_r($result2); //exit;
		
		if($leaking_id && ($leaking_balance_prev_bal==='' || $leaking_balance_prev_bal==0)){
			if($this->input->post('pay_amount')>=$this->input->post('grand_total')){
				$leaking_balance = 0;
				$leaking_status = 5;
				$pay_amount = $this->input->post('grand_total');
			}else{
				$leaking_status = 4;
				$leaking_balance =$change_amount;
				$pay_amount = $this->input->post('pay_amount');
			}
			$set_data5 = array(
				'leaking_total_amount' => $this->input->post('grand_total'),
				'leaking_balance' => $leaking_balance,
				'leaking_status' => $leaking_status,
				'leaking_updated_datetime' => $this->manila_now(),
			);
			$this->db->where('leaking_id',trim($leaking_id));
			$this->db->update($this->table_leaking_ledger, $set_data5); 

			$set_data6 = array(
				'leaking_id' => $leaking_id,
				'leakingledgerdetails_or_number' => $or_num,
				'leakingledgerdetails_amount' => $pay_amount,
				'leakingledgerdetails_prev_balance' => 0,
				'leakingledgerdetails_balance' => $leaking_balance,
				'leakingledgerdetails_transdate' => $this->manila_date_from_timestamp($trans_date),
				'leakingledgerdetails_created_datetime' => $this->manila_now(),
			);
			$result2 = $this->db->insert($this->table_leaking_ledger_details, $set_data6); //print_r($result2); //exit;
		}

		if($leaking_balance_pay>0 && ($this->input->post('leaking_percent')==='' || $this->input->post('leaking_percent')==0)){
			if($leaking_balance_prev_bal>0){
			
				if($leaking_current_balance_total>0){
					$leaking_balance = $leaking_current_balance_total;
					$leaking_status = 4;
					$pay_amount = $leaking_payment;

				}elseif($leaking_current_balance_total==0){
					$leaking_balance = 0;
					$leaking_status = 5;
					$pay_amount = $leaking_payment;
				}elseif($this->input->post('pay_amount')>=$this->input->post('grand_total')){
					$leaking_balance = 0;
					$leaking_status = 5;
					$pay_amount = $this->input->post('grand_total');
				}else{
					$leaking_status = 4;
					$leaking_balance =$change_amount;
					$pay_amount = $this->input->post('pay_amount');
				}
				$set_data5 = array(
					'leaking_total_amount' => $this->input->post('grand_total'),
					'leaking_balance' => $leaking_balance,
					'leaking_status' => $leaking_status,
					'leaking_updated_datetime' => $this->manila_now(),
				);
				$this->db->where('leaking_id',trim($leaking_id));
				$this->db->update($this->table_leaking_ledger, $set_data5); 

				$set_data6 = array(
					'leaking_id' => $leaking_id,
					'leakingledgerdetails_or_number' => $or_num,
					'leakingledgerdetails_amount' => $pay_amount,
					'leakingledgerdetails_prev_balance' => $leaking_balance_total,
					'leakingledgerdetails_balance' => $leaking_balance,
					'leakingledgerdetails_transdate' => $this->manila_date_from_timestamp($trans_date),
					'leakingledgerdetails_created_datetime' => $this->manila_now(),
				);
				$result2 = $this->db->insert($this->table_leaking_ledger_details, $set_data6); //print_r($result2); //exit;
			}
		}

		if ($this->db->trans_status() === false || !$result || !$result2) {
			$this->db->trans_rollback();
			return false;
		}

		$new_series = max((int) $series_row->doc_series_num, (int) $posted_or);
		$this->db->where('doc_id', (int) $series_row->doc_id);
		$this->db->update($this->table_doc_series_number, array('doc_series_num' => $new_series));

		$this->db->trans_commit();
		return $result2;
	}
	
	/** In Function Add records for select table **/
	public function add_record_multiple($id, $or_num = null){
		$trans_date = strtotime($this->input->post('transdate'));
		$allamount = $this->input->post('paid_total_amount');
		
		$pay_amount = $this->input->post('pay_amount');
		$remaining = $allamount - $pay_amount;
		
		$del_id = $_POST['checkbox'][$id];
		
		$customer_id = $_POST['customer_id_next'];
		$name = $_POST['fullname'];
		$oldmeter = $_POST['previousreading_'.$id];
		
		$aftermeter = $_POST['reading_'.$id];
		$consumedunits = $_POST['consumedunit_'.$id];
		$per_unit = $_POST['unit_price_'.$id];
		
		$currency = $_POST['currency'];
		$month = $_POST['monthid_'.$id];
		$year =  $_POST['year_'.$id];
		$status = $_POST['status_'.$id];
		//$date = date('Y-m-d');
		$create_date_time = $this->manila_now();

		if ($or_num === null || $or_num === '') {
			$or_num = $this->batch_or_number;
		}
		if ($or_num === null || $or_num === '') {
			$or_num = (int) $this->input->post('or_num');
		}
			
		$set_data = array(
			'customer_id' => $customer_id,
			'ledger_id'  => $this->input->post('ledger_id'),
			'invoice_id' => $this->input->post('time_format'),
			'name' => $name,
			'oldmeter' => $oldmeter,
			'aftermeter' => $aftermeter,
			'consumedunits' => $consumedunits,
			'per_unit' => $per_unit,
			'amount' => $_POST['prsentamount_'.$id],
			'balance' => $this->input->post('change_amount'),
			'pay_amount' => $pay_amount,
			'total' => $allamount,
			'currency' => $currency,
			'month' => $month,
			'year' => $year,
			'status' => $status,
			'or_number' => $or_num,
			'vat_percent' => $this->input->post('vat_percent'),
			'vat_amount' => $this->input->post('vat_amount'),
			'leaking_percent' => $this->input->post('leaking_percent'),
			'leaking_amount' => $this->input->post('leaking_amount'),
			'grand_total' => $this->input->post('grand_total'),
			'date' => $this->manila_date_from_timestamp($trans_date),
			'create_date_time' => $create_date_time,
			'userid' => $this->session->userdata('userid'),
			'username' => $this->session->userdata('username'),
		); 
		$result = $this->db->insert($this->table_name, $set_data); 
		$lastId = $this->db->insert_id(); 

		$set_data4 = array(
			'customer_billing_id' => $lastId,
			'status' => 1,
			'or_number' => $or_num,
			'update_date_time' => $this->manila_now(),
		);
		$this->db->where('customer_id',trim($customer_id));
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->update($this->table_meter_reading, $set_data4); 

		$set_data2 = array(
			'tableName' => 'addmetercustomer',
			'transaction_id' => $lastId,
			'ledger_id' => $this->input->post('ledger_id'),
			'ledger_id_for' => 'customer_id',
			'debit' => $this->input->post('grand_total'),//$allamount,
			'date' => $this->manila_date_from_timestamp($this->input->post('trans_date')),
			'create_date_time' => $this->manila_now(),
			'update_date_time' => $this->manila_now(),
		);
		$result2 = $this->db->insert($this->table_transactions, $set_data2); //print_r($result2); //exit;
		// save data(second entry) on transaction table
		/*$set_data3 = array(
						'tableName' => 'addmetercustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('ledger_id'),
		                'ledger_id_for' => 'ledger_id',
		                'credit' => $pay_amount,//mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'create_date_time' => $this->manila_now(),
					    'update_date_time' => $this->manila_now(),
					);
		$result3 = $this->db->insert($this->table_transactions, $set_data3); //print_r($result3); exit;*/
		//print_r($result);
		return $result2;
		
	}
	/** In Function Add records for transaction table **/
	public function add_transaction($id){
		
		        // save data(first entry) on transaction table
		$sql = "SELECT * FROM tbl_addmetercustomer WHERE id =(SELECT MAX(id) FROM tbl_addmetercustomer)";
		$query = $this->db->query($sql);
		$result = $query->result_array();  //print_r($result[0]['id']);exit;
		$lastId = $result[0]['id'];
		//$lastId = $this->db->insert_id(); 
		$set_data2 = array(
						'tableName' => 'addmetercustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('customer_id'),
						'ledger_id_for' => 'customer_id',
						'debit' => $this->input->post('pay_amount'),//$allamount,
					    'create_date_time' => $this->manila_now(),
					    'update_date_time' => $this->manila_now(),
					);
		$result2 = $this->db->insert($this->table_transactions, $set_data2); //print_r($result2); //exit;
		// save data(second entry) on transaction table
	/*	$set_data3 = array(
						'tableName' => 'addmetercustomer',
						'transaction_id' => $lastId,
						'ledger_id' => $this->input->post('ledger_id'),
						'ledger_id_for' => 'ledger_id',
		                'credit' => mysql_real_escape_string($this->input->post('pay_amount')),//$allamount,
					    'create_date_time' => $this->manila_now(),
					    'update_date_time' => $this->manila_now(),
					);
		$result3 = $this->db->insert($this->table_transactions, $set_data3); //print_r($result3); exit;*/
		return $result;
		
	}
	
  	/** In Function Update records for select table **/
	public function update_record($id){
		
		$ids = $this->input->post('name');
		$getData=$this->my_model->select_getoldmeter($ids);
		//echo'<pre>';print_r($getData);exit;
		if(!empty($getData)){
			if($getData[0]['status']=='1'){
		$balance = 0;
			}else{
			$balance = $getData[0]['total'];
            
			}
			
		}if(empty($getData)){
		
			$balance = 0;
		}	
    $meterdollar=$this->get_dollar_value();
    $consumedunits=$this->input->post('aftermeter')-$this->input->post('oldmeter');
	$amount=$meterdollar['amountrate']*$consumedunits;
    $total=$amount+$balance;
	///echo'<pre>';print_r($consumedunits);
	//echo'<pre>';print_r($amount);
	//echo'<pre>';print_r($balance);
	//echo'<pre>';print_r($total);exit;
		$set_data = array(
						'customer_id' => $this->input->post('name'),
						'name' => $this->input->post('name'),
						'oldmeter' => $this->input->post('oldmeter'),
						'aftermeter' => $this->input->post('aftermeter'),
						'consumedunits' => $consumedunits,
					    'amount' => $amount,
					    'balance' =>$balance,
					    'total' =>$total,
						'status' => 0,
						
						
					);
				//echo'<pre>';print_r($set_data);exit;	
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}
	
	public function update_balance($id,$amount){
        $set_data = array(
                          'balance' => $amount,
                    );
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		return $result;
	}	
	
  	/** In Function Delete records for select table **/
	public function delete_record($id){
		$this->db->where('id',$id);
		$result = $this->db->delete($this->table_name); 
		return $result;
	}
	/** In Function Delete records for select table **/
	public function delete_transaction_record($id){
		$this->db->where('transaction_id',$id);
		$result = $this->db->delete($this->table_transactions); 
		return $result;
	}

	
	
  	/** In Function Status Update records for select table **/
	public function status_record($id,$balance,$customer_id){
		$sts = ($balance == 0 ? 1 : 0);
		$set_data = array(
						'balance' => '0',
						'date' => $this->manila_date_from_timestamp(time()),
					);
		$this->db->where('id',$id);
		$result = $this->db->update($this->table_name, $set_data); 
		if($result){
			$set_data1 = array(
				'paid_status' => $sts
			);
			$this->db->where('customer_id',$customer_id);
			$result = $this->db->update($this->table_customers, $set_data1);
		}
		return $result;
	}
	
	public function get_name($id) {
        //$this->db->select("first_name,middle_name,last_name");
		$this->db->select('*');
		$this->db->from($this->table_customername);
		//$this->db->where('customer_id',$id);
		$this->db->or_where('customer_id',$id);
		//$this->db->or_where('mobile1',$id);
		//$this->db->or_where('mobile2',$id);
		//$this->db->or_where('email_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
    }
    
	function getReceiptData($customer, $month, $year, $invoice_id = null){
		
		$this->db->select('tbl_months.month_id as monthid, 
		(SELECT unit_price FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$customer.'" and tbl_addcustomer_reading.month="'.$month.'" and tbl_addcustomer_reading.year="'.$year.'") as reading_amount,
		(SELECT maintenance_fee FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$customer.'" and tbl_addcustomer_reading.month="'.$month.'" and tbl_addcustomer_reading.year="'.$year.'") as maintenance_fee,
		tbl_months.month_name as monthname,tbl_addmetercustomer.id as ine_id,tbl_addmetercustomer.invoice_id as invoice_ids,tbl_addmetercustomer.date as tdate, tbl_addmetercustomer.*');				   
		$this->db->from('tbl_addmetercustomer');
		$this->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
		$this->db->where('customer_id',$customer);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		if ($invoice_id !== null && $invoice_id !== '') {
			$this->db->where('invoice_id', $invoice_id);
		}
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}
	function getReceipt_Data($customer, $month, $year, $invoice_id = null){
		
		$this->db->select('tbl_months.month_id as monthid, 
		(SELECT unit_price FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$customer.'" and tbl_addcustomer_reading.month="'.$month.'" and tbl_addcustomer_reading.year="'.$year.'") as reading_amount,
		(SELECT maintenance_fee FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$customer.'" and tbl_addcustomer_reading.month="'.$month.'" and tbl_addcustomer_reading.year="'.$year.'") as maintenance_fee,
		tbl_months.month_name as monthname,tbl_addmetercustomer.id as ine_id,tbl_addmetercustomer.invoice_id as invoice_ids,tbl_addmetercustomer.date as tdate, tbl_addmetercustomer.*');				   
		$this->db->from('tbl_addmetercustomer');
		$this->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
		$this->db->where('customer_id',$customer);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		if ($invoice_id !== null && $invoice_id !== '') {
			$this->db->where('invoice_id', $invoice_id);
		}
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function customer_deatils($customer){
		$this->db->select('*');
		$this->db->from($this->table_customername);
		$this->db->or_where('customer_id',$customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_metercustomer(){
		$this->db->select('SUM(grand_total) as total1');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_income_monthlycustomer(){
		$this->db->select('SUM(paidamount) as total2');
		$this->db->from($this->table_monthly);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_expenses(){
		$this->db->select('SUM(total) as extotal1');
		$this->db->from($this->table_expenses);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function get_outcome_payroll(){
		$this->db->select('SUM(total) as extotal2');
		$this->db->from($this->table_payrol);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function total_customer(){
		$this->db->select('COUNT(id) as count_id');
		$this->db->from($this->table_customer);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	/** Server-side pagination: Get paginated records with filtering **/
	public function get_paginated_records($start = 0, $length = 10, $search = '', $order_column = 'tbl_addmetercustomer.id', $order_dir = 'desc', $billing_period = '', $paid_date = '') {
		$this->db->select($this->table_name.".*,SUM(".$this->table_name.".amount) as gross_amount,".$this->table_customername.".*,".$this->table_name.".id as id");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_name.".customer_id = ".$this->table_customername.".customer_id", 'left');
		
		// Billing period takes priority; otherwise filter by Paid Date (transaction date)
		if($billing_period != ''){
			$billperiod = explode(' ',$billing_period);
			$this->db->where($this->table_name.'.month',$billperiod[0]);
			$this->db->where($this->table_name.'.year',$billperiod[1]);
		} elseif ($paid_date != '') {
			$this->db->where($this->table_name.'.date', $paid_date);
		}
		
		// Apply search filter
		if($search != '') {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".middle_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".or_number LIKE '%".$search_escaped."%' OR
				".$this->table_name.".invoice_id LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		// Order by
		$this->db->order_by($order_column, $order_dir);
		
		// Group by invoice_id (as in original query)
		$this->db->group_by($this->table_name.'.invoice_id');
		
		// Limit and offset
		$this->db->limit($length, $start);
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	/** Server-side pagination: Get total count with filtering **/
	public function get_total_count($search = '', $billing_period = '', $paid_date = '') {
		$this->db->select("COUNT(DISTINCT ".$this->table_name.".invoice_id) as total");
		$this->db->from($this->table_name);
		$this->db->join($this->table_customername, $this->table_name.".customer_id = ".$this->table_customername.".customer_id", 'left');
		
		// Billing period takes priority; otherwise filter by Paid Date (transaction date)
		if($billing_period != ''){
			$billperiod = explode(' ',$billing_period);
			$this->db->where($this->table_name.'.month',$billperiod[0]);
			$this->db->where($this->table_name.'.year',$billperiod[1]);
		} elseif ($paid_date != '') {
			$this->db->where($this->table_name.'.date', $paid_date);
		}
		
		// Apply search filter
		if($search != '') {
			$search_escaped = $this->db->escape_like_str($search);
			$this->db->where("(
				".$this->table_name.".customer_id LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".first_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".middle_name LIKE '%".$search_escaped."%' OR
				".$this->table_customername.".last_name LIKE '%".$search_escaped."%' OR
				".$this->table_name.".or_number LIKE '%".$search_escaped."%' OR
				".$this->table_name.".invoice_id LIKE '%".$search_escaped."%'
			)", NULL, FALSE);
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		return isset($result['total']) ? intval($result['total']) : 0;
	}
	
}
?>