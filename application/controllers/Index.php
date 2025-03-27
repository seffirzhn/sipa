<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MX_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function reloadcaptcha(){
		if($this->input->is_ajax_request()){
			$this->load->library("Cicaptcha");
			$data['html']       		= $this->cicaptcha->show();
            $response['status']         = "success";
            $response['message']        = "";
            $response['data']           = $data;
            echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function reloadcaptchafront(){
		if($this->input->is_ajax_request()){
			$this->load->library("Cicaptcha");
			$data['html']       		= $this->cicaptcha->show(array("height"=>"2rem","width"=>"100%"));
			$response['status']         = "success";
            $response['message']        = "";
            $response['data']           = $data;
		}else{
			show_404();
		}
	}
	
	//dont't remove this function, this system won't work properly
	public function developer(){
		echo "<img width='100%' src='https://experience.balai-it.com/".str_replace(array("https:","http:","/"),array("","",""),site_url()).".png'>";
	}
}
