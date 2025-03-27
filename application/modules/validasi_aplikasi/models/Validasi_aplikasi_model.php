<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validasi_aplikasi_model extends CI_Model {
	private $table="data_aplikasi";
    private $primarykey="id_aplikasi";
	public function __construct(){
	   	
	}

    public function getDatagrid($param){
        $this->load->helper('file');    
        $status      = trim($this->input->post("search_status"));
        $opd      = trim($this->input->post("search_opd"));
        $jenis      = trim($this->input->post("search_jenis"));
        $asal      = trim($this->input->post("search_asal"));
        $order          = $this->input->post("order");
        $this->db->select("da.*,mo.nama as nama_opd,case when da.status='1' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Terverifikasi</span>' else '<span class=\"text-warning\"><i class=\"ti-alarm-clock\"></i> Belum Tervalidasi</span>' end as active")
            ->from($this->table." as da")
            ->join("master_opd as mo","mo.id_opd=da.id_opd","left");

            if($status!=""){
				$this->db->where("da.status",$status);
			}
		
		if($opd!=""){
				$this->db->where("da.id_opd",$opd);
			}

        if($jenis!=""){
            $this->db->where("da.id_jenis_layanan",$jenis);
        }
		
		if($asal!=""){
            $this->db->where("da.asal_aplikasi",$asal);
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
            $button_update  = '<button type="button" class="btn btn-sm btn-success open-form m-r-5"><i class="ti-check"></i></button>';
            $button_delete  = $this->load->view("properties/button_delete_data",null,true);
			
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['aksi']    = "";
                $val['url']    = "";
				if(!(preg_match('/http/i', $val["nama_domain"]))==1){
                	$val["url"] = "<a href='https://".$val["nama_domain"]."' target='_blank'>".$val["nama_domain"]."</a>";
				}else{
					$val["url"] = "<a href='".$val["nama_domain"]."' target='_blank'>".$val["nama_domain"]."</a>";
					}
                if($param['modulecrud']['acl_update']==1){
                    $val['aksi'].= $button_update;
                }
                if($param['modulecrud']['acl_delete']==1){
                    $val['aksi'].= $button_delete;
                }
				if($val['status']==1){
					$val['aksi'] .= '<br><span class="badge badge-primary"><i class="ti-check"></i> Terverifikasi</span>';
				}else{
					$val['aksi'] .= '<br><span class="badge badge-danger"><i class="ti-close"></i> Belum Terverifikasi</span>';
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
                $nama = $this->global_model->getdataoneField("nama_pansel","master_pansel",array("id_pansel"=>$id));
                $file = $this->global_model->getdataoneField("file","master_pansel",array("id_pansel"=>$id));
                $delete=$this->db->where($this->primarykey,$id)->delete($this->table);
                if($delete){
                    @unlink("./".$file);
                    $this->global_model->insertLog("menghapus data master pansel dengan nama ".$nama);
                    $response["status"]   = "success";
                    $response["message"]  = "Berhasil menghapus data master pansel";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "Gagal menghapus data master pansel";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data master pansel";
        }
        return $response;
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function createData($file){   
        
        $basis_aplikasi = $this->input->post("newbasis_aplikasi");
        $arrbasis_aplikasi = "";
        foreach($basis_aplikasi as $val){
            $arrbasis_aplikasi .= $val.',';
        }
        $data=array(
                "id_opd"=>$this->session->userdata("id_opd"),
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
                "metode_integrasi"=>trim($this->input->post("newmetode_integrasi")),
                "aplikasi_terintegrasi"=>trim($this->input->post("newaplikasi_integrasi")),
                "opd_terintegrasi"=>trim($this->input->post("newopd_terintegrasi")),
                "regulasi"=>trim($this->input->post("newregulasi")),
                "pic_aplikasi"=>trim($this->input->post("newpic_aplikasi")),
                "nip_pic"=>trim($this->input->post("newnip_pic")),
                "jabatan_pic"=>trim($this->input->post("newjabatan_pic")),
                "level_privileges"=>trim($this->input->post("newlevel_privileges")),
                "rencana_aplikasi"=>trim($this->input->post("newrencana_aplikasi")),
                "keterangan"=>trim($this->input->post("newketerangan")),
                "status"=>0,
                "no_telp"=>trim($this->input->post("newno_telp")),
                "created_by"=>$this->session->userdata("user_id"),
                "created_on"=>date("Y-m-d H:i:s"),
            ); 
        if($file!=null){
            $data["file_spt"] = $file;
        }
        $save   = $this->db->insert($this->table,$data);
        if($save){             
            $this->global_model->insertLog("menambah data master pansel (".$data['nama_pansel'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil menyimpan data master pansel";
            $response["autohide"] = true;
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menyimpan data master pansel";
        }
        return $response;
    }

    public function updateData($file){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        
        $data=array(
			"id_jenis_layanan"=>trim($this->input->post("newjenis_layanan")),
            "status"=>trim($this->input->post("newvalidasi")),
            "approved_by"=>$this->session->userdata("user_id"),
            "approved_on"=>date("Y-m-d H:i:s"),
        ); 
        $save=$this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            $this->global_model->insertLog("memvalidasi data aplikasi dengan id (".$id.")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memvalidasi data aplikasi dengan id";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memvalidasi data aplikasi dengan id";
        }
        return $response;
    }
	
	public function getDatagridpdf($param){
        $search_opd  = trim($this->input->post_get("search_opd"));
        $jenis      = trim($this->input->post_get("search_jenis"));
        $order          = $this->input->post("order");
        $this->db->select("da.*,mo.nama as nama_opd,mjl.jenis_layanan")
            ->from($this->table." as da")
            ->join("master_opd as mo","mo.id_opd=da.id_opd","left")
			->join("master_jenis_layanan as mjl","mjl.id_jenis_layanan=da.id_jenis_layanan");
        if($search_opd!=""){
            $this->db->where("da.id_opd",$search_opd);
        }

        if($jenis!=""){
            $this->db->where("da.id_jenis_layanan",$jenis);
        }

        return $this->db->get();
    }
}