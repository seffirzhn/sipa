<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settingapp extends MX_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->auth_model->checkIslogout();
        $this->load->model("settingapp_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "name"=>array("text"=>"NAMA APLIKASI","sorting"=>"true","width"=>88,"align"=>"left"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "settingapp";
        $this->configinput = array(
                            array(
                                'field' => 'newname',
                                'label' => 'Nama Aplikasi',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newsubname',
                                'label' => 'Sub Nama',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newtitle',
                                'label' => 'Judul',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newcopyright',
                                'label' => 'Copyright',
                                'rules' => 'trim|required'
                            ),
                        );
    }

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->settingapp_model->getDatagrid($param);
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                         = $this->settingapp_model->getDatabyid();
        $this->load->helper('file');
        $param["name"]              = $dt["name"]?:"";
        $param["subname"]           = $dt["subname"]?:"";
        $param["title"]             = $dt["title"]?:"";
        $param["copyright"]         = $dt["copyright"]?:"";
        $param["preview"]           = $dt["preview"]?:"";
        $param["flagcounter"]       = $dt["flagcounter"]?:"";
        $param["isforget"]          = $dt["isforget"]?:"";
        $param["iscaptcha"]         = $dt["iscaptcha"]?:"";
        $param["issinglesession"]   = $dt["issinglesession"]?:"";
        $param["icon"]              = $dt["icon"]?:"";
        $param["logo"]              = $dt["logo"]?:"";
        $param["background"]        = $dt["background"]?:"";
        $param["trylogin"]          = $dt["trylogin"]?:"0";
        $param["timelock"]          = $dt["timelock"]?:"0";
        $param["hostmail"]          = $dt["hostmail"]?:"";
        $param["portmail"]          = $dt["portmail"]?:"";
        $param["email"]             = $dt["email"]?:"";
        $param["passmail"]          = $dt["passmail"]?:"";
        $param["tokensms"]          = $dt["tokensms"]?:"";
        $param["deviceid"]          = $dt["deviceid"]?:"";
        $param["emailsms"]          = $dt["emailsms"]?:"";
        $param["passmail"]          = $dt["passmail"]?:"";
        $param["isgoogle"]          = $dt["isgoogle"]?:"";
        $param["isfacebook"]        = $dt["isfacebook"]?:"";
        $param["googleclientid"]    = $dt["google_client_id"]?:"";
        $param["googleclientsecret"]= $dt["google_client_secret"]?:"";
        $param["facebookappid"]     = $dt["facebook_app_id"]?:"";
        $param["facebookappsecret"] = $dt["facebook_app_secret"]?:"";
        $param['ext_icon']          = get_mime_by_extension(realpath($param["icon"]));
        $param['ext_logo']          = get_mime_by_extension(realpath($param["logo"]));
        $param['ext_background']    = get_mime_by_extension(realpath($param["background"]));
        $param["arrstatus"]         = array("y"=>"Active","n"=>"Non Active");
        $data["id"]                 = ($dt==null) ? "":$id;
        $data['html']               = $this->load->view('settingapp_form',$param,true);
        $response['status']         = "success";
        $response['message']        = "Get Form";
        $response['data']           = $data;
        echo json_encode($response);
    }

    function updatedata(){
        $this->auth_model->checkModulecrud("acl_update",$this->module);
        $response                 = $this->auth_model->setValidasiinput($this->configinput);
        if($response['status']=="success"){
            $dataupdate     = array();
            if(!empty($_FILES["newlogo"]["name"])){
                $filename                = $_FILES["newlogo"]["name"];
                $config['upload_path']   = realpath("resources/config_site/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 500;
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
                    $file_info           = $this->upload->data();
                    $dataupdate['logo']  = "resources/config_site/".$file_info["file_name"];
                }
            }

            if(!empty($_FILES["newicon"]["name"])){
                $filename                = $_FILES["newicon"]["name"];
                $config['upload_path']   = realpath("resources/config_site/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 100;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newicon')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info           = $this->upload->data();
                    $dataupdate['icon']  = "resources/config_site/".$file_info["file_name"];
                }
            }

            if(!empty($_FILES["newbackground"]["name"])){
                $filename                = $_FILES["newbackground"]["name"];
                $config['upload_path']   = realpath("resources/config_site/");
                $config['overwrite']     = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['max_size']      = 2000;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['encrypt_name']  = FALSE;
                $config['file_name']     = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload('newbackground')){
                    $response["status"]    = "error";
                    $response["message"]   = $this->upload->display_errors();
                    echo json_encode($response);
                    exit();
                }else{
                    $file_info           = $this->upload->data();
                    $dataupdate['background']  = "resources/config_site/".$file_info["file_name"];
                }
            }
            $response             = $this->settingapp_model->updateData($dataupdate);
        }
        echo json_encode($response);
    }

    function index()
    {
        $this->auth_model->checkModule(uri_string());
        $param['title']         = $this->global_model->getTitle(get_class());
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagrid'),"showform"=>site_url($this->module.'/showform'),'update'=>site_url($this->module."/updatedata"));
        $param["column"]        = $this->column;
        $konten                 = 'settingapp_page';
        $this->auth_model->buildViewAdmin($konten,$param);  
    }
}
?>
