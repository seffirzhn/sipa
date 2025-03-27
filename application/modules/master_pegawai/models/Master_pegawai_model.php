<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pegawai_model extends CI_Model {
	private $table="master_pegawai";
    private $primarykey="id_pegawai";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("mp.*,concat(p.nama_pangkat,' (',p.golongan_ruang,')') as pangkat,o.nama as opd,case when mp.is_active='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Aktif</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Tidak Aktif</span>' end as active")
            ->from($this->table." as mp")
            ->join("master_pangkat as p","p.id_pangkat=mp.id_pangkat")
            ->join("master_opd as o","o.id_opd=mp.id_opd");

            if($searchall!=""){
                $searchall   = explode(" ", $searchall);
                $this->db->group_start();
                foreach ($searchall as $val) {
                    $this->db->like("UPPER(mp.nama_pegawai)",strtoupper($val));
                    $this->db->or_like("UPPER(mp.nip)",strtoupper($val));
                    $this->db->or_like("UPPER(o.nama)",strtoupper($val));
                    $this->db->or_like("UPPER(p.nama_pangkat)",strtoupper($val));
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
                $val["kontak"]="";
                if($val["file"]!=""){
                    $val["file"] = "<a href='javascript:;' class='btn-preview' data-name='Foto Tokoh' data-link='".base_url($val["file"])."' data-ext='".get_mime_by_extension(realpath($val["file"]))."'><img src='".base_url($val["file"])."'  height='50px'/></a>";
                }
                $val["kontak"] = $val["no_telp"].'<br>'.$val['email'];
                // if($val['foto']){
                //     $ext_file = get_mime_by_extension(realpath($val['file']));
                //     $val["berkas"]   .= '<button class="btn btn-xs btn-info btn-preview"  data-name="FILE INFORMASI PUBLIK" data-link="'.base_url($val['foto']).'" data-ext="'.$ext_file.'" type="button"><i class="ti-clip"></i></button>';
                // }
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
                $delete=null;
                $nama = $this->global_model->getdataoneField("nama_pegawai","master_pegawai",array("id_pegawai"=>$id));
                $file = $this->global_model->getdataoneField("file","master_pegawai",array("id_pegawai"=>$id));
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    @unlink("./".$file);
                    $this->global_model->insertLog("menghapus data master pegawai dengan nama ".$nama);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data master pegawai";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data master pegawai";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data master pegawai";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($file){   
        if($this->global_model->checkRecordexist(array("master_pegawai"=>"nip"),trim($this->input->post("newnip")))){
            $response["status"]   = "warning";
            $response["message"]  = "Data pegawai ini sudah ada";
        }else{     
                $data=array(
                    "nama_pegawai"=>trim($this->input->post("newnama_pegawai")),
                    "nip"=>trim($this->input->post("newnip")),
                    "id_pangkat"=>trim($this->input->post("newpangkat")),
                    "id_opd"=>trim($this->input->post("newopd")),
                    "jabatan"=>trim($this->input->post("newjabatan")),
                    "alamat"=>trim($this->input->post("newalamat")),
                    "no_telp"=>trim($this->input->post("newno_telp")),
                    "email"=>trim($this->input->post("newemail")),
                    "is_active"=>trim($this->input->post("newactive")),
                    "created_by"=>$this->session->userdata("user_id"),
                    "created_on"=>date("Y-m-d H:i:s"),
                ); 
            if($file!=null){
                $data["file"] = $file;
            }
            $save   = $this->db->insert($this->table,$data);
            if($save){             
                $this->global_model->insertLog("menambah data master pegawai (".$data['nama_pegawai'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil menyimpan data master pegawai";
                $response["autohide"] = true;
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menyimpan data master pegawai";
            }
        }
        return $response;
    }

    public function updateData($file){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        if($this->global_model->checkRecordexist(array("master_pegawai"=>""),array("nip"=>trim($this->input->post("newnip")),"id_pegawai<>"=>$id))){
            $response["status"]   = "warning";
            $response["message"]  = "Data pegawai ini sudah ada";
        }else{
            $data=array(
                "nama_pegawai"=>trim($this->input->post("newnama_pegawai")),
                "nip"=>trim($this->input->post("newnip")),
                "id_pangkat"=>trim($this->input->post("newpangkat")),
                "id_opd"=>trim($this->input->post("newopd")),
                "jabatan"=>trim($this->input->post("newjabatan")),
                "alamat"=>trim($this->input->post("newalamat")),
                "no_telp"=>trim($this->input->post("newno_telp")),
                "email"=>trim($this->input->post("newemail")),
                "is_active"=>trim($this->input->post("newactive")),
                "changed_by"=>$this->session->userdata("user_id"),
                "changed_on"=>date("Y-m-d H:i:s"),
            );  
            if($file!=null){
                $data["file"] = $file;
            }
            $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
            if($save){
                $this->global_model->insertLog("memperbaharui data master pegawai (".$data['nama_pegawai'].")");
                $response["status"]   = "success";
                $response["message"]  = "Berhasil memperbaharui data master pegawai";
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal memperbaharui data master pegawai";
            }
        }
        return $response;
    }
}