<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class send_sms_notification extends CI_Controller {
	public function index() {
		$this->output
			->set_status_header(410)
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => false,
				'message' => 'This legacy SMS endpoint is no longer active. Use the Mobile Notifications module.'
			)));
	}
}
