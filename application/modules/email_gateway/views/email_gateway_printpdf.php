    <style type="text/css">

      .printpreview tr th {
        font-size: 9pt;
        font-weight: bold;
        font-family: "Cambria",sans-serif;
        padding: 4px;
        text-transform: uppercase;
      }
      .printpreview tr td {
        font-size: 9pt;
        font-family: "Cambria",sans-serif;
        vertical-align: top;
        padding: 4px;
      }
      
      .printpreview h4,.printpreview h2,.printpreview h3,.printpreview h5{
        text-align: center;
        font-family: "Cambria",sans-serif;
        font-weight: 300;
      }

      .printpreview table{
        width: 100%;
        border-collapse: collapse;
      }
      .printpreview table.table, .printpreview table.table th, .printpreview table.table td {
          border: 0.5px solid #333;
      }
      
      .printpreview table.tablejudul tr td {
          font-size: 10pt;
      }

      .printpreview table.tablejudul tr td {
          padding: 2px !important;
      }

      .printpreview{
        font-size: 9.5pt;
      }
    </style>
    <div class="printpreview">
      <h4><strong>LAPORAN EMAIL</strong></h4>
      <table class="table">
        <thead>
          <tr>
            <th width="3%">NO</th>
            <th width="13%">DIKIRIM</th>
            <th width="13%">DITERIMA</th>
            <th width="18%">TUJUAN</th>
            <th width="41%">SUBJECT</th>
            <th width="12%">STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php  
            if($data->num_rows()>0){ 
              $num = 0;
              foreach ($data->result_array() as $key => $value) {
          ?>
          <tr>
            <td align="right"><?php echo ++$num?></td>
            <td align="center"><?php echo $value['sendingtime']?></td>
            <td align="center"><?php echo $value['deliverytime']?></td>
            <td ><?php echo $value['destination']?></td>
            <td ><?php echo $value['subject']?></td>
            <td ><?php echo $value['status']?></td>
          </tr> 
          <?php
              }
            }
          ?>
        </tbody>        
      </table> 
    </div>