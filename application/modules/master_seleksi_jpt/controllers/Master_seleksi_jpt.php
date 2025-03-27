<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_seleksi_jpt extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("master_seleksi_jpt_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "judul_seleksi"=>array("text"=>"Seleksi JPT","sorting"=>"true","width"=>28,"align"=>"left"),
                            "periode"=>array("text"=>"Periode","sorting"=>"true","width"=>15,"align"=>"center"),
                            "active"=>array("text"=>"Status","sorting"=>"true","width"=>10,"align"=>"center"),
                            "file"=>array("text"=>"Berkas","sorting"=>"true","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "master_seleksi_jpt";
        $this->configinput = array(
                            array(
                                'field' => 'newjudul_seleksi',
                                'label' => 'Judul Seleksi',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtgl_mulai',
                                'label' => 'Tanggal Mulai',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtgl_selesai',
                                'label' => 'Tanggal Selesai',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->master_seleksi_jpt_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->master_seleksi_jpt_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $this->load->helper('file');
        $dt                         = $this->master_seleksi_jpt_model->getDatabyid();
        $param["id_master_seleksi"] = $dt["id_master_seleksi"]?:"";
        $param["judul_seleksi"]     = $dt["judul_seleksi"]?:"";
        $param["tgl_mulai"]         = $dt["tgl_mulai"]?:"";
        $param["tgl_selesai"]       = $dt["tgl_selesai"]?:"";
        $param["active"]        = isset($dt["is_active"])?$dt["is_active"]:"1";
        $param["file"]              = $dt["file"]?:"";
        $param['ext_file']          = get_mime_by_extension(realpath($param["file"]));
       
        $data['html']               = $this->load->view('master_seleksi_jpt_form',$param,true);
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
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/seleksi_jpt");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/seleksi_jpt/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'pdf|png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newfile')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info           = $this->upload->data();
                    $fileberkas          = "resources/seleksi_jpt/".$file_info["file_name"];
                }
            }
            $response             = $this->master_seleksi_jpt_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/seleksi_jpt");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/seleksi_jpt/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'pdf|png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newfile')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info           = $this->upload->data();
                    $fileberkas          = "resources/seleksi_jpt/".$file_info["file_name"];
                }
            }
            $response             = $this->master_seleksi_jpt_model->updateData($fileberkas);
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
        $konten                 = 'master_seleksi_jpt_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
