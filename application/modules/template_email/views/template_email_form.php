<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Template Email <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama","value"=>$nama,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group mb-0">
            <div class="p-l-15"  id="errpesan"></div>
            <?php echo form_textarea(array("data-parsley-class-handler"=>".note-editor","data-parsley-errors-container"=>"#errpesan","data-parsley-required"=>"true","name"=>"newpesan","id"=>"pesan","value"=>$pesan,"class"=>"form-control summernote ","data-height"=>"350"));?>
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