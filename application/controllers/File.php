<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends MX_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function download(){

		$lk = trim($this->input->get("lk"));
		$fl = trim($this->input->get("fl"));

		if($lk!=""){
			redirect($this->encryption->decrypt($lk));
		}else if($fl!=""){
			$file = $this->encryption->decrypt($fl);
			if(file_exists(realpath($file))){
				$this->load->helper('download');
				if(!$this->session->userdata($file)){
					$this->db->where(array("file"=>$file))->set("download","download+1",false)->update("download");
					$this->session->set_userdata($file,1);
				}
				force_download(realpath($file),null);
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

	public function download_lampiran(){
		$file = trim($this->input->get("file"));
		if($file!=""){
			$file = str_replace(base_url(), "./", $file);
			if(realpath($file)){
				$this->load->helper('download');
				force_download(realpath($file),NULL); 
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

	public function readfile(){
		$fl 	= trim($this->input->get("fl"));
		if($fl!=""){
			readfile($fl);
		}else{
			show_404();
		}
	}

	public function read(){
		$fl 	= trim($this->input->get("fl"));
		$title 	= trim($this->input->get("title"));
		$link 	= trim($this->input->get("link"));
		$file 	= $this->encryption->decrypt($fl);
		if($file!=""){
			$param["source"]		= "";
			if(strpos(strtolower($file),strtolower(base_url()))!== false){
				$param['file']		= $file;
				$param["source"]	= "";
			}else{
				$param['file']		= site_url("file/readfile")."?fl=".$file; 
				$param["source"]	= $file;
			}
			$param["link"]			= ($link)?:site_url();
			$param["nametitle"] 	= ($title)?:"Baca File";
        	$param['configcompany'] = $this->global_model->getConfigcompany();  
			$param['configsite']    = $this->global_model->getConfigsite();   
			$this->load->view('front/_shared/read_file_pdf',$param);
		}else{
			show_404();
		}
	}
	
	public function test(){
		$nip     = '199411252022021001';
				$config["uri"]=API_URI."rest/api/getPegawai";
				$config["header"] = array("token:D7ypX0Rlg9fSSLr37aogBucOC6QaBIigT9yCb3VJ");
				$config["post"] = array("nip"=>$nip);
				$result = $this->global_model->getDatafromservice($config);
				$data = json_decode($result);
				echo $result;
	}

}
