<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_aplikasi_model extends CI_Model {
	private $table="data_aplikasi";
    private $primarykey="id_aplikasi";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $group          = $this->session->userdata("group_id");
        $this->db->select("da.*,case when da.status='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Terverifikasi</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Belum Diverifikasi</span>' end as active")
            ->from($this->table." as da");
            
            if(in_array(3,$group)){
                 $this->db->where("da.id_opd",$this->session->userdata("id_opd"));
            }

            if($searchall!=""){
                $searchall   = explode(" ", $searchall);
                $this->db->group_start();
                foreach ($searchall as $val) {
                    $this->db->like("UPPER(mp.nama_pansel)",strtoupper($val));
                    $this->db->or_like("UPPER(mp.nomor_identitas)",strtoupper($val));
                    $this->db->or_like("UPPER(mp.jenis_identitas)",strtoupper($val));
                    $this->db->or_like("UPPER(mp.asal)",strtoupper($val));
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
                if($val['status']==0){
                    if($param['modulecrud']['acl_update']==1){
                        $val['aksi'].= $button_update;
                    }
                    if($param['modulecrud']['acl_delete']==1){
                        $val['aksi'].= $button_delete;
                    }
                }else{
                //     if(in_array(1,$group)){
                //         if($param['modulecrud']['acl_update']==1){
                //             $val['aksi'].= $button_update;
                //         }
                //    }
                        $val['aksi'].= '<button type="button" class="btn btn-sm btn-primary open-form m-r-5"><i class="ti-eye"></i></button>';
                    
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
                $nama = $this->global_model->getdataoneField("nama_aplikasi","data_aplikasi",array("id_aplikasi"=>$id));
                $file = $this->global_model->getdataoneField("file_spt","data_aplikasi",array("id_aplikasi"=>$id));
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    @unlink("./".$file);
                    $this->global_model->insertLog("menghapus data aplikasi dengan nama ".$nama);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data aplikasi";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data aplikasi";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data aplikasi";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($file,$logo){   
        
        $basis_aplikasi = $this->input->post("newbasis_aplikasi");
        $arrbasis_aplikasi = "";
        foreach($basis_aplikasi as $val){
            $arrbasis_aplikasi .= $val.',';
        }
        $data=array(
                "id_opd"=>trim($this->input->post("newopd")),
                "asal_aplikasi"=>trim($this->input->post("newasal_aplikasi")),
                "nama_aplikasi"=>trim($this->input->post("newnama_aplikasi")),
                "id_jenis_layanan"=>trim($this->input->post("newjenis_layanan")),
                "deskripsi_aplikasi"=>trim($this->input->post("newdeskripsi_aplikasi")),
                "tahun"=>trim($this->input->post("newtahun")),
                "basis_aplikasi"=>$arrbasis_aplikasi,
                "bahasa_pemrograman"=>trim($this->input->post("newbahasa_pemrograman")),
                "id_dbms"=>trim($this->input->post("newdbms")),
                "id_lokasi_hosting"=>trim($this->input->post("newlokasi_hosting")),
                "jumlah_pengguna"=>trim($this->input->post("newjumlah_pengguna")),
                "id_frekuensi_penggunaan"=>trim($this->input->post("newfrekuensi_penggunaan")),
                "nama_domain"=>trim($this->input->post("newnama_domain")),
                "penyedia_aplikasi"=>trim($this->input->post("newpenyedia_aplikasi")),
                "pengembang_aplikasi"=>trim($this->input->post("newpengembang_aplikasi")),
                "ketersediaan_integrasi"=>trim($this->input->post("newketersediaan")),
                "metode_integrasi"=>trim($this->input->post("newmetode_integrasi")),
                "aplikasi_terintegrasi"=>trim($this->input->post("newaplikasi_integrasi")),
                "opd_terintegrasi"=>trim($this->input->post("newopd_terintegrasi")),
                "regulasi"=>trim($this->input->post("newregulasi")),
                "pic_aplikasi"=>trim($this->input->post("newpic_aplikasi")),
                "nip_pic"=>trim($this->input->post("newnip_pic")),
                "jabatan_pic"=>trim($this->input->post("newjabatan_pic")),
                "level_privileges"=>trim($this->input->post("newlevel_privileges")),
                "rencana_aplikasi"=>trim($this->input->post("newrencana_aplikasi")),
                "proses_bisnis"=>trim($this->input->post("newproses_bisnis")),
                "keterangan"=>trim($this->input->post("newketerangan")),
                "status"=>0,
                "no_telp"=>trim($this->input->post("newno_telp")),
                "created_by"=>$this->session->userdata("user_id"),
                "created_on"=>date("Y-m-d H:i:s"),
            ); 
        if($file!=null){
            $data["file_spt"] = $file;
        }
        if($logo!=null){
            $data["file_logo"] = $logo;
        }
        $save   = $this->db->insert($this->table,$data);
        if($save){             
            $this->global_model->insertLog("menambah data aplikasi (".$data['nama_aplikasi'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data aplikasi";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data aplikasi";
        }
        return $response;
    }

    public function updateData($file,$logo){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $basis_aplikasi = $this->input->post("newbasis_aplikasi");
        $arrbasis_aplikasi = "";
        foreach($basis_aplikasi as $val){
            $arrbasis_aplikasi .= $val.',';
        }
        $data=array(
            "id_opd"=>trim($this->input->post("newopd")),
            "asal_aplikasi"=>trim($this->input->post("newasal_aplikasi")),
            "nama_aplikasi"=>trim($this->input->post("newnama_aplikasi")),
            "id_jenis_layanan"=>trim($this->input->post("newjenis_layanan")),
            "deskripsi_aplikasi"=>trim($this->input->post("newdeskripsi_aplikasi")),
            "tahun"=>trim($this->input->post("newtahun")),
            "basis_aplikasi"=>$arrbasis_aplikasi,
            "bahasa_pemrograman"=>trim($this->input->post("newbahasa_pemrograman")),
            "id_dbms"=>trim($this->input->post("newdbms")),
            "id_lokasi_hosting"=>trim($this->input->post("newlokasi_hosting")),
            "jumlah_pengguna"=>trim($this->input->post("newjumlah_pengguna")),
            "id_frekuensi_penggunaan"=>trim($this->input->post("newfrekuensi_penggunaan")),
            "nama_domain"=>trim($this->input->post("newnama_domain")),
            "penyedia_aplikasi"=>trim($this->input->post("newpenyedia_aplikasi")),
            "pengembang_aplikasi"=>trim($this->input->post("newpengembang_aplikasi")),
            "ketersediaan_integrasi"=>trim($this->input->post("newketersediaan")),
            "metode_integrasi"=>trim($this->input->post("newmetode_integrasi")),
            "aplikasi_terintegrasi"=>trim($this->input->post("newaplikasi_integrasi")),
            "opd_terintegrasi"=>trim($this->input->post("newopd_terintegrasi")),
            "regulasi"=>trim($this->input->post("newregulasi")),
            "pic_aplikasi"=>trim($this->input->post("newpic_aplikasi")),
            "nip_pic"=>trim($this->input->post("newnip_pic")),
            "jabatan_pic"=>trim($this->input->post("newjabatan_pic")),
            "level_privileges"=>trim($this->input->post("newlevel_privileges")),
            "rencana_aplikasi"=>trim($this->input->post("newrencana_aplikasi")),
             "proses_bisnis"=>trim($this->input->post("newproses_bisnis")),
            "keterangan"=>trim($this->input->post("newketerangan")),
            "no_telp"=>trim($this->input->post("newno_telp")),
            "changed_by"=>$this->session->userdata("user_id"),
            "changed_on"=>date("Y-m-d H:i:s"),
        ); 
    if($file!=null){
        $data["file_spt"] = $file;
    }
    if($logo!=null){
        $data["file_logo"] = $logo;
    }
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            $this->global_model->insertLog("memperbaharui data aplikasi (".$data['nama_aplikasi'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data aplikasi";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data aplikasi";
        }
        return $response;
    }
}