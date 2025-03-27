<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pilkada_model extends CI_Model {
	private $table="master_pilkada";
    private $primarykey="id_pilkada";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("p.*")
                ->from($this->table." as p");
        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(p.keterangan)",strtoupper($val));
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
                $val["tgl_pilkada"]        = changedate(date("d F Y",strtotime($val["tgl_pilkada"]))); 
                $val["tgl_awal_kampanye"]  = changedate(date("d F Y",strtotime($val["tgl_awal_kampanye"])));
                $val["tgl_akhir_kampanye"] = changedate(date("d F Y",strtotime($val["tgl_akhir_kampanye"])));
                $val["periode_awal"]       = changedate(date("d F Y",strtotime($val["periode_awal"])));
                $val["periode_akhir"]       = changedate(date("d F Y",strtotime($val["periode_akhir"])));               
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
            // if($this->global_model->checkRecordexist(array("pilkada"=>"id_pilkada"),$id)){
            //     $response["status"]   = "warning";
            //     $response["message"]  = "Tidak dapat menghapus data ini karena digunakan di tabel pilkada";
            // }else{
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    $this->global_model->insertLog("menghapus data pilkada dengan id ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data pilkada";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data pilkada";
                }
            // }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data pilkada";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData(){
        if($this->global_model->checkRecordexist(array("master_pilkada"=>"id_pilkada"),trim($this->input->post("newketerangan")))){
            $response["status"]   = "warning";
            $response["message"]  = "Data pilkada ini sudah ada";
        }else{
            $data=array(
                    "keterangan"=>trim($this->input->post("newketerangan")),
                    "tgl_pilkada"=>trim($this->input->post("newtgl_pilkada")),
                    "tgl_awal_kampanye"=>trim($this->input->post("newtgl_awal_kampanye")),
                    "tgl_akhir_kampanye"=>trim($this->input->post("newtgl_akhir_kampanye")),
                    "periode_awal"=>trim($this->input->post("newperiode_awal")),
                    "periode_akhir"=>trim($this->input->post("newperiode_akhir"))
                );
            $save   = $this->db->insert($this->table,$data);
            $id     = $this->db->insert_id();
            if($save){
                $this->global_model->insertLog("menambah data pilkada (".$data['keterangan'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data pilkada";
                $response["state"]    = "createdata";
                $response['autohide'] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data pilkada";
            }
        }
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        if($this->global_model->checkRecordexist(array("master_pilkada"=>""),array("id_pilkada"=>trim($this->input->post("newketerangan")),"keterangan<>"=>$id))){
            $response["status"]   = "warning";
            $response["message"]  = "Data pilkada ini sudah digunakan";
        }else{
            $data=array(
                    "keterangan"=>trim($this->input->post("newketerangan")),
                    "tgl_pilkada"=>trim($this->input->post("newtgl_pilkada")),
                    "tgl_awal_kampanye"=>trim($this->input->post("newtgl_awal_kampanye")),
                    "tgl_akhir_kampanye"=>trim($this->input->post("newtgl_akhir_kampanye")),
                    "periode_awal"=>trim($this->input->post("newperiode_awal")),
                    "periode_akhir"=>trim($this->input->post("newperiode_akhir"))
                );  
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $this->global_model->insertLog("memperbaharui data pilkada (".$data['keterangan'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data pilkada";
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data pilkada";
            }
        }
        return $response;
    }
}