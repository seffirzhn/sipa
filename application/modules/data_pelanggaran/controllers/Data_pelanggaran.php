<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_pelanggaran extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("data_pelanggaran_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "aksi"=>array("text"=>"Aksi","sorting"=>"false","width"=>8,"align"=>"center"),
                            "tanggal"=>array("text"=>"Tanggal","sorting"=>"false","width"=>13,"align"=>"left"),
                            "nama_kepala"=>array("text"=>"Paslon","sorting"=>"true","width"=>20,"align"=>"left"),
                            "nama_wakil"=>array("text"=>"Paslon","sorting"=>"true","width"=>20,"align"=>"left"),
                            "nama_kategori"=>array("text"=>"Kategori","sorting"=>"false","width"=>15,"align"=>"left"),
                            "nama_pelanggaran"=>array("text"=>"Pelanggaran","sorting"=>"false","width"=>20,"align"=>"left"),
                            "tindakan_bawaslu"=>array("text"=>"Tindakan","sorting"=>"false","width"=>20,"align"=>"center"),
                        );
        $this->module   = "data_pelanggaran";
        $this->configinput = array(
                            array(
                                'field' => 'newnama',
                                'label' => 'Nama',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newalamat',
                                'label' => 'Alamat',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtelp',
                                'label' => 'No Telp',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->data_pelanggaran_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->data_pelanggaran_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->data_pelanggaran_model->getDatabyid();
        $this->load->helper('file');    
        // $param["nama_ppid"]         = $this->global_model->getdataoneField("nama_ppid","ppid",array("id_ppid"=>$this->session->userdata("id_ppid")));
       
        $param["id_laporan_pelanggaran"] = $dt["id_laporan_pelanggaran"]?:"";
        $param["id_paslon"]         = $dt["id_paslon"]?:"";
        $param["id_pelanggaran"]    = $dt["id_pelanggaran"]?:"";
        $param["id_kategori"]       = $dt["id_pelanggaran"]?$this->global_model->getdataoneField("id_kategori_pelanggaran","master_pelanggaran",array("id_pelanggaran"=>$dt['id_pelanggaran'])):"";
        $param["tanggal"]           = $dt["tanggal"]?:date("Y-m-d");
        $param["jam_mulai"]         = $dt["jam_mulai"]?:"";
        $param["jam_selesai"]       = $dt["jam_selesai"]?:"";
        $param["kronologis"]        = $dt["kronologis"]?:"";
        $param["tindakan_bawaslu"]  = $dt["tindakan_bawaslu"]?:"";
        $param["keterangan"]        = $dt["keterangan"]?:"";
        $param["listpaslon"]        = $this->global_model->getDatatwodimensionOrder("paslon p,master_tokoh_politik mtp1, master_tokoh_politik mtp2","p.id_paslon, p.no_urut, concat('Paslon ',p.no_urut,' - ',mtp1.nama,' & ',mtp2.nama) as paslon","p.id_topol_kepala=mtp1.id_topol and p.id_topol_wakil=mtp2.id_topol ","no_urut","id_paslon","paslon",array(""=>"---Pilih Paslon---"));
        $param["listpelanggaran"]   = $this->global_model->getDatatwodimension("master_pelanggaran","*",array("id_kategori_pelanggaran"=>$param["id_kategori"]),"id_pelanggaran","nama_pelanggaran",array(""=>"---Pilih Pelanggaran---"));
        $param["listkategori"]      = $this->global_model->getDatatwodimension("master_kategori_pelanggaran","*",null,"id_kategori_pelanggaran","nama_kategori",array(""=>"---Pilih Kategori Pelanggaran---"));


        $param["actionspelanggaran"]       = site_url($this->module.'/listpelanggaran');
        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('data_pelanggaran_form',$param,true);
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    function listpelanggaran(){
        $kategori = trim($this->input->post("kategori"));
        $listpelanggaran = $this->db->get_where("master_pelanggaran",array("id_kategori_pelanggaran"=>$kategori));
        $response["status"]     = "success";
        $response["message"]    = "Get List Pelanggaran";
        $response["html"]       = '<option value="">--- Pilih Pelanggaran ---</option>';
        if($listpelanggaran->num_rows()>0){
            foreach ($listpelanggaran->result_array() as $key => $value) {
                $response["html"].= '<option value="'.$value["id_pelanggaran"].'">'.$value["nama_pelanggaran"].'</option>';
            }
        }
        echo json_encode($response);
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/data_pelanggaran");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/data_pelanggaran/");
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
                    $fileberkas          = "resources/data_pelanggaran/".$file_info["file_name"];
                }
            }
            $response  = $this->data_pelanggaran_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/data_pelanggaran");
                makeDirektori("./resources/data_pelanggaran/");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/data_pelanggaran/");
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
                    $fileberkas          = "resources/data_pelanggaran/".$file_info["file_name"];
                }
            }
            $response             = $this->data_pelanggaran_model->updateData($fileberkas);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param["listparpol"]    = $this->global_model->getDatatwodimension("master_partai_politik","*",null,"id_parpol","nama",array(""=>"---Pilih Parpol---","0"=>"Non Partai"));
        $param["listdaerah"]    = $this->global_model->getDatatwodimension("master_daerah","*",null,"id_daerah","nama_daerah",array(""=>"---Pilih Daerah---"));
        $param["column"]        = $this->column;
        $konten                 = 'data_pelanggaran_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
