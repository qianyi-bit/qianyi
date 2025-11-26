<?php
session_start();

// 关卡配置：每关的行数、列数、地雷数
$levels = [
    1 => ['rows' => 8, 'cols' => 8, 'mines' => 10],   // 第一关
    2 => ['rows' => 10, 'cols' => 12, 'mines' => 20], // 第二关
    3 => ['rows' => 12, 'cols' => 16, 'mines' => 35], // 第三关
    4 => ['rows' => 16, 'cols' => 20, 'mines' => 50], // 第四关
    5 => ['rows' => 20, 'cols' => 24, 'mines' => 70]  // 第五关
];

// 获取当前关卡配置
function getCurrentLevelConfig() {
    global $levels;
    $currentLevel = $_SESSION['current_level'] ?? 1;
    // 确保关卡有效
    if (!isset($levels[$currentLevel])) {
        $currentLevel = 1;
        $_SESSION['current_level'] = 1;
    }
    return $levels[$currentLevel];
}

// 初始化游戏
function initGame() {
    $config = getCurrentLevelConfig();
    $rows = $config['rows'];
    $cols = $config['cols'];
    $mines = $config['mines'];
    
    // 初始化棋盘
    $board = array_fill(0, $rows, array_fill(0, $cols, 0));
    $revealed = array_fill(0, $rows, array_fill(0, $cols, false));
    $flags = array_fill(0, $rows, array_fill(0, $cols, false));
    
    // 随机放置地雷
    $minesPlaced = 0;
    while ($minesPlaced < $mines) {
        $x = rand(0, $rows - 1);
        $y = rand(0, $cols - 1);
        
        if ($board[$x][$y] != -1) { // 不是地雷
            $board[$x][$y] = -1; // 标记为地雷
            $minesPlaced++;
            
            // 更新周围格子的数字
            for ($dx = -1; $dx <= 1; $dx++) {
                for ($dy = -1; $dy <= 1; $dy++) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;
                    
                    if ($nx >= 0 && $nx < $rows && $ny >= 0 && $ny < $cols && $board[$nx][$ny] != -1) {
                        $board[$nx][$ny]++;
                    }
                }
            }
        }
    }
    
    return [
        'board' => $board,
        'revealed' => $revealed,
        'flags' => $flags,
        'gameOver' => false,
        'win' => false,
        'rows' => $rows,
        'cols' => $cols,
        'mines' => $mines
    ];
}

// 递归翻开空白格子
function revealCell($x, $y) {
    $config = getCurrentLevelConfig();
    $rows = $config['rows'];
    $cols = $config['cols'];
    
    // 如果已经翻开或超出边界，返回
    if ($x < 0 || $x >= $rows || $y < 0 || $y >= $cols || $_SESSION['game']['revealed'][$x][$y]) {
        return;
    }
    
    // 翻开当前格子
    $_SESSION['game']['revealed'][$x][$y] = true;
    
    // 如果是空白格子，递归翻开周围格子
    if ($_SESSION['game']['board'][$x][$y] == 0) {
        for ($dx = -1; $dx <= 1; $dx++) {
            for ($dy = -1; $dy <= 1; $dy++) {
                if ($dx != 0 || $dy != 0) {
                    revealCell($x + $dx, $y + $dy);
                }
            }
        }
    }
}

// 检查是否胜利
function checkWin() {
    $config = getCurrentLevelConfig();
    $rows = $config['rows'];
    $cols = $config['cols'];
    $mines = $config['mines'];
    
    $revealedCount = 0;
    $correctFlags = 0;
    
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if ($_SESSION['game']['revealed'][$i][$j]) {
                $revealedCount++;
            }
            
            // 检查标记的地雷是否正确
            if ($_SESSION['game']['flags'][$i][$j] && $_SESSION['game']['board'][$i][$j] == -1) {
                $correctFlags++;
            }
        }
    }
    
    // 胜利条件：所有非地雷格子都被翻开，或者所有地雷都被正确标记
    $totalCells = $rows * $cols;
    if ($revealedCount == $totalCells - $mines || $correctFlags == $mines) {
        $_SESSION['game']['gameOver'] = true;
        $_SESSION['game']['win'] = true;
        // 显示所有地雷
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($_SESSION['game']['board'][$i][$j] == -1) {
                    $_SESSION['game']['flags'][$i][$j] = true;
                }
            }
        }
    }
}

// 处理游戏操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'reveal':
        case 'flag':
            if (!isset($_SESSION['game'])) {
                $_SESSION['game'] = initGame();
            }
            
            $x = intval($_POST['x'] ?? 0);
            $y = intval($_POST['y'] ?? 0);
            $config = getCurrentLevelConfig();
            
            // 检查坐标是否有效
            if ($x < 0 || $x >= $config['rows'] || $y < 0 || $y >= $config['cols']) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
            
            // 如果已经游戏结束或已经翻开，不做处理
            if ($_SESSION['game']['gameOver'] || ($action == 'reveal' && $_SESSION['game']['revealed'][$x][$y])) {
                break;
            }
            
            if ($action == 'reveal') {
                // 如果点击了地雷，游戏结束
                if ($_SESSION['game']['board'][$x][$y] == -1) {
                    $_SESSION['game']['gameOver'] = true;
                    $_SESSION['game']['win'] = false;
                    // 显示所有地雷
                    for ($i = 0; $i < $config['rows']; $i++) {
                        for ($j = 0; $j < $config['cols']; $j++) {
                            if ($_SESSION['game']['board'][$i][$j] == -1) {
                                $_SESSION['game']['revealed'][$i][$j] = true;
                            }
                        }
                    }
                    break;
                }
                
                // 翻开格子
                revealCell($x, $y);
                
                // 检查是否胜利
                checkWin();
            } elseif ($action == 'flag') {
                if (!$_SESSION['game']['revealed'][$x][$y]) {
                    $_SESSION['game']['flags'][$x][$y] = !$_SESSION['game']['flags'][$x][$y];
                    checkWin();
                }
            }
            break;
            
        case 'reset':
            unset($_SESSION['game']);
            break;
            
        case 'next_level':
            // 进入下一关
            $currentLevel = $_SESSION['current_level'] ?? 1;
            global $levels;
            if (isset($levels[$currentLevel + 1])) {
                $_SESSION['current_level'] = $currentLevel + 1;
            }
            unset($_SESSION['game']);
            break;
            
        case 'restart_level':
            // 重玩当前关
            unset($_SESSION['game']);
            break;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 初始化游戏（如果尚未初始化）
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = initGame();
}

// 获取当前关卡信息
$currentLevel = $_SESSION['current_level'] ?? 1;
$config = getCurrentLevelConfig();
$totalLevels = count($levels);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>关卡式扫雷游戏</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            padding: 20px;
        }
        
        .game-header {
            margin-bottom: 20px;
        }
        
        .level-info {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }
        
        .game-container {
            display: inline-block;
            margin: 0 auto;
            padding: 10px;
            background-color: #c0c0c0;
            border: 2px solid #7b7b7b;
            overflow: auto;
            max-width: 95%;
        }
        
        .info {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #e0e0e0;
            border: 1px solid #999;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(<?php echo $config['cols']; ?>, 30px);
            gap: 2px;
            margin: 0 auto;
        }
        
        .cell {
            width: 30px;
            height: 30px;
            background-color: #c0c0c0;
            border-top: 2px solid white;
            border-left: 2px solid white;
            border-right: 2px solid #7b7b7b;
            border-bottom: 2px solid #7b7b7b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }
        
        .cell.revealed {
            border: 1px solid #999;
            cursor: default;
        }
        
        .cell.flagged::after {
            content: "⚐";
        }
        
        .cell.mine {
            background-color: #ff6666;
        }
        
        .cell.mine::after {
            content: "💣";
        }
        
        .cell.number-1 { color: blue; }
        .cell.number-2 { color: green; }
        .cell.number-3 { color: red; }
        .cell.number-4 { color: purple; }
        .cell.number-5 { color: maroon; }
        .cell.number-6 { color: teal; }
        .cell.number-7 { color: black; }
        .cell.number-8 { color: gray; }
        
        .controls {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        button.secondary {
            background-color: #2196F3;
        }
        
        button.secondary:hover {
            background-color: #0b7dda;
        }
        
        .message {
            margin-top: 15px;
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px;
            border-radius: 4px;
        }
        
        .win { 
            color: green; 
            background-color: #dff0d8;
        }
        
        .lose { 
            color: red; 
            background-color: #f2dede;
        }
        
        .level-progress {
            margin: 10px 0;
            padding: 5px;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .progress {
            height: 100%;
            background-color: #4CAF50;
            width: <?php echo ($currentLevel / $totalLevels) * 100; ?>%;
        }
    </style>
</head>
<body>
    <div class="game-header">
        <h1>关卡式扫雷游戏</h1>
        <div class="level-info">
            当前关卡: <?php echo $currentLevel; ?>/<?php echo $totalLevels; ?>
            (棋盘: <?php echo $config['rows']; ?>×<?php echo $config['cols']; ?>, 地雷: <?php echo $config['mines']; ?>个)
        </div>
        <div class="level-progress">
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>
    
    <div class="game-container">
        <div class="info">
            <div>剩余地雷: <?php echo $config['mines'] - count(array_filter(array_merge(...$_SESSION['game']['flags']))); ?></div>
            <div>已翻开: <?php echo count(array_filter(array_merge(...$_SESSION['game']['revealed']))); ?>/<?php echo $config['rows'] * $config['cols'] - $config['mines']; ?></div>
        </div>
        
        <div class="grid">
            <?php for ($i = 0; $i < $config['rows']; $i++): ?>
                <?php for ($j = 0; $j < $config['cols']; $j++): ?>
                    <?php
                    $cellClass = 'cell';
                    $content = '';
                    
                    if ($_SESSION['game']['revealed'][$i][$j]) {
                        $cellClass .= ' revealed';
                        if ($_SESSION['game']['board'][$i][$j] == -1) {
                            $cellClass .= ' mine';
                        } elseif ($_SESSION['game']['board'][$i][$j] > 0) {
                            $cellClass .= ' number-' . $_SESSION['game']['board'][$i][$j];
                            $content = $_SESSION['game']['board'][$i][$j];
                        }
                    } elseif ($_SESSION['game']['flags'][$i][$j]) {
                        $cellClass .= ' flagged';
                    }
                    ?>
                    
                    <div 
                        class="<?php echo $cellClass; ?>"
                        data-x="<?php echo $i; ?>"
                        data-y="<?php echo $j; ?>"
                    >
                        <?php echo $content; ?>
                    </div>
                <?php endfor; ?>
            <?php endfor; ?>
        </div>
        
        <?php if ($_SESSION['game']['gameOver']): ?>
            <div class="message <?php echo $_SESSION['game']['win'] ? 'win' : 'lose'; ?>">
                <?php 
                if ($_SESSION['game']['win']) {
                    if ($currentLevel == $totalLevels) {
                        echo "恭喜你通关所有关卡！太棒了！";
                    } else {
                        echo "恭喜你通过第{$currentLevel}关！准备好挑战下一关了吗？";
                    }
                } else {
                    echo "很遗憾，踩到地雷了！再试一次吧！";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="controls">
            <?php if ($_SESSION['game']['gameOver']): ?>
                <?php if ($_SESSION['game']['win'] && $currentLevel < $totalLevels): ?>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="action" value="next_level">下一关</button>
                    </form>
                <?php endif; ?>
                <form method="post" style="display: inline;">
                    <button type="submit" name="action" value="restart_level" class="secondary">重玩本关</button>
                </form>
            <?php else: ?>
                <form method="post" style="display: inline;">
                    <button type="submit" name="action" value="reset">重新开始</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // 为每个格子添加点击事件
        document.querySelectorAll('.cell').forEach(cell => {
            // 左键点击翻开格子
            cell.addEventListener('click', function() {
                <?php if (!$_SESSION['game']['gameOver']): ?>
                    const x = this.getAttribute('data-x');
                    const y = this.getAttribute('data-y');
                    
                    // 创建表单并提交
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="reveal">
                        <input type="hidden" name="x" value="${x}">
                        <input type="hidden" name="y" value="${y}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                <?php endif; ?>
            });
            
            // 右键点击标记地雷
            cell.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                <?php if (!$_SESSION['game']['gameOver']): ?>
                    const x = this.getAttribute('data-x');
                    const y = this.getAttribute('data-y');
                    
                    // 创建表单并提交
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="flag">
                        <input type="hidden" name="x" value="${x}">
                        <input type="hidden" name="y" value="${y}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                <?php endif; ?>
            });
        });
    </script>
</body>
</html>
