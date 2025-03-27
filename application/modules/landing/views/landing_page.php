<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Sistem Informasi Pendataan Aplikasi</title>
    <link rel="Icon" type="png" href="resources/config_site/logo-tpi.png">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<header id="header">
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="<?= base_url('resources/config_site/logo-tpi.png') ?>" alt="Logo" class="brand-logo">
                <span class="ms-2 fw-bold fs-4 text-primary">Sistem Informasi Pendataan Aplikasi</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end fw-bold fs-6 text-uppercase flex-grow-1 pe-3 gap-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#home">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#our-apps">Aplikasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <section id="home" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-0 mt-5 mt-lg-0 position-relative">
                    <img src="assets/img/dotted-circle.svg" alt="Dotted" 
                    style="position:absolute; top: -50px; left: -80px; width: 300px; opacity: 0.3; z-index: 0;">
                    <h1 class="display-5 fw-bold mb-3 position-relative" style="z-index:1;">Selamat Datang</h1>
                    <p class="lead text-muted position-relative" style="z-index:1;">Sistem Informasi Pendataan Aplikasi</p>
                </div>
                <div class="col-lg-6 order-lg-1 text-center">
                    <video autoplay muted loop class="img-fluid" style="max-height: 2000px;">
                        <source src="assets/img/main-home.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </section>
    
    <section id="our-apps" class="py-5">
        <div class="container-fluid px-lg-4 px-xl-5">
            <div class="header text-center mb-4">
                <h1 class="mb-4">Aplikasi Kami</h1>
                <form class="search-filter" method="get" action="">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Cari Aplikasi Pelayanan..." 
                               value="<?= $this->input->get('search') ?>">
                        <button type="submit">
                            <svg aria-hidden="true" focusable="false" width="15" height="15" viewBox="0 0 24 24">
                                <path d="M23.707,22.293l-5.969-5.969a10.016,10.016,0,1,0-1.414,1.414l5.969,5.969a1,1,0,0,0,1.414-1.414ZM10,18a8,8,0,1,1,8-8A8.009,8.009,0,0,1,10,18Z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="filter-box">
                        <select name="jenis">
                            <option value="all">Semua Jenis Pelayanan</option>
                            <?php foreach($jenis_layanan as $jl): ?>
                                <option value="<?= $jl->id_jenis_layanan ?>" <?= ($selected_jenis == $jl->id_jenis_layanan) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($jl->jenis_layanan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <?php if (!empty($this->input->get('search')) && empty($aplikasi)): ?>
                    <p class="no-results">Aplikasi tidak ditemukan! Silakan coba kata kunci lain.</p>
                <?php endif; ?>
            </div>

            <div class="apps-grid">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-0 g-sm-3">
                    <?php if (!empty($aplikasi)) : ?>
                        <?php foreach($aplikasi as $app): ?>
                            <div class="col">
                                <div class="app-card h-100" data-url="http://<?= htmlspecialchars($app->nama_domain) ?>" data-jenis="<?= $app->id_jenis_layanan ?>">
                                    <div class="launch-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M19 19H5V5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                                        </svg>
                                    </div>
                                    <button class="hover-trigger" type="button"></button>
                                    <?php if (!empty($app->file_logo) && file_exists(FCPATH . $app->file_logo)): ?>
                                        <img class="app-logo" src="<?= base_url($app->file_logo) ?>" alt="<?= htmlspecialchars($app->nama_aplikasi) ?>">
                                        <?php else: ?>
                                            <img class="app-logo" src="<?= base_url('resources/config_site/logo-tpi.png') ?>" alt="Default Logo">
                                            <?php endif; ?>
                                            <h3 class="app-title"><?= htmlspecialchars($app->nama_aplikasi) ?></h3>
                                            <div class="hover-content">
                                                <div class="badge badge-overlay" data-jenis="<?= $app->id_jenis_layanan ?>">
                                                    <?= htmlspecialchars_decode($app->jenis_layanan) ?>
                                                </div>
                                                <p class="app-description-full"><?= htmlspecialchars($app->deskripsi_aplikasi) ?></p>
                                            </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="scroll-btn" id="scrollDown">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
    </svg>
</div>
<div class="scroll-btn scroll-top-btn" id="scrollUp">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
    </svg>
</div>

<footer class="footer">
    <div class="container">
        <p>© 2025 Kominfo Kota Tanjungpinang</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.app-card').forEach(card => {
        let isTouchDevice = 'ontouchstart' in window;
        
        // Desktop 
        if(!isTouchDevice) {
            card.addEventListener('mouseenter', () => {
                card.classList.add('hovered');
            });
            
            card.addEventListener('mouseleave', () => {
                card.classList.remove('hovered');
            });
        }
        
        // Mobile 
        if(isTouchDevice) {
            let tapTimer;
            
            card.addEventListener('touchstart', (e) => {
                e.preventDefault();
                card.classList.add('tapped');
            });
            
            card.addEventListener('touchend', (e) => {
                e.preventDefault();
                tapTimer = setTimeout(() => {
                    card.classList.remove('tapped');
                }, 1000);
            });
            
            card.addEventListener('click', (e) => {
                clearTimeout(tapTimer);
                const url = card.dataset.url;
                if(url) window.open(url, '_blank');
            });
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    document.querySelectorAll('.hover-trigger').forEach(trigger => {
        const card = trigger.closest('.app-card');
        
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            card.classList.toggle('active');
        });
        
        // Close ketika klik di luar
        document.addEventListener('click', (e) => {
            if(!card.contains(e.target)) {
                card.classList.remove('active');
            }
        });
        
        card.addEventListener('click', (e) => {
            if(!e.target.closest('.hover-trigger')) {
                const url = card.dataset.url;
                if(url) window.open(url, '_blank');
            }
        });
    });

    const scrollDown = document.getElementById('scrollDown');
    const scrollUp = document.getElementById('scrollUp');

    window.addEventListener('scroll', () => {
      const appsGrid = document.querySelector('.apps-grid');
      const rect = appsGrid.getBoundingClientRect();

      if (rect.top <= window.innerHeight && rect.bottom >= 0) {
        scrollDown.style.display = 'flex';
        scrollUp.style.display = (window.scrollY > window.innerHeight) ? 'flex' : 'none';
      } else {
        scrollDown.style.display = 'none';
        scrollUp.style.display = 'none';
      }
    });

    let lastScrollTop = 0;
    const header = document.getElementById("header");

    window.addEventListener("scroll", function () {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            header.style.top = "-100px"; 
        } else {
            header.style.top = "0";
        }
        lastScrollTop = scrollTop;
    });

    scrollDown.addEventListener('click', () => {
      window.scrollBy({
        top: window.innerHeight,
        behavior: 'smooth'
      });
    });

    scrollUp.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
</script>
</body>
</html>