<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("modules_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "module_name"=>array("text"=>"MODUL","sorting"=>"true","width"=>24,"align"=>"left"),
                            "module_parent"=>array("text"=>"INDUK MODUL","sorting"=>"true","width"=>24,"align"=>"left"),
                            "module_link"=>array("text"=>"TAUTAN","sorting"=>"true","width"=>20,"align"=>"left"),
                            "module_order"=>array("text"=>"URUTAN","sorting"=>"true","width"=>10,"align"=>"right"),
                            "module_icon"=>array("text"=>"IKON","sorting"=>"false","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "module";
        $this->configinput = array(
                            array(
                                'field' => 'newmodule_name',
                                'label' => 'Modul',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newmodule_parent',
                                'label' => 'Induk Modul',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newmodule_link',
                                'label' => 'Tautan',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newmodule_order',
                                'label' => 'Urutan',
                                'rules' => 'trim|required|numeric'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->modules_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->modules_model->deleteData();
        echo json_encode($response);
    }
    
    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                     = $this->modules_model->getDatabyid();
        $param["module_id"]     = $dt["module_id"]?:"";
        $param["module_name"]   = $dt["module_name"]?:"";
        $param["module_parent"] = $dt["module_parent_id"]?:"0";
        $param["module_link"]   = $dt["module_link"]?:"";
        $param["module_order"]  = $dt["module_order"]?:"";
        $param["module_icon"]   = $dt["module_icon"]?:"";
        $param['listparent']    = $this->global_model->getDatatwodimension("modules","module_id,concat(module_order,' - ',module_name) as module_name","module_id<>'".$param['module_id']."' order by module_order asc","module_id","module_name",array("0"=>"Root"));
        $data["id"]             = ($dt==null) ? "":$id;
        $data['html']           = $this->load->view('modules_form',$param,true);
        $response['status']     = "success";
        $response['message']    = "Get Form";
        $response['data']       = $data;
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->modules_model->createData();
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->modules_model->updateData();
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
        $konten                 = 'modules_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
