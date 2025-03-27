<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guides extends MX_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->auth_model->checkIslogout();
        $this->load->model("guides_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "name"=>array("text"=>"PANDUAN","sorting"=>"true","width"=>50,"align"=>"left"),
                            "group_name"=>array("text"=>"GRUP","sorting"=>"true","width"=>28,"align"=>"left"),
                            "status"=>array("text"=>"STATUS","sorting"=>"false","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "guides";
        $this->configinput = array(
                            array(
                                'field' => 'newname',
                                'label' => 'Panduan',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newdescription',
                                'label' => 'Deskripsi',
                                'rules' => 'trim|required'
                            ),
                        );
    }

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->guides_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response               = $this->guides_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                     = $this->guides_model->getDatabyid();
        $param["id"]            = $dt["guide_id"]?:"0";
        $param["name"]          = $dt["name"]?:"";
        $param["description"]   = $dt["description"]?:"";
        $param["status"]        = isset($dt["status"])?$dt["status"]:"y";
        $param["liststatus"]    = array("y"=>"Active","n"=>"Non Active");
        $param['listgroup']     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name");
        $param['group_id']      = $this->global_model->getDataonedimension("guides_groups","group_id","guide_id='".$param["id"]."'");
        $data["id"]             = ($dt==null) ? "":$id;
        $data['html']           = $this->load->view('guides_form',$param,true);
        $response['status']     = "success";
        $response['message']    = "Get Form";
        $response['data']       = $data;
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->guides_model->createData();
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->guides_model->updateData();
        }
        echo json_encode($response);
    }

    function index()
    {
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["listactive"]    = array(""=>"--- Pilih Status ---","y"=>"Active","n"=>"Non Active");
        $param["listgroup"]     = $this->global_model->getDatatwodimension("groups","*",null,"group_id","group_name",array(""=>"--- Pilih Grup ---"));
        $param["column"]        = $this->column;
        $konten                 = 'guides_page';
        $this->auth_model->buildViewAdmin($konten,$param);
    }
}
?>
