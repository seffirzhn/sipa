<style type="text/css">
  .btn-file {
      position: relative;
      overflow: hidden;
  }
  .btn-file input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 999px;
      text-align: right;
      opacity: 0;
      outline: none;
      background: #fff;
      cursor: pointer;
      display: block;
  }

  .frame-foto{
    margin:auto;
    display: table;
  }
  .profile-image{
    width: 15rem;
    height: 15rem;
    border-radius: 50%;
    overflow: hidden;
    background-color: #2e3648;
  }
</style>

<div class="card">
  <div class="card-heading">
    <h4 class="card-title">PROFIL PENGGUNA</h4>
  </div>
  <div class="card-body">
    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabmain" role="tab">
                <span class="d-block d-sm-none"><i class="ti ti-home"></i></span>
                <span class="d-none d-sm-block">Utama</span> 
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabfoto" role="tab">
                <span class="d-block d-sm-none"><i class="ti ti-image"></i></span>
                <span class="d-none d-sm-block">Foto Profil</span> 
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabpassword" role="tab">
                <span class="d-block d-sm-none"><i class="ti ti-key"></i></span>
                <span class="d-none d-sm-block">Atur Ulang Katasandi</span>   
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#taboauth" role="tab">
                <span class="d-block d-sm-none"><i class="ti ti-world"></i></span>
                <span class="d-none d-sm-block">Hubungkan Ke Media Sosial</span>    
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active p-3" id="tabmain" role="tabpanel">
          <div class="row">
            <div class="col-md-4">
              <form id="form-profil">
                <div class="form-body ">
                  <h4 class="m-b-5 f-w-500">Profil</h4>
                  <div class="form-group">
                    <input type="text" name="nama" value="<?php echo $user['name'] ?>" class="form-control" />
                    <span class="msg"></span>
                  </div>
                  <h4>Email</h4>
                  <div class="form-group">
                    <input type="text" name="email" value="<?php echo $user['email'] ?>" class="form-control" />
                    <span class="msg"></span>
                  </div>
                  <h4>No Hp.</h4>
                  <div class="form-group">
                    <input type="text" name="notelp" value="<?php echo $user['phone'] ?>" class="form-control" />
                    <span class="msg"></span>
                  </div>
                  <h4 class="m-b-5 f-w-500">Grup</h4>
                  <span>Anda adalah anggota dari grup berikut : <strong><?php echo $user['group_name'] ?></strong></span>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabfoto" role="tabpanel">
          <div class="row">
            <div class="col-md-4">
              <form method="post" id="form-gambar">
                <div class="frame-foto">
                  <div id="vfoto" class="profile-image">
                    <img src="<?php echo $user['image_profile']?>" width="100%"/>
                  </div>
                  <div class="btn-group btn-block mt-3">
                    <span class="btn btn-warning btn-file">
                      <span class="fa-lg ti-upload"></span>
                      <input type="file" name="newfoto" accept="image/png, image/jpeg, image/gif"/>
                    </span>
                    <button type="button" class="btn-delete-foto btn btn-danger"><i class="fa-lg ti-trash"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabpassword" role="tabpanel">
          <form method="post" class="row" id="form-password">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo form_password(array("data-parsley-required"=>"true","data-parsley-minlength"=>"8","data-parsley-errors-container"=>"#errold","name"=>"sandilama","class"=>"form-control formpassword","placeholder"=>"Katasandi Lama"));?>
                <span id="errold"></span>
              </div>
              <div class="form-group">
                <?php echo form_password(array("id"=>"passnya","data-parsley-required"=>"true","data-parsley-minlength"=>"8","data-parsley-errors-container"=>"#errpass","name"=>"sandibaru","class"=>"form-control formpassword","placeholder"=>"Katasandi Baru"));?>
                <span id="errpass"></span>
              </div>
              <div class="form-group">
                <?php echo form_password(array("data-parsley-equalto"=>"#passnya","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errkonfirmpass","data-parsley-minlength"=>"8","name"=>"konfirmasisandibaru","class"=>"formpassword form-control ipassword ","placeholder"=>"Konfirmasi Katasandi"));?>
                <span id="errkonfirmpass"></span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">Ubah</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane p-3" id="taboauth" role="tabpanel">
          <div class="row">
            <div class="col-md-4">
              <?php if($google_connect_url or $facebook_connect_url){ ?>
              <h4 class="m-b-10 f-w-500">Hubungkan akun ke</h4>
              <?php }?>
              <?php if($google_connect_url){ ?>
              <div class="form-group m-t-5">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ti-google"></i></span>
                  </div>
                  <?php if($namagoogle){?>
                  <input type="text" class="form-control" readonly="" value="<?php echo $namagoogle?>">
                  <div class="input-group-append">
                  <?php echo anchor(site_url('profile/unbind/google'),'Hapus',array("class"=>"btn btn-danger remove-google"));?>
                  </div>
                  <?php }else{ ?>
                  <div class="input-group-append">
                  <?php echo anchor($google_connect_url,'Hubungkan Sekarang',array("class"=>"btn btn-danger connect-google"));?>
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php }?>
              <?php if($facebook_connect_url){ ?>
              <div class="form-group m-t-5">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ti-facebook"></i></span>
                  </div>
                  <?php if($namafacebook){?>
                  <input type="text" class="form-control" readonly="" value="<?php echo $namafacebook?>">
                  <div class="input-group-append">
                    <?php echo anchor(site_url('profile/unbind/facebook'),'Hapus',array("class"=>"btn btn-info remove-facebook"));?>
                  </div>
                  <?php }else{ ?>
                  <div class="input-group-append">
                  <?php echo anchor($facebook_connect_url,'Hubungkan Sekarang',array("class"=>"btn btn-info connect-facebook"));?>
                  </div>
                  <?php } ?>
                </div>
              </div>
              <?php }?>
              <?php echo $this->session->flashdata("errormessage")?>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<script defer src="<?php echo base_url()?>assets/plugins/bootstrap-show-password/bootstrap-show-password.min.js"></script>
<script defer src="<?php echo base_url()?>assets/js/profile.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#form-password .formpassword').password();
  });
  var actionprofile = "<?php echo $actionprofile?>";
</script>
<script type="text/javascript">
  $(document).ready(function(){
  <?php 
    if($facebook_connect_url){
      if($namafacebook){
  ?>  
    $(".remove-facebook").on("click",function(a){
      a.preventDefault();
      var dom = $(this);
      swal({
          title: "Yakin ingin menghapus akun facebook anda dari akun ini?",
          text: "",
          icon: "error",
          buttons: {
              cancel: {
                  text: "Batal",
                  visible: !0,
                  className: "btn btn-default",
                  closeModal: !0
              },
              confirm: {
                  text: "Hapus",
                  visible: !0,
                  className: "btn btn-danger",
                  closeModal: !0
              }
          },
          closeOnClickOutside: !0,
          dangerMode: !0
      }).then(function(e) {
          if(e){
            document.location=dom.attr("href");
          }
      })
    });
  <?php
      }else{
  ?>
    $(".connect-facebook").on("click",function(a){
      a.preventDefault();
      var dom = $(this);
      swal({
          title: "Yakin ingin menghubungkan akun facebook anda ke akun ini?",
          text: "hanya email, nama, dan foto yang kami gunakan",
          icon: "info",
          buttons: {
              cancel: {
                  text: "Batal",
                  visible: !0,
                  className: "btn btn-default",
                  closeModal: !0
              },
              confirm: {
                  text: "Hubungkan",
                  visible: !0,
                  className: "btn btn-primary",
                  closeModal: !0
              }
          },
          closeOnClickOutside: !0,
          dangerMode: !0
      }).then(function(e) {
          if(e){
            document.location=dom.attr("href");
          }
      })
    });
  <?php
      } 
    }
  ?>

  <?php 
    if($google_connect_url){
      if($namagoogle){
  ?>  
    $(".remove-google").on("click",function(a){
      a.preventDefault();
      var dom = $(this);
      swal({
          title: "Yakin ingin menghapus akun google anda dari akun ini?",
          text: "",
          icon: "error",
          buttons: {
              cancel: {
                  text: "Batal",
                  visible: !0,
                  className: "btn btn-default",
                  closeModal: !0
              },
              confirm: {
                  text: "Hapus",
                  visible: !0,
                  className: "btn btn-danger",
                  closeModal: !0
              }
          },
          closeOnClickOutside: !0,
          dangerMode: !0
      }).then(function(e) {
          if(e){
            document.location=dom.attr("href");
          }
      })
    });
  <?php
      }else{
  ?>
    $(".connect-google").on("click",function(a){
      a.preventDefault();
      var dom = $(this);
      swal({
          title: "Yakin ingin menghubungkan akun google anda ke akun ini?",
          text: "hanya email, nama, dan foto yang kami gunakan",
          icon: "info",
          buttons: {
              cancel: {
                  text: "Batal",
                  visible: !0,
                  className: "btn btn-default",
                  closeModal: !0
              },
              confirm: {
                  text: "Hubungkan",
                  visible: !0,
                  className: "btn btn-danger",
                  closeModal: !0
              }
          },
          closeOnClickOutside: !0,
          dangerMode: !0
      }).then(function(e) {
          if(e){
            document.location=dom.attr("href");
          }
      })
    });
  <?php
      } 
    }
  ?>
  });
</script>