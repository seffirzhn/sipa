<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_parpol extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("parpol_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>6,"align"=>"center"),
                            "nama"=>array("text"=>"PARTAI POLITIK","sorting"=>"true","width"=>66,"align"=>"left"),
                            "logo"=>array("text"=>"LOGO","sorting"=>"false","width"=>20,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "master_parpol";
        $this->configinput = array(
                            array(
                                'field' => 'newnama',
                                'label' => 'Nama Parpol', 
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->parpol_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->parpol_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $this->load->helper('file');
        $dt                     = $this->parpol_model->getDatabyid();
        $param["nama"]          = $dt["nama"]?:"";
        $param["logo"]          = $dt["logo"]?:"";
        $param['ext_logo']      = get_mime_by_extension(realpath($param["logo"]));
        $data['html']           = $this->load->view('parpol_form',$param,true);
        $data["id"]             = ($dt==null) ? "":$id;
        $response['status']     = "success";
        $response['message']    = "Get Form";
        $response['data']       = $data;
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $logo       = "";
            if(!empty($_FILES["newlogo"]["name"])){
                $filename                = $_FILES["newlogo"]["name"];
                $config['upload_path']   = realpath("resources/image/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 1000;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newlogo')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info      = $this->upload->data();
                    $logo           = "resources/image/".$file_info["file_name"];
                }
            }
            $response             = $this->parpol_model->createData($logo);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $logo       = "";
            if(!empty($_FILES["newlogo"]["name"])){
                $filename                = $_FILES["newlogo"]["name"];
                $config['upload_path']   = realpath("resources/image/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 1000;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newlogo')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info      = $this->upload->data();
                    $logo           = "resources/image/".$file_info["file_name"];
                }
            }
            $response             = $this->parpol_model->updateData($logo);
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
        $konten                 = 'parpol_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
