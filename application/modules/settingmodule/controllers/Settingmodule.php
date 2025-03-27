<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settingmodule extends MX_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->auth_model->checkIslogout();
        $this->load->model("settingmodule_model");
        $this->module   = "settingmodule";
    }

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $modulecrud             = $this->auth_model->getModulecrud($this->module);
        $group_id               = trim($this->input->post("group_id"));
        $getData                = $this->settingmodule_model->getDatagrid(array(),$modulecrud["acl_update"],$group_id);
        $data["recordsTotal"]   = count($getData);
        $data["grid"]           = $getData;
        $response["status"]     = "success";
        $response["message"]    = "Get data acls";
        $response["data"]       = $data; 
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response               = $this->settingmodule_model->updateData();
        echo json_encode($response);
    }

    function index()
    {
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),'update'=>site_url($this->module."/updatedata"));
        $param["listgroup"]     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name",array(""=>"--- Pilih Grup ---"));
        $konten                 = 'settingmodule_page';
        $this->auth_model->buildViewAdmin($konten,$param);
    }
}
?>
