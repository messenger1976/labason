<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('convertNumberToWordsPH'))
{
    function convertNumberToWordsPH($number) {
        $hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ' ';
        $negative    = 'negative ';
        $dictionary  = [
            0 => 'zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . convertNumberToWordsPH(abs($number));
        }

        $pesos = floor($number);
        $centavos = round(($number - $pesos) * 100);
        $result = '';

        // Convert Pesos
        if ($pesos > 0) {
            $result .= convertToWords($pesos, $dictionary, $hyphen, ' ', $separator, true) . ' Peso' . ($pesos > 1 ? 's' : '');
        }

        // Convert Centavos with "and" before it
        if ($centavos > 0) {
            if ($pesos > 0) {
                $result .= $conjunction; // Add "and" before Centavos
            }
            $result .= convertToWords($centavos, $dictionary, $hyphen, $conjunction, $separator, false) . ' Centavo' . ($centavos > 1 ? 's' : '');
        }

        return $result;
    }
}

if(!function_exists('convertToWords'))
{
    function convertToWords($number, $dictionary, $hyphen, $conjunction, $separator, $isPesos) {
        $string = '';

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = (int) ($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= ($isPesos ? $conjunction : ' ') . convertToWords($remainder, $dictionary, $hyphen, $conjunction, $separator, $isPesos);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convertToWords($numBaseUnits, $dictionary, $hyphen, $conjunction, $separator, $isPesos) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convertToWords($remainder, $dictionary, $hyphen, $conjunction, $separator, $isPesos);
                }
                break;
        }

        return $string;
    }
}

if(!function_exists('getCustomerInfo'))
{
    function getCustomerInfo($zone_id)
    {
        $CI = &get_instance();
        
        $CI->db->where('status', '1');
        $CI->db->where('zone', $zone_id);
        
        
        $customerinfo = $CI->db->get('tbl_addcustomer')->result();
        return $customerinfo;

    }
}

if(!function_exists('getMonthName'))
{
    function getMonthName($id)
    {
        $CI = &get_instance();
        $CI->db->where('month_id', $id);
        $monthname = $CI->db->get('tbl_months')->result();
        return $monthname;

    }
}

if(!function_exists('customerbillingperiod'))
{
    function customerbillingperiod($bp_month,$bp_year,$bp_current_month,$bp_current_year, $zone_id) {
        $customerinfoList = getCustomerInfo($zone_id);
        
        foreach($customerinfoList as $customerinfodata){ 
            $CI20 = &get_instance();
            $CI20->db->where('bp_period_month', $bp_month);
            $CI20->db->where('bp_period_year', $bp_year);
            $CI20->db->where('bp_zone_id', $customerinfodata->zone);
            $bp = $CI20->db->get('tbl_billing_period')->row();
            $bp_id = $bp->bp_id;

            $customerinfodataInsertDetails = array( 
                'customer_id' => $customerinfodata->customer_id,
                'month' => $bp_month, 
                'year' => $bp_year, 
                'bp_id' => $bp_id, 
                
            ); 
            $checkresult_id = check_customerbillingrecord($customerinfodata->customer_id,$bp_id,$bp_month,$bp_year);
			if($checkresult_id){
               
                
                
            }else{
                $CI3 = &get_instance();
                $CI3->db->where('doc_name', 'BILLING');
                $billing_number = $CI3->db->get('tbl_doc_series_number')->row();
                $doc_num = $billing_number->doc_series_num+1;
                $customerinfodataInsertDetails1 = array( 
                    'refno' => $doc_num,
                    'customer_status' => $customerinfodata->status
                );
                $customerinfodataInsertDetails = array_merge($customerinfodataInsertDetails,$customerinfodataInsertDetails1);
                $CI2 = &get_instance();
                $CI2->db->insert('tbl_addcustomer_reading', $customerinfodataInsertDetails);
                $checkresult_id = $CI2->db->insert_id();

                $update_counter_array = array( 
                    'doc_series_num' => $doc_num
                );
                $C5 = &get_instance();
                $C5->db->where('doc_name', 'BILLING');
                $C5->db->update('tbl_doc_series_number', $update_counter_array);
                
            }

            $customer_current_billing_data = currentbalance_forwarding_period($customerinfodata->customer_id,$bp_current_month,$bp_current_year);
            
            if($customer_current_billing_data->id){
                if($customer_current_billing_data->invoice_id!=NULL && $customer_current_billing_data->invoice_id!=''){
                    $arrears = 0;
                }else{
                    $arrears = $customer_current_billing_data->penalty;
                }
                $maintenance_fee = get_maintenance_fee();
                
                // Calculate franchise fee
                $franchise_fee_percentage = get_franchise_fee_percentage();
                $unit_price = isset($customer_current_billing_data->unit_price) ? $customer_current_billing_data->unit_price : 0;
                $sc_discount = isset($customer_current_billing_data->sc_discount) ? $customer_current_billing_data->sc_discount : 0;
                
                if($customerinfodata->account_type == 3){
                    // SC: compute after SC deduction
                    $bill_amount_for_franchise = $unit_price - $sc_discount;
                } else {
                    // Non-SC: compute on unit price
                    $bill_amount_for_franchise = $unit_price;
                }
                $franchise_fee_amount = ($bill_amount_for_franchise * $franchise_fee_percentage) / 100;
                
                $update_counter_array1 = array( 
                    'previous_reading' => $customer_current_billing_data->reading,
                    'arrears' => $arrears,
                    'customer_status' => $customerinfodata->status,
                    'maintenance_fee' => $maintenance_fee,
                    'franchise_fee_percent' => number_format($franchise_fee_percentage, 2, '.', ''),
                    'franchise_fee_amount' => number_format($franchise_fee_amount, 2, '.', ''),
                );
                $C5 = &get_instance();
                $C5->db->where('id', $checkresult_id);
                $C5->db->update('tbl_addcustomer_reading', $update_counter_array1);
            }
            

            
            
		}
        return true;
       
    }
}

if (!function_exists("check_customerbillingrecord")) {
    function check_customerbillingrecord($customer_id,$bp_id,$bp_month,$bp_year) {
        $CI9 = &get_instance();
        $CI9->db->select('id');
        //$CI->db->from("tbl_addcustomer_reading");
        $CI9->db->where('customer_id', $customer_id); // Example condition
        $CI9->db->where('bp_id', $bp_id); // Example condition
        $CI9->db->where('month', $bp_month); // Example condition
        $CI9->db->where('year', $bp_year); // Example condition
        //$row_count = $CI9->db->count_all_results('tbl_addcustomer_reading');
        $query = $CI9->db->get('tbl_addcustomer_reading');
		$result = $query->row();
       
        return $result->id;
        
    }

}

if(!function_exists("currentbalance_forwarding_period")){
    function currentbalance_forwarding_period($customer_id,$billingmonth,$billingyear, $status=''){
        $CI = &get_instance();
        $CI->db->select("
        tbl_addcustomer.address,
        tbl_addcustomer.customer_id,
        tbl_addcustomer.first_name,
        tbl_addcustomer.last_name, 
        tbl_zone.zone as zonename,
        tbl_addcustomer_reading.* ,
        tbl_addmetercustomer.invoice_id
        ");
		$CI->db->from("tbl_addcustomer_reading");
			
		
		$CI->db->join("tbl_addmetercustomer", 'tbl_addcustomer_reading.customer_id=tbl_addmetercustomer.customer_id AND tbl_addcustomer_reading.month=tbl_addmetercustomer.month AND tbl_addcustomer_reading.year=tbl_addmetercustomer.year','left');

		$CI->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
		$CI->db->join('tbl_zone', 'tbl_addcustomer.zone=tbl_zone.id','left');

		
		if($billingmonth !='' && $billingyear !=''){
			$CI->db->where("tbl_addcustomer_reading.month",$billingmonth);
			$CI->db->where("tbl_addcustomer_reading.year",$billingyear);
		}
		
		
		if($customer_id !=''){
			$CI->db->where('tbl_addcustomer_reading.customer_id',$customer_id);
		}	
		/*if($zone !='all'){
			$CI->db->where('tbl_addcustomer.zone',$zone);
		}*/	
		if($status =='unpaid'){
            $CI->db->where('tbl_addmetercustomer.invoice_id IS NULL');
        }
		$CI->db->order_by('tbl_addcustomer.last_name','ASC');
		$CI->db->order_by('tbl_addcustomer.first_name','ASC');
		$query = $CI->db->get();
		$result = $query->row();
		return $result;		
    }
}

if(!function_exists("isValidMySQLDate")){
    function isValidMySQLDate($date) {
        $format = 'Y-m-d'; // MySQL DATE format
        $d = DateTime::createFromFormat($format, $date);
        
        return $d && $d->format($format) === $date;
    }
}

if(!function_exists("customer_id_generate")){
    function customer_id_generate($class_code='000', $zone_code='000') {
        // NOTE: validation/guards temporarily disabled per request.
        //if($class_code!=='000' && $class_code!==''){
        //    $CI1 = &get_instance();
        //    $CI1->db->where('class_id', $class_code);
        //    $classification = $CI1->db->get('tbl_classification')->row();
        //    if($classification && isset($classification->class_code)){
        //        $class_code = $classification->class_code;
        //    } else {
        //        $class_code = '000';
        //    }
        //} else {
        //    $class_code = '000';
        //}
       
        // NOTE: validation/guards temporarily disabled per request.
        //if($zone_code!=='000' && $zone_code!==''){
        //    $CI2 = &get_instance();
        //    $CI2->db->where('id', $zone_code);
        //    $zone = $CI2->db->get('tbl_zone')->row();
        //    if($zone && isset($zone->zone_code) && $zone->zone_code != '' && $zone->zone_code != null){
        //        $zone_code = trim($zone->zone_code);
        //    } else {
        //        log_message('warning', 'Zone code not found for zone_id: ' . $zone_code . '. Zone exists: ' . ($zone ? 'Yes' : 'No'));
        //        $zone_code = '000';
        //    }
        //} else {
        //    $zone_code = '000';
        //}
        //
        //// Ensure zone_code is never empty
        //if(empty($zone_code) || $zone_code == ''){
        //    $zone_code = '000';
        //}
        
        // Original behavior (no validation/guards):
        if($class_code!=='000'){
            $CI1 = &get_instance();
            $CI1->db->where('class_id', $class_code);
            $classification = $CI1->db->get('tbl_classification')->row();
            $class_code = $classification->class_code;
        }
       
        if($zone_code!=='000'){
            $CI2 = &get_instance();
            $CI2->db->where('id', $zone_code);
            $zone = $CI2->db->get('tbl_zone')->row();
            $zone_code = $zone->zone_code;
        }
        

        // NOTE: validation/guards temporarily disabled per request.
        //$CI3 = &get_instance();
        //$CI3->db->where('doc_name', 'MEMBER');
        //$member_number = $CI3->db->get('tbl_doc_series_number')->row();
        //if($member_number && isset($member_number->doc_series_num)){
        //    $doc_num = $member_number->doc_series_num+1;
        //} else {
        //    $doc_num = 1;
        //}
        //
        //// Ensure all segments have valid values
        //$class_code = empty($class_code) ? '000' : trim($class_code);
        //$zone_code = empty($zone_code) ? '000' : trim($zone_code);
        //
        //return $class_code.'-'.$zone_code.'-'.sprintf('%05d',$doc_num);
        
        // Original behavior:
        $CI3 = &get_instance();
        $CI3->db->where('doc_name', 'MEMBER');
        $member_number = $CI3->db->get('tbl_doc_series_number')->row();
        $doc_num = $member_number->doc_series_num+1;
        
        return $class_code.'-'.$zone_code.'-'.sprintf('%05d',$doc_num);
    }
}

if(!function_exists("detailsbillingpayment")){
    function detailsbillingpayment($invoice_id) {
        $str_invoicepayment ='';
        $CI = &get_instance();
        $CI->db->select('tbl_addmetercustomer.*,tbl_months.month_name as monthname');
        $CI->db->from('tbl_addmetercustomer');
        $CI->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
        $CI->db->where('invoice_id', $invoice_id);
        $paymentdetailsinfo = $CI->db->get()->result();
        
        foreach($paymentdetailsinfo as $paymentdetailsinfodata){
            $CI1 = &get_instance();
            $CI1->db->select('tbl_months.month_id as monthid, 
            (SELECT unit_price FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$paymentdetailsinfodata->customer_id.'" and tbl_addcustomer_reading.month="'.$paymentdetailsinfodata->month.'" and tbl_addcustomer_reading.year="'.$paymentdetailsinfodata->year.'") as reading_amount,
            tbl_months.month_name as monthname,tbl_addmetercustomer.id as ine_id,tbl_addmetercustomer.invoice_id as invoice_ids,tbl_addmetercustomer.date as tdate, tbl_addmetercustomer.*');				   
            $CI1->db->from('tbl_addmetercustomer');
            $CI1->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
            $CI1->db->where('customer_id',$paymentdetailsinfodata->customer_id);
            $CI1->db->where('month',$paymentdetailsinfodata->month);
            $CI1->db->where('year',$paymentdetailsinfodata->year);
            $query = $CI1->db->get()->row_array();
            extract($query);
            $panalty_msg ='';
            if($amount !== $reading_amount){
                $penalty = $amount - $reading_amount;
                //$amount = $penalty;
                $panalty_msg = '<span style="font-size:9px;line-height:8px;"><br/>Penalty = 10% = '. number_format($reading_amount,2).' + '.number_format($penalty,2).'</span>';
            }
            $str_invoicepayment .="<tr>
									<td>$paymentdetailsinfodata->monthname $paymentdetailsinfodata->year $panalty_msg</td>
									<td></td>
									<td align=right valign=top>$paymentdetailsinfodata->consumedunits</td>
									<td align=right valign=top style='text-align:right; width: 70px;'>$paymentdetailsinfodata->amount</td>
								</tr>";
        }
        
        return $str_invoicepayment;
    }
}

if(!function_exists("detailsbillingpayment_ver1")){
    function detailsbillingpayment_ver1($invoice_id) {
        $str_invoicepayment ='';
        $CI = &get_instance();
        $CI->db->select('tbl_addmetercustomer.*,tbl_months.month_name as monthname');
        $CI->db->from('tbl_addmetercustomer');
        $CI->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
        $CI->db->where('invoice_id', $invoice_id);
        $paymentdetailsinfo = $CI->db->get()->result();
        
        foreach($paymentdetailsinfo as $paymentdetailsinfodata){
            $CI1 = &get_instance();
            $CI1->db->select('tbl_months.month_id as monthid, 
            (SELECT unit_price FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$paymentdetailsinfodata->customer_id.'" and tbl_addcustomer_reading.month="'.$paymentdetailsinfodata->month.'" and tbl_addcustomer_reading.year="'.$paymentdetailsinfodata->year.'") as reading_amount,
            (SELECT maintenance_fee FROM tbl_addcustomer_reading WHERE tbl_addcustomer_reading.customer_id="'.$paymentdetailsinfodata->customer_id.'" and tbl_addcustomer_reading.month="'.$paymentdetailsinfodata->month.'" and tbl_addcustomer_reading.year="'.$paymentdetailsinfodata->year.'") as maintenance_fee,
            tbl_months.month_name as monthname,tbl_addmetercustomer.id as ine_id,tbl_addmetercustomer.invoice_id as invoice_ids,tbl_addmetercustomer.date as tdate, tbl_addmetercustomer.*');				   
            $CI1->db->from('tbl_addmetercustomer');
            $CI1->db->join('tbl_months','tbl_addmetercustomer.month = tbl_months.month_id');
            $CI1->db->where('customer_id',$paymentdetailsinfodata->customer_id);
            $CI1->db->where('month',$paymentdetailsinfodata->month);
            $CI1->db->where('year',$paymentdetailsinfodata->year);
            $query = $CI1->db->get()->row_array();
            extract($query);
            $panalty_msg ='<span style="font-size:9px;line-height:8px;"><br/>(Bill Amt: '.number_format($reading_amount,2).')';
            if($maintenance_fee>0.00){
                    $panalty_msg .= 'WMMF = +'. number_format($maintenance_fee,2).'/';
                    $amount -= $maintenance_fee;
                }
            if($amount !== $reading_amount){
                
                $penalty = $amount - $reading_amount;
                if($penalty>0){
                    //$amount = $penalty;
                    $panalty_msg .= 'Penalty = +'.number_format($penalty,2).'/';
                }
                
            }
            $panalty_msg .= '</span>';
            $str_invoicepayment .="<tr>
									<td  style='width: 75%;'>$paymentdetailsinfodata->monthname $paymentdetailsinfodata->year $panalty_msg</td>
									<td style='width: 5%;'>$paymentdetailsinfodata->consumedunits</td>
									<td  style='text-align: right;'>$paymentdetailsinfodata->amount</td>
								</tr>";
        }
        
        return $str_invoicepayment;
    }
}

if(!function_exists("get_customer_unpaid_records")){
    function get_customer_unpaid_records($customer_id='',$billingmonth='',$billingyear=''){ 
        $CI = &get_instance();
        $CI->db->select("tbl_addcustomer.address, tbl_addcustomer.customer_id, tbl_addcustomer.first_name, tbl_addcustomer.last_name, tbl_addcustomer_reading.*, tbl_zone.zone as zonename");
        $CI->db->from("tbl_addcustomer_reading");
            
        
        $CI->db->join('tbl_addmetercustomer', 'tbl_addcustomer_reading.customer_id=tbl_addmetercustomer.customer_id AND tbl_addcustomer_reading.month=tbl_addmetercustomer.month AND tbl_addcustomer_reading.year=tbl_addmetercustomer.year','left');

        $CI->db->join('tbl_addcustomer', 'tbl_addcustomer_reading.customer_id=tbl_addcustomer.customer_id','left');
        $CI->db->join('tbl_zone', 'tbl_addcustomer.zone='.'tbl_zone.id','left');

        
        if($billingmonth !='' && $billingyear !=''){
            $CI->db->where("tbl_addcustomer_reading.month",$billingmonth);
            $CI->db->where("tbl_addcustomer_reading.year",$billingyear);
        }
        
        
        if($customer_id !=''){
            $CI->db->where('tbl_addcustomer.customer_id',$customer_id);
        }	
        $CI->db->where('tbl_addmetercustomer.invoice_id IS NULL');
        $query = $CI->db->get();
        $result = $query->row()->penalty;
        
        return $result;		
    }
}

if (!function_exists("get_maintenance_fee")) {
    function get_maintenance_fee() {
        $CI = &get_instance();
        $CI->db->select('value');
        $CI->db->from('tbl_global_settings');
        $CI->db->where('code', 'MAINTENANCE_FEE');
        $query = $CI->db->get();
        $result = $query->row();
        
        if($result && isset($result->value)){
            return number_format($result->value, 2, '.', '');
        }
        
        // Default fallback value if setting doesn't exist
        return '25.00';
    }
}

if (!function_exists("get_franchise_fee_percentage")) {
    function get_franchise_fee_percentage() {
        $CI = &get_instance();
        $CI->db->select('value');
        $CI->db->from('tbl_global_settings');
        $CI->db->where('code', 'FRANCHISE_FEE_PERCENTAGE');
        $query = $CI->db->get();
        $result = $query->row();
        
        if($result && isset($result->value)){
            return floatval($result->value);
        }
        
        // Default fallback value if setting doesn't exist
        return 2.00;
    }
}

if (!function_exists("calculate_franchise_fee")) {
    function calculate_franchise_fee($unit_price, $sc_discount = 0, $account_type = 0) {
        $percentage = get_franchise_fee_percentage();
        
        // If SC account (account_type == 3), compute after SC deduction
        if($account_type == 3) {
            $bill_amount = $unit_price - $sc_discount;
        } else {
            $bill_amount = $unit_price;
        }
        
        $franchise_fee_amount = ($bill_amount * $percentage) / 100;
        
        return array(
            'percent' => number_format($percentage, 2, '.', ''),
            'amount' => number_format($franchise_fee_amount, 2, '.', '')
        );
    }
}


