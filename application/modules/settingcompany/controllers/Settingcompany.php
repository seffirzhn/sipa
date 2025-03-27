<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settingcompany extends MX_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->auth_model->checkIslogout();
        $this->load->model("settingcompany_model");
        $this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "nama"=>array("text"=>"NAMA INSTANSI","sorting"=>"true","width"=>88,"align"=>"left"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
        $this->module   = "settingcompany";
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
                                'field' => 'newnotelepon',
                                'label' => 'No Telepon',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newemail',
                                'label' => 'Email',
                                'rules' => 'trim|required|valid_email'
                            ),
                            /*array(
                                'field' => 'newpersentase_indeks_sekolah',
                                'label' => 'Indeks Sekolah',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_indeks_siswa',
                                'label' => 'Indeks Siswa',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_akreditasi',
                                'label' => 'Akreditasi',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_jumlah_mahasiswa',
                                'label' => 'Jumlah Mahasiswa',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_ipk_alumni',
                                'label' => 'IPK Alumni',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_mapel',
                                'label' => 'Nilai Mata Pelajaran',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_peringkat',
                                'label' => 'Peringkat',
                                'rules' => 'trim|required'
                            ),
                            array(
                                'field' => 'newpersentase_mapel_prioritas',
                                'label' => 'Nilai Mata Pelajaran Prioritas',
                                'rules' => 'trim|required'
                            ),*/
                        );
    }

    function datagrid(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $param['column']        = $this->column;
        $param['modulecrud']    = $this->auth_model->getModulecrud($this->module);
        $response               = $this->settingcompany_model->getDatagrid($param);
        echo json_encode($response);
    }

    function showform(){
        $this->auth_model->checkModulecrud("acl_read",$this->module);
        $id = trim($this->input->post("id"));
        $dt                                 = $this->settingcompany_model->getDatabyid();
        $this->load->helper('file');
        $param["nama"]                      = $dt["nama"]?:"";
        $param["alamat"]                    = $dt["alamat"]?:"";
        $param["email"]                     = $dt["email"]?:"";
        $param["notelepon"]                 = $dt["notelepon"]?:"";
        $param["fax"]                       = $dt["fax"]?:"";
/*        $param["tgl_bukaregistrasi"]        = $dt["tgl_bukaregistrasi"]?:"";
        $param["tgl_tutupregistrasi"]       = $dt["tgl_tutupregistrasi"]?:"";
        $param["tgl_terakhirpengisian"]     = $dt["tgl_terakhirpengisian"]?:"";
        $param["pembayaran_sebelumhari_h"]  = $dt["pembayaran_sebelumhari_h"]?:"";
        $param["pembayaran_hari_h"]         = $dt["pembayaran_hari_h"]?:"";
        $param["namaujian"]                 = $dt["namaujian"]?:"";
        $param["norekening"]                = $dt["norekening"]?:"";
        $param["atasnama"]                  = $dt["atasnama"]?:"";
        $param["jumlahjurusan"]             = $dt["jumlahjurusan"]?:"";
        $param["informasi"]                 = $dt["informasi"]?:"";
        $param["alur"]                      = $dt["alur"]?:"";
        $param["biaya"]                     = $dt["biaya"]?:"";
        $param["namabank"]                  = $dt["namabank"]?:"";*/
        $param["logo"]                      = $dt["logo"]?:"";
        $param["kop"]                       = $dt["kop"]?:"";
/*        $param["persentase_indeks_sekolah"] = $dt["persentase_indeks_sekolah"]?:"";
        $param["persentase_indeks_siswa"]   = $dt["persentase_indeks_siswa"]?:"";
        $param["persentase_akreditasi"]     = $dt["persentase_akreditasi"]?:"";
        $param["persentase_jumlah_mahasiswa"]= $dt["persentase_jumlah_mahasiswa"]?:"";
        $param["persentase_ipk_alumni"]     = $dt["persentase_ipk_alumni"]?:"";
        $param["persentase_mapel"]          = $dt["persentase_mapel"]?:"";
        $param["persentase_peringkat"]      = $dt["persentase_peringkat"]?:"";
        $param["persentase_mapel_prioritas"]= $dt["persentase_mapel_prioritas"]?:"";*/
        $param['ext_logo']                  = get_mime_by_extension(realpath($param["logo"]));
        $data["id"]                         = ($dt==null) ? "":$id;
        $data['html']                       = $this->load->view('settingcompany_form',$param,true);
        $response['status']                 = "success";
        $response['message']                = "Get Form";
        $response['data']                   = $data;
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
            $response             = $this->settingcompany_model->updateData($dataupdate);
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
        $konten                 = 'settingcompany_page';
        $this->auth_model->buildViewAdmin($konten,$param);  
    }
}
?>
