<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_online_model extends CI_Model {
	private $table="users_online";
    private $primarykey="id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $username       = trim($this->input->post("search_username"));
        $name           = trim($this->input->post("search_name"));
        $group          = trim($this->input->post("search_group"));
        $order          = $this->input->post("order");
        $this->db->select("uo.id,uo.times,u.user_name,u.name,".$this->global_model->concat("g.group_name","group_name"))
                ->from($this->table." as uo")
                ->join("users as u","u.user_id=uo.user_id")
                ->join("users_groups ug","ug.user_id=u.user_id")
                ->join("groups as g","ug.group_id=g.group_id");
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
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
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
}