<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Modul <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newmodule_name","value"=>$module_name,"class"=>"form-control  first-input-form "));?>
          </div>
          <div class="form-group">
            <label class="control-label">Induk Modul <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newmodule_parent',$listparent,$module_parent, array("data-parsley-class-handler"=>".dropdown-parent","data-parsley-errors-container"=>"#errparent","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-parent btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errparent"></span>
          </div>
          <div class="form-group">
            <label class="control-label">Tautan <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newmodule_link","value"=>$module_link,"class"=>"form-control "));?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Urutan <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","data-parsley-type"=>"number","name"=>"newmodule_order","value"=>$module_order,"class"=>"form-control "));?>
          </div>
          <div class="form-group">
            <label class="control-label">Ikon</label>
            <?php echo form_input(array("name"=>"newmodule_icon","value"=>$module_icon,"class"=>"form-control "));?>
            <em>
              Use 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-material.html" target="_new">Material Design</a>, 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-fontawesome.html" target="_new">Font Awesome</a>, 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-ion.html" target="_new">Ion Icons</a>, 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-themify.html" target="_new">Themify Icons</a>, 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-dripicons.html" target="_new">Dripicons</a>, or 
              <a href="https://themesbrand.com/veltrix/layouts/vertical/icons-typicons.html" target="_new">Typicons</a>
              class name
            </em>
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