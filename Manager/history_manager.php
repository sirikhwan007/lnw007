<?php
// ================== SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================== DB ==================
include __DIR__ . '/../config.php';

// ================== QUERY ==================
$result = $conn->query("SELECT * FROM repair_history ORDER BY report_time DESC");

// ================== USER ==================
$username = $_SESSION['username'] ?? 'ผู้ใช้งาน';
$role = $_SESSION['role'] ?? 'ไม่ทราบสิทธิ์';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติการแจ้งซ่อม</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/factory_monitoring/manager/assets/css/Sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: #f8fafd;
            margin: 0;
            overflow-x: hidden;
        }

        /* ===== Layout ===== */
        .main {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-wrapper {
            width: 250px;
            min-width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: #fff;
            box-shadow: 2px 0 8px rgba(0, 0, 0, .05);
            z-index: 1000;
        }

        .repair-history-container {
            margin-left: 250px;
            /* ⭐ แก้ sidebar บัง */
            width: calc(100% - 250px);
            padding: 30px;
        }

        /* ===== Title ===== */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ===== Table ===== */
        .table-wrapper {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
            overflow-x: auto;
        }

        .repair-table {
            width: 100%;
            min-width: 1200px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .repair-table thead th {
            background: #eef3f9;
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .repair-table tbody td {
            background: #fff;
            padding: 15px;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            vertical-align: middle;
        }

        .detail-cell {
            max-width: 250px;
            font-size: 13px;
            color: #555;
        }

        /* ===== Status Badge ===== */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .success {
            background: #e6ffed;
            color: #28a745;
            border: 1px solid #28a745;
        }

        .pending {
            background: #fff8e1;
            color: #ffc107;
            border: 1px solid #ffc107;
        }

        .in-progress {
            background: #e0f2f7;
            color: #17a2b8;
            border: 1px solid #17a2b8;
        }

        .failed {
            background: #ffe6e6;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        /* ===== Responsive ===== */
        @media (max-width: 992px) {
            .repair-history-container {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <section class="main">

        <!-- ===== Sidebar ===== -->
        <div class="sidebar-wrapper">
            <?php include __DIR__ . '/partials/SidebarManager.php'; ?>
        </div>

        <!-- ===== Content ===== -->
        <div class="repair-history-container">

            <h2 class="page-title">
                <i class="fas fa-history"></i> ประวัติการแจ้งซ่อม
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
                            <th class="text-center"><i class="fas fa-search"></i> ตรวจสอบเครื่องจักร</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;
                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):

                                $statusClass = match ($row['status']) {
                                    'สำเร็จ' => 'success',
                                    'กำลังซ่อม' => 'in-progress',
                                    'รอดำเนินการ' => 'pending',
                                    'ซ่อมไม่สำเร็จ' => 'failed',
                                    default => 'pending'
                                };

                                $reportTime = date('d/m/Y H:i', strtotime($row['report_time']));
                                $repairTime = (!empty($row['repair_time']) && $row['repair_time'] !== '0000-00-00 00:00:00')
                                    ? date('d/m/Y H:i', strtotime($row['repair_time']))
                                    : '-';
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['machine_id']) ?></td>
                                    <td><?= htmlspecialchars($row['reporter']) ?></td>
                                    <td><?= htmlspecialchars($row['position']) ?></td>
                                    <td><?= htmlspecialchars($row['type']) ?></td>
                                    <td class="detail-cell"><?= htmlspecialchars($row['detail'] ?? '-') ?></td>
                                    <td class="text-center"><?= $reportTime ?></td>
                                    <td class="text-center"><?= $repairTime ?></td>
                                    <td class="text-center">
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit_repairmanager.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-search"></i> ตรวจสอบ
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">ไม่มีข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>