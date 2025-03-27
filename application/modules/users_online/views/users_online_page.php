<div class="row m-b-40" id="data-tableuser">
  <div class="col-md-12">
    <div class="card panel-grid">
      <div class="card-heading">
        <h4 class="card-title">DATA <?php echo $title?></h4>
      </div>
      <div class="card-toolbar panel-toolbar">
        <form class="searchform mb-0" action="javascript:;">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Pengguna" class="form-control input-sm" name="search_username"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-1">
                <input type="text" placeholder="Cari Nama" class="form-control input-sm" name="search_name"/>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group mb-1">
                <?php echo form_dropdown('search_group',$listgroup, null, array("class"=>"form-control input-sm select-picker","data-style"=>"btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body"> 
        <div class="table-toolbar display-none">
          <div class="float-right ml-1">
          </div>          
        </div>
        <?php $this->load->view("properties/table_grid")?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    Ajaxpaging.init({target:"#data-tableuser",url:<?php echo json_encode($actions)?>});
  });
</script> 
 