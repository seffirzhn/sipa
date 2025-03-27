<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paslon_model extends CI_Model {
	private $table="paslon";
    private $primarykey="id_paslon";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $nama           = trim($this->input->post("search_nama"));
        $pilkada          = trim($this->input->post("search_pilkada"));
        $daerah          = trim($this->input->post("search_daerah"));
        $this->db->select("p.*,md.nama_daerah,mtp1.nama as nama_kepala, mtp2.nama as nama_wakil")
            ->from($this->table." as p")
            ->join("master_pilkada pil","pil.id_pilkada=p.id_pilkada")
            ->join("master_daerah md","md.id_daerah=p.id_daerah")
            ->join("master_tokoh_politik as mtp1","mtp1.id_topol=p.id_topol_kepala","left")
            ->join("master_tokoh_politik as mtp2","mtp2.id_topol=p.id_topol_wakil","left")
            ->where("p.id_pilkada",$pilkada)
            ->where("p.id_daerah",$daerah);
            // ->order_by("p.id_daerah");

        
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
                $val["berkas"]="";
                if($val["foto_paslon"]!=""){
                    $val["foto_paslon"] = "<a href='javascript:;' class='btn-preview' data-name='Foto Tokoh' data-link='".base_url($val["foto_paslon"])."' data-ext='".get_mime_by_extension(realpath($val["foto_paslon"]))."'><img src='".base_url($val["foto_paslon"])."'  height='50px'/></a>";
                }
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
                $file = $this->global_model->getdataoneField("foto_paslon","paslon",array("id_paslon"=>$id));
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    @unlink("./".$file);
                    $this->global_model->insertLog("menghapus data paslon dengan id ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data paslon";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data paslon";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data paslon";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($file){        
        $data=array(
                "id_pilkada"=>trim($this->input->post("newpilkada")),
                "id_daerah"=>trim($this->input->post("newdaerah")),
                "no_urut"=>trim($this->input->post("newno_urut")),
                "id_topol_kepala"=>trim($this->input->post("newkepala")),
                "id_topol_wakil"=>trim($this->input->post("newwakil")),
                "created_by"=>$this->session->userdata("user_id"),
                "created_on"=>date("Y-m-d H:i:s"),
            ); 
        if($file!=null){
            $data["foto_paslon"] = $file;
        }
        $save   = $this->db->insert($this->table,$data);
        $id     = $this->db->insert_id();
        if($save){             
            $this->global_model->insertLog("menambah data paslon (".$id.")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data paslon";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data paslon";
        }
        return $response;
    }

    public function updateData($file){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data=array(
            "id_pilkada"=>trim($this->input->post("newpilkada")),
            "id_daerah"=>trim($this->input->post("newdaerah")),
            "no_urut"=>trim($this->input->post("newno_urut")),
            "id_topol_kepala"=>trim($this->input->post("newkepala")),
            "id_topol_wakil"=>trim($this->input->post("newwakil")),
            "changed_by"=>$this->session->userdata("user_id"),
            "changed_on"=>date("Y-m-d H:i:s"),
        );  
        if($file!=null){
            $data["foto_paslon"] = $file;
        }
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            $this->global_model->insertLog("memperbaharui data paslon dengan id (".$id.")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data paslon";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data paslon";
        }
        return $response;
    }
}