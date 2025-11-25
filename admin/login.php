<?php
session_start();
require_once 'db_config.php'; // 引入数据库配置

// 安全措施1：CSRF令牌生成

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// 安全措施2：防暴力破解（限制失败次数）
$max_attempts = 5;      // 最大失败次数
$lockout_time = 300;    // 锁定时间（5分钟）
$user_ip = $_SERVER['REMOTE_ADDR']; // 获取用户IP
$attempts_key = "login_attempts_$user_ip";

// 初始化失败计数器
if (!isset($_SESSION[$attempts_key])) {
    $_SESSION[$attempts_key] = ['count' => 0, 'locked_until' => 0];
}
$attempts = &$_SESSION[$attempts_key];

// 初始化错误信息
$identifier_err = $password_err = $login_err = "";
// $identifier 用于存储用户输入的用户名或邮箱
$identifier = $password = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 检查是否被锁定
    if (time() < $attempts['locked_until']) {
        $login_err = "登录失败次数过多，请" . ($attempts['locked_until'] - time()) . "秒后再试";
    } else {
        // 验证CSRF令牌
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $login_err = "请求异常，请刷新页面重试";
        } else {
            // 1. 用户名/邮箱验证 (我们将输入字段名称保留为 'username'，但在逻辑上视为 'identifier')
            if (empty(trim($_POST["username"]))) {
                $identifier_err = "请输入用户名或邮箱";
            } else {
                $identifier = trim($_POST["username"]);
            }

            // 2. 密码验证
            if (empty(trim($_POST["password"]))) {
                $password_err = "请输入密码";
            } else {
                $password = trim($_POST["password"]);
            }

            // 验证通过后查询数据库
            if (empty($identifier_err) && empty($password_err)) {
                $identifier_safe = htmlspecialchars($identifier);
                
                // 新的查询：同时检查 username 或 email 是否匹配 $identifier
                $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
                $stmt = $conn->prepare($sql);
                // 将相同的 $identifier 绑定两次
                $stmt->bind_param("ss", $identifier_safe, $identifier_safe);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    // 验证密码（与加密存储的哈希值比对）
                    if (password_verify($password, $row["password"])) {
                        // 登录成功：重置失败计数器，加固会话
                        $attempts['count'] = 0;
                        $attempts['locked_until'] = 0;

                        // 安全措施3：会话加固（防会话劫持/固定）
                        session_regenerate_id(true); // 重新生成session ID
                        $_SESSION["user_id"] = $row["id"];
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["login_time"] = time(); // 记录登录时间

                        // 设置会话Cookie安全属性
                        ini_set('session.cookie_httponly', 1); // 禁止JS访问
                        ini_set('session.cookie_samesite', 'Lax'); // 限制跨站请求

                        // 跳转到首页
                        header("location: dashboard.php");
                        exit;
                    } else {
                        // 密码错误：累加失败次数
                        $attempts['count']++;
                        if ($attempts['count'] >= $max_attempts) {
                            $attempts['locked_until'] = time() + $lockout_time; // 锁定
                        }
                        $login_err = "用户名/邮箱或密码错误（剩余" . ($max_attempts - $attempts['count']) . "次机会）";
                    }
                } else {
                    // 用户名/邮箱不存在：同样累加失败次数（防枚举用户名）
                    $attempts['count']++;
                    if ($attempts['count'] >= $max_attempts) {
                        $attempts['locked_until'] = time() + $lockout_time;
                    }
                    $login_err = "用户名/邮箱或密码错误（剩余" . ($max_attempts - $attempts['count']) . "次机会）";
                }
                $stmt->close();
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安全登录</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        body { 
            background: linear-gradient(135deg, #4285f4, #673ab7); 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column;
            align-items: center; 
            padding: 20px;
            overflow: hidden; 
            position: relative;
            font-family: 'Microsoft Yahei', sans-serif;
        }
        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0; left: 0;
            overflow: hidden;
            z-index: 0;
        }
        .background li {
            position: absolute;
            list-style: none;
            background: rgba(255, 255, 255, 0.15);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 8px;
        }
        .background li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .background li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .background li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .background li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .background li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .background li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .background li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .background li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .background li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .background li:nth-child(10){ left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        /* 返回首页按钮 - 固定在最上方 */
        .back-to-home {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 32px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            font-weight: bold;
            color: #4285f4;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-to-home:hover {
            transform: translateX(-50%) translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
            background: white;
        }
        .back-to-home i {
            font-size: 20px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!-- 动态背景 -->
    <div class="background">
        <ul>
            <li></li><li></li><li></li><li></li><li></li>
            <li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>

    <!-- 返回首页按钮（新增） -->
    <a href="/index.html" class="back-to-home">
        <i class="fa fa-home"></i>
        返回首页
    </a>

    <!-- 登录表单容器（保持原样） -->
    <div class="container bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-2xl w-full max-w-md mt-24">
        <h2 class="text-3xl font-bold text-center mb-8 text-indigo-900">用户登录</h2>
        
        <?php if (isset($_SESSION['register_success'])): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 text-sm">
                <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($login_err)): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 text-sm">
                <?php echo $login_err; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">用户名或邮箱</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($identifier); ?>" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       placeholder="请输入用户名或邮箱" required>
                <span class="text-red-600 text-sm"><?php echo $identifier_err; ?></span>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 mb-2">密码</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       placeholder="请输入密码" required>
                <span class="text-red-600 text-sm"><?php echo $password_err; ?></span>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-4 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition shadow-lg">
                立即登录
            </button>

            <div class="text-center mt-6 text-gray-600">
                没有账号？<a href="register.php" class="text-blue-600 hover:underline font-semibold">立即注册</a>
            </div>
        </form>
    </div>
</body>
</html>