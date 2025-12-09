<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**

 * content Controller 

 * @author		Spark(yasin)

 * @copyright	Copyright (c) 2013, Sparkinfosys.com

 * @version		2.0

 * @package		Ecommerce

 * @subpackage  content 

 * @link         

 * */

class web_settings extends CI_Controller {

	// Declare globle variable here

	public $headerPage = '../../views/admin-includes/header';

	public $seoPage = '../../views/admin-includes/seo-standards';

	public $table_name = 'tbl_content';

	//set List page

	public $listPage = 'web_settings';

	//set Add page

	public $addPage  = 'web_settings-add';

	//set Edit page

	public $editPage = 'web_settings-edit';

	//set View page

	public $viewPage = 'web_settings-view';

	//set Redirect page to list page

	public $listPage_redirect = '/master/web_settings';

	//set Redirect page to add page

	public $addPage_redirect = '/master/web_settings/add/';

	//set Redirect page to edit page

	public $editPage_redirect = '/master/web_settings/edit/';

	

	// Autoloading a system library usin constructor method

	public function __construct() {

        parent::__construct();

  		$this->load->model('web_settings_model','my_model'); // Loading the dashboard Model		
		//$this->load->model('common_model','comm_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;">', '</div>');
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		error_reporting(0);
		ini_set('display_errors','off'); 				
		$this->load->model('adminheader_model','top_model');
    }

	/** Default Function **/

	public function index(){ 
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_all_records();
		$this->load->view($this->headerPage,$this->head);

		$this->load->view($this->listPage,$data);

	}

	/** View Function **/

	public function view($id){ 

		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);
		$this->head['leftCounts'] = $this->count_model->get_left_count();
		$this->load->view($this->headerPage,$this->head);

		$this->load->view($this->viewPage,$data);

	}

	

	/** Add Function **/

	public function add(){ 

		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		if ($this->form_validation->run('content') == TRUE) {

		//if($this->input->post('attributes_groups') != ''){

			$exit_data = array(

							'attribute_group_name' => mysql_escape_string(ucwords($this->input->post('attribute_group_name')))

						  );				

			$exit_details = $this->my_model->exit_details($exit_data);

			if($exit_details == 0){

				$result = $this->my_model->add_record();

				if($result){

					$this->session->set_flashdata('msg_succ', 'Inserted Successfully...');

					redirect($this->listPage_redirect);

				}else{

					$data['msg'] = "Not Inserted...";

				}

			}else{

					$data['msg'] = "Already Exists...";

			}

		}
		$this->head['leftCounts'] = $this->count_model->get_left_count();
		$this->load->view($this->headerPage,$this->head);

		$data['seo_stands'] = $this->load->view($this->seoPage, '', TRUE);

		$this->load->view($this->addPage,$data);

	}

	

	/** Edit Function **/

	public function edit($id){ 

		$data['msg'] ='';
		$this->head['roleResponsible'] = $this->top_model->get_responsibilities();
		$data['record'] = $this->my_model->get_single_record($id);


		if($this->input->post('add') != ''){

			$result = $this->my_model->update_record($id);

			if($result){

				$this->session->set_flashdata('msg_succ', 'Updated Successfully...');

				redirect($this->listPage_redirect);

			}else{

				$data['msg'] = "Not Updated...";

			}

		}
		$this->load->view($this->headerPage,$this->head);

		$this->load->view($this->editPage,$data);

	}

	

	/** Delete Function **/

	public function delete($id){ 

		$data['msg'] ='';
		if($id){

			$result = $this->my_model->delete_record($id);

			if($result){

				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');

				redirect($this->listPage_redirect);

			}else{

				$data['msg'] = "Not Deleted...";

			}

		}

	}

	/** Multiple Delete Function **/

	public function multi_delete(){

		$data['msg'] ='';

		if($this->input->post('delete_ids') != ''){

			$delete_ids = $this->input->post('delete_ids');

			for($i=0;$i<count($delete_ids);$i++){

				$result = $this->my_model->delete_record($delete_ids[$i]);

			}

			if($result){

				$this->session->set_flashdata('msg_succ', 'Deleted Successfully...');

				redirect($this->listPage_redirect);

			}else{

				$this->session->set_flashdata('msg_succ', 'Not Deleted...');

				redirect($this->listPage_redirect);

			}

		}else{

			$this->session->set_flashdata('msg_succ', 'Select any Check Box...');

			redirect($this->listPage_redirect);

		}

	}



}

?>