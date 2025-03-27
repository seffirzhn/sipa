<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parpol_model extends CI_Model {
	private $table="master_partai_politik";
    private $primarykey="id_parpol";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("pp.*")
                ->from($this->table." as pp");
        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(pp.nama)",strtoupper($val));
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
                $val["logo"]    = "<img src='".$val["logo"]."' height='50px'/>";
                if($param['modulecrud']['acl_update']==1){
                    $val['aksi'].= $button_update;
                }
                if($param['modulecrud']['acl_delete']==1){
                    $val['aksi'].= $button_delete;
                }             
                // unset($val[$this->primarykey]);
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
            // if($this->global_model->checkRecordexist(array("kab_kota"=>"id_kab_kota"),$id)){
            //     $response["status"]   = "warning";
            //     $response["message"]  = "Tidak dapat menghapus data ini karena digunakan di tabel kab_kota";
            // }else{
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    $this->global_model->insertLog("menghapus data partai politik dengan id ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data partai politik";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data partai politik";
                }
            // }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data partai politik";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($logo=""){
        if($this->global_model->checkRecordexist(array("master_partai_politik"=>"nama"),trim($this->input->post("newnama")))){
            $response["status"]   = "warning";
            $response["message"]  = "Data partai politik ini sudah ada";
        }else{
            $data=array(
                    "nama"=>trim($this->input->post("newnama")),
                    "created_by"=>$this->session->userdata("user_id"),
                    "created_on"=>date("Y-m-d H:i:s")
                );
            if($logo!=""){
                $data["logo"] = $logo;
            }
            $save   = $this->db->insert($this->table,$data);
            if($save){
                $this->global_model->insertLog("menambah data partai politik (".$data['nama'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data partai politik";
                $response["state"]    = "createdata";
                $response['autohide'] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data partai politik";
            }
        }
        return $response;
    }

    public function updateData($logo=""){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        if($this->global_model->checkRecordexist(array("master_partai_politik"=>""),array("id_parpol"=>trim($this->input->post("newid_parpol")),"id_parpol<>"=>$id))){
            $response["status"]   = "warning";
            $response["message"]  = "Data partai politik ini sudah digunakan";
        }else{
            $data=array(
                    "nama"=>trim($this->input->post("newnama")),
                    "changed_by"=>$this->session->userdata("user_id"),
                    "changed_on"=>date("Y-m-d H:i:s")
                ); 
            if($logo!=""){
                $data["logo"] = $logo;
            } 
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $this->global_model->insertLog("memperbaharui data partai politik (".$data['nama'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data partai politik";
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data partai politik";
            }
        }
        return $response;
    }
}