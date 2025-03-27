<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body p-b-0">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label class="control-label">Panduan <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newname","value"=>$name,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Grup <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newgroup[]',$listgroup,$group_id, array("multiple"=>"","data-parsley-class-handler"=>".dropdown-group","data-parsley-errors-container"=>"#errgroup","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-group btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
            <span  id="errgroup"></span>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <label class="control-label">Status</label>
            <div class="mt-1">
                <?php 
                  echo form_checkbox("newstatus","y",("y"==$status)?TRUE:FALSE,array("id"=>"cbstatus","switch"=>"bool"));
                  echo form_label("","cbstatus",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                ?>
            </div>
            <span  id="errstatus"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group mb-0">
            <div class="p-l-15"  id="errguidesdescription"></div>
            <?php echo form_textarea(array("data-parsley-class-handler"=>".note-editor","data-parsley-errors-container"=>"#errguidesdescription","data-parsley-required"=>"true","name"=>"newdescription","id"=>"guidesdescription","value"=>$description,"class"=>"form-control summernote ","data-height"=>"350"));?>
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