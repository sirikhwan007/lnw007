<?php
session_start();
require_once "../config.php";

/* ============================================================
   1. Auth Guard: ตรวจสอบสิทธิ์การเข้าใช้งาน
   ============================================================ */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: /factory_monitoring/login.php");
    exit();
}

$username = $_SESSION['username'];

/* ============================================================
   2. Data Fetching: ดึงข้อมูลสรุปงาน
   ============================================================ */
$stats = ['pending' => 0, 'completed' => 0];

// ฟังก์ชันช่วยนับจำนวนงานตามสถานะ
function countJobs($conn, $username, $status)
{
    $sql = "SELECT COUNT(*) AS count FROM repair_history WHERE username = ? AND status = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $username, $status);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res['count'] ?? 0;
    }
    return 0;
}

$stats['pending']   = countJobs($conn, $username, 'รอดำเนินการ');
$stats['completed'] = countJobs($conn, $username, 'สำเร็จ');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard | Factory Monitoring</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/factory_monitoring/Technician/assets/css/sidebar_technician.css">
    <link rel="stylesheet" href="/factory_monitoring/Technician/assets/css/dashboard_technician.css">
    <script src="/factory_monitoring/Technician/assets/js/sidebar_technician.js" defer></script>
</head>

<body>

    <?php include "SidebarTechnician.php"; ?>

    <main class="dashboard-container">
        <header class="dashboard-header">
            <h1 class="dashboard-title">Dashboard Technician</h1>
            <p class="welcome-text">
                <i class="fa-regular fa-circle-user"></i>
                สวัสดีคุณ <strong><?= htmlspecialchars($username) ?></strong> วันนี้มีงานอะไรรออยู่บ้าง?
            </p>
        </header>

        <div class="card-grid">

            <a href="work_orders.php" class="card-link">
                <div class="stat-card">
                    <div class="icon-box bg-pending">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <div class="stat-info">
                        <h3>งานซ่อมที่ได้รับ</h3>
                        <p class="job-count" style="color: var(--primary-color);">
                            <?= number_format($stats['pending']) ?> <small>งาน</small>
                        </p>
                    </div>
                </div>
            </a>

            <a href="history_technician.php" class="card-link">
                <div class="stat-card">
                    <div class="icon-box bg-success">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>งานที่เสร็จแล้ว</h3>
                        <p class="job-count" style="color: var(--success-color);">
                            <?= number_format($stats['completed']) ?> <small>งาน</small>
                        </p>
                    </div>
                </div>
            </a>

            <a href="profile.php" class="card-link">
                <div class="stat-card">
                    <div class="icon-box bg-profile">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div class="stat-info">
                        <h3>โปรไฟล์</h3>
                        <p class="job-count" style="color: #6c757d; font-size: 1rem;">จัดการข้อมูลส่วนตัว</p>
                    </div>
                </div>
            </a>

        </div>
    </main>

</body>

</html>