<div class="row mt-5">
  <!-- begin col-3 -->
  <div class="col-md-12">
      <h2 class="text-center f-w-400" >Selamat Datang di <?php echo $configsite["subname"]?><br/><?php echo $configcompany['nama']?></h2>
  </div>
</div>
<hr style="border:2px solid #999" class="mt-3 mb-3" />
<div class="row">
  <div class="col-md-3">
      <div class="card mini-stat bg-primary text-white">
          <div class="card-body">
              <div class="mb-0">
                  <div class="float-start">
                  <!-- <i class="mdi mdi-arrow-up text-success"></i> -->
                    <lord-icon
                      src="https://cdn.lordicon.com/zeabctil.json"
                      trigger="loop"
                      style="width:60px;height:60px">
                    </lord-icon>
                  </div>
                  <h4 class="fw-medium font-size-20"><?= $statistik[0]??0 ?> Aplikasi </h4>
                  <h5 class="font-size-14 text-uppercase text-white-50">Pelayanan Publik</h5>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-3">
      <div class="card mini-stat bg-warning text-white">
          <div class="card-body">
              <div class="mb-0">
                  <div class="float-start">
                  <!-- <i class="mdi mdi-arrow-up text-success"></i> -->
                    <lord-icon
                      src="https://cdn.lordicon.com/jefnhaqh.json"
                      trigger="loop"
                      style="width:60px;height:60px">
                    </lord-icon>
                  </div>
                  <h4 class="fw-medium font-size-20"><?= $statistik[1]??0 ?> Aplikasi </h4>
                  <h5 class="font-size-14 text-uppercase text-white-50">Pelayanan Internal Unit Kerja</h5>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-3">
      <div class="card mini-stat bg-success text-white">
          <div class="card-body">
              <div class="mb-0">
                  <div class="float-start">
                  <!-- <i class="mdi mdi-arrow-up text-success"></i> -->
                    <lord-icon
                      src="https://cdn.lordicon.com/zlwsgwzg.json"
                      trigger="loop"
                      style="width:60px;height:60px">
                    </lord-icon>
                  </div>
                  <h4 class="fw-medium font-size-20"><?= $statistik[2]??0 ?> Aplikasi </h4>
                  <h5 class="font-size-14 text-uppercase text-white-50">Pelayanan Internal Pemko TPI</h5>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-3">
      <div class="card mini-stat bg-danger text-white">
          <div class="card-body">
              <div class="mb-0">
                  <div class="float-start">
                  <!-- <i class="mdi mdi-arrow-up text-success"></i> -->
                    <lord-icon
                      src="https://cdn.lordicon.com/kjkiqtxg.json"
                      trigger="loop"
                      style="width:60px;height:60px">
                    </lord-icon>
                  </div>
                  <h4 class="fw-medium font-size-20"><?= $statistik[3]??0 ?> Aplikasi </h4>
                  <h5 class="font-size-14 text-uppercase text-white-50">Pelayanan Antar OPD</h5>
              </div>
          </div>
      </div>
  </div>
</div>
<?php echo $configsite["preview"]?>

<script src="https://cdn.lordicon.com/fudrjiwc.js"></script>