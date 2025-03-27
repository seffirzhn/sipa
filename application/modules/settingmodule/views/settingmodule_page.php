<div class="row" id="data-tablesettingmodule">
	<div class="col-md-12">
		<div class="card panel-grid">
      <div class="card-heading">
        <h4 class="card-title">DATA <?php echo $title?></h4>
      </div>
      <div class="card-toolbar panel-toolbar">
        <form class="searchform mb-0" action="javascript:;">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group mb-1">
                <?php echo form_dropdown('group_id',$listgroup, null, array("class"=>"form-control input-sm select-picker","id"=>"group_id","data-style"=>"btn btn-outline-secondary waves-effect"));?>
              </div>
            </div>
          </div>
        </form>
      </div>
    	<div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th width="35%">MODUL</th>
                <th width="25%">TAUTAN</th>
                <th width="8%">LIHAT</th>
                <th width="8%">TAMBAH</th>
                <th width="8%">UBAH</th>
                <th width="8%">HAPUS</th>
                <th width="8%">SETUJU</th>
              </tr>
            </thead>
            <tbody >
              <tr>
                <td colspan="7" align="center">Pilih grup terlebih dahulu</td>
              </tr>
            </tbody>
          </table>
        </div>
    	</div>
  	</div>
  </div>
</div> 
<script type="text/javascript">
  $(document).ready(function(){
    Ajaxpaging.initsettingmodule({target:"#data-tablesettingmodule",url:<?php echo json_encode($actions)?>});
  });
</script>