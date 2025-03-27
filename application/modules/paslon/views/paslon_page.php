<div class="row" id="data-tablepaslon">
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
            <!-- <div class="col-md-4"> 
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Nama" class="form-control input-sm" name="search_nama"/>
              </div>
            </div> -->
            <div class="col-md-6">
              <div class="form-group mb-1">
                <input type="text" class="form-control input-sm" value="<?php echo $pilkada; ?>" readonly/>
                <?php echo form_hidden("search_pilkada",$id_pilkada); ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-1">
                <?php echo form_dropdown("search_daerah",$listdaerah,null,array("class"=>"select-picker form-control","data-style"=>"btn btn-outline-secondary waves-effect"));?>
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
<script type="text/javascript">
  $(document).ready(function(){
	 Ajaxpaging.init({target:"#data-tablepaslon",url:<?php echo json_encode($actions)?>,simplesearch:false,paramfromsearch:true});
  });
</script> 
 