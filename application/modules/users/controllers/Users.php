<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("users_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "user_name"=>array("text"=>"PENGGUNA","sorting"=>"true","width"=>22,"align"=>"left"),
                            "name"=>array("text"=>"NAMA","sorting"=>"true","width"=>24,"align"=>"left"),
                            "group_name"=>array("text"=>"GRUP","sorting"=>"true","width"=>20,"align"=>"left"),
                            "nama_opd"=>array("text"=>"OPD","sorting"=>"true","width"=>14,"align"=>"left"),
                            "active"=>array("text"=>"STATUS","sorting"=>"false","width"=>8,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "users";
        $this->configinput = array(
                            array(
                                'field' => 'newuser_name',
                                'label' => 'Nama Pengguna',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newname',
                                'label' => 'Nama',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newgroup[]',
                                'label' => 'Grup',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newactive',
                                'label' => 'Status',
                                'rules' => 'trim'
                            ),
                            
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->users_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response               = $this->users_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        if($this->encryption->decrypt($id)==$this->session->userdata('user_id')){
            $response['status']   = "warning";
            $response['message']  = 'Maaf, anda tidak diperkenankan memperbarui data user anda';
        }else{
            $dt                     = $this->users_model->getDatabyid();
            $param["id"]            = $dt["user_id"]?:"0";
            $param["id_daerah"]     = $dt["id_daerah"]?:"0";
            $param["id_opd"]     = $dt["id_daerah"]?:"0";
            $param["required"]      = $dt["user_id"]?"false":"true";
            $param["user_name"]     = $dt["user_name"]?:"";
            $param["name"]          = $dt["name"]?:"";
            $param["active"]        = isset($dt["active"])?$dt["active"]:"1";
            $param["email"]         = $dt["email"]?:"";
            $param['listgroup']     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name");

            $param["listdaerah"]    = $this->global_model->getDatatwodimension("master_daerah","*",null,"id_daerah","nama_daerah",array(""=>"---Pilih Daerah---"));
            $param["listopd"]    = $this->global_model->getDatatwodimension("master_opd","*",null,"id_opd","nama",array(""=>"---Pilih OPD---"));
            $param['group_id']      = $this->global_model->getDataonedimension("users_groups","group_id",array("user_id"=>$param["id"]));
            $data["id"]             = ($dt==null) ? "":$id;
            $data['html']           = $this->load->view('users_form',$param,true);
            $response['status']     = "success";
            $response['message']    = "Get Form";
            $response['data']       = $data;
        }
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $this->configinput[]    = array(
                                'field' => 'newpass',
                                'label' => 'Katasandi',
                                'rules' => 'trim|min_length[8]|required'
                            );
        $this->configinput[]    = array(
                                'field' => 'newkonfirmpass',
                                'label' => 'Konfirmasi Katasandi',
                                'rules' => 'trim|min_length[8]|matches[newpass]|required'
                            );
                            
        $response               = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response           = $this->users_model->createData();
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $this->configinput[]    = array(
                                'field' => 'newpass',
                                'label' => 'Katasandi',
                                'rules' => 'trim|min_length[8]'
                            );
        $this->configinput[]    = array(
                                'field' => 'newkonfirmpass',
                                'label' => 'Konfirmasi Katasandi',
                                'rules' => 'trim|min_length[8]|matches[newpass]'
                            );
        $response               = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response           = $this->users_model->updateData();
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["listactive"]    = array(""=>"--- Pilih Status ---","1"=>"Aktif","0"=>"Tidak Aktif");
        $param["listgroup"]     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name",array(""=>"--- Pilih Grup ---"));
        $param["column"]        = $this->column;
        $konten                 = 'users_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
