<link href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<div class="row" id="data-tableactivity">
	<div class="col-md-12">
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
                <input type="text" placeholder="Cari Pengguna" class="form-control input-sm" name="search_username"/>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Aktifitas" class="form-control input-sm" name="search_activity"/>
              </div>
            </div>
            
          </div>
        </form>
      </div>
      <div class="card-body"> 
        <div class="table-toolbar display-none">
          <div class="float-right ml-1">
            <?php 
              if($modulecrud["acl_delete"]=="1"){ 
                $this->load->view("properties/button_delete_choose");
                $this->load->view("properties/button_delete_all");
              }
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
  	Ajaxpaging.init({target:"#data-tableactivity",url:<?php echo json_encode($actions)?>});
  });
</script> 
 