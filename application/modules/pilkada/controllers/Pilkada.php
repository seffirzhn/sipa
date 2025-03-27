<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pilkada extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
        $this->load->model("pilkada_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"center"),
                            "keterangan"=>array("text"=>"Pilkada","sorting"=>"true","width"=>28,"align"=>"left"),
                            "tgl_pilkada"=>array("text"=>"Tgl. Pilkada","sorting"=>"true","width"=>10,"align"=>"center"),
                            "tgl_awal_kampanye"=>array("text"=>"Awal Kampanye","sorting"=>"true","width"=>10,"align"=>"center"),
                            "tgl_akhir_kampanye"=>array("text"=>"Akhir Kampanye","sorting"=>"true","width"=>10,"align"=>"center"),
                            "periode_awal"=>array("text"=>"Periode Awal","sorting"=>"true","width"=>10,"align"=>"center"),
                            "periode_akhir"=>array("text"=>"Periode Akhir","sorting"=>"true","width"=>10,"align"=>"center"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "pilkada";
        $this->configinput = array(
                            array(
                                'field' => 'newketerangan',
                                'label' => 'Keterangan Pilkada',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtgl_pilkada',
                                'label' => 'Tgl. Pilkada',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtgl_awal_kampanye',
                                'label' => 'Tgl. Awal Kampanye',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtgl_akhir_kampanye',
                                'label' => 'Tgl. Akhir Kampanye',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newperiode_awal',
                                'label' => 'Periode Awal',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newperiode_akhir',
                                'label' => 'Periode Akhir',
                                'rules' => 'trim|required'
                            ),
                        );
	}

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->pilkada_model->getDatagrid($param);
        echo json_encode($response);
    }

    function deletedata(){
        $this->auth_model->checkModulecrud("acl_delete",$this->module);
        $response                 = $this->pilkada_model->deleteData();
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $this->load->helper('file');
        $dt                         = $this->pilkada_model->getDatabyid();
        $param["id_pilkada"]        = $dt["id_pilkada"]?:"";
        $param["keterangan"]        = $dt["keterangan"]?:"";
        $param["tgl_pilkada"]       = $dt["tgl_pilkada"]?:"";
        $param["tgl_awal_kampanye"] = $dt["tgl_awal_kampanye"]?:"";
        $param["tgl_akhir_kampanye"]= $dt["tgl_akhir_kampanye"]?:"";
        $param["periode_awal"]      = $dt["periode_awal"]?:"";
        $param["periode_akhir"]     = $dt["periode_akhir"]?:"";
        // $param["is_active"]         = $dt["is_active"]?:"";
       
        $data['html']               = $this->load->view('pilkada_form',$param,true);
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
            $response             = $this->pilkada_model->createData();
        }
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $response             = $this->pilkada_model->updateData();
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
        $konten                 = 'pilkada_page';
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
