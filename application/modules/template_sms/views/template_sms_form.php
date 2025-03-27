<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Template Email <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama","value"=>$nama,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Pesan <span class="text-danger">*</span></label>
            <?php echo form_textarea(array("data-parsley-required"=>"true","name"=>"newpesan","value"=>$pesan,"rows"=>"5","class"=>"form-control"));?>
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