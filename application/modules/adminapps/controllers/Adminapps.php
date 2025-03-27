<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminapps extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
        $this->auth_model->checkIslogout();
		$konten                 = "adminapps_page";
        if($this->input->get("layout")=="horizontal"){
            $this->db->where("user_id",$this->session->userdata("user_id"))->update("users",array("layout_admin"=>"layout_admin_horizontal"));
            $this->session->set_userdata("layout_admin","layout_admin_horizontal");
        }else if($this->input->get("layout")=="vertical"){
            $this->db->where("user_id",$this->session->userdata("user_id"))->update("users",array("layout_admin"=>"layout_admin_vertical"));
            $this->session->set_userdata("layout_admin","layout_admin_vertical");
        }
        $param['link']          = site_url(CI_ADMIN_PATH."/getstatactivities");  
        $param['configcompany'] = $this->global_model->getConfigcompany();    
        $param['actions']       = site_url(CI_ADMIN_PATH."/statistik");
        $param['configsite']    = $this->global_model->getConfigsite();   
        $param["statistik"]     = $this->getStatistik();
        $this->auth_model->buildViewAdmin($konten,$param);
	}

    public function getStatistik(){
        $stat = array();
        $id_opd = $this->session->userdata("id_opd");
        $group = $this->session->userdata("group_id")[0];
        $this->db->select("da.id_jenis_layanan,count(da.id_jenis_layanan) as jumlah, jl.jenis_layanan")
         ->from("data_aplikasi as da")
         ->join("master_jenis_layanan as jl","jl.id_jenis_layanan=da.id_jenis_layanan")
         ->group_by("da.id_jenis_layanan");
        //  ->get();
         if($group==3){
            $this->db->where("da.id_opd",$id_opd);
         }
         $data = $this->db->get();
         foreach($data->result_array() as $val){
            $stat[] = $val["jumlah"];
         }
         return $stat;
     }
    
	public function session(){
        if($this->input->is_ajax_request()){
            $sessionuser    = $this->session->userdata('user_id');
            if($sessionuser!=""){
                $datauser       = $this->global_model->getDataonerow("*","users",array("user_id"=>$sessionuser));
                $response       = array();
                if($datauser!=null){
                    if($datauser['active']=='1'){
                        $time       = date("Y-m-d H:i:s");
                        $timecheck  = date("Y-m-d H:i:s",strtotime("-5minutes"));
                        $session    = session_id();
                        if($this->global_model->checkRecordexist(array("users_online"=>"user_id"),$sessionuser)==false){
                            $this->db->insert("users_online",array("user_id"=>$sessionuser,"times"=>$time));
                        }else{
                            $this->db->set("times",$time)->where("user_id",$sessionuser)->update("users_online");
                        }
                        $this->db->where("times < ",$timecheck)->delete("users_online");
                        $this->session->set_userdata('user_login','1');
                        $data['online_user']        = $this->global_model->getDataonefield("sum(1)","users_online");
                        $response['status']         = "success";
                        $response['message']        = "";
                        $response['data']           = $data;
                        echo json_encode($response);
                    }else{
                        $this->session->sess_destroy();
                        $data['linkredirect']       = site_url(CI_LOGIN_PATH);
                        $response['status']         = "warning";
                        $response['message']        = "Akun anda telah dinonaktifkan oleh administrator";
                        $response['data']           = $data;
                        echo json_encode($response);
                    }
                }else{
                    $this->session->sess_destroy();
                    $data['linkredirect']       = site_url(CI_LOGIN_PATH);
                    $response['status']         = "warning";
                    $response['message']        = "Akun anda tidak ditemukan";
                    $response['data']           = $data;
                    echo json_encode($response);
                }
            }else{
                $data['linkredirect']       = site_url(CI_LOGIN_PATH);
                $response['status']         = "warning";
                $response['message']        = "Silahkan masuk terlebih dahulu";
                $response['data']           = $data;
                echo json_encode($response);
            }
        }else{
            show_404();
        }
    }

    function browse()
    {
        $this->auth_model->checkIslogout();
        $this->load->library('browsefile');
    }


    public function statistik(){
        $this->auth_model->checkIslogout();
        if($this->input->is_ajax_request()){
            $response["status"]             = "success";
            $response["message"]            = "Berhasil";
            $response["data"]               = array();
            echo json_encode($response);
        }else{
            show_404();
        }
    }

    function notification(){
        if($this->input->is_ajax_request()){
            $list_notifikasi = $this->db->order_by("inserttime","desc")->get_where("send_notifikasi",array("status"=>"0","touser"=>$this->session->userdata("user_id")));
            $result          ='<div class="text-reset notification-item"><div class="media"><div class="media-body"><h6 class="mt-0 mb-1">Tidak ada pemberitahuan baru</h6></div></div></div>';
            if($list_notifikasi->num_rows()>0){
                $result      = "";
                foreach ($list_notifikasi->result_array() as $key => $value) {
                    $result .='<a href="'.site_url(CI_ADMIN_PATH.'/readnotification').'?id='.urlencode($this->encryption->encrypt($value["send_notifikasi_id"])).'" class="text-reset notification-item"><div class="media"><div class="media-body"><h6 class="mt-0 mb-1">'.$value["title"].'</h6><div class="font-size-12 text-muted"><p class="mb-1"><small>'.$value['inserttime'].'</small><br/>'.$value["notifikasi"].'</p></div></div></div></a>';
                }
            }
            $data["notif"]          = $result;
            $data["countnew"]       = $list_notifikasi->num_rows();    
            $response["status"]     = "success";
            $response["message"]    = "Berhasil";
            $response["data"]       = $data;
            echo json_encode($response);
        }else{
            show_404();
        }
    }

    function readnotification(){
        $id         = $this->encryption->decrypt(trim($this->input->get("id")));
        $notif      = $this->global_model->getDataonerow("*","send_notifikasi",array("send_notifikasi_id"=>$id,"touser"=>$this->session->userdata("user_id")));
        if($notif!=null){
            if($notif["status"]=="0"){
                $this->db->where(array("touser"=>$this->session->userdata("user_id"),"status"=>"0","send_notifikasi_id"=>$id))->update("send_notifikasi",array("status"=>"1","readtime"=>date("Y-m-d H:i:s")));
            }
            redirect($notif["url"]);
        }else{
            show_404();
        }
    }
}
?>
