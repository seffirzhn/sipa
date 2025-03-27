<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_gateway_model extends CI_Model {
	private $table="send_mail";
    private $primarykey="send_mail_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $destination    = trim($this->input->post("search_destination"));
        $subject        = trim($this->input->post("search_subject"));
        $active         = trim($this->input->post("search_active"));
        $datemin        = trim($this->input->post("sdatemin"));         
        $datemax        = trim($this->input->post("sdatemax")); 
        $order          = $this->input->post("order");
        $this->db->select("g.send_mail_id,g.subject,g.destination,g.sendingtime,g.deliverytime,case when g.status='y' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Terkirim</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Belum / Tidak Terkirim</span>' end as status")
                ->from($this->table." as g");
        
        if($destination!=""){
            $destination   = explode(" ", $destination);
            foreach ($destination as $val) {
                $this->db->like("UPPER(g.destination)",strtoupper($val));
            }
        }

        if($subject!=""){
            $subject   = explode(" ", $subject);
            foreach ($subject as $val) {
                $this->db->like("UPPER(g.subject)",strtoupper($val));
            }
        }

        if($active!=""){
            $this->db->where("g.status",$active);
        }

        if($datemin!="" && $datemax!=""){
            $this->db->group_start();
            $this->db->where("date(g.sendingtime)>=",$datemin);
            $this->db->where("date(g.sendingtime)<=",$datemax);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where("date(g.deliverytime)>=",$datemin);
            $this->db->where("date(g.deliverytime)<=",$datemax);
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
            $button_baca    = form_button(array("type"=>"button","class"=>"btn btn-sm btn-primary open-form m-r-5","content"=>"Baca"));
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['aksi']    = $button_baca;
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

    public function getDataprint(){
        $destination    = trim($this->input->post_get("search_destination"));
        $subject        = trim($this->input->post_get("search_subject"));
        $active         = trim($this->input->post_get("search_active"));
        $datemin        = trim($this->input->post_get("sdatemin"));         
        $datemax        = trim($this->input->post_get("sdatemax")); 
        $this->db->select("g.send_mail_id,g.subject,g.destination,g.sendingtime,g.deliverytime,case when g.status='y' then 'Terkirim' else 'Belum / Tidak Terkirim' end as status")
                ->from($this->table." as g");
        
        if($destination!=""){
            $destination   = explode(" ", $destination);
            foreach ($destination as $val) {
                $this->db->like("UPPER(g.destination)",strtoupper($val));
            }
        }

        if($subject!=""){
            $subject   = explode(" ", $subject);
            foreach ($subject as $val) {
                $this->db->like("UPPER(g.subject)",strtoupper($val));
            }
        }

        if($active!=""){
            $this->db->where("g.status",$active);
        }

        if($datemin!="" && $datemax!=""){
            $this->db->group_start();
            $this->db->where("date(g.sendingtime)>=",$datemin);
            $this->db->where("date(g.sendingtime)<=",$datemax);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where("date(g.deliverytime)>=",$datemin);
            $this->db->where("date(g.deliverytime)<=",$datemax);
            $this->db->group_end();
        }

        $this->db->order_by("g.sendingtime","desc");

        return $this->db->get();
    }

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return $this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id)));
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
                $response["message"]  = "Berhasil menghapus data email";
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menghapus data email";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data email";
        }
        return $response;
    }

    public function updateData(){
        $id         = $this->encryption->decrypt(trim($this->input->post("id")));
        $config     = $this->global_model->getDataonerow("subject,message,destination,attachment","send_mail",array($this->primarykey=>$id));
        if($config!=null){
            $config_site            = $this->global_model->getConfigsite();
            $config['hostmail']     = $config_site["hostmail"];
            $config['portmail']     = $config_site["portmail"];
            $config['email']        = $config_site["email"];
            $config['passmail']     = $this->encryption->decrypt($config_site["passmail"]);
            $config['nameapps']     = $config_site["name"];
            $config["instansi"]     = $this->global_model->getConfigcompany("nama");
            $this->load->library("emailgateway");
            $result                 = $this->emailgateway->send($config);
            if($result["status"]=="success"){
                $this->db->where("send_mail_id",$id)->update("send_mail",array("deliverytime"=>date("Y-m-d H:i:s"),"status"=>"y"));
                $response["status"]   = "success";
                $response["message"]  = $result["message"];
                $response["autohide"] = true;    
            }else{
                $response["status"]   = "error";
                $response["message"]  = $result["message"];
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Data email tidak ditemukan";
        }
        
        return $response;
    }
}