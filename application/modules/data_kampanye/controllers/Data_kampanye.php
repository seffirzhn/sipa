<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_kampanye extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("data_kampanye_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "tanggal"=>array("text"=>"Tanggal","sorting"=>"true","width"=>10,"align"=>"left","addclass"=>"white-space-normal"),
                            "waktu"=>array("text"=>"Waktu","sorting"=>"false","width"=>10,"align"=>"center"),
                            "paslon"=>array("text"=>"Pasangan Calon","sorting"=>"false","width"=>26,"align"=>"left","addclass"=>"white-space-normal"),
                            "tempat_kegiatan"=>array("text"=>"Lokasi","sorting"=>"false","width"=>25,"align"=>"left","addclass"=>"white-space-normal"),
                            "jumlah_peserta"=>array("text"=>"Peserta","sorting"=>"false","width"=>5,"align"=>"center"),
                            "keterangan"=>array("text"=>"Keterangan","sorting"=>"false","width"=>20,"align"=>"left","addclass"=>"white-space-normal"),
                            "aksi"=>array("text"=>"Aksi","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "data_kampanye";
        $this->configinput = array(
                            array( 
                                'field' => 'newtanggal',
                                'label' => 'Tanggal',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newjam_mulai',
                                'label' => 'Jam Mulai',
                                'rules' => 'trim'
                            ),
                            array(
                                'field' => 'newjam_selesai',
                                'label' => 'Jam Selesai',
                                'rules' => 'trim'
                            ),
                            array(
                                'field' => 'newtempat_kegiatan',
                                'label' => 'Lokasi',
                                'rules' => 'trim'
                            ),
                            array(
                                'field' => 'newuraian_kegiatan',
                                'label' => 'Uraian Kegiatan',
                                'rules' => 'trim'
                            ),
                            array(
                                'field' => 'newjumlah_peserta',
                                'label' => 'Uraian Kegiatan',
                                'rules' => 'trim|numeric'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->data_kampanye_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->data_kampanye_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->data_kampanye_model->getDatabyid();
        $this->load->helper('file');    
        $param["id_kampanye"]       = $dt["id_kampanye"]?:"";
        $param["id_paslon"]         = $dt["id_paslon"]?:"";
        $param["tanggal"]           = $dt["tanggal"]?:date("Y-m-d");
        $param["jam_mulai"]         = $dt["jam_mulai"]?:"";
        $param["jam_selesai"]       = $dt["jam_selesai"]?:"";
        $param["tempat_kegiatan"]   = $dt["tempat_kegiatan"]?:"";
        $param["uraian_kegiatan"]   = $dt["uraian_kegiatan"]?:"";
        $param["jumlah_peserta"]    = $dt["jumlah_peserta"]?:"";
        $param["keterangan"]        = $dt["keterangan"]?:"";
        $param["listrinciantopol"]  = $this->global_model->getDatatwodimension("master_tokoh_politik","*",null,"id_topol","nama",array(""=>"---Pilih Tokoh Politik---"));
        $param["listpaslon"]        = $this->global_model->getDatatwodimensionOrder("paslon p,master_tokoh_politik mtp1, master_tokoh_politik mtp2","p.id_paslon, p.no_urut, concat('Paslon ',p.no_urut,' - ',mtp1.nama,' & ',mtp2.nama) as paslon","p.id_topol_kepala=mtp1.id_topol and p.id_topol_wakil=mtp2.id_topol and p.id_daerah=".$this->session->userdata("id_daerah"),"no_urut","id_paslon","paslon",array(""=>"---Pilih Paslon---"));
        $param['listtahun']         = $this->global_model->getListyear("2020",date("Y")+3);
        $param["listtopol"]         = $this->db->order_by("id_topol")->get_where("laporan_kampanye_tokoh_politik",array("id_kampanye"=>$param["id_kampanye"])); 
        $param["listdokumentasi"]   = $this->db->get_where("laporan_kampanye_dokumentasi",array("id_kampanye"=>$param["id_kampanye"]));
        $param["actdeletedok"]      = site_url($this->module."/deleteDokumentasi");
        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('data_kampanye_form',$param,true);
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    function deleteDokumentasi($id_dokumentasi_kampanye=null){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id_dokumentasi_kampanye = ($id_dokumentasi_kampanye)?:trim($this->input->post("id_dokumentasi_kampanye"));
        $delete                  = $this->db->where("id_dokumentasi_kampanye",$id_dokumentasi_kampanye)->delete("laporan_kampanye_dokumentasi");
        $result = "";
        if($delete){
            $result = "Dokumentasi berhasil dihapus";
        }else{
            $result = "Dokumentasi gagal dihapus";
        }
        echo $result;
    }

    function createdata(){
        $this->auth_model->checkModulecrud("acl_create",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $filedokumentasi    = null;

            if(!empty($_FILES['newfiledokumentasi']['name'])){
                $countrincian = count($_FILES['newfiledokumentasi']['name']);
     
                for($i=0;$i<$countrincian;$i++){
                    if(!empty($_FILES['newfiledokumentasi']['name'][$i])){
                        $_FILES['file']['name']     = $_FILES['newfiledokumentasi']['name'][$i];
                        $_FILES['file']['type']     = $_FILES['newfiledokumentasi']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['newfiledokumentasi']['tmp_name'][$i];
                        $_FILES['file']['error']    = $_FILES['newfiledokumentasi']['error'][$i];
                        $_FILES['file']['size']     = $_FILES['newfiledokumentasi']['size'][$i];
                        
                        makeDirektori("./resources/data_kampanye");
                        $filename                = $_FILES['file']['name'];
                        $config['upload_path']   = realpath("resources/data_kampanye/");
                        $config['overwrite']     = FALSE;
                        $config['remove_spaces'] = TRUE;
                        $config['max_size']      = 10240;
                        $config['allowed_types'] = 'png|jpg|jpeg';
                        $config['encrypt_name']  = FALSE;
                        $config['file_name']     = $filename;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload("file")){
                            $response["status"]    = "error";
                            $response["message"]   = $this->upload->display_errors();
                            echo json_encode($response);
                            exit();
                        }else{
                            $file_info           = $this->upload->data();
                            $filedokumentasi[$i]   = "resources/data_kampanye/".$file_info["file_name"];
                        }
                    }else{
                        $filedokumentasi[$i]  = "";
                    }
                } 
            }
            $response  = $this->data_kampanye_model->createData($filedokumentasi);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $filedokumentasi    = null;
            
            if(!empty($_FILES['newfiledokumentasi']['name'])){
                $countrincian = count($_FILES['newfiledokumentasi']['name']);
     
                for($i=0;$i<$countrincian;$i++){
                    if(!empty($_FILES['newfiledokumentasi']['name'][$i])){
                        $_FILES['file']['name']     = $_FILES['newfiledokumentasi']['name'][$i];
                        $_FILES['file']['type']     = $_FILES['newfiledokumentasi']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['newfiledokumentasi']['tmp_name'][$i];
                        $_FILES['file']['error']    = $_FILES['newfiledokumentasi']['error'][$i];
                        $_FILES['file']['size']     = $_FILES['newfiledokumentasi']['size'][$i];
                        
                        makeDirektori("./resources/data_kampanye");
                        $filename                = $_FILES['file']['name'];
                        $config['upload_path']   = realpath("resources/data_kampanye/");
                        $config['overwrite']     = FALSE;
                        $config['remove_spaces'] = TRUE;
                        $config['max_size']      = 2000;
                        $config['allowed_types'] = 'png|jpg|jpeg';
                        $config['encrypt_name']  = FALSE;
                        $config['file_name']     = $filename;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload("file")){
                            $response["status"]    = "error";
                            $response["message"]   = $this->upload->display_errors();
                            echo json_encode($response);
                            exit();
                        }else{
                            $file_info           = $this->upload->data();
                            $filedokumentasi[$i]   = "resources/data_kampanye/".$file_info["file_name"];
                        }
                    }else{
                        $filedokumentasi[$i]  = "";
                    }
                }
            }
            // echo json_encode($filedokumentasi);
            // exit();
            $response             = $this->data_kampanye_model->updateData($filedokumentasi);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"));
        $param['listtahun']     = $this->global_model->getListyear("2020",date("Y")+3,array(""=>"--- Pilih Tahun ---"));
        $param["listpaslon"]    = $this->global_model->getDatatwodimensionOrder("paslon p,master_daerah md,master_tokoh_politik mtp1, master_tokoh_politik mtp2","p.id_paslon, p.no_urut, concat(md.nama_daerah,' - Paslon ',p.no_urut,' - ',mtp1.nama,' & ',mtp2.nama) as paslon","p.id_topol_kepala=mtp1.id_topol and p.id_topol_wakil=mtp2.id_topol and p.id_daerah=md.id_daerah","no_urut","id_paslon","paslon",array(""=>"---Pilih Paslon---"));
        $param["column"]        = $this->column;
        $konten                 = 'data_kampanye_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
