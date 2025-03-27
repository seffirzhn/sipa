<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
	private $table="users";
    private $primarykey="user_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $username       = trim($this->input->post("search_username"));
        $name           = trim($this->input->post("search_name"));
        $group          = trim($this->input->post("search_group"));
        $active         = trim($this->input->post("search_active"));
        $order          = $this->input->post("order");
        $this->db->select("u.user_id,u.user_name,u.name,mo.nama as nama_opd,".$this->global_model->concat("g.group_name","group_name").",case when u.active='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Aktif</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Tidak Aktif</span>' end as active")
                ->from($this->table." as u")
                ->join("users_groups ug","ug.user_id=u.user_id")
                ->join("groups as g","ug.group_id=g.group_id")
                ->join("master_opd as mo","u.id_opd=mo.id_opd","left");
        if($username!=""){ 
            $this->db->like("UPPER(u.user_name)",strtoupper($username));
        }
        if($name!=""){
            $name   = explode(" ", $name);
            foreach ($name as $val) {
                $this->db->like("UPPER(u.name)",strtoupper($val));
            }
        }
        if($group!=""){
            $this->db->where("ug.group_id",$group);
        }
        if($active!=""){
            $this->db->where("u.active",$active);
        }

        $order_by   = array_keys($param['column']);
        $order_by   = $order_by[$order[0]["column"]];
        if($order_by!=""){
            $this->db->order_by($order_by,$order[0]["dir"]);
        }
        $this->db->group_by("u.user_id");
        
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
            $button_delete  = $this->load->view("properties/button_delete_data",null,true);
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['aksi']    = "";
                if($param['modulecrud']['acl_update']==1){
                    $val['aksi'].= $button_update;
                }
                if($param['modulecrud']['acl_delete']==1){
                    $val['aksi'].= $button_delete;
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

    function deleteData(){
        $id = trim($this->input->post("id"));
        if($id!=""){
            $id = $this->encryption->decrypt($id);
            $delete=null;
            if($id!=$this->session->userdata("user_id")){
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    $delete2 = $this->db->where("user_id",$id)->delete("users_groups");
                    $this->global_model->insertLog("menghapus user dengan user id ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus user";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus user";
                }
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Tidak dapat menghapus user anda";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus user";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData(){
        if($this->global_model->checkRecordexist(array("users"=>"user_name"),trim($this->input->post("newuser_name")))){
            $response["status"]   = "warning";
            $response["message"]  = "Data user ini sudah ada";
        }else{
            $pass = trim($this->input->post("newpass"))?:'user12345';
            $data=array(
                    "user_name"=>trim($this->input->post("newuser_name")),
                    "name"=>trim($this->input->post("newname")),
                    "password"=>$this->encryption->encrypt($pass),
                    "id_opd"=>trim($this->input->post("newopd")),
                    "active"=>trim($this->input->post("newactive")),
                    "created_by"=>$this->session->userdata("user_id"),
                    "created_on"=>date("Y-m-d H:i:s"),
                );
            $save   = $this->db->insert($this->table,$data);
            $id     = $this->db->insert_id();
            if($save){
                $arrgroup = $this->input->post("newgroup");
                if(count($arrgroup)>0){
                    $datagroup = array();
                    foreach ($arrgroup as $key) {
                        $datagroup[] = array("user_id"=>$id,"group_id"=>$key);
                    }
                    $this->db->insert_batch("users_groups",$datagroup);
                }

                $this->global_model->insertLog("menambah data user (".$data['user_name'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data user";
                $response["autohide"] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data user";
            }
        }
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        if($this->global_model->checkRecordexist(array($this->table=>""),array("user_name"=>trim($this->input->post("newuser_name")),"user_id<>"=>$id))){
            $response["status"]   = "warning";
            $response["message"]  = "Data user ini sudah ada";
        }else{
            $data=array(
                "user_name"=>trim($this->input->post("newuser_name")),
                "name"=>trim($this->input->post("newname")),
                "active"=>trim($this->input->post("newactive")),
                "id_opd"=>trim($this->input->post("newopd")),
                "changed_by"=>$this->session->userdata("user_id"),
                "changed_on"=>date("Y-m-d H:i:s"),
            );  
            $pass = trim($this->input->post("newpass"));
            if($pass!=''){
                $data['password']=$this->encryption->encrypt($pass);
            }
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $arrgroup = $this->input->post("newgroup");
                if(count($arrgroup)>0){
                    $this->db->where("user_id",$id)->delete("users_groups"); 
                    $datagroup = array();
                    foreach ($arrgroup as $key) {
                        $datagroup[] = array("user_id"=>$id,"group_id"=>$key);
                    }
                    $this->db->insert_batch("users_groups",$datagroup);
                }
                $this->global_model->insertLog("memperbaharui data user (".$data['user_name'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data user";
                $response["autohide"] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data user";
            }
        }
        return $response;
    }
}