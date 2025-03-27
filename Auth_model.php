<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}

	function getUser(){
		$user 	= trim($this->input->post("username"));
		$pass 	= trim($this->input->post("password"));
		$query 	= $this->db->select("u.user_id,u.user_name,u.name,u.phone,u.id_daerah,u.id_opd,u.email,u.active as user_active,1 as user_login,u.password, case when u.image_profile<>'' then case when u.extend_image='y' then u.image_profile else concat('".base_url()."',u.image_profile) end else concat('".base_url()."','assets/img/noavatar.png') end as image_profile,u.layout_admin")
				->from("users as u")
				->where("u.user_name",$user)
				->or_where("u.email",$user)
				->get();
		if($query->num_rows() > 0){
			$result = $query->row_array();
			if(md5($this->encryption->decrypt($result["password"]))==md5($pass)){
				$groupname = array();
				$this->db->select("g.group_name,g.group_id")
						->from("groups as g")
						->join("users_groups as ug","ug.group_id=g.group_id")
						->where("ug.user_id",$result["user_id"]);
				$datagroup = $this->db->get();
				if($datagroup->num_rows()>0){
					foreach ($datagroup->result_array() as $key => $value) {
						$result["group_id"][] = $value["group_id"];
						$groupname[] = $value["group_name"];
					}
				}
				$result["group_name"] = implode(", ", $groupname);
				unset($result["password"]);
				return $result;
			}else{
				return null;
			}
		}else{
			return null;
		}
	}

	function getUserbyoauth($oauth_uid,$provider){

		$query 	= $this->db->select("u.user_id,u.user_name,u.name,u.phone,u.email,u.active as user_active,1 as user_login, case when u.image_profile is null or u.image_profile='' then 'assets/img/noavatar.png' else u.image_profile end as image_profile")
				->from("users as u")
				->join("oauth o","o.user_id=u.user_id")
				->where(array("o.provider"=>$provider,"o.oauth_uid"=>$oauth_uid))
				->get();
		if($query->num_rows() > 0){
			$result = $query->row_array();
			$groupname = array();
			$this->db->select("g.group_name,g.group_id")
					->from("groups as g")
					->join("users_groups as ug","ug.group_id=g.group_id")
					->where("ug.user_id",$result["user_id"]);
			$datagroup = $this->db->get();
			if($datagroup->num_rows()>0){
				foreach ($datagroup->result_array() as $key => $value) {
					$result["group_id"][] = $value["group_id"];
					$groupname[] = $value["group_name"];
				}
			}
			$result["group_name"] = implode(", ", $groupname);
			return $result;	
		}else{
			return null;
		}
	}

	function getUserbyusername(){
		$user 	= trim($this->input->post("username"));
				$this->db->select("user_id as user,active as user_active")
							->from("users")
							->where("user_name",$user)
							->or_where("email",$user);
		$query 	= $this->db->get();
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return null;
		}
	}

	function getUserfull(){
		$result 				= $this->session->userdata();
		if($result["name"]=="" && !$this->input->is_ajax_request()){
			redirect("profile/logout");
		}
		return $result;
	}

	function getModule($module){
		$result = array("acl_read"=>"0","acl_create"=>"0","acl_update"=>"0","acl_delete"=>"0","acl_approve"=>"0");
		$this->db->select("a.module_id,a.acl_read,a.acl_create,a.acl_update,a.acl_delete,a.acl_approve")
		->from("acls as a")
		->join("modules as m","a.module_id=m.module_id")
		->where_in("a.group_id",$this->session->userdata("group_id"))
		->where("LOWER(m.module_link)",strtolower($module));
		$data= $this->db->get();
		if($data->num_rows()>0){
			foreach ($data->result_array() as $key => $value) {
				foreach ($result as $key2=>$value2) {
					if($value[$key2]=="1"){
						$result[$key2]=$value[$key2];
					}
				}
			}
		}
		return $result;
	}

	function getModulecrud($module){
		return $this->getModule($module);
	}
	
	function checkModulecrud($crud="",$module=""){
		$Q 			= $this->getModule($module);
		if($Q[$crud]==null || $Q[$crud]=="0" || !$this->input->is_ajax_request()){
			show_404();
		}
	}

	function checkModule($module=""){
		$data 		= array();
		$Q 			= $this->getModule($module);			
		if(($Q["acl_read"]=="0" or $Q["acl_read"]==null or $Q["acl_read"]=="") and $this->input->is_ajax_request()){
			$this->output->set_status_header("404");
			exit();
		}else if($Q["acl_read"]=="0" and !$this->input->is_ajax_request()){
			show_404();
		}
	}

	public function checkIslogin(){

		if($this->session->userdata('user_id')!="" && $this->session->userdata('user_active')=="1" && $this->session->userdata('user_login')=="1"){
			if($this->input->is_ajax_request()){
				$response['status']         = "success";
				$response['message']        = "<div class='alert alert-success'>Anda sudah masuk. Mengalihkan...</div>";
				$response['linkredirect']   = site_url(CI_ADMIN_PATH);
				echo json_encode($response);
	            exit();
			}else{
				redirect(CI_ADMIN_PATH);
			}
		}
	}

	public function checkIslogout(){
		$sessionuser_id 	= $this->session->userdata('user_id');
		$sessionuser_active = $this->session->userdata('user_active');
		$sessionuser_login 	= $this->session->userdata('user_login');
		if($this->input->is_ajax_request()){
            $response['message'] 	= "";
			if($sessionuser_login!="1" && $sessionuser_id==""){
                $response['message']	= "Silahkan masuk terlebih dahulu";
            }else if($sessionuser_active!="1"){
                $response['message']	= "Status user anda tidak aktif";
			}
			if($response['message']!=""){
				$this->session->sess_destroy();
				$response['status']         = "warning";
	            $response['linkredirect']   = site_url(CI_LOGIN_PATH);
				echo json_encode($response);
	            exit();
        	}
		}else{
			if($sessionuser_login!="1" && $sessionuser_id==""){
				redirect(CI_LOGIN_PATH);
			}else if($sessionuser_active!="1"){
				redirect(CI_LOGIN_PATH);
			}
		}
	}

	public function setValidasiinput($config){
		$this->load->library('form_validation');
		$result = array("status"=>"success","message"=>"");
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters("","<br/>");
		if($this->form_validation->run() == false){
			$result['status']	= "error";
			$result['message']	= validation_errors();
		}
		return $result;
	}

	public function checkIsloginmember(){

		if($this->session->userdata('member_id')!="" && $this->session->userdata('member_login')=="1"){
			redirect(site_url('beranda'));
		}
	}

	public function checkIslogoutmember(){
		$sessionmember_id 	= $this->session->userdata('member_id');
		$sessionmember_login	= $this->session->userdata('member_login');
		if($this->input->is_ajax_request()){
            $response['message'] 	= "";
			if($sessionmember_login!="1" && $sessionmember_id==""){
                $response['message']	= "Silahkan masuk terlebih dahulu";
            }
			if($response['message']!=""){
				$this->session->sess_destroy();
				$response['status']         = "warning";
	            $response['linkredirect']   = site_url("login");
				echo json_encode($response);
	            exit();
        	}
		}else{
			$strmessage = "";
			if($sessionmember_login!="1" && $sessionmember_id==""){
				$strmessage = "Silahkan masuk terlebih dahulu";
			}

			if($strmessage!=""){
				$this->session->sess_destroy();
				redirect("login");
				exit();
			}
		}
	}

	function buildMenusidebar($parent=0,$rootmenu=0){
        $result     = ($parent!=null) ? '<ul class="sub-menu" aria-expanded="false">' : "";
        $user_id    = $this->session->userdata("user_id");
        $getModule  = $this->getModulemenu($parent,$user_id);
        if($getModule!=null){
        	foreach ($getModule->result_array() as $key=>$val) {
                $getSubmodule 	= $this->getModulemenu($val["module_id"],$user_id);
                $result    		.= "<li ".(($getSubmodule!=null)?'class="has-sub"':'')." >";                
                $link       	= ($getSubmodule!=null)?"javascript:;": (($val["module_link"]=="#")? "#" : site_url($val["module_link"])) ;
                $arrow      	= ($getSubmodule!=null)?'has-arrow':"";
                $class      	= ($getSubmodule!=null)?" waves-effect ":"linkajax waves-effect";
                $icon       	= ($val["module_icon"]!="" && $rootmenu==0) ? '<i class="'.$val["module_icon"].'"></i>' : "";
                $result    		.= "<a href='".$link."'  link='".$link."' class='".$class." ".$arrow."' icon='".$icon."' name='".$val["module_name"]."'>".$icon."<span class='title'>".($val["module_name"])."</span></a>";
                $result    		.= ($getSubmodule!=null) ? $this->buildMenusidebar($val["module_id"],$rootmenu+1,$user_id) : "";
                $result    		.= "</li>";
            }
        }
        $result     .= ($parent!=null) ? "</ul>" : "";
        return $result;
    }     

    function buildMenutop($parent=0,$rootmenu=0){
        $result     = ($parent!=null) ? '<div class="dropdown-menu">' : "";
        $user_id    = $this->session->userdata("user_id");
        $getModule  = $this->getModulemenu($parent,$user_id);
        if($getModule!=null){
        	foreach ($getModule->result_array() as $key=>$val) {
        		if($rootmenu==0){
	                $getSubmodule 	= $this->getModulemenu($val["module_id"],$user_id);
	                $result    		.= "<li ".(($getSubmodule!=null)?'class="nav-item dropdown"':'')." >";                
	                $link       	= ($getSubmodule!=null)?"javascript:;": (($val["module_link"]=="#")? "#" : site_url($val["module_link"])) ;
	                $arrow      	= ($getSubmodule!=null)?' arrow-none':"";
	                $class      	= ($getSubmodule!=null)?" dropdown-toggle arrow-none waves-effect ":"linkajax waves-effect";
	                $icon       	= ($val["module_icon"]!="" && $rootmenu==0) ? '<i class="'.$val["module_icon"].' mr-2"></i>' : "";
	                $attr 			= ($getSubmodule!=null)?'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"':"";
	                $result    		.= "<a href='".$link."' ".$attr."  link='".$link."' class='nav-link ".$class." ".$arrow."' icon='".$icon."' name='".$val["module_name"]."'>".$icon."<span class='title'>".($val["module_name"])."</span></a>";
	                $result    		.= ($getSubmodule!=null) ? $this->buildMenutop($val["module_id"],$rootmenu+1,$user_id) : "";
	                $result    		.= "</li>";        			
        		}else{
        			$getSubmodule 	= $this->getModulemenu($val["module_id"],$user_id);
	                $result    		.= (($getSubmodule!=null)?'<div class="dropdown">':'');                
	                $link       	= ($getSubmodule!=null)?"javascript:;": (($val["module_link"]=="#")? "#" : site_url($val["module_link"])) ;
	                $arrow      	= ($getSubmodule!=null)?' arrow-none':"";
	                $arrowdown     	= ($getSubmodule!=null)?'<div class="arrow-down"></div>':"";
	                $attr 			= ($getSubmodule!=null)?'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"':"";
	                $class      	= ($getSubmodule!=null)?"dropdown-toggle arrow-none waves-effect ":"linkajax waves-effect";
	                $icon       	= ($val["module_icon"]!="" && $rootmenu==0) ? '<i class="'.$val["module_icon"].' mr-2"></i>' : "";
	                $result    		.= "<a href='".$link."' ".$attr."  link='".$link."' class='dropdown-item ".$class." ".$arrow." ' icon='".$icon."' name='".$val["module_name"]."'>".$icon."<span class='title'>".($val["module_name"])."</span> ".$arrowdown."</a>";
	                $result    		.= ($getSubmodule!=null) ? $this->buildMenutop($val["module_id"],$rootmenu+1,$user_id) : "";
	                $result    		.= (($getSubmodule!=null)?"</div>":"");      
        		}
            }
        }
        $result     .= ($parent!=null) ? "</div>" : "";
        return $result;
    }     

    public function getModulemenu($parent=0,$user_id){
        $this->db->select("m.module_id,m.module_name,m.module_link,m.module_icon")
                ->from("modules as m")
                ->join("acls as a","a.module_id=m.module_id")
                ->where_in("a.group_id",$this->session->userdata("group_id"))
                ->where(array("m.module_parent_id"=>$parent,"a.acl_read"=>"1"))
                ->group_by("m.module_id,m.module_name,m.module_link,m.module_icon,m.module_order")
                ->order_by("m.module_order","asc")
                ->order_by("m.module_id","asc");
        $getData = $this->db->get();
        if($getData->num_rows() > 0)
        	return $getData;
        else
            return null;
    }  

    public function buildViewAdmin($konten,$param){
    	if($this->input->is_ajax_request()){
    		$data['html']       	= $this->load->view($konten,$param,true);
            $response['status']     = "success";
            $response['message']    = "";
            $response['data']       = $data;
            echo json_encode($response);
    	}else{
    		$param['datauser']          = $this->getUserfull();
	        $param['configsite']        = $this->global_model->getConfigsite();   
	        $param["konten"]            = $konten;
	        if($this->session->userdata("layout_admin")=="layout_admin_horizontal"){
	        	$param['buildMenusidebar']  = $this->buildMenutop();
	    	}else if($this->session->userdata("layout_admin")=="layout_admin_vertical"){
	        	$param['buildMenusidebar']  = $this->buildMenusidebar();
	    	}
	        $this->load->view('properties/'.$this->session->userdata("layout_admin"),$param);     
	    }
    }

    function updatePassword($user_id){
        $sandi = $this->encryption->encrypt(trim($this->input->post("sandibaru")));
        if($sandi!=""){
            $data=array('password'=>$sandi);
            $this->db->where("user_id",$user_id);
            $this->db->update("users",$data);
            $response['status']     = "success";
            $response['message']    = "";
            return $response;
        }
    }
}