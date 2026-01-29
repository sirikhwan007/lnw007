<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 1. ตรวจสอบแค่ Username อย่างเดียวเพื่อดึงข้อมูลผู้ใช้ (รวมถึงรหัสผ่านที่ถูก Hash ไว้)
    $stmt = $conn->prepare("SELECT user_id, username, password, role, profile_image FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // 2. ใช้ฟังก์ชัน password_verify ตรวจสอบรหัสผ่านที่พิมพ์มา กับค่า Hash ใน DB
        if (password_verify($password, $user['password'])) {

            // ถ้าผ่าน ให้สร้าง session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image'];

            switch ($user['role']) {
                case 'Manager':
                    header("Location: Manager/dashboard.php");
                    break;
                case 'Operator':
                    header("Location: Operator/dashboard.php");
                    break;
                case 'Technician':
                    header("Location: Technician/dashboard.php");
                    break;
                default:
                    // สำหรับ Admin หรือสิทธิ์อื่นๆ ที่ไม่ได้ระบุไว้ข้างบน
                    header("Location: admin/index.php");
                    break;
            }
            exit;

            // ไปหน้า dashboard
            header("Location: admin/index.php");
            exit;
        } else {
            // รหัสผ่านไม่ตรงกับ Hash
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        // ไม่พบ Username นี้ในระบบ
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Kanit, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f7f7f7;
        }

        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #6f1e51;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if ($error) echo '<div class="error">' . $error . '</div>'; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>

</html>