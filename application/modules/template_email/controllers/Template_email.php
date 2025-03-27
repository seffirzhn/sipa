<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_email extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("template_email_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "nama"=>array("text"=>"TEMPLATE","sorting"=>"true","width"=>88,"align"=>"left"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "template_email";
        $this->configinput = array(
                            array(
                                'field' => 'newnama',
                                'label' => 'Template',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpesan',
                                'label' => 'Pesan',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->template_email_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->template_email_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                     = $this->template_email_model->getDatabyid();
        $param["nama"]          = $dt["nama"]?:"";
        $param["pesan"]         = $dt["pesan"]?:"";
        $data["id"]             = ($dt==null) ? "":$id;
        $data['html']           = $this->load->view('template_email_form',$param,true);
        $response['status']     = "success";
        $response['message']    = "Get Form";
        $response['data']       = $data;
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->template_email_model->updateData();
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["column"]        = $this->column;
        $konten                 = 'template_email_page';
        $this->auth_model->buildViewAdmin($konten,$param); 
	}
}
?>
