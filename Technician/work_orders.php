<?php
session_start();
include __DIR__ . "/../config.php";

/* ===============================
   1. ตรวจสอบ Login + Role
================================ */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: ../login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'ช่างเทคนิค';
$profileImage = $_SESSION['profile_image'] ?? 'default_profile.png';

/* ===============================
   2. ดึงงานซ่อม
================================ */
$sql = "SELECT * FROM repair_history
        WHERE technician_id IS NULL OR technician_id = ?
        ORDER BY 
            CASE WHEN status = 'กำลังซ่อม' THEN 0 ELSE 1 END,
            report_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>งานซ่อมที่มี</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/factory_monitoring/Technician/assets/css/sidebar_technician.css">
    <link rel="stylesheet" href="/factory_monitoring/Technician/assets/css/work_orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <button class="btn btn-light btn-hamburger" onclick="document.querySelector('.sidebar').classList.toggle('active')">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <?php include __DIR__ . '/SidebarTechnician.php'; ?>

    <!-- Content -->
    <div class="repair-history-container">
        <h2 class="page-title">
            <i class="fas fa-tools text-primary"></i> งานซ่อมที่มี
        </h2>

        <div class="table-wrapper">
            <table class="repair-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> </th>
                        <th><i class="fas fa-microchip"></i> ID เครื่องจักร</th>
                        <th><i class="fas fa-user"></i> ชื่อผู้แจ้ง</th>
                        <th><i class="fas fa-location-dot"></i> ตำแหน่ง</th>
                        <th><i class="fas fa-tags"></i> ประเภท</th>
                        <th><i class="fas fa-file-lines"></i> รายละเอียด</th>
                        <th class="text-center"><i class="fas fa-calendar-plus"></i> วันที่แจ้ง</th>
                        <th class="text-center"><i class="fas fa-calendar-check"></i> วันที่ซ่อมเสร็จ</th>
                        <th class="text-center"><i class="fas fa-circle-info"></i> สถานะ</th>
                        <th class="text-center"><i class="fas fa-check-circle"></i> รับงาน</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $result->fetch_assoc()):
                        $statusClass = match ($row['status']) {
                            'สำเร็จ' => 'success',
                            'กำลังซ่อม' => 'in-progress',
                            'ซ่อมไม่สำเร็จ' => 'failed',
                            default => 'pending'
                        };
                        $isAssigned = ($row['technician_id'] == $current_user_id);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['machine_id']) ?></td>
                            <td><?= htmlspecialchars($row['reporter']) ?></td>
                            <td><?= htmlspecialchars($row['position'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['type']) ?></td>
                            <td class="detail-cell"><?= htmlspecialchars($row['detail']) ?></td>
                            <td class="text-center"><?= date('d/m/Y H:i', strtotime($row['report_time'])) ?></td>
                            <td class="text-center">
                                <?= $row['repair_time'] && $row['repair_time'] != '0000-00-00 00:00:00'
                                    ? date('d/m/Y H:i', strtotime($row['repair_time'])) : '-' ?>
                            </td>
                            <td class="text-center">
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!$isAssigned): ?>
                                    <form method="post" action="actions/accept_job.php">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-success btn-sm" onclick="return confirm('ยืนยันรับงาน?')">
                                            ✔ รับงาน
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-success">รับแล้ว</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>