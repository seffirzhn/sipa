<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settingmodule_model extends CI_Model {
	private $table="acls";
    private $primarykey="acl_id";
	public function __construct(){
		
	}

    public function getDataacls($group_id,$parent_id){
        if($group_id!=""){
            $this->db->select("a.acl_id,m.module_name,m.module_link,a.acl_read,a.acl_create,a.acl_update,a.acl_delete,a.acl_approve,m.module_order,m.module_id")
                    ->from("acls as a")
                    ->join("modules as m","a.module_id=m.module_id","left")
                    ->where("a.group_id",$group_id)
                    ->where("m.module_parent_id",$parent_id)
                    ->order_by("m.module_order","asc");
            $getData  = $this->db->get();
            if($getData->num_rows() > 0)
                return $getData->result_array();
            else
                return null; 
        }else{
            return null; 
        }           
    }

    public function getDatagrid($data=array(),$modulecrudupdate,$group_id=null,$parent_id=0,$counttab=-1){
        $counttab   = $counttab+1;
        $dt         = $this->getDataacls($group_id,$parent_id);
        if($dt!=null){
            foreach ($dt as $key=>$val) {
                $acl_id     = $this->encryption->encrypt($val['acl_id']);
                $checkdata  = $this->getDataacls($group_id,$val["module_id"]);
                $aclread    = ($val["acl_read"]=="1")?'<span class="ti-check"></span>':'<span class="ti-close"></span>';
                if($modulecrudupdate==1){
                    $aclread    = "<div>".form_checkbox("read","1",($val["acl_read"]=="1"),array("class"=>"module_acl","id"=>"acl_read".$acl_id,"switch"=>"bool")).form_label("","acl_read".$acl_id,array("data-on-label"=>"On","
                                                    data-off-label"=>"Off","class"=>"mb-0"))."</div>";
                }     
                $aclcreate  = ($val["acl_create"]=="1")?'<span class="ti-check"></span>':'<span class="ti-close"></span>';
                if($modulecrudupdate==1){
                    $aclcreate  = "<div>".form_checkbox("create","1",($val["acl_create"]=="1"),array("class"=>"module_acl","id"=>"acl_create".$acl_id,"switch"=>"bool")).form_label("","acl_create".$acl_id,array("data-on-label"=>"On","
                                                    data-off-label"=>"Off","class"=>"mb-0"))."</div>";
                }                
                $aclupdate  = ($val["acl_update"]=="1")?'<span class="ti-check"></span>':'<span class="ti-close"></span>';
                if($modulecrudupdate==1){
                    $aclupdate  = "<div>".form_checkbox("update","1",($val["acl_update"]=="1"),array("class"=>"module_acl","id"=>"acl_update".$acl_id,"switch"=>"bool")).form_label("","acl_update".$acl_id,array("data-on-label"=>"On","
                                                    data-off-label"=>"Off","class"=>"mb-0"))."</div>";
                }     
                $acldelete  = ($val["acl_delete"]=="1")?'<span class="ti-check"></span>':'<span class="ti-close"></span>';
                if($modulecrudupdate==1){
                    $acldelete  = "<div>".form_checkbox("delete","1",($val["acl_delete"]=="1"),array("class"=>"module_acl","id"=>"acl_delete".$acl_id,"switch"=>"bool")).form_label("","acl_delete".$acl_id,array("data-on-label"=>"On","
                                                    data-off-label"=>"Off","class"=>"mb-0"))."</div>";
                }     
                $aclapprove = ($val["acl_approve"]=="1")?'<span class="ti-check"></span>':'<span class="ti-close"></span>';
                if($modulecrudupdate==1){
                    $aclapprove = "<div>".form_checkbox("approve","1",($val["acl_approve"]=="1"),array("class"=>"module_acl","id"=>"acl_approve".$acl_id,"switch"=>"bool")).form_label("","acl_approve".$acl_id,array("data-on-label"=>"On","
                                                    data-off-label"=>"Off","class"=>"mb-0"))."</div>";
                }     
                $resultrows =   '<td align="left">'.str_repeat("•",$counttab)." ".strtoupper($val["module_name"]).'</td>
                                <td>'.$val["module_link"].'</td>
                                <td align="center" class="'.(($val['acl_read']=="1")?'table-successs':'table-dangesr').'">'.$aclread.'</td>';
                if($checkdata==null){
                    $resultrows    .=   '<td align="center" class="'.(($val['acl_create']=="1")?'table-successs':'table-dansger').'">'.$aclcreate.'</td>
                                        <td align="center" class="'.(($val['acl_update']=="1")?'table-successs':'table-dangser').'">'.$aclupdate.'</td>
                                        <td align="center" class="'.(($val['acl_delete']=="1")?'table-successs':'table-dangser').'">'.$acldelete.'</td>
                                        <td align="center" class="'.(($val['acl_approve']=="1")?'table-successs':'table-dansger').'">'.$aclapprove.'</td>';
                }else{
                    $resultrows    .='<td colspan="4"></td>';
                }
                $data[]  = array("id"=>$acl_id,"acls"=>$resultrows);
                if($checkdata!=null){
                    $data = $this->getDatagrid($data,$modulecrudupdate,$group_id,$val["module_id"],$counttab);
                }
            }
        }
        return $data;
    }

    public function updateData(){
        $acl    = trim($this->input->post("acl"));
        $status = trim($this->input->post("status"))=="true"?"1":"0";
        switch ($acl) {
            case 'read':
                $acl = "read";
                break;
            case 'create':
                $acl = "create";
                break;
            case 'update':
                $acl = "update";
                break;
            case 'delete':
                $acl = "delete";
                break;
            case 'approve':
                $acl = "approve";
                break;            
            default:
                $acl = "read";
                break;
        }
        $id = $this->encryption->decrypt(trim($this->input->post("id")));
        $save=$this->db->where(array($this->primarykey=>$id,"group_id"=>trim($this->input->post("group_id"))))->update($this->table,array("acl_".$acl=>$status));
        if($save){
            $this->global_model->insertLog("memperbaharui data acl (".$acl.")");
            $result["status"]   = "success";
            $result["message"]  = "Berhasil memperbaharui data atur acl (".$acl." ".$id.")";
        }else{
            $result["status"]   = "error";
            $result["message"]  = "Gagal memperbaharui data atur acl";
        }
        return $result;
    }
}