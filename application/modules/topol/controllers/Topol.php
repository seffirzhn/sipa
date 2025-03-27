<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_topol extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("master_topol_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "nama"=>array("text"=>"Nama Tokoh","sorting"=>"true","type_sort"=>"desc","width"=>13,"align"=>"center"),
                            "nama_parpol"=>array("text"=>"Parpol","sorting"=>"false","width"=>20,"align"=>"left"),
                            "nama_daerah"=>array("text"=>"Daerah","sorting"=>"false","width"=>20,"align"=>"left"),
                            "alamat"=>array("text"=>"Alamat","sorting"=>"false","width"=>15,"align"=>"left"),
                            "no_telp"=>array("text"=>"Telp.","sorting"=>"false","width"=>10,"align"=>"center"),
                            "berkas"=>array("text"=>"Foto","sorting"=>"false","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "master_topol";
        $this->configinput = array(
                            array(
                                'field' => 'newjudul',
                                'label' => 'Judul',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->master_topol_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->master_topol_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->master_topol_model->getDatabyid();
        $this->load->helper('file');    
        $param["nama_ppid"]         = $this->global_model->getdataoneField("nama_ppid","ppid",array("id_ppid"=>$this->session->userdata("id_ppid")));
        $param["judul"]             = $dt["judul"]?:"";
        $param["keterangan"]        = $dt["keterangan"]?:"";
        $param["m_informasi"]       = $dt["m_informasi"]?:"";
        $param["penanggung_jawab"]  = $dt["penanggung_jawab"]?:"";
        $param["tempat_informasi"]  = $dt["tempat_informasi"]?:"";
        $param["file"]              = $dt["file"]?:"";
        $param['ext_file']          = get_mime_by_extension(realpath($param["file"]));

        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('master_topol_form',$param,true);
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
                $idppid = $this->session->userdata("id_ppid");
                makeDirektori("./resources/master_topol");
                makeDirektori("./resources/master_topol/".$idppid);
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/master_topol/".$idppid."/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'pdf';
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
                    $fileberkas          = "resources/master_topol/".$idppid."/".$file_info["file_name"];
                }
            }
            $response  = $this->master_topol_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                $idppid = $this->session->userdata("id_ppid");
                makeDirektori("./resources/master_topol");
                makeDirektori("./resources/master_topol/".$idppid);
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/master_topol/".$idppid."/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'pdf';
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
                    $fileberkas          = "resources/master_topol/".$idppid."/".$file_info["file_name"];
                }
            }
            $response             = $this->master_topol_model->updateData($fileberkas);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param['listtahun']         = $this->global_model->getListyear("2020",date("Y"));
        $param["column"]        = $this->column;
        $konten                 = 'master_topol_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
