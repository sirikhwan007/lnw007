<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /factory_monitoring/login.php");
    exit();
}

$profileImage = $_SESSION['profile_image'] ?? 'default_profile.png';
$username     = $_SESSION['username'] ?? 'Manager';
$role         = $_SESSION['role'] ?? 'Manager';

// ป้องกัน session เก่าที่ไม่มี upload_path
$uploadPath = $_SESSION['upload_path'] ?? '/factory_monitoring/manager/uploads/';
?>

<div class="sidebar">
    <div class="sidebar-top">

        <a href="/factory_monitoring/manager/profile.php" class="profile-btn">
            <div class="sb-logo">

                <img src="<?php echo $uploadPath . htmlspecialchars($profileImage); ?>"
                     class="profile-img"
                     onerror="this.src='/factory_monitoring/assets/img/default_profile.png'">

                <div class="profile-info">
                    <span class="profile-name"><?php echo htmlspecialchars($username); ?></span>
                    <span class="profile-role"><?php echo htmlspecialchars($role); ?></span>
                </div>
            </div>
        </a>

        <ul class="sb-ul">

            <li>
                <a href="/factory_monitoring/Manager/dashboard.php">
                    <i class="fas fa-chart-line fontawesome"></i>
                    <span class="sb-text">หน้าหลัก</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/Manager/machines_status.php">
                    <i class="fas fa-industry fontawesome"></i>
                    <span class="sb-text">สถานะเครื่องจักร</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/Manager/downtime.php">
                    <i class="fas fa-stopwatch fontawesome"></i>
                    <span class="sb-text">Downtime / OEE</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/Manager/history_manager.php">
                    <i class="fas fa-screwdriver-wrench fontawesome"></i>
                    <span class="sb-text">ประวัติการแจ้งซ่อม</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/manager/pm_schedule.php">
                    <i class="fas fa-calendar-check fontawesome"></i>
                    <span class="sb-text">ตาราง PM</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/manager/workforce.php">
                    <i class="fas fa-user-clock fontawesome"></i>
                    <span class="sb-text">พนักงานหน้างาน</span>
                </a>
            </li>

            <li>
                <a href="/factory_monitoring/manager/reports.php">
                    <i class="fas fa-file-lines fontawesome"></i>
                    <span class="sb-text">รายงาน & วิเคราะห์</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="sidebar-bottom">
        <a href="/factory_monitoring/logout.php" class="btn btn-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span class="sb-text">ออกจากระบบ</span>
        </a>
    </div>
</div>
