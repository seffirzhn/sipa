<?php
$http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';
$newurl = str_replace("index.php","", $_SERVER['SCRIPT_NAME']);
$base_url    = "$http" . $_SERVER['SERVER_NAME'] . "" . $newurl; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
  	<title><?php echo $heading; ?></title>
  	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <link href="<?php echo $base_url?>/assets/img/404.png" rel="icon" type="image/png"/>
  	<!-- ================== BEGIN BASE CSS STYLE ================== -->

    <link href="<?php echo $base_url?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo $base_url?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo $base_url?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

  	<!-- ================== END BASE JS ================== -->
</head>
<body >
    <script type="text/javascript">
      $(document).ready(function(){
          $('body').append("</bo"+"dy>");
        });
    </script>
    <div class="authentication-bg d-flex align-items-center pb-0 vh-100">
        <div class="content-center w-100">
                <div class="container">
                    <div class="card mo-mt-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-4 ml-auto">
                                    <div class="ex-page-content">
                                        <h1 class="text-dark display-1 mt-4">404!</h1>
                                        <h4 class="mb-4"><?php echo $heading; ?></h4>
                                        <p class="mb-5"><?php echo $message; ?></p>
                                        <a class="btn btn-primary mb-5 waves-effect waves-light" href="<?php echo $base_url?>"><i class="mdi mdi-home"></i> Kembali</a>
                                    </div>
                        
                                </div>
                                <div class="col-lg-5 mx-auto">
                                    <img src="<?php echo $base_url?>assets/img/error.png" alt="" class="img-fluid mx-auto d-block">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end container -->
        </div>

    </div>
	</div>
	<script src="<?php echo $base_url?>assets/libs/jquery/jquery.min.js"></script>
  <script src="<?php echo $base_url?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
</html>