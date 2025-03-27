<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">Tanggal <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtanggal","value"=>$tanggal,"class"=>"form-control date-picker"));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Jam Kampanye <span class="text-danger">*</span></label>
            <div class="input-group " >
              <?php echo form_input(array("name"=>"newjam_mulai","id"=>"jam_mulai","value"=>$jam_mulai,"class"=>"form-control","placeholder"=>"00:00","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglkampanye"));?>
              <span class="input-group-addon input-group-prepend input-group-append" ><span class="input-group-text">s/d</span></span>
              <?php echo form_input(array("name"=>"newjam_selesai","id"=>"jam_selesai","value"=>$jam_selesai,"class"=>"form-control","placeholder"=>"00:00","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglkampanye"));?>
            </div>
            <span id="errtglkampanye"></span>
          </div>
        </div>
        <div class="col-md-7"> 
          <div class="form-group">
            <label class="control-label">Pasangan Calon <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newpaslon',$listpaslon,$id_paslon, array("data-parsley-class-handler"=>".dropdown-paslon","data-parsley-errors-container"=>"#errpaslon","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-paslon btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errpaslon"></span>
          </div> 
        </div> 
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">Jumlah Peserta <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjumlah_peserta","value"=>$jumlah_peserta,"class"=>"form-control  "));?>
          </div>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <label class="control-label">Lokasi Kampanye <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newlokasi_kampanye","value"=>$tempat_kegiatan,"class"=>"form-control  "));?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group mb-0">
          <label class="control-label">Uraian Kegiatan Kampanye <span class="text-danger">*</span></label>
          <div class="p-l-15"  id="errdeskripsi_kampanye"></div>
          <?php echo form_textarea(array("data-parsley-class-handler"=>".note-editor","data-parsley-errors-container"=>"#errdeskripsi_kampanye","data-parsley-required"=>"false","name"=>"newuraian_kegiatan","id"=>"guidesdeskripsi_kampanye","value"=>$uraian_kegiatan,"class"=>"form-control summernote ","data-height"=>"350"));?>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
        <label class="control-label">Tokoh yang Hadir <span class="text-danger">*</span></label>
            <div class="table-responsive" style="overflow-x: unset">
              <table class="table table-bordered ">
                <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="90%">Nama Tokoh Politik</th>
                    <th width="5%" style="text-align: center"><button type="button" class="btn btn-success btn-sm btn-tambah-rincian"><i class="ti-plus"></i><span class="hidden-sm hidden-xs"></span></button></th>
                  </tr>
                </thead>
                <tbody id="bodyrincian">
                  <?php 
                    if($listtopol->num_rows()>0){
                      $i = 0;
                      foreach ($listtopol->result_array() as $key => $value) {
                        $i++;
                    ?>
                    <tr>
                      <td ><?php echo form_hidden("id_topol_kampanye[]",$value["id_topol_kampanye"]).form_input(array("data-parsley-required"=>"true","name"=>"newurutan[]","value"=>$i,"class"=>"form-control "));?></td>
                      
                      <td><?php echo form_dropdown('newtopol[]',$listrinciantopol,$value["id_topol"], array("data-parsley-class-handler"=>".dropdown-topol","data-parsley-errors-container"=>"#errtopol","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-topol btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
                      </td>
                      <td align="center">
                        <button type="button" class="btn btn-danger btn-hapus-rincian"><i class="ti-trash"></i><span class="hidden-sm hidden-xs"></span></button>
                      </td>
                    </tr> 
                  <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
        <label class="control-label">Dokumentasi <span class="text-danger">*</span></label>
            <div class="table-responsive">
              <table class="table table-bordered " id="table-dokumentasi">
                <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="90%">File Foto</th>
                    <th width="5%" style="text-align: center"><button type="button" class="btn btn-success btn-sm btn-tambah-rincian-dok"><i class="ti-plus"></i><span class="hidden-sm hidden-xs"></span></button></th>
                  </tr>
                </thead>
                <tbody id="bodyrinciandok">
                  <?php 
                    if($listdokumentasi->num_rows()>0){
                      $i = 0;
                      foreach ($listdokumentasi->result_array() as $key => $value) {
                        $i++;
                    ?>
                    <tr>
                      <td ><?php echo form_hidden("id_dokumentasi_kampanye[]",$value["id_dokumentasi_kampanye"]).form_input(array("data-parsley-required"=>"true","name"=>"newurutan[]","value"=>$i,"class"=>"form-control "));?></td>
                      
                      <td>
                        <!-- <div>
                          <div class="m-b-5">
                            <span class="btn btn-primary btn-file">
                              <span><i class="ti-search fa-lg"></i> Telusuri</span>
                              <?php 
                                echo form_upload(array("name"=>"newfiledokumentasi[]","id"=>"newfilerincian".($key+1),"accept"=>"image/*"));
                              ?>
                            </span>
                          </div>
                        </div> -->
                        <?php 
                          if($value["file_foto"]!=""){
                        ?>
                        <div class="m-t-5">
                            <a href="javascript:;" class="btn btn-primary btn-preview" data-name="FILE" data-link="<?php echo base_url($value["file_foto"])?>" data-ext="<?php echo get_mime_by_extension(realpath($value["file_foto"]))?>">Klik untuk pratinjau berkas</a>
                        </div>
                        <?php
                          }
                        ?>  
                      </td>
                      <td align="center">
                        <button type="button" class="btn btn-danger btn-hapus-rincian-dok" data-id="<?php echo $value["id_dokumentasi_kampanye"] ?>"><i class="ti-trash"></i><span class="hidden-sm hidden-xs"></span></button>
                      </td>
                    </tr> 
                  <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Keterangan <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newketerangan","value"=>$keterangan,"class"=>"form-control  "));?>
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
<div id="inputfile" class="d-none">
  <div>
    <div class="m-b-5">
      <span class="btn btn-primary btn-file">
        <span><i class="ti-search fa-lg"></i> Telusuri</span>
        <?php 
          echo form_upload(array("name"=>"newfiledokumentasi[]","id"=>"newfilerincian##","accept"=>"image/*"));
        ?>
      </span>
    </div>
  </div>
</div>
<div id="dropdowntopol" class="d-none">
  <div class="form-group">
    <?php echo form_dropdown('newtopol[]',$listrinciantopol,null, array("data-parsley-class-handler"=>".dropdown-topol","data-parsley-errors-container"=>"#errtopol","data-parsley-required"=>"true","class"=>"select-picker-list select-picker form-control ","data-style"=>"dropdown-topol btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $('#jam_mulai').mask('00:00');
     $('#jam_selesai').mask('00:00');
    var num_file = <?php echo $listtopol->num_rows()+1?>;
    var num_dok  = <?php echo $listdokumentasi->num_rows()+1?>;
    for (var i = 1; i < num_dok; i++) {
      Browsefile.init({target:"#data-tabledata_kampanye .forminput #newfilerincian"+i,text:"Max. 2MB"});
    }

    Browsefile.init({target:"#data-tabledata_kampanye .forminput #newgambar",text:"Max. 2MB"});
    $("#data-tabledata_kampanye .forminput").off("click",".btn-tambah-rincian").on("click",".btn-tambah-rincian",function(){
      var htmlsatuan    = $("#data-tabledata_kampanye .panel-form .body-form #dropdowntopol").html();
      htmlsatuan        = htmlsatuan.replace("select-picker-list","select-picker-list"+num_file);
      var htmlrincian   = '<tr><td><input type="hidden" name="id_topol_kampanye[]" value=""><input type="text" name="newurutan[]" value="'+num_file+'" data-parsley-required="true" class="form-control "  /></td><td>'+htmlsatuan+'</td><td align="center"><button type="button" class="btn btn-danger btn-hapus-rincian"><i class="ti-trash"></i><span class="hidden-sm hidden-xs"></span></button></td></tr>';
      $("#data-tabledata_kampanye .forminput #bodyrincian").append(htmlrincian);
      $("#data-tabledata_kampanye .forminput #bodyrincian .select-picker-list"+num_file).selectpicker();
      /*$(".table-responsive .select-picker-list"+num_file).on('shown.bs.select', function(e) {
//        alert($(this).parents(".bootstrap-select").hasClass("show"));
        if($(this).parents(".dropup").hasClass("show")){
          $(this).parents(".table-responsive").css("overflow-x","auto");
        }else{
          $(this).parents(".table-responsive").css("overflow-x","unset");
        }
      });*/
      Browsefile.init({target:"#data-tabledata_kampanye .forminput #newfilerincian"+num_file,text:"Max. 2MB"});
      num_file++;
    });

    $("#data-tabledata_kampanye .forminput").off("click",".btn-tambah-rincian-dok").on("click",".btn-tambah-rincian-dok",function(){
      var htmlfile      = $("#data-tabledata_kampanye .panel-form .body-form #inputfile").html();
      htmlfile          = htmlfile.replace("newfilerincian##","newfilerincian"+num_dok);
      var htmlrincian   = '<tr><td><input type="hidden" name="id_dokumentasi_kampanye[]" value=""><input type="text" name="newurutan[]" value="'+num_dok+'" data-parsley-required="true" class="form-control "  /></td><td>'+htmlfile+'</td><td align="center"><button type="button" class="btn btn-danger btn-hapus-rincian-dok"><i class="ti-trash"></i><span class="hidden-sm hidden-xs"></span></button></td></tr>';
      $("#data-tabledata_kampanye .forminput #bodyrinciandok").append(htmlrincian);
      Browsefile.init({target:"#data-tabledata_kampanye .forminput #newfilerincian"+num_dok,text:"Max. 2MB"});
      num_dok++;
    });

    $("#data-tabledata_kampanye .forminput #bodyrincian").off("click",".btn-hapus-rincian").on("click",".btn-hapus-rincian",function(){
        $(this).parent().parent().remove(); 
    });

    $("#data-tabledata_kampanye .forminput #bodyrinciandok").off("click",".btn-hapus-rincian-dok").on("click",".btn-hapus-rincian-dok",function(){
      var value = $(this).attr("data-id");
      // alert(value);
        if(value>0){
        deletegambar(value);
        }
        $(this).parent().parent().remove(); 
    });


    function deletegambar(value) {
        blockUI();
        $.post('<?php echo $actdeletedok?>', {
            "fromajax": "yes",
            "id_dokumentasi_kampanye": value
        }, function(response) {
            unblockUI();
        });
    }
    
    $("#jam_mulai").mask("00:00:00");
    $("#jam_selesai").mask("00:00:00");
  });
</script>