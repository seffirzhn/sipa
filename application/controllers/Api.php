<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MX_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){	
		$response["message"] = "API Works";
		$response["status"]	 = 200;
			
			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
			header("Content-Type: application/json");
            echo json_encode($response,JSON_PRETTY_PRINT);
	}
	
	function getApp($id_opd=null){
		$req_header					= apache_request_headers();
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Authorization,token");
		if($id_opd!=null){
			$data = $this->db->get_where("data_aplikasi",array("id_opd"=>$id_opd));	
			$this->db->select("da.*, mjl.jenis_layanan")
					->from("data_aplikasi as da")
					->join("master_jenis_layanan as mjl","mjl.id_jenis_layanan=da.id_jenis_layanan")
					->where("da.id_opd",$id_opd);
			$data = $this->db->get();
		}else{
			//$data = $this->db->get("data_aplikasi");
			$this->db->select("da.*, mjl.jenis_layanan")
					->from("data_aplikasi as da")
					->join("master_jenis_layanan as mjl","mjl.id_jenis_layanan=da.id_jenis_layanan");
			$data = $this->db->get();
		}
		$response["data"] = $data->result_array();
		$response["message"] = "success";
		$response["status"]	 = 200;
	 		header("Content-Type: application/json");
            echo json_encode($response,JSON_PRETTY_PRINT);
	}

}
