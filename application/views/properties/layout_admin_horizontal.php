<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $configsite['title'];?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="<?php echo $configsite['title'];?>" name="description" />
    <meta content="abdulionel" name="author" />
	<link href="<?php echo base_url($configsite['icon']);?>" rel="icon" type="image/png"/>
    <link href="<?php echo base_url()?>assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo base_url()?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/plugins/bootstrap-sweetalert/sweetalert.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/css/abdadmin.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url()?>assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript">
		var base_url 		= "<?php echo base_url()?>";
		var site_url 		= "<?php echo site_url()?>";
		var appname 		= '<?php echo $configsite['name'];?>';
		var environment 	= "<?php echo ENVIRONMENT?>";
		var elfinder_url 	= "<?php echo site_url(CI_ADMIN_PATH.'/browse')?>";;
		var session_url		= "<?php echo site_url(CI_ADMIN_PATH.'/session')?>";
		var notif_url		= "<?php echo site_url(CI_ADMIN_PATH.'/notification')?>";
		var rnotif_url		= "<?php echo site_url(CI_ADMIN_PATH.'/readnotification')?>";
	</script>
</head>
<body data-topbar="dark" data-layout="horizontal" data-layout-size="boxed">
	<?php $this->load->view("properties/remove_iklan")?>
	<div id="layout-wrapper">
		<header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="<?php echo site_url(CI_ADMIN_PATH)?>" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?php echo base_url($configsite['logo']);?>" alt="" height="35">
                            </span>
                            <span class="logo-lg text-white font-size-20">
                                <img src="<?php echo base_url($configsite['logo']);?>" alt="" height="35">
                                <?php echo strtoupper($configsite['name']);?>
                            </span>
                        </a>

                        <a href="<?php echo site_url(CI_ADMIN_PATH)?>" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?php echo base_url($configsite['logo']);?>" alt="" height="35">
                            </span>
                            <span class="logo-lg text-white font-size-20">
                                <img src="<?php echo base_url($configsite['logo']);?>" alt="" height="35">
                                <?php echo strtoupper($configsite['name']);?>
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm mr-2 font-size-24 d-lg-none header-item waves-effect waves-light"
                            data-toggle="collapse" data-target="#topnav-menu-content">
                            <i class="mdi mdi-menu"></i>
                    </button>
                </div>

                <div class="d-flex">

                    <div class="dropdown d-none d-md-block ml-2">
                        <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><?php echo $datauser['name']?> | <?php echo $datauser['group_name']?> | 
                            <span class="countonline">0</span> Pengguna | Memori {memory_usage} | Memuat Halaman {elapsed_time} detik
                        </button>
                    </div>

                    <div class="dropdown d-none d-lg-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-bell-outline"></i>
                            <span class="countnewnotification"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="m-0 font-size-16"> Pemberitahuan </h5>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 250px;">
                                <div class="list-notification"></div>
                            </div>
                            <div class="p-2 border-top">
                                <a class="btn btn-sm btn-link font-size-14 btn-block linkajax linkprofil text-center" link="<?php echo site_url('profile/notification')?>" name="Notification" href="<?php echo site_url('profile/notification')?>">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="<?php echo $datauser['image_profile']?>"
                                alt="<?php echo strtoupper($datauser['name'])?>">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                        	<?php 
								if($datauser['group_name']!="demo"){
							?>
                            <!-- item-->
                            <a link="<?php echo site_url('profile')?>" name="Profil" href="<?php echo site_url('profile')?>" class="linkajax dropdown-item linkprofil"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> Profile</a>
                            <a link="<?php echo site_url('profile/panduan')?>" name="Panduan" href="<?php echo site_url('profile/panduan')?>" class="linkajax linkprofil dropdown-item"><i class="mdi mdi-book font-size-17 align-middle mr-1"></i> Panduan</a>
                            <?php }else{?>
                            <a class="dropdown-item d-block" href="javascript:;" onclick="alert('Fitur ini tidak tersedia untuk user demo')" ><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> Profil</a>
                            <?php }?>
                            <a class="dropdown-item d-md-none d-sm-block noti-icon right-bar-toggle" href="javascript:;" ><i class="mdi mdi-settings-outline font-size-17 align-middle mr-1"></i> Pengaturan</a>
                            <div class="dropdown-divider"></div>
                            <a class="linklogout dropdown-item text-danger" link="<?php echo site_url('profile/logout')?>" href="javascript:;" ><i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i> Keluar</a>
                        </div>
                    </div>

                    <div class="dropdown d-md-block d-none">
                        <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                            <i class="mdi mdi-settings-outline"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav" >
                            <li class="nav-item">
                                <a href="<?php echo site_url(CI_ADMIN_PATH)?>" link="<?php echo site_url(CI_ADMIN_PATH)?>" namelink="BERANDA" class="linkajax waves-effect nav-link"><i class="ti-home mr-2"></i><span>Dashboard</span></a>
                            </li>
                            <?php echo $buildMenusidebar;?>
                            <li class="nav-item"><a class="linklogout nav-link" link="<?php echo site_url('profile/logout')?>" href="javascript:;"><i class="ti-power-off mr-2"></i><span class="title">Keluar</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid content" id="content">
                	<?php $this->load->view($konten)?>
                </div>
            </div>
	        <footer class="footer">
		        <div class="container-fluid">
		            <div class="row">
		                <div class="col-12">
		                    <?php echo $configsite['copyright']?>
		                </div>
		            </div>
		        </div>
		    </footer>
		</div>
    </div>

    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title px-3 py-4">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
                <h5 class="m-0">Pengaturan</h5>
            </div>

            <!-- Settings -->
            <hr class="mt-0" />
            <h6 class="text-center">Pilih Tata Letak</h6>

            <div class="p-4">
                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input theme-choice" id="light-mode-switch" checked />
                    <label class="custom-control-label" for="light-mode-switch">Mode Cahaya</label>
                </div>
                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" 
                        data-appStyle="assets/css/app-dark.min.css" />
                    <label class="custom-control-label" for="dark-mode-switch">Mode Gelap</label>
                </div>
                <div class="btn btn-group btn-block">
                    <a href="<?php echo site_url(CI_ADMIN_PATH)?>?layout=vertical" class="btn btn-sm btn-primary">Vertikal</a>
                    <a href="<?php echo site_url(CI_ADMIN_PATH)?>?layout=horizontal" class="btn btn-sm btn-primary">Horisontal</a>
                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>

    <div class="rightbar-overlay"></div>
	<!-- end scroll to top btn -->
    <script src="<?php echo base_url()?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/metismenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/blockui/jquery.blockui.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/parsleyjs/parsley.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/masked-input/masked-input.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/notification/bootstrap-notify.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/app.js"></script>
    <script src="<?php echo base_url()?>assets/js/abdadmin.js"></script>
    <script src="<?php echo base_url()?>assets/js/ajaxpaging.js"></script>
<!-- END BODY -->
</html>