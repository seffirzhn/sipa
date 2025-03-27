<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}

	function getConfigsite($column=null){
		$query 	= $this->db->select(($column?$column." as data":"name,subname,title,icon,logo,background,copyright,preview,flagcounter,isforget,iscaptcha,issinglesession,trylogin,timelock,hostmail,portmail,email,passmail,tokensms,deviceid,emailsms,passsms,google_client_id,google_client_secret,facebook_app_id,facebook_app_secret,isfacebook,isgoogle"))->from("config_site")->where("config_site_id","1")->get();
		if($query->num_rows() > 0){
			$data = $query->row_array();
			return ($column)?$data['data']:$data;
		}else{
			return null;
		}
	}

	function getConfigcompany($column=null){
		$query 	= $this->db->select(($column?$column." as data":"nama,alamat,notelepon,email,fax,logo,kop"))->from("config_company")->where("config_company_id","1")->get();
		if($query->num_rows() > 0){
			$data = $query->row_array();
			return ($column)?$data['data']:$data;
		}else{
			return null;
		}
	}
	
	function countData($query){
		return $this->getDataonefield("sum(1)","(".$query.") as thedata");
	}

	function getDataonerow($select,$table,$where=null){
		$this->db->select($select);
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$getData = $this->db->limit(1)->get($table);
		if($getData->num_rows() > 0){
			return $getData->row_array();
		}else{
			return null;	
		}
	}

	function getDataonefield($select,$table,$where=null){
		$this->db->select($select." as data");
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$getData = $this->db->limit(1)->get($table);
		if($getData->num_rows() > 0){
			$data = $getData->row_array();
			return $data['data'];
		}else{
			return null;	
		}
	}

	function checkRecordexist($from,$value) {
		$num = 0;
		foreach ($from as $table => $where) {
			if(is_array($value)){
				$getData = $this->db->limit(1)->get_where($table,$value);
			}else{
				$getData = $this->db->limit(1)->get_where($table,array($where=>$value));
			}
			if($getData->num_rows() > 0){
				$num++;
			}
		}
		if($num>0){
			return true;
		}else{
			return false;
		}
	}

	function getDataonedimension($tabel=null,$kolom=null,$where=null,$result=array()) {	
		$this->db->select($kolom);
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$this->db->from($tabel);
		$query = $getData = $this->db->get();
		if($getData->num_rows() > 0){
			foreach ($getData->result_array() as $key => $value) {
				$result[] = $value[$kolom];
			}
		}
		return $result;
	}

	function getDatatwodimension($tabel=null,$kolom=null,$where=null,$nameval=null,$namelist=null,$result=array()) {	
		$this->db->select($kolom);
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$this->db->from($tabel);
		$query = $getData = $this->db->get();
		if($getData->num_rows() > 0){
			foreach ($getData->result_array() as $key => $value) {
				$result[$value[$nameval]] = $value[$namelist];
			}				
		}
		return $result;
	}

	function getDatatwodimensionOrder($tabel=null,$kolom=null,$where=null,$order=null,$nameval=null,$namelist=null,$result=array()) {	
		$this->db->select($kolom);
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$this->db->from($tabel);
		if($order!=null){
			$this->db->order_by($order);
		}
		$query = $getData = $this->db->get();
		if($getData->num_rows() > 0){
			foreach ($getData->result_array() as $key => $value) {
				$result[$value[$nameval]] = $value[$namelist];
			}				
		}
		return $result;
	}

	function getRandomkey($arr_tabel,$length,$type=null){
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($type=="numeric"){
			$chars = "0123456789";
		}else if($type=="alphabet"){
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		$the_chars = null;
	    if($length > 0)
	    {
	        $len_chars = (strlen($chars) - 1);
	        $the_chars = $chars{rand(0, $len_chars)};
	        for ($i = 1; $i < $length; $i = strlen($the_chars))
	        {
	            $r = $chars{rand(0, $len_chars)};
	            if ($r != $the_chars{$i - 1}) $the_chars .=  $r;
	        }
	    }
		if($this->checkRecordexist($arr_tabel,$the_chars)==true){
			return $this->getRandomkey($arr_tabel,$length,$type);
		}else{
			return $the_chars;
		}
	}

	function getIncementkey($tabel,$kolom,$where=null,$lenght=null){
		$result = str_repeat("0", $lenght)."1";
		if($lenght==null){
			$lenght=6;
		}
		$this->db->select_max($kolom,"maxkode");
		if($where!=null){
			if(is_array($where)){
				$this->db->where($where);
			}else{
				$this->db->where($where,null,false);
			}
		}
		$getData = $this->db->get($tabel);
		if($getData->num_rows()>0){
			$getData 	= $getData->row_array();
			$max 		= (int)substr($getData['maxkode'],1,strlen($getData['maxkode']))+1;
			$result 	= str_repeat("0", $lenght-(strlen($max)-1)).$max; 
		}
		return $result;
	}

	function insertLog($activity=null){
		$data=array(
				"activity"=>trim($activity),
				"created_by"=>$this->session->userdata("user_id"),
				"created_on"=>date("Y-m-d H:i:s"),
			);
		$this->db->insert("users_log",$data);
	}

	function getListyear($start=null,$end=null,$year=array()){
		$start 	= $start ?: date("Y");
		$end 	= $end ?: date("Y");
		if($start==$end){
			$year[$start] = $start;
		}else if($start<$end){
			for ($i=$start; $i <= $end; $i++) { 
				$year[$i]	=	$i; 	
			}
		}else if($start>$end){
			for ($i=$start; $i >= $end; $i--) { 
				$year[$i]	=	$i; 	
			}
		}
		return $year;
	}

	function getDatafromservice($config=array()){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$config["uri"]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		if(isset($config["userpwd"])){
			curl_setopt($ch, CURLOPT_USERPWD, $config["userpwd"]);
		}
		if(isset($config["header"])){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $config["header"]);
		}
		if(isset($config["post"])){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $config["post"]);
		}
		$result=curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}	

	function callAPI($url, $data=null, $header=null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_USERPWD, "univ1:12345678");
        if ($data){
	        curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);   
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_VERBOSE, 1); 
		curl_setopt($curl, CURLOPT_NOBODY, 0); 
		$result=curl_exec ($curl);
		curl_close ($curl);
		return $result;
	}

	function getTitle($class){
		return $this->getDataonefield("module_name","modules",array("module_link"=>$class));
	}

	function insertMail($config){
		$waktu 					= date('Y-m-d H:i:s');
		$datasend 				= $config;
		$datasend["sendingtime"]= $waktu;
		$datasend["created_on"]	= $waktu;
		$datasend["created_by"]	= $this->session->userdata('user_id');
		$this->db->insert("send_mail",$datasend);
		$insert_id 				= $this->db->insert_id();
		if($insert_id!=""){
			$config_site 			= $this->getConfigsite();
			$config['hostmail']		= $config_site["hostmail"];
			$config['portmail']		= $config_site["portmail"];
			$config['email']		= $config_site["email"];
			$config['passmail']		= $this->encryption->decrypt($config_site["passmail"]);
			$config['nameapps']		= $config_site["name"];
			$config["instansi"]		= $this->getConfigcompany("nama");
			$this->load->library("emailgateway");
			$result  				= $this->emailgateway->send($config);
			if($result["status"]=="success"){
				$this->db->where("send_mail_id",$insert_id)->update("send_mail",array("deliverytime"=>date("Y-m-d H:i:s"),"status"=>"y"));
			}
		}
		return $result;
	}
		

	function insertSms($config){
		$result 				= array("status"=>"success");
		$waktu 					= date('Y-m-d H:i:s');
		$datasend 				= $config;
		$datasend["sendingtime"]= $waktu;
		$datasend["created_on"]	= $waktu;
		$datasend["created_by"]	= $this->session->userdata('user_id');
		$this->db->insert("send_sms",$datasend);
		$insert_id 				= $this->db->insert_id();
		if($insert_id!=""){
			$config_site 			= $this->getConfigsite();
			$config['tokensms']		= $config_site["tokensms"];
			$config['deviceid']		= $config_site["deviceid"];
			$config['emailsms']		= $config_site["emailsms"];
			$config['passsms']		= $this->encryption->decrypt($config_site["passsms"]);
			$config['nameapps']		= $config_site["name"];
			$config["instansi"]		= $this->getConfigcompany("nama");
			$this->load->library("smsgateway");
			$this->smsgateway->initialize($config['tokensms'],$config['emailsms'],$config['passsms']);
			
			$result  				= $this->smsgateway->sendMessageToNumber($config['destination'], $config['message'], $config['deviceid']);
			if(isset($result["response"][0]["id"])){
				$this->db->where("send_sms_id",$insert_id)->update("send_sms",array("smsgatewaymeid"=>$result["response"][0]["id"]));
			}
		}
		return $result;
	}

	function concat($field,$asfield,$separator=","){
		$result 	= "";
		$driver 	= $this->db->dbdriver;
		if($driver=="postgre"){
			$result = "string_agg(DISTINCT ".$field.", '".$separator."') as ".$asfield;
		}else if($driver=="mysqli"){
			$result = "group_concat(".$field." SEPARATOR ' ".$separator."') as ".$asfield;
		}
		return $result;
	}

	function dateformat($field,$format,$asfield="",$asnumeric=false){
		$result 	= "";
		$driver 	= $this->db->dbdriver;
		if($driver=="postgre"){
			$format = str_replace(array("year","month","day","hour","minute","second"), array("YYYY","MM","DD","HH24","MI","SS"), $format);
			$result = "to_char(".$field.", '".$format."')";
			if($asnumeric==true){
				$result = "to_number(".$result.",'".str_repeat("9", strlen($format))."')";
			}
			$result.= (($asfield!="")?"as ".$asfield:"");
		}else if($driver=="mysqli"){
			$format = str_replace(array("year","month","day","hour","minute","second"), array("%Y","%m","%d","%H","%i","%s"), $format);
			$result = "DATE_FORMAT(".$field.",'".$format."')".(($asfield!="")?"as ".$asfield:"");
		}
		return $result;
	}

	function insertNotif($title,$notifikasi,$touser,$url=null){
		$datanotif["notifikasi"] 	= $notifikasi;
		$datanotif["inserttime"] 	= date('Y-m-d H:i:s');
		$datanotif["touser"] 		= $touser;
		$datanotif["title"]			= $title;
		$datanotif["url"]			= $url;
		$datanotif["fromuser"]		= $this->session->userdata('user_id');
		$this->db->insert("send_notifikasi",$datanotif);
	}
}