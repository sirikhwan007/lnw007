<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Operator') {
    header("Location: /factory_monitoring/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="/factory_monitoring/Operator/assets/css/dashboard.css">

    <style>
        /* Sidebar Base */
        .sidebar-operator {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1f242d;
            color: #ffffff;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.1);
            transition: 0.3s;
        }

        /* Profile & Menu */
        .op-top { width: 100%; padding: 0 15px; }
        .op-profile-btn { display: block; text-decoration: none; color: inherit; margin-bottom: 20px; }
        .op-logo { display: flex; align-items: center; gap: 12px; }
        .op-profile-img { width: 55px; height: 55px; border-radius: 8px; object-fit: cover; }
        .op-profile-info .op-profile-name { font-size: 16px; font-weight: bold; }
        .op-profile-info .op-profile-role { font-size: 13px; opacity: 0.7; }

        .op-ul { list-style: none; padding: 0; margin-top: 10px; }
        .op-ul li a {
            display: flex; align-items: center; gap: 12px; padding: 12px 15px;
            border-radius: 10px; color: #dcdcdc; text-decoration: none; transition: 0.25s;
        }
        .op-ul li a:hover, .op-ul li a.active-link { background: #8e44ad; color: #fff; }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: 0.3s;
            min-height: 100vh;
        }

        /* Hamburger Button */
        .btn-hamburger {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            font-size: 1.5rem;
            cursor: pointer;
            background: #1f242d;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar-operator { left: -260px; }
            .sidebar-operator.active { left: 0; }
            .main-content { margin-left: 0; }
            .btn-hamburger { display: block; }
        }

        .btn-logout {
            background: #c0392b; color: #fff; padding: 12px;
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; border-radius: 10px; justify-content: center;
            margin: 15px;
        }
    </style>
</head>

<body>

    <div class="btn-hamburger"><i class="fa-solid fa-bars"></i></div>

    <?php include __DIR__ . '/SidebarOperator.php'; ?>

    <main class="main-content">
        <div class="container-fluid">
            <div class="dashboard-header mb-4">
                <h2 class="dashboard-title">แดชบอร์ด Operator</h2>
                <p class="text-muted">พื้นที่สำหรับติดตามสถานะเครื่องจักร แจ้งปัญหา และดูคำขอที่คุณส่งไว้</p>
            </div>
            
            <div class="row">
                </div>
        </div>
        
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.querySelector(".sidebar-operator");
            const btnHamburger = document.querySelector(".btn-hamburger");
            // ควยยยยยยยยยย
            if (btnHamburger) {
                btnHamburger.addEventListener("click", () => {
                    sidebar.classList.toggle("active");
                });
            }
            // Auto-active Menu
            const currentUrl = window.location.href;
            const links = document.querySelectorAll(".op-ul a");
            links.forEach(a => {
                if (a.href === currentUrl) {
                    a.classList.add("active-link");
                }
            });
        });
    </script>
    <script src="SidebarOperator.js"></script>
</body>
</html>