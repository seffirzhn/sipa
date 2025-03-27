<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_jenis_nilai extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("master_jenis_nilai_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "nama_jenis_nilai"=>array("text"=>"Jenis Penilaian","sorting"=>"true","width"=>28,"align"=>"left"),
                            "bobot"=>array("text"=>"Bobot","sorting"=>"true","width"=>15,"align"=>"center"),
                            "active"=>array("text"=>"Status","sorting"=>"true","width"=>10,"align"=>"center"),
                            "keterangan"=>array("text"=>"Ket.","sorting"=>"true","width"=>15,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "master_jenis_nilai";
        $this->configinput = array(
                            array(
                                'field' => 'newjenis_nilai',
                                'label' => 'Jenis Penilaian',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newbobot',
                                'label' => 'Bobot',
                                'rules' => 'trim|required|numeric'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->master_jenis_nilai_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->master_jenis_nilai_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->master_jenis_nilai_model->getDatabyid();
        $param["id_master_seleksi"] = $dt["id_master_seleksi"]?:"";
        $param["nama_jenis_nilai"]     = $dt["nama_jenis_nilai"]?:"";
        $param["bobot"]         = $dt["bobot"]?:"";
        $param["keterangan"]         = $dt["keterangan"]?:"";
        $param["active"]        = isset($dt["is_active"])?$dt["is_active"]:"1";
        $data['html']               = $this->load->view('master_jenis_nilai_form',$param,true);
        $data["id"]                 = ($dt==null) ? "":$id;
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->master_jenis_nilai_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->master_jenis_nilai_model->updateData($fileberkas);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["column"]        = $this->column;
        $konten                 = 'master_jenis_nilai_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
