<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body p-b-0">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Tujuan <span class="text-danger">*</span></label>
            <?php echo form_input(array("readonly"=>"","value"=>$destination,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Dikirim <span class="text-danger">*</span></label>
            <?php echo form_input(array("readonly"=>"","value"=>$sendingtime,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Diterima <span class="text-danger">*</span></label>
            <?php echo form_input(array("readonly"=>"","value"=>$deliverytime,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-12">
          <hr/>
          <div class="bg-secondary p-3">
            <?php echo $message?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer text-right">
    <button type="submit" class="btn btn-success " ><i class="ti-send"></i> Kirim Ulang</button>
  </div>
</form>