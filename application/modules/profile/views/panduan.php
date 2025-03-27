<div class="row m-b-40" id="data-tableuser">
  <div class="col-md-12">
    <div class="card panel-grid">
      <div class="card-heading">
        <h4 class="card-title">PANDUAN</h4>
      </div>
      <div class="card-body"> 
        <?php 
          if($panduan->num_rows()>0){
            $panduan2 = $panduan->result_array();
            $no = 1;
        ?>
          <div id="accordion" class="card-accordion">
        <?php
            foreach ($panduan2 as $key => $value) {
        ?>  
          <div class="card mb-1">
            <div class="card-header bg-primary text-white p-3" data-toggle="collapse" data-target="#collapse<?php echo $no?>">
              <?php echo $no.". ".$value['name']?>
            </div>
            <div id="collapse<?php echo $no?>" class="collapse" data-parent="#accordion">
              <div class="card-body bg-secondary text-black">
                <?php echo $value['description']?>
              </div>
            </div>
          </div>
        <?php
              $no++;
            }
        ?>
          </div>
        <?php
          }else{
        ?>
          <div class="alert alert-warning">
            Belum ada panduan
          </div>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>
                