<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_kampanye_model extends CI_Model {
	private $table="laporan_kampanye";
    private $primarykey="id_kampanye";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $search_paslon  = trim($this->input->post("search_paslon"));
        $search_tanggal  = trim($this->input->post("search_tanggal"));
        $order          = $this->input->post("order");
        $tempatkegiatan = trim($this->input->post("search_tempatkegiatan"));
        $this->db->select("lk.*,concat(mtp.nama,' & ',mtp2.nama) as paslon")
            ->from($this->table." as lk")
            ->join("paslon p","p.id_paslon=lk.id_paslon")
            ->join("master_tokoh_politik mtp","mtp.id_topol=p.id_topol_kepala")
            ->join("master_tokoh_politik mtp2","mtp2.id_topol=p.id_topol_wakil")
            ->where("p.id_daerah",$this->session->userdata("id_daerah"));   

        if($tempatkegiatan!=""){
            $this->db->like("lk.tempat_kegiatan",$tempatkegiatan);
        }

        if($search_paslon!=""){
            $this->db->like("lk.id_paslon",$search_paslon);
        }

        if($search_tanggal!=""){
            $this->db->like("lk.tanggal",$search_tanggal);
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
                //$val['tanggal'] = date("d - m - Y", strtotime($val['tanggal']));
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

    function deleteData(){
        $id = trim($this->input->post("id"));
        if($id!=""){
                $id = $this->encryption->decrypt($id);
                $delete=null;
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    $listfoto  = $this->global_model->getDataonedimension("laporan_kampanye_dokumentasi","file_foto",array($this->primarykey=>$id));
                    if($listfoto!=null){
                        foreach ($listfoto as $key => $value) {
                            @unlink("./".$value);
                        }
                    }
                    $this->db->where($this->primarykey,$id)->delete("laporan_kampanye_dokumentasi");
                    $this->db->where($this->primarykey,$id)->delete("laporan_kampanye_tokoh_politik");
                    $this->global_model->insertLog("menghapus data kampanye dengan id kampanye ".$id);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data kampanye";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data kampanye";
                }
                
                
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data kampanye";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($filedokumentasi){   
        
        $data=array(
                "id_paslon"=>trim($this->input->post("newpaslon")),
                "tanggal"=>trim($this->input->post("newtanggal")),
                "jam_mulai"=>trim(date("H:i:s", strtotime($this->input->post("newjam_mulai")))),
                "jam_selesai"=>trim(date("H:i:s", strtotime($this->input->post("newjam_selesai")))),
                "tempat_kegiatan"=>trim($this->input->post("newlokasi_kampanye")),
                "jumlah_peserta"=>trim($this->input->post("newjumlah_peserta")),
                "uraian_kegiatan"=>trim($this->input->post("newuraian_kegiatan")),
                "keterangan"=>trim($this->input->post("newketerangan")),
                "created_by"=>$this->session->userdata("user_id"),
                "created_on"=>date("Y-m-d H:i:s"),
            ); 
        $save   = $this->db->insert($this->table,$data);
        $id     = $this->db->insert_id();
        if($save){             
            //start kelola rincian 
            $countdata = count($this->input->post("newtopol")); 
            $countdok  = count($filedokumentasi);
            $topol = $this->input->post("newtopol");
            if(isset($countdata)){
                $datatopol_insert   = array();
                for ($i=0; $i < $countdata ; $i++) {
                        $data_topol   = array(
                                        "id_kampanye"=>$id,
                                        "id_topol"=>trim($topol[$i]),
                                    );
                        $datatopol_insert[] = $data_topol;                        
                    
                }
                if(count($datatopol_insert)>0){
                   
                    $this->db->insert_batch("laporan_kampanye_tokoh_politik",$datatopol_insert);
                }
            }

            if(isset($countdok)){
                $datadok_insert     = array();
                for ($i=0; $i < $countdok ; $i++){
                    $data_dok   = array(
                                    "id_kampanye"=>$id,
                                    "file_foto"=>$filedokumentasi[$i],
                                );
                    $datadok_insert[] = $data_dok; 
                }
                if(count($datadok_insert)>0){
                    $this->db->insert_batch("laporan_kampanye_dokumentasi",$datadok_insert);
                }
            }
            //end kelola rincian 
            $this->global_model->insertLog("menambah data kampanye (".$id.")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data kampanye";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data kampanye";
        }
        return $response;
    }

    public function updateData($filedokumentasi){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data=array(
            "id_paslon"=>trim($this->input->post("newpaslon")),
            "tanggal"=>trim($this->input->post("newtanggal")),
            "jam_mulai"=>trim(date("H:i:s", strtotime($this->input->post("newjam_mulai")))),
            "jam_selesai"=>trim(date("H:i:s", strtotime($this->input->post("newjam_selesai")))),
            "tempat_kegiatan"=>trim($this->input->post("newlokasi_kampanye")),
            "jumlah_peserta"=>trim($this->input->post("newjumlah_peserta")),
            "uraian_kegiatan"=>trim($this->input->post("newuraian_kegiatan")),
            "keterangan"=>trim($this->input->post("newketerangan")),
            "changed_by"=>$this->session->userdata("user_id"),
            "changed_on"=>date("Y-m-d H:i:s"),
        );  
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            //start kelola rincian 
            $countdok                = count($filedokumentasi);
            $list_topol              = $this->db->get_where("laporan_kampanye_tokoh_politik",array("id_kampanye"=>$id));
            $list_dokumentasi        = $this->db->get_where("laporan_kampanye_dokumentasi",array("id_kampanye"=>$id));
            $id_topol_kampanye       = $this->input->post("id_topol_kampanye");
            $topol                   = $this->input->post("newtopol");
            if($list_topol->num_rows()){
                $data_topol_update   = array();
                foreach ($list_topol->result_array() as $key => $value) {
                    if(isset($id_topol_kampanye)>0 and in_array($value["id_topol_kampanye"],$id_topol_kampanye)){
                        $keys   = str_replace(array("[","]",'"',','),array("","","",""),json_encode(array_keys($id_topol_kampanye, $value["id_topol_kampanye"])));
                        $data_rincian = array(
                                        "id_topol_kampanye"=>$value["id_topol_kampanye"],
                                        "id_kampanye"=>$id,
                                        "id_topol"=>trim($topol[$keys]),
                                    );
                        // if($filerincian[$keys]!=""){
                        //     $data_rincian["file"] = $filerincian[$keys];
                        // }else{
                        //     $data_rincian["file"] = null;
                        // }
                        $data_topol_update[] = $data_rincian;
                    }else{
                        $this->db->where(array("id_topol_kampanye"=>$value["id_topol_kampanye"],"id_kampanye"=>$id))->delete("laporan_kampanye_tokoh_politik");
                    }
                }
                if(count($data_topol_update)>0){
                    $this->db->update_batch("laporan_kampanye_tokoh_politik",$data_topol_update,"id_topol_kampanye");
                }
            }

            if(isset($id_topol_kampanye)){
                $data_topol_insert   = array();
                foreach ($id_topol_kampanye as $key => $value) {
                    if($value==""){
                        $data_topol   = array(
                                            "id_kampanye"=>$id,
                                            "id_topol"=>trim($topol[$key]),
                                         );
                        // if($filerincian[$key]!=""){
                        //     $data_rincian["file"] = $filerincian[$key];
                        // }
                        $data_topol_insert[] = $data_topol;
                        
                    }
                }
                if(count($data_topol_insert)>0){
                    $this->db->insert_batch("laporan_kampanye_tokoh_politik",$data_topol_insert);
                }
            }

            if(isset($countdok)){
                $datadok_insert     = array();
                for ($i=0; $i < $countdok ; $i++){
                    $data_dok   = array(
                                    "id_kampanye"=>$id,
                                    "file_foto"=>$filedokumentasi[$i],
                                );
                    if($filedokumentasi[$i]!=""){
                    $datadok_insert[] = $data_dok;
                    } 
                }
                if(count($datadok_insert)>0){
                    $this->db->insert_batch("laporan_kampanye_dokumentasi",$datadok_insert);
                }
            }
            //end kelola rincian
            $this->global_model->insertLog("memperbaharui data kampanye id (".$id.")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data kampanye";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data kampanye";
        }
        return $response;
    }
}