<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settingcompany_model extends CI_Model {
    private $table="config_company";
    private $primarykey="config_company_id";
    public function __construct(){
        
    }

    public function getDatagrid($param){
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("cs.config_company_id,cs.nama")->from($this->table." as cs");

        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(cs.nama)",strtoupper($val));
            }
            $this->db->group_end();
        }

        $order_by   = array_keys($param['column']);
        $order_by   = $order_by[$order[0]["column"]];
        if($order_by!=""){
            $this->db->order_by($order_by,$order[0]["dir"]);
        }

        $row        = (int)trim($this->input->post("length"));
        $display    = ($row>0 && $row<=500)? $row : 10;
        $page       = (int)trim($this->input->post("start"));
        $this->db->limit($display,$page);
        
        $query      = $this->db->get_compiled_select();

        $queryfrom  = substr($query,0,strpos($query, 'ORDER BY'));
        $jumlahdata = $this->global_model->countData($queryfrom);
        $MaxPage    = ceil($jumlahdata / $display);
        $grid       = array();
        if($jumlahdata>0){
            $no             = $page;
            $data_grid      = $this->db->query($query);
            $button_update  = $this->load->view("properties/button_edit_data",null,true);
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['aksi']    = "";
                if($param['modulecrud']['acl_update']==1){
                    $val['aksi'].= $button_update;
                }
                unset($val[$this->primarykey]);
                $grid[]         = $val; 
            }
        }
        $response["status"]     = "success";
        $response["message"]    = "Get ".$jumlahdata." record(s) ";
        $data["grid"]           = $grid;
        $data["recordsTotal"]   = $jumlahdata;
        $data["page"]           = (ceil($page/$display)+1);
        $data["maxPage"]        = $MaxPage;
        $response["data"]       = $data;
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function updateData($data){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data_old                       = $this->global_model->getDataonerow("logo","config_company",array("config_company_id"=>$id));
        $data["nama"]                   = trim($this->input->post("newnama"));
        $data["fax"]                    = trim($this->input->post("newfax"));
        $data["alamat"]                 = trim($this->input->post("newalamat"));
        $data["email"]                  = trim($this->input->post("newemail"));
        $data["notelepon"]              = trim($this->input->post("newnotelepon"));
        $data["kop"]                    = trim($this->input->post("newkop",NULL,FALSE));
        /*$data["tgl_bukaregistrasi"]     = trim($this->input->post("newtgl_bukaregistrasi"))?:null;
        $data["tgl_terakhirpengisian"]  = trim($this->input->post("newtgl_terakhirpengisian"))?:null;
        $data["tgl_tutupregistrasi"]    = trim($this->input->post("newtgl_tutupregistrasi"))?:null;
        $data["pembayaran_sebelumhari_h"] = trim($this->input->post("newpembayaran_sebelumhari_h"));
        $data["pembayaran_hari_h"]      = trim($this->input->post("newpembayaran_hari_h"));
        $data["namaujian"]              = trim($this->input->post("newnamaujian"));
        $data["norekening"]             = trim($this->input->post("newnorekening"));
        $data["atasnama"]               = trim($this->input->post("newatasnama"));
        $data["namabank"]               = trim($this->input->post("newnamabank"));
        $data["jumlahjurusan"]          = trim($this->input->post("newjumlahjurusan"));
        $data["biaya"]                  = str_replace(".","",trim($this->input->post("newbiaya")));
        $data["informasi"]              = trim($this->input->post("newinformasi",NULL,FALSE));
        $data["alur"]                   = trim($this->input->post("newalur",NULL,FALSE));
        $data["kop"]                    = trim($this->input->post("newkop",NULL,FALSE));
        $data["persentase_indeks_sekolah"]= trim($this->input->post("newpersentase_indeks_sekolah"));
        $data["persentase_indeks_siswa"]= trim($this->input->post("newpersentase_indeks_siswa"));
        $data["persentase_akreditasi"]  = trim($this->input->post("newpersentase_akreditasi"));
        $data["persentase_jumlah_mahasiswa"]= trim($this->input->post("newpersentase_jumlah_mahasiswa"));
        $data["persentase_ipk_alumni"]  = trim($this->input->post("newpersentase_ipk_alumni"));
        $data["persentase_mapel"]       = trim($this->input->post("newpersentase_mapel"));
        $data["persentase_peringkat"]   = trim($this->input->post("newpersentase_peringkat"));
        $data["persentase_mapel_prioritas"]= trim($this->input->post("newpersentase_mapel_prioritas"));*/
        $data["changed_by"]             = $this->session->userdata("user_id");
        $data["changed_on"]             = date("Y-m-d H:i:s");
        $save                           = $this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            if(isset($data['logo'])){
                @unlink("./".$data_old['logo']);
            }
            $this->global_model->insertLog("memperbaharui data config company (".$data['nama'].")");
            $result["status"]   = "success";
            $result["message"]  = "Berhasil memperbaharui data config company";
            $result["autohide"] = true;    
        }else{
            $result["status"]   = "error";
            $result["message"]  = "Gagal memperbaharui data config company";
        }
        return $result;
    }
}