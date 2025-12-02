<?php
session_start();
// 引入数据库配置 (需要一个 $conn 对象)
require_once 'db_config.php'; 

// --- 辅助函数 ---

/**
 * 生成6位数字验证码
 */
function generateVerificationCode(): string {
    return strval(random_int(100000, 999999));
}

/**
 * 尝试发送真实的邮箱验证码。
 * 依赖于您的PHP环境是否配置了SMTP服务器（如sendmail）。
 * @return bool 尝试发送是否成功 (不代表邮件一定能被接收)
 */
function sendVerificationEmail(string $to_email, string $code): bool {
    // 1. 设置邮件内容
    $subject = "您的注册验证码 - " . date("Y-m-d H:i:s");
    $message = "您好，\n\n您的注册验证码是：{$code}。\n\n请在5分钟内使用此验证码完成注册。\n\n如果您没有请求此验证码，请忽略此邮件。\n\n[系统自动发送，请勿回复]";
    
    // 设置邮件头
    $headers = "From: webmaster@yourdomain.com\r\n"; // <<< ⚠️ 请替换为您的真实发件邮箱
    $headers .= "Reply-To: webmaster@yourdomain.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // 2. 调用 PHP 内置的 mail() 函数
    // WARNING: 依赖于服务器配置，可能会失败！
    $mail_sent = mail($to_email, $subject, $message, $headers);

    if ($mail_sent) {
        // 如果 mail() 函数执行成功，则存储验证码
        $_SESSION['verification_code'] = $code;
        $_SESSION['code_expiry'] = time() + (5 * 60); // 5分钟有效期

        error_log("【邮件发送尝试成功】目标: $to_email, 验证码: $code (5分钟内有效)。请检查收件箱。");
        return true;
    } else {
        error_log("【邮件发送失败】PHP mail() 函数调用失败。请检查服务器SMTP配置。");
        return false;
    }
}


// --- 初始化和状态管理 ---

// CSRF令牌生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 设定注册步骤：1 (初始状态/待获取验证码), 2 (验证码已发送/待验证)
if (!isset($_SESSION['reg_step']) || $_SERVER["REQUEST_METHOD"] == "GET") {
    $_SESSION['reg_step'] = 1;
    // 检查重置请求
    if (isset($_GET['reset']) || isset($_POST['reset_state'])) {
        unset($_SESSION['reg_step']);
        unset($_SESSION['reg_data']);
        unset($_SESSION['verification_code']);
        unset($_SESSION['code_expiry']);
        // 确保重定向以清除 URL 参数或 POST 数据
        header("location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit();
    }
}

// 初始化变量
$username_err = $email_err = $password_err = $confirm_password_err = $code_err = $reg_err = "";
$username = $email = $password = $confirm_password = $verification_code = "";
$is_step_2 = ($_SESSION['reg_step'] == 2); // 标记当前是否处于验证码等待状态

// 如果在步骤2，从Session加载暂存数据（用于只读显示）
if ($is_step_2 && isset($_SESSION['reg_data'])) {
    $username = $_SESSION['reg_data']['username'] ?? '';
    $email = $_SESSION['reg_data']['email'] ?? '';
    // P/CP 不从 Session 加载，因为它们只在 Step 1 提交时使用
}


// --- POST 请求处理 ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 安全措施：验证CSRF令牌
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $reg_err = "请求异常，请刷新页面重试";
        $_SESSION['reg_step'] = 1; // 令牌失败，重置到第一步
        // 重新加载页面以显示错误
    } else {

        // 获取 POST 提交的全部字段
        $username = trim($_POST["username"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $password = trim($_POST["password"] ?? '');
        $confirm_password = trim($_POST["confirm_password"] ?? '');
        $verification_code = trim($_POST["verification_code"] ?? '');

        // --- 通用信息验证 (应用于 '发送验证码' 和 '完成注册' 两种情况) ---
        // 1. 用户名验证
        if (empty($username)) {
            $username_err = "请输入用户名";
        } elseif (!preg_match('/^[\p{L}\p{N}_]{5,20}$/u', $username)) {
            $username_err = "用户名仅支持文字、数字、下划线（5-20位）";
        } else {
            $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        }

        // 2. 邮箱验证 (格式+唯一性)
        if (empty($email)) {
            $email_err = "请输入邮箱";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "请输入有效的邮箱（如：user@example.com）";
        } else {
            // 检查邮箱是否已注册 (仅在验证通过后检查)
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) { $email_err = "系统错误：无法检查邮箱唯一性"; }
            else {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $email_err = "该邮箱已被注册";
                }
                $stmt->close();
            }
        }

        // 3. 密码安全 (只在发送验证码时检查，因为 Step 2 时会从 Session 中取)
        if (isset($_POST['send_code']) || $_SESSION['reg_step'] == 1) {
            if (empty($password)) {
                $password_err = "请输入密码";
            } elseif (strlen($password) < 6) {
                $password_err = "密码至少6个字符";
            }
            
            // 4. 确认密码验证
            if (empty($confirm_password)) {
                $confirm_password_err = "请确认密码";
            } elseif ($password !== $confirm_password) {
                $confirm_password_err = "两次密码不一致";
            }
        }


        // --- 动作 1: 发送验证码 (用户点击 '发送验证码' 按钮) ---
        if (isset($_POST['send_code']) && empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
            
            // 准备数据
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $verification_code_new = generateVerificationCode(); // 生成新验证码

            // 暂存注册数据到 Session
            $_SESSION['reg_data'] = [
                'username' => $username,
                'email' => $email,
                'password_hash' => $hashed_password
            ];

            // 尝试发送真实邮件并设置 Session 中的验证码和过期时间
            $mail_success = sendVerificationEmail($email, $verification_code_new);

            if ($mail_success) {
                 // 切换到步骤2状态
                $_SESSION['reg_step'] = 2;
                $is_step_2 = true; // 更新标记
                
                // 清空所有密码错误，因为密码已在 Session 中安全存储
                $password_err = $confirm_password_err = ""; 
                // 重定向到自身以避免表单重复提交，并确保 $is_step_2 标记被刷新
                header("location: " . strtok($_SERVER["REQUEST_URI"], '?'));
                exit();

            } else {
                // 邮件发送失败，停留在 Step 1 并显示错误
                $reg_err = "⚠️ 验证码发送失败！请检查您的邮箱地址是否正确，或联系管理员检查服务器邮件配置。";
                $_SESSION['reg_step'] = 1;
            }
        } 
        
        // --- 动作 2: 完成注册 (用户点击 '完成注册' 按钮) ---
        elseif (isset($_POST['register_final'])) {
            
            if ($_SESSION['reg_step'] != 2) {
                $reg_err = "请先点击 '发送验证码' 按钮获取验证码。";
            } else {
                // 此时 U/E/P/CP 验证已在发送验证码时完成，数据在 Session 中
                $reg_data = $_SESSION['reg_data'] ?? null;
                
                if (!$reg_data) {
                    $reg_err = "会话数据丢失，请重新开始注册";
                    $_SESSION['reg_step'] = 1;
                } elseif (empty($verification_code)) {
                    $code_err = "请输入验证码";
                } elseif ($verification_code !== ($_SESSION['verification_code'] ?? '')) {
                    $code_err = "验证码错误";
                } elseif (time() > ($_SESSION['code_expiry'] ?? 0)) {
                    $code_err = "验证码已过期，请重新发送";
                } else {
                    // 验证码通过，执行数据库插入
                    $username_final = $reg_data['username'];
                    $email_final = $reg_data['email'];
                    $hashed_password_final = $reg_data['password_hash'];

                    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if (!$stmt) {
                        $reg_err = "注册失败：数据库语句准备失败。";
                    } else {
                        $stmt->bind_param("sss", $username_final, $email_final, $hashed_password_final);

                        if ($stmt->execute()) {
                            // 注册成功，清理Session并跳转
                            unset($_SESSION['csrf_token']);
                            unset($_SESSION['reg_step']);
                            unset($_SESSION['reg_data']);
                            unset($_SESSION['verification_code']);
                            unset($_SESSION['code_expiry']);
                            
                            $_SESSION['register_success'] = "注册成功！请登录";
                            header("location: login.php"); // 假设您有 login.php
                            exit;
                        } else {
                            $reg_err = "注册失败，请稍后再试";
                        }
                        $stmt->close();
                    }
                }
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
    <title>安全注册（邮箱验证）</title>
    <style>
        /* 基础样式 */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f5f7fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .title { text-align: center; margin-bottom: 25px; color: #1e3a8a; font-weight: 700; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 6px; color: #4b5563; font-size: 14px; font-weight: 500; }
        input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; transition: all 0.2s; }
        input[readonly] { background-color: #f3f4f6; cursor: default; }
        input:focus { outline: none; border-color: #4285f4; box-shadow: 0 0 0 3px rgba(66,133,244,0.3); }
        .error { color: #dc2626; font-size: 13px; margin-top: 5px; display: block; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; border: 1px solid; }
        .alert-error { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .alert-info { background: #e0f2fe; color: #075985; border-color: #7dd3fc; }
        .btn-primary { width: 100%; padding: 12px; background: #4285f4; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; transition: background 0.3s, opacity 0.3s; font-weight: 600; }
        .btn-primary:hover:not(:disabled) { background: #3367d6; }
        .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; opacity: 0.7; }
        
        /* 验证码行对齐修正 */
        .code-row { 
            display: flex; 
            align-items: center; /* 垂直居中对齐 */
            gap: 8px; 
        }
        .code-row input { /* 确保输入框占据剩余空间并继承样式 */
             width: auto; 
             flex-grow: 1;
             margin-top: 0; /* 消除输入框默认的上边距 */
        }
        .code-btn { 
            flex-shrink: 0;
            padding: 12px 15px; /* 保持与 input 相同的垂直 padding */
            background: #10b981; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 15px; 
            cursor: pointer; 
            transition: background 0.3s, opacity 0.3s; 
        }
        .code-btn:hover:not(:disabled) { background: #059669; }
        .code-btn:disabled { background: #9ca3af; cursor: not-allowed; }
        
        .link { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .link a { color: #4285f4; text-decoration: none; transition: color 0.2s; }
        .link a:hover { color: #3367d6; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">用户注册</h2>
        
        <?php if (!empty($reg_err)): ?>
            <div class="alert alert-error"><?php echo $reg_err; ?></div>
        <?php endif; ?>

        <!-- 注册步骤提示，用于 Step 2 状态 -->
        <?php if ($is_step_2): ?>
            <div class="alert alert-info">
                ✅ 信息已锁定，已向 <strong><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></strong> 发送验证码。请检查您的收件箱和垃圾邮件箱。
            </div>
        <?php endif; ?>

        <!-- 统一的表单结构 -->
        <form id="reg-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <!-- 用户名 -->
            <div class="form-group">
                <label>用户名</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required <?php echo $is_step_2 ? 'readonly' : ''; ?>>
                <span class="error"><?php echo $username_err; ?></span>
            </div>

            <!-- 邮箱 -->
            <div class="form-group">
                <label>邮箱</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required <?php echo $is_step_2 ? 'readonly' : ''; ?>>
                <span class="error"><?php echo $email_err; ?></span>
            </div>

            <!-- 验证码输入框与发送按钮 (修正后的结构) -->
            <div class="form-group">
                <label>邮箱验证码</label>
                <div class="code-row">
                    <!-- Input takes up remaining width -->
                    <input type="text" id="verification_code" name="verification_code" maxlength="6" pattern="\d{6}" 
                           placeholder="请输入6位验证码" value="<?php echo htmlspecialchars($verification_code, ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Button has fixed width -->
                    <button type="submit" id="send-code-btn" name="send_code" class="code-btn" <?php echo $is_step_2 ? 'disabled' : ''; ?>>
                        <?php echo $is_step_2 ? '已发送 (5分钟有效)' : '发送验证码'; ?>
                    </button>
                </div>
                <span class="error"><?php echo $code_err; ?></span>
            </div>
            
            <!-- 密码输入框 -->
            <div class="form-group">
                <label>密码</label>
                <!-- 即使在 Step 2，也保留输入框，但设为只读以显示结构一致性 -->
                <input type="password" id="password" name="password" required value="<?php echo $is_step_2 ? '********' : htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $is_step_2 ? 'readonly' : ''; ?>>
                <span class="error"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <label>确认密码</label>
                <input type="password" id="confirm_password" name="confirm_password" required value="<?php echo $is_step_2 ? '********' : htmlspecialchars($confirm_password, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $is_step_2 ? 'readonly' : ''; ?>>
                <span class="error"><?php echo $confirm_password_err; ?></span>
            </div>

            <!-- 最终注册按钮：使用 name="register_final" 触发 PHP 逻辑 -->
            <button type="submit" id="register-final-btn" name="register_final" class="btn-primary" disabled>
                完成注册
            </button>
            
            <div class="link">
                <!-- 链接用于重置状态 -->
                <?php if ($is_step_2): ?>
                    <!-- 重置状态，回到 Step 1 -->
                    <a href="?reset=1">返回修改信息</a>
                    <span style="color:#ddd; margin: 0 10px;">|</span>
                <?php endif; ?>
                已有账号？<a href="login.php">立即登录</a>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sendCodeBtn = document.getElementById('send-code-btn');
                const registerFinalBtn = document.getElementById('register-final-btn');
                const isStep2 = <?php echo json_encode($is_step_2); ?>;
                const form = document.getElementById('reg-form');
                const codeInput = document.getElementById('verification_code');
                
                // 初始化按钮状态
                if (isStep2) {
                    // Step 2: 发送按钮已禁用 (在 PHP 中设置了 '已发送' 文本)
                    registerFinalBtn.disabled = true; // 默认禁用，等待用户输入验证码
                } else {
                    // Step 1: 禁用最终注册按钮
                    registerFinalBtn.disabled = true;
                }

                // 监听页面重置链接点击 (清除 session 状态)
                const resetLink = document.querySelector('a[href="?reset=1"]');
                if (resetLink) {
                    resetLink.addEventListener('click', function(e) {
                        if(!confirm('确定要放弃当前验证并重新输入信息吗?')) {
                             e.preventDefault();
                        }
                    });
                }
                
                // 监听验证码输入框，如果输入了 6 位数字，启用“完成注册”按钮
                codeInput.addEventListener('input', function() {
                    const isValidCode = codeInput.value.length === 6 && !isNaN(codeInput.value);
                    if (isStep2 && isValidCode) {
                        registerFinalBtn.disabled = false;
                    } else if (isStep2) {
                        registerFinalBtn.disabled = true;
                    }
                });

                // --- 客户端阻止多次点击 '发送验证码' ---
                sendCodeBtn.addEventListener('click', function(e) {
                     if (sendCodeBtn.disabled) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </div>
</body>
</html>
