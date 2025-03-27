<link href="<?php echo base_url()?>assets/plugins/summernote/summernote.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/plugins/elfinder/css/elfinder.min.css" rel="stylesheet" />
<div class="row" id="data-tableguides">
	<div class="col-md-12">
	  <?php 
      if($modulecrud["acl_create"]=="1" or $modulecrud["acl_update"]=="1"){
        $this->load->view("properties/layout_form");
      }
    ?>
    <div class="card panel-grid">
      <div class="card-heading">
        <h4 class="card-title">DATA <?php echo $title?></h4>
      </div>
      <div class="card-toolbar panel-toolbar">
        <form class="searchform mb-0" action="javascript:;">
          <div class="row">
            <div class="col-md-7">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Panduan" class="form-control input-sm" name="search_name"/>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group mb-1">
                <?php echo form_dropdown('search_group',$listgroup, null, array("class"=>"form-control input-sm select-picker","data-style"=>"btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-1">
                <?php echo form_dropdown('search_active',$listactive, null, array("class"=>"form-control input-sm select-picker","data-style"=>"btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body"> 
        <div class="table-toolbar display-none">
          <div class="float-right ml-1">
            <?php 
              if($modulecrud["acl_create"]=="1"){ 
                $this->load->view("properties/button_add_data");
              }
            ?>
          </div>          
        </div>
        <?php $this->load->view("properties/table_grid")?>
      </div>
    </div>
  </div>
</div>
<script defer src="<?php echo base_url()?>assets/plugins/summernote/summernote.min.js"></script>
<script defer src="<?php echo base_url()?>assets/plugins/elfinder/js/elfinder.min.js"></script>
<script defer src="<?php echo base_url()?>assets/plugins/summernote/summernote-ext-elfinder.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  	Ajaxpaging.init({target:"#data-tableguides",url:<?php echo json_encode($actions)?>});
  });
</script> 
 