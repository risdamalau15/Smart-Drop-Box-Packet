<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Dropbox System</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="<?php echo isset($_GET['page']) && $_GET['page'] == 'Admin' ? 'login-page' : ''; ?>">
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">Smart DropBox</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="index.php?page=dashboard" class="sidebar-link">
                        <i class="lni lni-users"></i>
                        <span>Daftar Resi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="index.php?page=status" class="sidebar-link">
                        <i class="lni lni-search"></i>
                        <span>Status</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="index.php?page=tracking" class="sidebar-link">
                        <i class="lni lni-travel"></i>  
                        <span>Tracking Paket</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="index.php?page=CCTV" class="sidebar-link">
                        <i class="lni lni-zoom"></i>  
                        <span>CCTV</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-enter"></i>   
                    <span>Admin</span>
                </a>
            </div>
        </aside>
        <div class="main p-3">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            if ($page == 'dashboard') {
                include('dashboard.php');
            } elseif ($page == 'status') {
                include('status.php');
            } elseif ($page == 'tracking') {
                include('tracking.php');
            } elseif ($page == 'CCTV') {
                echo '<iframe src="http://192.168.189.2/" style="width: 100%; height: 100vh; border: none;"></iframe>';
            } elseif ($page == 'Admin') {
                    header('Location');
            } else {
                echo "<h2>Page not found</h2>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="../js/script.js"></script>
</body>
</html>
