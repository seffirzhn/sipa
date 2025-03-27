<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_online extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("users_online_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "times"=>array("text"=>"TERAKHIR ONLINE","sorting"=>"true","width"=>13,"align"=>"center"),
                            "user_name"=>array("text"=>"PENGGUNA","sorting"=>"true","width"=>20,"align"=>"left"),
                            "name"=>array("text"=>"NAMA","sorting"=>"true","width"=>35,"align"=>"left"),
                            "group_name"=>array("text"=>"GRUP","sorting"=>"true","width"=>30,"align"=>"left"),
                        );
        $this->module   = "users_online";
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->users_online_model->getDatagrid($param);
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'));
        $param["listactive"]    = array(""=>"--- Pilih Status ---","1"=>"Aktif","0"=>"Tidak Aktif");
        $param["listgroup"]     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name",array(""=>"--- Pilih Grup ---"));
        $param["column"]        = $this->column;
        $konten                 = 'users_online_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
