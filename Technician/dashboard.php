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
function countJobs($conn, $username, $status) {
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

    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --bg-light: #f8f9fa;
        }

        body { background-color: var(--bg-light); font-family: 'Sarabun', sans-serif; }

        .dashboard-container {
            margin-left: 260px; /* เว้นที่ให้ Sidebar */
            padding: 40px 20px;
            transition: all 0.3s ease;
        }

        .dashboard-header { margin-bottom: 30px; }
        .dashboard-title { font-size: 1.8rem; font-weight: 700; color: #333; }
        .welcome-text { color: #666; font-size: 1.1rem; }

        /* Card Grid System */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .card-link { text-decoration: none; color: inherit; }

        .stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: 0.3s transform ease, 0.3s box-shadow ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .bg-pending { background: rgba(13, 110, 253, 0.1); color: var(--primary-color); }
        .bg-success { background: rgba(25, 135, 84, 0.1); color: var(--success-color); }
        .bg-profile { background: rgba(108, 117, 125, 0.1); color: #6c757d; }

        .stat-info h3 { font-size: 1.1rem; margin: 0; color: #555; }
        .stat-info .job-count { font-size: 1.6rem; font-weight: 800; margin: 5px 0 0; }

        @media (max-width: 992px) {
            .dashboard-container { margin-left: 0; padding-top: 80px; }
        }
    </style>

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