<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("groups_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "group_name"=>array("text"=>"GRUP","sorting"=>"true","width"=>20,"align"=>"left"),
                            "group_description"=>array("text"=>"KETERANGAN","sorting"=>"true","width"=>68,"align"=>"left"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "groups";
        $this->configinput = array(
                            array(
                                'field' => 'newgroup_name',
                                'label' => 'Grup',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newrole_name',
                                'label' => 'Role Name Mobile',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->groups_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->groups_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->groups_model->getDatabyid();
        $param["group_name"]        = $dt["group_name"]?:"";
        $param["role_name"]         = $dt["role_name"]?:"";
        $param["group_description"] = $dt["group_description"]?:"";
        $param["ismobile"]          = $dt["ismobile"]?:"0";
        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('groups_form',$param,true);
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->groups_model->createData();
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->groups_model->updateData();
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
        $konten                 = 'groups_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
