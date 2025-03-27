<link href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<div class="row mb-5 pb-5" id="data-tablemasterseleksi">
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
<script defer src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
   Ajaxpaging.init({target:"#data-tablemasterseleksi",url:<?php echo json_encode($actions)?>,simplesearch:true});
  });
</script> 