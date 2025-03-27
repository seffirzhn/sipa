<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_model extends CI_Model {
	private $table="modules";
    private $primarykey="module_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $module         = trim($this->input->post("search_module"));
        $parent         = trim($this->input->post("search_parent"));
        $link           = trim($this->input->post("search_link"));
        $ordermodule    = trim($this->input->post("search_order"));
        $order          = $this->input->post("order");
        $this->db->select("m.module_id,m.module_name,m.module_link,m.module_order,case when m2.module_name<>'' then m2.module_name else 'Root' end as module_parent,case when m.module_icon<>'' then concat('<i class=\" ',m.module_icon,'\">') else '-' end as module_icon")
                ->from($this->table." as m")
                ->join($this->table." as m2","m.module_parent_id=m2.module_id","left");
        if($module!=""){
            $module   = explode(" ", $module);
            foreach ($module as $val) {
                $this->db->like("UPPER(m.module_name)",strtoupper($val));
            }
        }

        if(strtolower($parent)=="root"){
            $this->db->where("m.module_parent_id",0);
        }else if($parent!=""){
            $parent   = explode(" ", $parent);
            foreach ($parent as $val) {
                $this->db->like("UPPER(m2.module_name)",strtoupper($val));
            }
        }

        if($link!=""){
            $link   = explode(" ", $link);
            foreach ($link as $val) {
                $this->db->like("UPPER(m.module_link)",strtoupper($val));
            }
        }

        if($ordermodule!=""){
            $this->db->like("m.module_order",$ordermodule);
        }

        $order_by   = array_keys($param['column']);
        $order_by   = $order_by[$order[0]["column"]];
        if($order_by!=""){
            if($order_by=="module_order"){
                $this->db->order_by("m2.module_order",$order[0]["dir"]);
            }
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
            $delete=null;
            $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
            if($delete){
                $delete2 = $this->db->where($this->primarykey,$id)->delete("acls");
                $this->global_model->insertLog("menghapus modul dengan user id ".$id);
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menghapus modul";
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menghapus modul";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus modul";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData(){
        $data=array(
                "module_name"=>trim($this->input->post("newmodule_name")),
                "module_parent_id"=>trim($this->input->post("newmodule_parent")),
                "module_link"=>trim($this->input->post("newmodule_link")),
                "module_icon"=>trim($this->input->post("newmodule_icon")),
                "module_order"=>trim($this->input->post("newmodule_order")),
                "created_by"=>$this->session->userdata("user_id"),
                "created_on"=>date("Y-m-d H:i:s"),
            );
        $save   = $this->db->insert($this->table,$data);
        $id     = $this->db->insert_id();
        if($save){
            $groups    = $this->db->get("groups");
            if($groups->num_rows()>0){
                $insertacl  = array();
                foreach ($groups->result_array() as $key => $value) {
                    $insertacl[] = array("group_id"=>$value["group_id"],"module_id"=>$id);
                }
                $this->db->insert_batch("acls",$insertacl);
            }
            $this->global_model->insertLog("menambah data modul (".$data['module_name'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data modul";
            $response["state"]    = "createdata";
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data modul";
        }
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data=array(
                "module_name"=>trim($this->input->post("newmodule_name")),
                "module_parent_id"=>trim($this->input->post("newmodule_parent")),
                "module_link"=>trim($this->input->post("newmodule_link")),
                "module_icon"=>trim($this->input->post("newmodule_icon")),
                "module_order"=>trim($this->input->post("newmodule_order")),
                "changed_by"=>$this->session->userdata("user_id"),
                "changed_on"=>date("Y-m-d H:i:s"),
            );  
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            $this->global_model->insertLog("memperbaharui data modul (".$data['module_name'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data modul";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data modul";
        }
        return $response;
    }
}