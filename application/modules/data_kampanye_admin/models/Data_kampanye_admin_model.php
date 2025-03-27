<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_kampanye_admin_model extends CI_Model {
	private $table="laporan_kampanye";
    private $primarykey="id_kampanye";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $search_daerah  = trim($this->input->post("search_daerah"));
        $search_tanggal  = trim($this->input->post("stanggal"));
        $order          = $this->input->post("order");
        $this->db->select("lk.*,concat(mtp.nama,' & ',mtp2.nama) as paslon")
            ->from($this->table." as lk")
            ->join("paslon p","p.id_paslon=lk.id_paslon")
            ->join("master_tokoh_politik mtp","mtp.id_topol=p.id_topol_kepala")
            ->join("master_tokoh_politik mtp2","mtp2.id_topol=p.id_topol_wakil");   

        if($search_daerah!=""){
            $this->db->where("p.id_daerah",$search_daerah);
        }

        if($search_tanggal!=""){
            $this->db->where("lk.tanggal",$search_tanggal);
        }


        $order_by   = array_keys($param['column']);
        $order_by   = $order_by[$order[0]["column"]];
        if($order_by!=""){
            $this->db->order_by($order_by,$order[0]["dir"]);
            $this->db->order_by("lk.tanggal");
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
                //$val['tanggal'] = date("d-m-Y", strtotime($val['tanggal']));
                $val['waktu']   = "";
                $val['waktu']   = date("H:i", strtotime($val['jam_mulai'])).' s/d '.date("H:i", strtotime($val['jam_selesai']));
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

    public function getDatagridpdf($param){
        $search_daerah  = trim($this->input->post_get("search_daerah"));
        $search_tanggal  = trim($this->input->post_get("stanggal"));   
        $order          = $this->input->post("order");
        $this->db->select("lk.*,concat(date_format(lk.jam_mulai,'%H:%i'),' s/d ',date_format(lk.jam_selesai, '%H:%i')) as waktu,concat('Paslon ',p.no_urut,'  - ',mtp.nama,' & ',mtp2.nama) as paslon,group_concat(concat('<ul>','- ',mtp3.nama,'</ul>') separator '<br/>') as tokoh_hadir")
            ->from($this->table." as lk")
            ->join("paslon p","p.id_paslon=lk.id_paslon")
            ->join("master_tokoh_politik mtp","mtp.id_topol=p.id_topol_kepala")
            ->join("master_tokoh_politik mtp2","mtp2.id_topol=p.id_topol_wakil")
            ->join("laporan_kampanye_tokoh_politik lktp","lk.id_kampanye=lktp.id_kampanye")
            ->join("master_tokoh_politik mtp3","mtp3.id_topol=lktp.id_topol");   

        if($search_daerah!=""){
            $this->db->where("p.id_daerah",$search_daerah);
        }

        if($search_tanggal!=""){
            $this->db->where("lk.tanggal",$search_tanggal);
        }
        
        $this->db->group_by("lk.id_kampanye");  
        $this->db->order_by("lk.jam_mulai","asc");

        return $this->db->get();
    }

    public function getDokumentasipdf($param){
        $search_daerah  = trim($this->input->post_get("search_daerah"));
        $search_tanggal = trim($this->input->post_get("stanggal")); 
        $this->db->select("lk.*,lkd.file_foto")
        ->from($this->table." as lk")
        ->join("paslon p","p.id_paslon=lk.id_paslon")
        ->join("laporan_kampanye_dokumentasi lkd","lkd.id_kampanye=lk.id_kampanye");   

    if($search_daerah!=""){
        $this->db->where("p.id_daerah",$search_daerah);
    }

    if($search_tanggal!=""){
        $this->db->where("lk.tanggal",$search_tanggal);
    } 


    return $this->db->get(); 

    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }
}