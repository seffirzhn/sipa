<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Grup <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newgroup_name","value"=>$group_name,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Keterangan</label>
            <?php echo form_input(array("name"=>"newgroup_description","value"=>$group_description,"class"=>"form-control "));?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">Pengguna Mobile</label>
            <div class="mt-1">
                <?php 
                  echo form_checkbox("newismobile","1",(1==$ismobile)?TRUE:FALSE,array("id"=>"cbismobile","switch"=>"bool"));
                  echo form_label("","cbismobile",array("data-on-label"=>"Ya","data-off-label"=>"Tidak","class"=>"mb-0"));
                ?>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Role Name Mobile <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newrole_name","value"=>$role_name,"class"=>"form-control "));?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer text-right">
    <?php 
      $this->load->view("properties/button_submit_form");
      $this->load->view("properties/button_reset_form");
    ?>
  </div>
</form>