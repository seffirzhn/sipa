<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway_model extends CI_Model {
	private $table="send_sms";
    private $primarykey="send_sms_id";
	public function __construct(){
		
	}

    public function getDatagrid($param){
        $destination    = trim($this->input->post("search_destination"));
        $message        = trim($this->input->post("search_message"));
        $active         = trim($this->input->post("search_active"));
        $datemin        = trim($this->input->post("sdatemin"));         
        $datemax        = trim($this->input->post("sdatemax")); 
        $order          = $this->input->post("order");
        $this->db->select("g.send_sms_id,g.message,g.destination,g.sendingtime,g.deliverytime,case when g.status='y' then '<span class=\"text-success\"><i class=\"ti-check\"></i> Terkirim</span>' else '<span class=\"text-danger\"><i class=\"ti-close\"></i> Belum / Tidak Terkirim</span>' end as status")
                ->from($this->table." as g");
        
        if($destination!=""){
            $destination   = explode(" ", $destination);
            foreach ($destination as $val) {
                $this->db->like("UPPER(g.destination)",strtoupper($val));
            }
        }

        if($message!=""){
            $message   = explode(" ", $message);
            foreach ($message as $val) {
                $this->db->like("UPPER(g.message)",strtoupper($val));
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
        $message        = trim($this->input->post_get("search_message"));
        $active         = trim($this->input->post_get("search_active"));
        $datemin        = trim($this->input->post_get("sdatemin"));         
        $datemax        = trim($this->input->post_get("sdatemax")); 
        $this->db->select("g.send_sms_id,g.message,g.destination,g.sendingtime,g.deliverytime,case when g.status='y' then 'Terkirim' else 'Belum / Tidak Terkirim' end as status")
                ->from($this->table." as g");
        
        if($destination!=""){
            $destination   = explode(" ", $destination);
            foreach ($destination as $val) {
                $this->db->like("UPPER(g.destination)",strtoupper($val));
            }
        }

        if($message!=""){
            $message   = explode(" ", $message);
            foreach ($message as $val) {
                $this->db->like("UPPER(g.message)",strtoupper($val));
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

    public function sinkron(){
        $response   = array("status"=>"info","message"=>"Tidak ada sms yang disinkronkan");
        $listsms    = $this->db->get_where($this->table,array("status"=>"n"));
        if($listsms->num_rows()>0){
            $count                  = 0;
            $config                 = $this->global_model->getConfigsite();
            $config['passsms']      = $this->encryption->decrypt($config["passsms"]);
            $this->load->library("smsgateway");
            $this->smsgateway->initialize($config['tokensms'],$config['emailsms'],$config['passsms']);
            foreach ($listsms->result_array() as $key => $value) {
                $getSMS             = $this->smsgateway->getMessage($value["smsgatewaymeid"]);
                if($getSMS["response"]["status"]=="sent"){
                    $timedelivery   = date("Y-m-d H:i:s",strtotime($getSMS["response"]["log"][2]["occurred_at"]));
                    $this->db->where($this->primarykey,$value["send_sms_id"])->update($this->table,array("status"=>"y","deliverytime"=>$timedelivery));
                    $count++;
                }                
            }
            if($count>0){
                $response["status"]       = "success";
                $response["message"]      = "Berhasil mensinkronkan ".$count." sms";
            }
        }
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
                $response["message"]  = "Berhasil menghapus data sms";
            }else{
                $response["status"]   = "error";
                $response["message"]  = "Gagal menghapus data sms";
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal menghapus data sms";
        }
        return $response;
    }

    public function updateData(){
        $id             = $this->encryption->decrypt(trim($this->input->post("id")));
        $configsms      = $this->global_model->getDataonerow("send_sms_id,smsgatewaymeid,message,destination",$this->table,array($this->primarykey=>$id));
        if($configsms!=null){
            $config                 = $this->global_model->getConfigsite();
            $config['passsms']      = $this->encryption->decrypt($config["passsms"]);
            $this->load->library("smsgateway");
            $this->smsgateway->initialize($config['tokensms'],$config['emailsms'],$config['passsms']);
            if($configsms["smsgatewaymeid"]!=""){
                $getSMS             = $this->smsgateway->getMessage($configsms["smsgatewaymeid"]);
                if($getSMS["response"]["status"]=="sent"){
                    $getSMS                 = $this->smsgateway->sendMessageToNumber($configsms['destination'], $configsms['message'], $config['deviceid']);
                    if(isset($getSMS["response"][0]["id"])){
                        $timedelivery   = date("Y-m-d H:i:s");
                        $this->db->where($this->primarykey,$configsms["send_sms_id"])->update($this->table,array("status"=>"y","deliverytime"=>$timedelivery));
                        $response["status"]   = "success";
                        $response["message"]  = "Sms terkirim";
                    }else{
                        $response["status"]   = "error";
                        $response["message"]  = "SMS Tidak terkirim";
                    }
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "SMS Tidak terkirim";
                }
            }else{
                $getSMS                 = $this->smsgateway->sendMessageToNumber($configsms['destination'], $configsms['message'], $config['deviceid']);
                if(isset($getSMS["response"][0]["id"])){
                    $timedelivery   = date("Y-m-d H:i:s");
                    $this->db->where($this->primarykey,$configsms["send_sms_id"])->update($this->table,array("status"=>"y","deliverytime"=>$timedelivery,"smsgatewaymeid"=>$getSMS["response"][0]["id"]));
                    $response["status"]   = "success";
                    $response["message"]  = "Sms terkirim";
                }else{
                    $response["status"]   = "error";
                    $response["message"]  = "SMS Tidak terkirim";
                }
            }
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Data sms tidak ditemukan";
        }
        
        return $response;
    }
}