<link href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<div class="row" id="data-tablesmsgateway">
	<div class="col-md-12">
    <div class="card display-none panel-form">
      <div class="card-heading">
        <h4 class="card-title">BACA SMS</h4>
        <div class="card-heading-btn">
          <button type="button" class="close-panel btn btn-xs btn-icon btn-circle btn-danger" title="Tutup"><i class="ti-close"></i></button>
        </div>
      </div>
      <div class=" body-form">
      </div>
    </div>
    <?php $this->load->view("properties/layout_preview_print")?>
    <div class="card panel-grid">
      <div class="card-heading">
        <h4 class="card-title">DATA <?php echo $title?></h4>
      </div>
      <div class="card-toolbar panel-toolbar">
        <form class="searchform mb-0" action="javascript:;">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group mb-1">
                <div class="input-group input-daterange" >
                  <?php echo form_input(array("name"=>"sdatemin","class"=>"form-control input-sm input-range","placeholder"=>"Dari Tanggal"));?>
                  <span class="input-group-addon input-group-prepend input-group-append" ><span class="input-group-text">s/d</span></span>
                  <?php echo form_input(array("name"=>"sdatemax","class"=>"form-control input-sm input-range","placeholder"=>"Sampai Tanggal"));?>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Tujuan" class="form-control input-sm" name="search_destination"/>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Pesan" class="form-control input-sm" name="search_message"/>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-1">
                <?php echo form_dropdown('search_active',$listactive, null, array("class"=>"form-control input-sm select-picker","data-style"=>"btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
              </div>
            </div>
            <button type="button" class="d-none" id="btnfilter"></button>
          </div>
        </form>
      </div>
      <div class="card-body"> 
        <div class="table-toolbar display-none">
          <div class="float-right ml-1">
            <?php 
              if($modulecrud["acl_update"]=="1"){ 
                echo form_button(array("type"=>"button","class"=>"btn btn-success btn-sinkron","content"=>"<i class='ti-reload'></i> Sinkron"));
              }
              if($modulecrud["acl_delete"]=="1"){ 
                $this->load->view("properties/button_delete_choose");
              }
              $this->load->view("properties/button_print_pdf");
            ?>
          </div>          
        </div>
        <?php $this->load->view("properties/table_grid")?>
      </div>
    </div>
  </div>
</div>
<script defer src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    Ajaxpaging.init({target:"#data-tablesmsgateway",url:<?php echo json_encode($actions)?>});
    <?php 
      if($modulecrud["acl_update"]=="1"){ 
    ?>
      $("#data-tablesmsgateway").off("click",".btn-sinkron").on("click",".btn-sinkron",function(){swal({title:"Yakin ingin mensinkronkan data sms dengan smsgateway.me?",text:"Data akan terupdate sesuai data smsgateway.me",icon:"success",buttons:{cancel:{text:"Batal",visible:!0,className:"btn  default",closeModal:!0},confirm:{text:"Sinkronkan",visible:!0,className:"btn  btn-success",closeModal:!0}},closeOnClickOutside:!0,dangerMode:!0}).then(function(a){a&&(blockUI(),$.ajax({url:"<?php echo $actions['sinkron']?>",type:"POST",data:null,contentType:!1,dataType:"json",async:!0,processData:!1,xhr:function(){return customxhr($.ajaxSettings.xhr())},success:function(a){messageBox(a.message,a.status),"success"==a.status&&$("#data-tablesmsgateway .searchform #btnfilter").click()},error:function(a,e,s){formatErrorMessage(a,e,s)},complete:function(){unblockUI(),clearconsole()}}))})});
    <?php 
      }
    ?>
  });
</script> 
 