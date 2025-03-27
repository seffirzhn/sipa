<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guides_model extends CI_Model {
	private $table="guides";
    private $primarykey="guide_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $name           = trim($this->input->post("search_name"));
        $group          = trim($this->input->post("search_group"));
        $active         = trim($this->input->post("search_active"));
        $order          = $this->input->post("order");
        $this->db->select("g.guide_id,g.name,g.description,".$this->global_model->concat("g2.group_name","group_name").",case when g.status='y' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Active</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Non Active</span>' end as status")
                ->from($this->table." as g")
                ->join("guides_groups gg","gg.guide_id=g.guide_id")
                ->join("groups as g2","gg.group_id=g2.group_id");
        
        if($name!=""){
            $name   = explode(" ", $name);
            foreach ($name as $val) {
                $this->db->like("UPPER(g.name)",strtoupper($val));
            }
        }
        if($group!=""){
            $this->db->where("gg.group_id",$group);
        }
        if($active!=""){
            $this->db->where("g.status",$active);
        }

        $order_by   = array_keys($param['column']);
        $order_by   = $order_by[$order[0]["column"]];
        if($order_by!=""){
            $this->db->order_by($order_by,$order[0]["dir"]);
        }
        $this->db->group_by("g.guide_id");

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
                $delete2 = $this->db->where("guide_id",$id)->delete("guides_groups");
                $this->global_model->insertLog("menghapus panduan dengan id ".$id);
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menghapus panduan";
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menghapus panduan";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus panduan";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData(){
        $data=array(
                "name"=>trim($this->input->post("newname")),
                "description"=>trim($this->input->post("newdescription",NULL,FALSE)),
                "status"=>trim($this->input->post("newstatus"))?:"n",
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
                    $datagroup[] = array("guide_id"=>$id,"group_id"=>$key);
                }
                $this->db->insert_batch("guides_groups",$datagroup);
            }

            $this->global_model->insertLog("menambah data panduan (".$data['name'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data panduan";
            $response["state"]    = "createdata";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data panduan";
        }
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data=array(
            "name"=>trim($this->input->post("newname")),
            "description"=>trim($this->input->post("newdescription",NULL,FALSE)),
            "status"=>trim($this->input->post("newstatus"))?:"n",
            "changed_by"=>$this->session->userdata("user_id"),
            "changed_on"=>date("Y-m-d H:i:s"),
        );  
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            $arrgroup = $this->input->post("newgroup");
            if(count($arrgroup)>0){
                $this->db->where("guide_id",$id)->delete("guides_groups"); 
                $datagroup = array();
                foreach ($arrgroup as $key) {
                    $datagroup[] = array("guide_id"=>$id,"group_id"=>$key);
                }
                $this->db->insert_batch("guides_groups",$datagroup);
            }
            $this->global_model->insertLog("memperbaharui data panduan (".$data['name'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data panduan";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data panduan";
        }
        return $response;
    }
}