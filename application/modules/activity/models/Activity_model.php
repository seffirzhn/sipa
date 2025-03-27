<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model {
	private $table="users_log";
    private $primarykey="user_log_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $username       = trim($this->input->post("search_username"));
        $activity       = trim($this->input->post("search_activity"));
        $datemin        = trim($this->input->post("sdatemin"));         
        $datemax        = trim($this->input->post("sdatemax"));     
        $order          = $this->input->post("order");
        $this->db->select("ul.user_log_id,ul.activity,ul.created_on,u.user_name")
                ->from($this->table." as ul")
                ->join("users as u","ul.created_by=u.user_id");
        
        if($username!=""){
            $username   = explode(" ", $username);
            foreach ($username as $val) {
                $this->db->like("UPPER(u.user_name)",strtoupper($val));
            }
        }

        if($activity!=""){
            $activity   = explode(" ", $activity);
            foreach ($activity as $val) {
                $this->db->like("UPPER(ul.activity)",strtoupper($val));
            }
        }

        if($datemin!="" && $datemax!=""){
            $this->db->group_start();
            $this->db->where("date(ul.created_on)>=",$datemin);
            $this->db->where("date(ul.created_on)<=",$datemax);
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
            $no         = $page;
            $data_grid  = $this->db->query($query); 
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['checklist'] = '<div class="checkbox"><input class="for_cek_all" id="cb'.$no.'" title="pilih" type="checkbox"/><label for="cb'.$no.'"></label></div>';
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
        $id = $this->input->post("checklist");
        if($id!=""){
            $arrid = array();
            foreach ($id as $keyid) {
                $arrid[] = $this->encryption->decrypt($keyid);
            }
            $delete=null;
            $delete=$this->db->where_in($this->primarykey,$arrid)->delete($this->table);
            if($delete){
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menghapus data aktivitas";
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menghapus data aktivitas";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data aktivitas";
        }
        return $response;
    }

    function deleteAlldata(){
        $delete=$this->db->truncate($this->table);
        if($delete){
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menghapus semua data aktivitas";
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus semua data aktivitas";
        }
        return $response;
    }
}