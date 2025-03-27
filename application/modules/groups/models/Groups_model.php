<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends CI_Model {
	private $table="groups";
    private $primarykey="group_id";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("g.*")->from($this->table." as g");

        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(g.group_name)",strtoupper($val));
                $this->db->or_like("UPPER(g.group_description)",strtoupper($val));
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
            if($this->global_model->checkRecordexist(array("users_groups"=>"group_id"),$id)){
                $response["status"]   = "warning";
                $response["message"]  = "Tidak dapat menghapus data ini karena digunakan di tabel user";
            }else{
                $delete=null;
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    $delete2=$this->db->where("group_id",$id)->delete("acls");
                    $delete2=$this->db->where("group_id",$id)->delete("guides_groups");
                    $this->global_model->insertLog("menghapus group dengan group id ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus group";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus group";
                }
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus group";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData(){        
        if($this->global_model->checkRecordexist(array("groups"=>"group_name"),trim($this->input->post("newgroup_name")))){
            $response["status"]   = "warning";
            $response["message"]  = "Data group ini sudah ada";
        }else{
            $data=array(
                    "group_name"=>trim($this->input->post("newgroup_name")),
                    "role_name"=>trim($this->input->post("newrole_name")),
                    "group_description"=>trim($this->input->post("newgroup_description")),
                    "ismobile"=>trim($this->input->post("newismobile")),
                    "created_by"=>$this->session->userdata("user_id"),
                    "created_on"=>date("Y-m-d H:i:s"),
                );
            $save   = $this->db->insert($this->table,$data);
            $id     = $this->db->insert_id();
            if($save){
                $modules    = $this->db->get("modules");
                if($modules->num_rows()>0){
                    $insertacl  = array();
                    foreach ($modules->result_array() as $key => $value) {
                        $insertacl[] = array("module_id"=>$value["module_id"],"group_id"=>$id);
                    }
                    $this->db->insert_batch("acls",$insertacl);
                }
                $this->global_model->insertLog("menambah data group (".$data['group_name'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data group";
                $response["autohide"] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data group";
            }
        }
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        if($this->global_model->checkRecordexist(array("groups"=>""),array("group_name"=>trim($this->input->post("newgroup_name")),"group_id<>"=>$id))){
            $response["status"]   = "warning";
            $response["message"]  = "Group Name sudah digunakan";
        }else{
            $data=array(
                "group_name"=>trim($this->input->post("newgroup_name")),
                "role_name"=>trim($this->input->post("newrole_name")),
                "group_description"=>trim($this->input->post("newgroup_description")),
                "ismobile"=>trim($this->input->post("newismobile")),
                "changed_by"=>$this->session->userdata("user_id"),
                "changed_on"=>date("Y-m-d H:i:s"),
            );  
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $this->global_model->insertLog("memperbaharui data group (".$data['group_name'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data group";
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data group";
            }
        }
        return $response;
    }
}