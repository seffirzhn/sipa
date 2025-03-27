<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {
	private $table="users";
    private $primarykey="user_id";
	public function __construct(){
		
	}

    public function getDatanotification($param){
        $title          = trim($this->input->post("search_title"));
        $notification   = trim($this->input->post("search_notification"));
        $datemin        = trim($this->input->post("sdatemin"));         
        $datemax        = trim($this->input->post("sdatemax")); 
        $order          = $this->input->post("order");
        $this->db->select("sn.send_notifikasi_id,sn.inserttime,sn.title,sn.notifikasi,sn.url,case when sn.status='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Sudah</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Belum</span>' end as status")
                ->from("send_notifikasi as sn")
                ->where("sn.touser",$this->session->userdata("user_id"));

        if($title!=""){
            $title   = explode(" ", $title);
            foreach ($title as $val) {
                $this->db->like("UPPER(sn.title)",strtoupper($val));
            }
        }

        if($notification!=""){
            $notification   = explode(" ", $notification);
            foreach ($notification as $val) {
                $this->db->like("UPPER(sn.notifikasi)",strtoupper($val));
            }
        }

        if($datemin!="" && $datemax!=""){
            $this->db->group_start();
            $this->db->where("date(sn.inserttime)>=",$datemin);
            $this->db->where("date(sn.inserttime)<=",$datemax);
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
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val["send_notifikasi_id"]);
                $val['aksi']    = anchor(site_url(CI_ADMIN_PATH.'/readnotification').'?id='.urlencode($val['id']),"Kunjungi",array("class"=>"btn btn-sm btn-primary"));
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

    function updatePassword(){
        $sandi = $this->encryption->encrypt(trim($this->input->post("sandibaru")));
        if($sandi!=""){
            $data=array('password'=>$sandi);
            $this->db->where($this->primarykey,$this->session->userdata('user_id'));
            $this->db->update($this->table,$data);
            $this->global_model->insertLog("mengganti kata sandi");
            return array("class"=>"success");
        }
    }

    function updateName(){
        $nama = trim($this->input->post("nama"));
        if($nama!=""){
            $this->session->set_userdata("name",$nama);
            $this->db->where($this->primarykey,$this->session->userdata("user_id"));
            $this->db->update($this->table,array("name"=>$nama));
            $this->global_model->insertLog("mengganti nama");
            return array("class"=>"success");
        }
    }

    function updatePhone(){
        $phone = trim($this->input->post("notelp"));
        if($phone!=""){
            $this->session->set_userdata("phone",$phone);
            $this->db->where($this->primarykey,$this->session->userdata("user_id"));
            $this->db->update($this->table,array("phone"=>$phone));
            $this->global_model->insertLog("mengganti no hp");
            return array("class"=>"success");
        }
    }

    function updateEmail(){
        $email = trim($this->input->post("email"));
        if($email!=""){
            $this->session->set_userdata("email",$email);
            $this->db->where($this->primarykey,$this->session->userdata("user_id"));
            $this->db->update($this->table,array("email"=>$email));
            $this->global_model->insertLog("mengganti email");
            return array("class"=>"success");
        }
    }

}