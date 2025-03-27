<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_gateway extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("email_gateway_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "checklist"=>array("text"=>'<div class="checkbox"><input id="for_cek_all" type="checkbox" ><label for="for_cek_all"></label></div>',"sorting"=>"false","width"=>5,"align"=>"center"),
                            "sendingtime"=>array("text"=>"DIKIRIM","sorting"=>"true","width"=>13,"align"=>"center","type_sort"=>"desc"),
                            "deliverytime"=>array("text"=>"DITERIMA","sorting"=>"true","width"=>13,"align"=>"center"),
                            "destination"=>array("text"=>"TUJUAN","sorting"=>"true","width"=>15,"align"=>"left"),
                            "subject"=>array("text"=>"SUBJECT","sorting"=>"true","width"=>33,"align"=>"left","addclass"=>"white-space-normal"),
                            "status"=>array("text"=>"STATUS","sorting"=>"false","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "email_gateway";
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->email_gateway_model->getDatagrid($param);
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $this->load->helper('file');
        $id = trim($this->input->post("id"));
        $dt                     = $this->email_gateway_model->getDatabyid();
        $param["subject"]       = $dt["subject"]?:"";
        $param["message"]       = $dt["message"]?:"";
        $param["destination"]   = $dt["destination"]?:"";
        $param["sendingtime"]   = $dt["sendingtime"]?:"";
        $param["deliverytime"]  = $dt["deliverytime"]?:"";
        $param["attachment"]    = $dt["attachment"]?:"";
        $param['ext_attachment']= get_mime_by_extension(realpath($param["attachment"]));
        $param["status"]        = isset($dt["status"])?$dt["status"]:"y";
        $param["liststatus"]    = array("y"=>"Active","n"=>"Non Active");
        $data["id"]             = ($dt==null) ? "":$id;
        $data['html']           = $this->load->view('email_gateway_form',$param,true);
        $response['status']     = "success";
        $response['message']    = "Get Form";
        $response['data']       = $data;
        echo json_encode($response);
    }

    function printpdf(){
        $param['configcompany']     = $this->global_model->getConfigcompany();  
        $param["data"]              = $this->email_gateway_model->getDataprint();
        $configpdf["htmlcontent"]   = $this->load->view('email_gateway_printpdf',$param,true);
        $configpdf["namefile"]      = "Laporan Email Terkirim.pdf";
        $configpdf["paperorientation"]  = "A4-L";
        $configpdf["typeprocess"]       = trim($this->input->post_get("forcedownload")=="1")?"D":"I";
        $this->load->library("printpdf");
        $this->printpdf->do_print($configpdf);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->email_gateway_model->deleteData();
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $response             = $this->email_gateway_model->updateData();
        echo json_encode($response);
    }

	function index()
	{
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),"deletebyselect"=>site_url($this->module.'/deletedata'),'printpdf'=>site_url($this->module."/printpdf"),'update'=>site_url($this->module."/updatedata"));
        $param["listactive"]    = array(""=>"--- Pilih Status ---","y"=>"Terkirim","n"=>"Belum / Tidak Terkirim");
        $param["column"]        = $this->column;
        $konten                 = 'email_gateway_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
