<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_jenis_nilai_model extends CI_Model {
	private $table="master_jenis_nilai";
    private $primarykey="id_jenis_nilai";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("mjn.*,case when mjn.is_active='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Aktif</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Tidak Aktif</span>' end as active")
                ->from($this->table." as mjn");
        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(mjn.nama_jenis_nilai)",strtoupper($val));
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
                $val['bobot'] = $val['bobot'].'%';      
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
            $data=array(
                    "nama_jenis_nilai"=>trim($this->input->post("newjenis_nilai")),
                    "bobot"=>trim($this->input->post("newbobot")),
                    "keterangan"=>trim($this->input->post("newketerangan")),
                    "is_active"=>trim($this->input->post("newactive")),
                    "created_by"=>$this->session->userdata("user_id"),
                    "created_on"=>date("Y-m-d H:i:s")
                );

            $save   = $this->db->insert($this->table,$data);
            if($save){
                $this->global_model->insertLog("menambah data master jenis penilaian (".$data['nama_jenis_nilai'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data master jenis penilaian";
                $response["state"]    = "createdata";
                $response['autohide'] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data master jenis penilaian";
            }
        
        return $response;
    }

    public function updateData(){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
            $data=array(
                "nama_jenis_nilai"=>trim($this->input->post("newjenis_nilai")),
                "bobot"=>trim($this->input->post("newbobot")),
                "keterangan"=>trim($this->input->post("newketerangan")),
                "is_active"=>trim($this->input->post("newactive")),
                "changed_by"=>$this->session->userdata("user_id"),
                "changed_on"=>date("Y-m-d H:i:s")
            );
        
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $this->global_model->insertLog("memperbaharui data master jenis nilai (".$data['nama_jenis_nilai'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data master jenis nilai";
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data master jenis nilai";
            }
        return $response;
    }
}