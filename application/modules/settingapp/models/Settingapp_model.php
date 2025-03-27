<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settingapp_model extends CI_Model {
    private $table="config_site";
    private $primarykey="config_site_id";
    public function __construct(){
        
    }

    public function getDatagrid($param){
        $searchall      = trim($this->input->post("search_all"));
        $order          = $this->input->post("order");
        $this->db->select("cs.config_site_id,cs.name")->from($this->table." as cs");

        if($searchall!=""){
            $searchall   = explode(" ", $searchall);
            $this->db->group_start();
            foreach ($searchall as $val) {
                $this->db->like("UPPER(cs.name)",strtoupper($val));
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
            foreach ($data_grid->result_array() as $row=>$val) {
                $val['no']      = ++$no;
                $val['id']      = $this->encryption->encrypt($val[$this->primarykey]);
                $val['aksi']    = "";
                if($param['modulecrud']['acl_update']==1){
                    $val['aksi'].= $button_update;
                }
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

    public function getDatabyid(){
        $id = trim($this->input->post("id"));
        return ($id!="")?$this->global_model->getDataonerow("*",$this->table,array($this->primarykey=>$this->encryption->decrypt($id))):null;
    }

    public function updateData($data){
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $data_old               = $this->global_model->getDataonerow("icon,logo,background","config_site",array("config_site_id"=>$id));
        $data["name"]           = trim($this->input->post("newname"));
        $data["subname"]        = trim($this->input->post("newsubname"));
        $data["title"]          = trim($this->input->post("newtitle"));
        $data["copyright"]      = trim($this->input->post("newcopyright"));
        $data["preview"]        = trim($this->input->post("newpreview"));
        $data["flagcounter"]    = trim($this->input->post("newflagcounter",NULL,FALSE));
        $data["isforget"]       = trim($this->input->post("newforget"))?:"n";
        $data["iscaptcha"]      = trim($this->input->post("newcaptcha"))?:"n";
        $data["issinglesession"]= trim($this->input->post("newsinglesession"))?:"n";
        $data["trylogin"]       = trim($this->input->post("newtrylogin"));
        $data["timelock"]       = trim($this->input->post("newtimelock"));
        $data["hostmail"]       = trim($this->input->post("newhostmail"));
        $data["portmail"]       = trim($this->input->post("newportmail"));
        $data["email"]          = trim($this->input->post("newemail"));

        $data["tokensms"]       = trim($this->input->post("newtokensms"));
        $data["deviceid"]       = trim($this->input->post("newdeviceid"));
        $data["emailsms"]       = trim($this->input->post("newemailsms"));

        $data["isgoogle"]       = trim($this->input->post("newisgoogle"))?:"n";
        $data["isfacebook"]     = trim($this->input->post("newisfacebook"))?:"n";

        $data["google_client_id"]       = trim($this->input->post("newgoogleclientid"));
        $data["google_client_secret"]   = trim($this->input->post("newgoogleclientsecret"));

        $data["facebook_app_id"]        = trim($this->input->post("newfacebookappid"));
        $data["facebook_app_secret"]    = trim($this->input->post("newfacebookappsecret"));

        $data["changed_by"]     = $this->session->userdata("user_id");
        $data["changed_on"]     = date("Y-m-d H:i:s");

        if(trim($this->input->post("newpassmail"))){
            $data["passmail"]       = $this->encryption->encrypt(trim($this->input->post("newpassmail")));
        }

        if(trim($this->input->post("newpasssms"))){
            $data["passsms"]       = $this->encryption->encrypt(trim($this->input->post("newpasssms")));
        }

        $save                   = $this->db->where($this->primarykey,$id)->update($this->table,$data);
        if($save){
            if(isset($data['logo'])){
                @unlink("./".$data_old['logo']);
            }
            if(isset($data['icon'])){
                @unlink("./".$data_old['icon']);
            }
            if(isset($data['background'])){
                @unlink("./".$data_old['background']);
            }
            $this->global_model->insertLog("memperbaharui data config site (".$data['name'].")");
            $response["status"]   = "success";
            $response["message"]  = "Berhasil memperbaharui data config site";
            $response["autohide"] = true;    
        }else{
            $response["status"]   = "error";
            $response["message"]  = "Gagal memperbaharui data config site";
        }
        return $response;
    }
}