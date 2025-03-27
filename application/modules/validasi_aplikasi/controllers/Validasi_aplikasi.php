<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi_aplikasi extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("validasi_aplikasi_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "aksi"=>array("text"=>"Aksi","sorting"=>"false","width"=>8,"align"=>"center"),
                            "nama_aplikasi"=>array("text"=>"Aplikasi","sorting"=>"true","width"=>30,"align"=>"left","addclass"=>"white-space-normal"),
                            "nama_opd"=>array("text"=>"OPD","sorting"=>"true","width"=>20,"align"=>"left","addclass"=>"white-space-normal"),
                            "url"=>array("text"=>"Domain/Subdomain","sorting"=>"true","width"=>20,"align"=>"left"),
                            //"active"=>array("text"=>"Status","sorting"=>"false","width"=>10,"align"=>"center"),
                            "tahun"=>array("text"=>"Tahun","sorting"=>"true","width"=>8,"align"=>"left"),
                        );
        $this->module   = "validasi_aplikasi";
        $this->configinput = array(
                            array(
                                'field' => 'newvalidasi',
                                'label' => 'Nama Pansel',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->validasi_aplikasi_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->validasi_aplikasi_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->validasi_aplikasi_model->getDatabyid();
        $this->load->helper('file');    
        $param["nama_aplikasi"]     = $dt["nama_aplikasi"]?:"";
		$param["asal_aplikasi"]     = $dt["asal_aplikasi"]?:"";
        $param["penyedia_aplikasi"] = $dt["penyedia_aplikasi"]?:"";
        $param["deskripsi_aplikasi"]= $dt["deskripsi_aplikasi"]?:"";
        $param["id_jenis_layanan"]  = $dt["id_jenis_layanan"]?:"";
        $param["tahun"]             = $dt["tahun"]?:"";
        $param["basis_aplikasi"]    = explode(',',$dt["basis_aplikasi"])??"";
        $param["bahasa_pemrograman"]= $dt["bahasa_pemrograman"]?:"";
        $param["id_dbms"]           = $dt["id_dbms"]?:"";
        $param["dbms_lainnya"]      = $dt["dbms_lainnya"]?:"";
        $param["id_lokasi_hosting"] = $dt["id_lokasi_hosting"]?:"";
        $param["jumlah_pengguna"]   = $dt["jumlah_pengguna"]?:"";
        $param["id_frekuensi_penggunaan"]= $dt["id_frekuensi_penggunaan"]?:"";
        $param["frekuensi_penggunaan_lainnya"]= $dt["frekuensi_penggunaan_lainnya"]?:"";
        $param["nama_domain"]       = $dt["nama_domain"]?:"";
        $param["ketersediaan_integrasi"]= $dt["ketersediaan_integrasi"]?:"";
        $param["metode_integrasi"]  = $dt["metode_integrasi"]?:"";
        $param["aplikasi_integrasi"]= $dt["aplikasi_terintegrasi"]?:"";
        $param["opd_terintegrasi"]     = $dt["opd_terintegrasi"]?:"";
        $param["pengembang_aplikasi"] = $dt["pengembang_aplikasi"]?:"";
        $param["regulasi"]          = $dt["regulasi"]?:"";
        $param["pic_aplikasi"]      = $dt["pic_aplikasi"]?:"";
        $param["nip_pic"]           = $dt["nip_pic"]?:"";
        $param["jabatan_pic"]       = $dt["jabatan_pic"]?:"";
        $param["no_telp"]           = $dt["no_telp"]?:"";
        $param["level_privileges"]  = $dt["level_privileges"]?:"";
        $param["rencana_aplikasi"]  = $dt["rencana_aplikasi"]?:"";
        $param["proses_bisnis"]  = $dt["proses_bisnis"]?:"";
        $param["keterangan"]        = $dt["keterangan"]?:"";
        $param["file"]              = $dt["file_spt"]?:"";
        $param['ext_file']          = get_mime_by_extension(realpath($param["file"]));
        $param["file_logo"]              = $dt["file_logo"]?:"";
        $param['ext_file_logo']          = get_mime_by_extension(realpath($param["file_logo"]));
        $param["status"]            = $dt["status"];

        $param["dbms"]              = $this->global_model->getDataonefield("dbms","master_dbms",array("id_dbms"=>$param["id_dbms"]));
        $param["lokasi_hosting"]    = $this->global_model->getDataonefield("lokasi_hosting","master_lokasi_hosting",array("id_lokasi_hosting"=>$param["id_lokasi_hosting"]));
        $param["jenis_layanan"]= $this->global_model->getDataonefield("jenis_layanan","master_jenis_layanan",array("id_jenis_layanan"=>$param["id_jenis_layanan"]));
        $param["frekuensi_penggunaan"]= $this->global_model->getDataonefield("frekuensi_penggunaan","master_frekuensi_penggunaan",array("id_frekuensi_penggunaan"=>$param["id_frekuensi_penggunaan"]));

        $param["listvalidasi"]         = array("0"=>"Belum Tervalidasi","1"=>"Tervalidasi");
        $param["listjenis"]         = array(""=>"-- Ketersedian Integrasi --","Ada"=>"Ada","Tidak Ada"=>"Tidak Ada","Tidak Tahu"=>"Tidak Tahu");
        $param["listjumlahpengguna"]= array(""=>"-- Pilih Jumlah Pengguna --","di bawah dan sama dengan 25 pengguna"=>"di bawah dan sama dengan 25 pengguna","di atas 25 sampai dengan 100 pengguna"=>"di atas 25 sampai dengan 100 pengguna","di atas 100 pengguna"=>"di atas 100 pengguna");
        $param["listbasis"]         = array("Website"=>"Website","Android"=>"Android","iOS"=>"iOS","Desktop"=>"Desktop");
        $param["listtahun"]         = $this->global_model->getListyear("2010",date("Y"),array(""=>"--- Pilih Tahun ---"));
        $param["listdbms"]          = $this->global_model->getDatatwodimension("master_dbms","*",array("status"=>1),"id_dbms","dbms",array(""=>"---Pilih DBMS---"));
        $param["listjenislayanan"]  = $this->global_model->getDatatwodimension("master_jenis_layanan","*",array("status"=>1),"id_jenis_layanan","jenis_layanan",array(""=>"---Pilih Jenis Layanan---"));
        $param["listlokasihosting"] = $this->global_model->getDatatwodimension("master_lokasi_hosting","*",array("status"=>1),"id_lokasi_hosting","lokasi_hosting",array(""=>"--Pilih Lokasi Hosting--"));
        $param["listfrekuensi"]     = $this->global_model->getDatatwodimension("master_frekuensi_penggunaan","*",array("status"=>1),"id_frekuensi_penggunaan","frekuensi_penggunaan",array(""=>"---Pilih Frekuensi Penggunaan---"));

        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('validasi_aplikasi_form',$param,true);
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
                makeDirektori("./resources/validasi_aplikasi");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/validasi_aplikasi/");
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
                    $fileberkas          = "resources/validasi_aplikasi/".$file_info["file_name"];
                }
            }
            $response  = $this->validasi_aplikasi_model->createData($fileberkas);
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $fileberkas = null;
            if(!empty($_FILES["newfile"]["name"])){
                makeDirektori("./resources/validasi_aplikasi");
                makeDirektori("./resources/validasi_aplikasi/");
                $filename                = $_FILES["newfile"]["name"];
                $config['upload_path']   = realpath("resources/validasi_aplikasi/");
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
                    $fileberkas          = "resources/validasi_aplikasi/".$file_info["file_name"];
                }
            }
            $response             = $this->validasi_aplikasi_model->updateData($fileberkas);
        }
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"),'printpdf'=>site_url($this->module."/printpdf"));
        $param["listopd"]    = $this->global_model->getDatatwodimension("master_opd","*",null,"id_opd","nama",array(""=>"--- Semua OPD ---"));
        $param["listjenis"]    = $this->global_model->getDatatwodimension("master_jenis_layanan","*",null,"id_jenis_layanan","jenis_layanan",array(""=>"--- Semua Layanan ---"));
        $param["liststatus"]    = array(""=>"-- Semua Status --","0"=>"Belum Terverifikasi","1"=>"terverifikasi");
        $param["listasal"]    = array(""=>"-- Semua Asal --","1"=>"Aplikasi yang dikembangkan sendiri","2"=>"Aplikasi yang dikembangkan Instansi luar");
        $param["column"]        = $this->column;
        $konten                 = 'validasi_aplikasi_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
	
	 public function printpdf(){
        ini_set("pcre.backtrack_limit", "5000000");
        ini_set("memory_limit","6G");
        $param['column']                = $this->column;
        // $param["sess_opd"]              = $this->global_model->getDataonefield("nama","master_opd",array("id_opd"=>$this->session->userdata("id_opd")));
     //   $param["tanggal"]               = trim($this->input->post_get("stanggal")); 
       // $param["hari_tanggal"]          = changedate(date("l, d F Y",strtotime($param["tanggal"])));
        $id_opd                      = trim($this->input->post_get("search_opd"));  
        $param["jenis"]                      = trim($this->input->post_get("search_jenis"));  
        $param["opd"]                = $this->global_model->getDataonefield("nama","master_opd",array("id_opd"=>$id_opd));
        $param["jenis_layanan"]         = $this->global_model->getDataonefield("jenis_layanan","master_jenis_layanan",array("id_jenis_layanan"=>$param["jenis"]));
        $param["data"]                  = $this->validasi_aplikasi_model->getDatagridpdf($param);
        $param['configcompany']         = $this->global_model->getConfigcompany();
        $configpdf["htmlcontent"]       = $this->load->view('data_aplikasi_printpdf',$param,true);
        $configpdf["namefile"]          = "Data Aplikasi.pdf";
        $configpdf["paperorientation"]  = "A4-L";
        $configpdf["typeprocess"]       = "I";
        $this->load->library("printpdf");
        $this->printpdf->do_print($configpdf);
    }
}
?>
