<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paslon extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("paslon_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "nama_kepala"=>array("text"=>"Nama Kepala","sorting"=>"true","type_sort"=>"asc","width"=>13,"align"=>"left"),
                            "nama_wakil"=>array("text"=>"Nama Wakil","sorting"=>"true","width"=>20,"align"=>"left"),
                            "nama_daerah"=>array("text"=>"Daerah","sorting"=>"true","width"=>20,"align"=>"left"),
                            "no_urut"=>array("text"=>"No Urut","sorting"=>"false","width"=>15,"align"=>"left"),
                            "foto_paslon"=>array("text"=>"Foto","sorting"=>"false","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "paslon";
        $this->configinput = array(
                            array(
                                'field' => 'newno_urut',
                                'label' => 'No Urut',
                                'rules' => 'trim|required|numeric'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->paslon_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->paslon_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->paslon_model->getDatabyid();
        $this->load->helper('file');    
        // $param["nama_ppid"]         = $this->global_model->getdataoneField("nama_ppid","ppid",array("id_ppid"=>$this->session->userdata("id_ppid")));
        $param["daerah"]            = trim($this->input->post_get("search_daerah"));
        $param["nama_daerah"]       = $this->global_model->getdataoneField("nama_daerah","master_daerah",array("id_daerah"=>$param["daerah"]));
        $param["pilkada"]           = trim($this->input->post_get("search_pilkada"));
        $param["nama_pilkada"]      = $this->global_model->getdataoneField("keterangan","master_pilkada",array("id_pilkada"=>$param["pilkada"]));
        $param["id_daerah"]         = $dt["id_daerah"]?:"";
        $param["id_pilkada"]        = $dt["id_pilkada"]?:"";
        $param["id_topol_kepala"]   = $dt["id_topol_kepala"]?:"";
        $param["id_topol_wakil"]    = $dt["id_topol_wakil"]?:"";
        $param["no_urut"]           = $dt["no_urut"]?:"";
        $param["listpilkada"]       = $this->global_model->getDatatwodimension("master_pilkada","*",null,"id_pilkada","keterangan",array(""=>"---Pilih Pilkada---"));
        $param["listkepala"]        = $this->global_model->getDatatwodimension("master_tokoh_politik","*",null,"id_topol","nama",array(""=>"---Pilih Kepala---"));
        $param["listwakil"]         = $this->global_model->getDatatwodimension("master_tokoh_politik","*",null,"id_topol","nama",array(""=>"---Pilih Wakil---"));
        $param["listdaerah"]        = $this->global_model->getDatatwodimension("master_daerah","*",null,"id_daerah","nama_daerah",array(""=>"---Pilih Daerah---"));
        $param["file"]              = $dt["foto_paslon"]?:"";
        $param['ext_file']          = get_mime_by_extension(realpath($param["file"]));

        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('paslon_form',$param,true);
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
                makeDirektori("./resources/paslon/");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/paslon/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'png|jpg|jpeg';
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
                    $fileberkas          = "resources/paslon/".$file_info["file_name"];
                }
            }
            $response  = $this->paslon_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/paslon/");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/paslon/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'png|jpg|jpeg';
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
                    $fileberkas          = "resources/paslon/".$file_info["file_name"];
                }
            }
            $response             = $this->paslon_model->updateData($fileberkas);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $tanggal                = date("Y-m-d");
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["id_pilkada"]    = $this->global_model->getdataoneField("id_pilkada","master_pilkada","periode_awal<='".$tanggal."' and periode_akhir>='".$tanggal."'");
        $param["pilkada"]       = $this->global_model->getdataoneField("keterangan","master_pilkada",array("id_pilkada"=>$param["id_pilkada"]));
        $param["listpilkada"]   = $this->global_model->getDatatwodimension("master_pilkada","*","periode_awal<='".$tanggal."' and periode_akhir>='".$tanggal."'","id_pilkada","keterangan",array(""=>"---Pilih Pilkada---"));
        $param["listdaerah"]    = $this->global_model->getDatatwodimension("master_daerah","*",null,"id_daerah","nama_daerah",array(""=>"---Pilih Daerah---"));
        $param["column"]        = $this->column;
        $konten                 = 'paslon_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
