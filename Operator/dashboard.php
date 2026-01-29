<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /factory_monitoring/login.php");
    exit();
}

// ตรวจสิทธิ์ Operator
if ($_SESSION['role'] !== 'Operator') {
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
    <link rel="stylesheet" href="/factory_monitoring/Operator/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
</head>

<body>
    <script>
// ปุ่มเปิด sidebar สำหรับ mobile (ถ้าเพื่อนเพิ่มปุ่มใน header)
document.addEventListener("DOMContentLoaded", () => {

    const sidebar = document.querySelector(".sidebar-operator");
    const btnHamburger = document.querySelector(".btn-hamburger");

    if (btnHamburger) {
        btnHamburger.addEventListener("click", () => {
            sidebar.classList.toggle("active");
        });
    }

    // ระบบ active menu อัตโนมัติตาม URL
    const links = document.querySelectorAll(".op-ul a");
    links.forEach(a => {
        if (a.href === window.location.href) {
            a.style.background = "#8e44ad";
            a.style.color = "#fff";
            a.style.fontWeight = "bold";
        }
    });

});
</script>


    <!-- ปุ่มแฮมเบอร์เกอร์ -->
    <div class="btn-hamburger"><i class="fa-solid fa-bars"></i></div>

    <section class="main">

        <!-- เรียก Sidebar ของ Operator เท่านั้น -->
        <?php include __DIR__ . '/SidebarOperator.php'; ?>

        <div class="dashboard">
            <h2 class="dashboard-title">แดชบอร์ด Operator</h2>
            <p>พื้นที่สำหรับติดตามสถานะเครื่องจักร แจ้งปัญหา และดูคำขอที่คุณส่งไว้</p>
        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- เรียก JS ของ Operator -->
    <script src="SidebarOperator.js"></script>

</body>

</html>
<style>
/* ===============================
   Sidebar Operator แบบชิดซ้าย 100%
   =============================== */

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
}

/* Top section */
.op-top {
    width: 100%;
    padding: 0 15px;
}

/* Profile */
.op-profile-btn {
    display: block;
    text-decoration: none;
    color: inherit;
    margin-bottom: 20px;
}

.op-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.op-profile-img {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    object-fit: cover;
}

.op-profile-info .op-profile-name {
    font-size: 16px;
    font-weight: bold;
}

.op-profile-info .op-profile-role {
    font-size: 13px;
    opacity: 0.7;
}

/* Menu */
.op-ul {
    list-style: none;
    width: 100%;
    padding-left: 0;
    margin-top: 10px;
}

.op-ul li {
    margin-bottom: 5px;
}

.op-ul li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 10px;
    color: #dcdcdc;
    text-decoration: none;
    transition: 0.25s ease;
}

.op-ul li a:hover {
    background: #8e44ad;
    color: #fff;
}

/* Icons */
.fontawesome {
    width: 22px;
    text-align: center;
    font-size: 1.2rem;
}

/* Logout */
.sidebar-bottom {
    padding: 15px;
}

.btn-logout {
    background: #c0392b;
    color: #fff;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    border-radius: 10px;
    justify-content: center;
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar-operator {
        left: -260px;
        transition: 0.3s;
    }

    .sidebar-operator.active {
        left: 0;
    }
}




/* ===== กันพื้นที่ให้เนื้อหา ===== */
.dashboard {
    margin-left: 260px;
    padding: 30px;
    transition: 0.3s;
}

/* ===== มือถือให้ sidebar ซ่อน และ content ชิดซ้าย ===== */
@media (max-width: 992px) {
    .dashboard {
        margin-left: 0;
    }
}



/* ให้เนื้อหาหลักหลบ Sidebar */
.main {
    margin-left: 260px;
    padding: 20px;
}

/* Dashboard ขยับให้ไม่ชน Sidebar */
.dashboard {
    margin-left: 0;
    padding: 20px;
}

/* Responsive สำหรับมือถือ */
@media (max-width: 992px) {
    .main {
        margin-left: 0;
    }
}


</style>
