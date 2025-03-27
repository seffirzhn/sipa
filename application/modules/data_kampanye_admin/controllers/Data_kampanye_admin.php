<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_kampanye_admin extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("data_kampanye_admin_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "tanggal"=>array("text"=>"Tanggal","sorting"=>"true","width"=>10,"align"=>"left","addclass"=>"white-space-normal"),
                            "waktu"=>array("text"=>"Waktu","sorting"=>"false","width"=>10,"align"=>"center"),
                            "paslon"=>array("text"=>"Pasangan Calon","sorting"=>"false","width"=>26,"align"=>"left","addclass"=>"white-space-normal"),
                            "tempat_kegiatan"=>array("text"=>"Lokasi","sorting"=>"false","width"=>25,"align"=>"left","addclass"=>"white-space-normal"),
                            "jumlah_peserta"=>array("text"=>"Peserta","sorting"=>"false","width"=>5,"align"=>"center"),
                            "keterangan"=>array("text"=>"Keterangan","sorting"=>"false","width"=>20,"align"=>"left","addclass"=>"white-space-normal"),
                        );
        $this->module   = "data_kampanye_admin";
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
        $response               = $this->data_kampanye_admin_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->data_kampanye_admin_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->data_kampanye_admin_model->getDatabyid();
        $this->load->helper('file');    
        $param["id_kampanye"]       = $dt["id_kampanye"]?:"";
        $param["id_paslon"]         = $dt["id_paslon"]?:"";
        $param["tanggal"]           = $dt["tanggal"]?:"";
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
        $data['html']               = $this->load->view('data_kampanye_admin_form',$param,true);
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    public function printpdf(){
        ini_set("pcre.backtrack_limit", "5000000");
        ini_set("memory_limit","6G");
        $param['column']                = $this->column;
        // $param["sess_opd"]              = $this->global_model->getDataonefield("nama","master_opd",array("id_opd"=>$this->session->userdata("id_opd")));
        $param["tanggal"]               = trim($this->input->post_get("stanggal")); 
        $param["hari_tanggal"]          = changedate(date("l, d F Y",strtotime($param["tanggal"])));
        $id_daerah                      = trim($this->input->post_get("search_daerah"));  
        $param["daerah"]                = $this->global_model->getDataonefield("nama_daerah","master_daerah",array("id_daerah"=>$id_daerah));
        $param["data"]                  = $this->data_kampanye_admin_model->getDatagridpdf($param);
        $param["data_foto"]             = $this->data_kampanye_admin_model->getDokumentasipdf($param);
        $param['configcompany']         = $this->global_model->getConfigcompany();
        $configpdf["htmlcontent"]       = $this->load->view('data_kampanye_admin_printpdf',$param,true);
        $configpdf["namefile"]          = "Data Kampanye.pdf";
        $configpdf["paperorientation"]  = "Legal-L";
        $configpdf["typeprocess"]       = "I";
        $this->load->library("printpdf");
        $this->printpdf->do_print($configpdf);
    }


	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"delete"=>site_url($this->module.'/deletedata'),'create'=>site_url($this->module."/createdata"),'update'=>site_url($this->module."/updatedata"),'printpdf'=>site_url($this->module."/printpdf"));
        $param["listdaerah"]  = $this->global_model->getDatatwodimension("master_daerah","*",null,"id_daerah","nama_daerah",array(""=>"---Pilih Daerah---"));
        $param["listpaslon"]    = $this->global_model->getDatatwodimensionOrder("paslon p,master_daerah md,master_tokoh_politik mtp1, master_tokoh_politik mtp2","p.id_paslon, p.no_urut, concat(md.nama_daerah,' - Paslon ',p.no_urut,' - ',mtp1.nama,' & ',mtp2.nama) as paslon","p.id_topol_kepala=mtp1.id_topol and p.id_topol_wakil=mtp2.id_topol and p.id_daerah=md.id_daerah","no_urut","id_paslon","paslon",array(""=>"---Pilih Paslon---"));
        $param["column"]        = $this->column;
        $konten                 = 'data_kampanye_admin_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
