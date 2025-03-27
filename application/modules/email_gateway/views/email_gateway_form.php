<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body p-b-0">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Subject</label>
            <?php echo form_input(array("readonly"=>"","value"=>$subject,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Tujuan</label>
            <?php echo form_input(array("readonly"=>"","value"=>$destination,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Dikirim</label>
            <?php echo form_input(array("readonly"=>"","value"=>$sendingtime,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Diterima</label>
            <?php echo form_input(array("readonly"=>"","value"=>$deliverytime,"class"=>"form-control  first-input-form "));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Lampiran</label>
            <div class="mt-1">
            <?php 
              if($attachment!=""){
            ?>
              <a href="javascript:;" class="btn-preview" data-name="Lampiran Email" data-link="<?php echo base_url($attachment)?>" data-ext="<?php echo $ext_attachment?>">Klik untuk pratinjau berkas</a>
            <?php 
              }else{
                echo "Tidak ada lampiran KTP";
              }
            ?>
            </div>
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
  <?php 
//    if($status=="n"){
      if(1==1){
  ?>
  <div class="card-footer text-right">
    <button type="submit" class="btn btn-success " ><i class="ti-send"></i> Kirim Ulang</button>
  </div>
  <?php 
    }
  ?>
</form>