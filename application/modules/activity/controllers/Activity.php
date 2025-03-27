<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("activity_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "checklist"=>array("text"=>'<div class="checkbox"><input id="for_cek_all" type="checkbox" ><label for="for_cek_all"></label></div>',"sorting"=>"false","width"=>5,"align"=>"center"),
                            "created_on"=>array("text"=>"WAKTU","sorting"=>"true","width"=>13,"align"=>"center","type_sort"=>"desc"),
                            "user_name"=>array("text"=>"PENGGUNA","sorting"=>"true","width"=>18,"align"=>"left"),
                            "activity"=>array("text"=>"AKTIFITAS","sorting"=>"true","width"=>60,"align"=>"left"),
                        );
        $this->module   = "activity";	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->activity_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response               = $this->activity_model->deleteData();
        echo json_encode($response);
    }

    function deleteall(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response               = $this->activity_model->deleteAlldata();
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"deletebyselect"=>site_url($this->module.'/deletedata'),"deleteall"=>site_url($this->module.'/deleteall'));
        $param["column"]        = $this->column;
        $konten                 = 'activity_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
