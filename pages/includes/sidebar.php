<?php

/**
 * Main Sidebar
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
function isActive($data)
{
    $array = explode('/', $_SERVER['REQUEST_URI']);
    $key = array_search("pages", $array);
    $name = $array[$key + 1];
    return $name === $data ? 'active' : '';
}
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars fa-2x"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto ">
        <li class="nav-item d-md-none d-block">
            <a href="../dashboard/">
                <img src="../../assets/images/AdminLogo.png"
                    alt="Admin Logo"
                    width="50px"
                    class="img-circle elevation-3">
                <span class="font-weight-light pl-1">ENC System</span>
            </a>
        </li>
        <li class="nav-item d-md-block d-none">
            <a class="nav-link">เข้าสู่ระบบครั้งล่าสุด: <?php echo $_SESSION['AD_LOGIN'] ?> </a>
        </li>
    </ul>
</nav>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../dashboard/" class="brand-link">
        <img src="../../assets/images/AdminLogo.png"
            alt="Admin Logo"
            class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">ENC System</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../assets/images/avatar5.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="../manager/" class="d-block">
                    <?php
                    $fname = $_SESSION['name'] ?? 'ไม่มีชื่อ';
                    echo $fname;
                    ?>
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../dashboard/" class="nav-link <?php echo isActive('dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../timetracking/" class="nav-link <?php echo isActive('products') ?>">
                        <i class="nav-icon fas fa fa-clock"></i>
                        <p>Time Tracking</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../prodorderlines/" class="nav-link <?php echo isActive('members') ?>">
                        <i class="nav-icon fas fa fa-search"></i>
                        <p>Project Code</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../nghistorys/" class="nav-link <?php echo isActive('nghistorys') ?>">
                        <i class="nav-icon fas fa fa-list-alt"></i>
                        <p>P-Code / T-Code</p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="../orders/" class="nav-link <?php echo isActive('orders') ?>">
                        <i class="nav-icon fas fa-cart-arrow-down"></i>
                        <p>รายการสั่งซื้อ</p>
                    </a>
                </li> -->
                <li class="nav-header">My account</li>
                <li class="nav-item">
                    <a href="../logout.php" id="logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sign out</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>