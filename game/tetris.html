<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>俄罗斯方块游戏</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Arial', sans-serif;
        }
        
        .game-board {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 1px;
            background: rgba(0, 0, 0, 0.1);
            padding: 2px;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        
        .cell {
            width: 25px;
            height: 25px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .cell.filled {
            border: 1px solid rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .cell.ghost {
            opacity: 0.3;
            border: 1px dashed rgba(255, 255, 255, 0.5);
        }
        
        .cell.I { background: linear-gradient(135deg, #00d2ff, #3a7bd5); }
        .cell.O { background: linear-gradient(135deg, #f2d50f, #da0641); }
        .cell.T { background: linear-gradient(135deg, #a044ff, #6a3093); }
        .cell.S { background: linear-gradient(135deg, #89f7fe, #66a6ff); }
        .cell.Z { background: linear-gradient(135deg, #fd746c, #ff9068); }
        .cell.J { background: linear-gradient(135deg, #37ecba, #72afd3); }
        .cell.L { background: linear-gradient(135deg, #ee9ca7, #ffdde1); }
        
        .next-piece {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 8px;
        }
        
        .next-cell {
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        @keyframes lineRemove {
            0% { background: rgba(255, 255, 255, 0.8); }
            100% { background: transparent; }
        }
        
        .removing {
            animation: lineRemove 0.5s ease;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-white text-center mb-8 drop-shadow-lg">俄罗斯方块</h1>
            
            <div class="flex flex-col md:flex-row gap-8 justify-center items-start">
                <!-- 游戏主板 -->
                <div class="bg-white/10 backdrop-blur-md rounded-lg p-4 shadow-2xl">
                    <div id="gameBoard" class="game-board"></div>
                </div>
                
                <!-- 游戏信息面板 -->
                <div class="flex flex-col gap-4">
                    <!-- 分数显示 -->
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-4 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-2">游戏信息</h2>
                        <div class="text-white">
                            <p class="mb-2">分数: <span id="score" class="font-bold text-2xl">0</span></p>
                            <p class="mb-2">等级: <span id="level" class="font-bold text-2xl">1</span></p>
                            <p>消除行数: <span id="lines" class="font-bold text-2xl">0</span></p>
                        </div>
                    </div>
                    
                    <!-- 下一个方块 -->
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-4 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-2">下一个方块</h2>
                        <div id="nextPiece" class="next-piece"></div>
                    </div>
                    
                    <!-- 游戏控制 -->
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-4 shadow-xl">
                        <button id="startBtn" class="btn w-full py-3 px-6 text-white font-bold rounded-lg mb-3">
                            开始游戏
                        </button>
                        <button id="pauseBtn" class="btn w-full py-3 px-6 text-white font-bold rounded-lg" disabled>
                            暂停
                        </button>
                    </div>
                    
                    <!-- 操作说明 -->
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-4 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-2">操作说明</h2>
                        <div class="text-white text-sm">
                            <p class="mb-1">← → : 左右移动</p>
                            <p class="mb-1">↓ : 快速下落</p>
                            <p class="mb-1">↑ : 旋转方块</p>
                            <p>空格 : 直接落下</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 游戏常量
        const COLS = 10;
        const ROWS = 20;
        const BLOCK_SIZE = 25;
        
        // 方块形状定义
        const SHAPES = {
            I: [[1, 1, 1, 1]],
            O: [[1, 1], [1, 1]],
            T: [[0, 1, 0], [1, 1, 1]],
            S: [[0, 1, 1], [1, 1, 0]],
            Z: [[1, 1, 0], [0, 1, 1]],
            J: [[1, 0, 0], [1, 1, 1]],
            L: [[0, 0, 1], [1, 1, 1]]
        };
        
        // 游戏状态
        let board = [];
        let currentPiece = null;
        let nextPiece = null;
        let score = 0;
        let level = 1;
        let lines = 0;
        let gameRunning = false;
        let gamePaused = false;
        let dropInterval;
        let dropSpeed = 1000;
        
        // 初始化游戏板
        function initBoard() {
            board = Array(ROWS).fill().map(() => Array(COLS).fill(0));
            const gameBoard = document.getElementById('gameBoard');
            gameBoard.innerHTML = '';
            
            for (let row = 0; row < ROWS; row++) {
                for (let col = 0; col < COLS; col++) {
                    const cell = document.createElement('div');
                    cell.className = 'cell';
                    cell.id = `cell-${row}-${col}`;
                    gameBoard.appendChild(cell);
                }
            }
        }
        
        // 创建方块类
        class Piece {
            constructor(type) {
                this.type = type;
                this.shape = SHAPES[type];
                this.x = Math.floor(COLS / 2) - Math.floor(this.shape[0].length / 2);
                this.y = 0;
            }
            
            rotate() {
                // 创建旋转后的形状
                const rotated = [];
                const rows = this.shape.length;
                const cols = this.shape[0].length;
                
                for (let i = 0; i < cols; i++) {
                    rotated[i] = [];
                    for (let j = rows - 1; j >= 0; j--) {
                        rotated[i][rows - 1 - j] = this.shape[j][i];
                    }
                }
                
                const prevShape = this.shape;
                const prevX = this.x;
                this.shape = rotated;
                
                // 尝试墙踢（Wall Kick）
                if (this.collision()) {
                    // 尝试向左移动
                    this.x = prevX - 1;
                    if (this.collision()) {
                        // 尝试向右移动
                        this.x = prevX + 1;
                        if (this.collision()) {
                            // 对于I型方块，尝试移动2格
                            if (this.type === 'I') {
                                this.x = prevX - 2;
                                if (this.collision()) {
                                    this.x = prevX + 2;
                                    if (this.collision()) {
                                        // 无法旋转，恢复原状
                                        this.shape = prevShape;
                                        this.x = prevX;
                                    }
                                }
                            } else {
                                // 无法旋转，恢复原状
                                this.shape = prevShape;
                                this.x = prevX;
                            }
                        }
                    }
                }
            }
            
            moveLeft() {
                this.x--;
                if (this.collision()) {
                    this.x++;
                }
            }
            
            moveRight() {
                this.x++;
                if (this.collision()) {
                    this.x--;
                }
            }
            
            moveDown() {
                this.y++;
                if (this.collision()) {
                    this.y--;
                    this.lock();
                    return false;
                }
                return true;
            }
            
            hardDrop() {
                while (this.moveDown()) {
                    score += 2;
                }
                updateScore();
            }
            
            collision() {
                for (let row = 0; row < this.shape.length; row++) {
                    for (let col = 0; col < this.shape[row].length; col++) {
                        if (this.shape[row][col]) {
                            const newX = this.x + col;
                            const newY = this.y + row;
                            
                            if (newX < 0 || newX >= COLS || newY >= ROWS) {
                                return true;
                            }
                            
                            if (newY >= 0 && board[newY][newX]) {
                                return true;
                            }
                        }
                    }
                }
                return false;
            }
            
            lock() {
                for (let row = 0; row < this.shape.length; row++) {
                    for (let col = 0; col < this.shape[row].length; col++) {
                        if (this.shape[row][col]) {
                            const newY = this.y + row;
                            const newX = this.x + col;
                            if (newY >= 0) {
                                board[newY][newX] = this.type;
                            }
                        }
                    }
                }
                
                clearLines();
                
                if (this.y <= 0) {
                    gameOver();
                    return;
                }
                
                currentPiece = nextPiece;
                nextPiece = createRandomPiece();
                displayNextPiece();
            }
        }
        
        // 创建随机方块
        function createRandomPiece() {
            const types = Object.keys(SHAPES);
            const type = types[Math.floor(Math.random() * types.length)];
            return new Piece(type);
        }
        
        // 清除完成的行
        function clearLines() {
            let linesCleared = 0;
            let rowsToRemove = [];
            
            // 先找出所有需要清除的行
            for (let row = ROWS - 1; row >= 0; row--) {
                if (board[row].every(cell => cell !== 0)) {
                    rowsToRemove.push(row);
                    linesCleared++;
                    
                    // 添加消除动画效果
                    for (let col = 0; col < COLS; col++) {
                        const cell = document.getElementById(`cell-${row}-${col}`);
                        cell.classList.add('removing');
                    }
                }
            }
            
            // 如果有需要清除的行
            if (linesCleared > 0) {
                setTimeout(() => {
                    // 从下往上删除行
                    for (let i = rowsToRemove.length - 1; i >= 0; i--) {
                        board.splice(rowsToRemove[i], 1);
                        board.unshift(Array(COLS).fill(0));
                    }
                    
                    lines += linesCleared;
                    
                    // 根据消除的行数计算分数
                    let lineScore = 0;
                    switch(linesCleared) {
                        case 1: lineScore = 100 * level; break;
                        case 2: lineScore = 300 * level; break;
                        case 3: lineScore = 500 * level; break;
                        case 4: lineScore = 800 * level; break;
                    }
                    score += lineScore;
                    
                    // 每10行升一级
                    const newLevel = Math.floor(lines / 10) + 1;
                    if (newLevel > level) {
                        level = newLevel;
                        dropSpeed = Math.max(100, 1000 - (level - 1) * 100);
                        resetDropInterval();
                    }
                    
                    updateScore();
                    render();
                }, 300);
            }
        }
        
        // 更新分数显示
        function updateScore() {
            document.getElementById('score').textContent = score;
            document.getElementById('level').textContent = level;
            document.getElementById('lines').textContent = lines;
        }
        
        // 显示下一个方块
        function displayNextPiece() {
            const nextPieceDiv = document.getElementById('nextPiece');
            nextPieceDiv.innerHTML = '';
            
            for (let row = 0; row < 4; row++) {
                for (let col = 0; col < 4; col++) {
                    const cell = document.createElement('div');
                    cell.className = 'next-cell';
                    
                    if (nextPiece && 
                        row < nextPiece.shape.length && 
                        col < nextPiece.shape[row].length && 
                        nextPiece.shape[row][col]) {
                        cell.className += ` filled ${nextPiece.type}`;
                    }
                    
                    nextPieceDiv.appendChild(cell);
                }
            }
        }
        
        // 计算幽灵方块位置
        function getGhostPosition() {
            if (!currentPiece) return null;
            
            let ghostY = currentPiece.y;
            const originalY = currentPiece.y;
            
            // 模拟下落直到碰撞
            while (true) {
                currentPiece.y++;
                if (currentPiece.collision()) {
                    currentPiece.y--;
                    ghostY = currentPiece.y;
                    break;
                }
            }
            
            currentPiece.y = originalY;
            return ghostY;
        }
        
        // 渲染游戏板
        function render() {
            // 清除画板
            for (let row = 0; row < ROWS; row++) {
                for (let col = 0; col < COLS; col++) {
                    const cell = document.getElementById(`cell-${row}-${col}`);
                    cell.className = 'cell';
                    
                    if (board[row][col]) {
                        cell.classList.add('filled', board[row][col]);
                    }
                }
            }
            
            // 绘制幽灵方块
            if (currentPiece && gameRunning && !gamePaused) {
                const ghostY = getGhostPosition();
                if (ghostY !== null && ghostY > currentPiece.y) {
                    for (let row = 0; row < currentPiece.shape.length; row++) {
                        for (let col = 0; col < currentPiece.shape[row].length; col++) {
                            if (currentPiece.shape[row][col]) {
                                const x = currentPiece.x + col;
                                const y = ghostY + row;
                                if (y >= 0 && y < ROWS && x >= 0 && x < COLS) {
                                    const cell = document.getElementById(`cell-${y}-${x}`);
                                    if (!board[y][x]) {
                                        cell.classList.add('ghost', currentPiece.type);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // 绘制当前方块
            if (currentPiece) {
                for (let row = 0; row < currentPiece.shape.length; row++) {
                    for (let col = 0; col < currentPiece.shape[row].length; col++) {
                        if (currentPiece.shape[row][col]) {
                            const x = currentPiece.x + col;
                            const y = currentPiece.y + row;
                            if (y >= 0 && y < ROWS && x >= 0 && x < COLS) {
                                const cell = document.getElementById(`cell-${y}-${x}`);
                                cell.classList.remove('ghost');
                                cell.classList.add('filled', currentPiece.type);
                            }
                        }
                    }
                }
            }
        }
        
        // 游戏主循环
        function gameLoop() {
            if (!gameRunning || gamePaused) return;
            
            if (currentPiece) {
                currentPiece.moveDown();
            }
            
            render();
        }
        
        // 重置下落间隔
        function resetDropInterval() {
            if (dropInterval) {
                clearInterval(dropInterval);
            }
            dropInterval = setInterval(gameLoop, dropSpeed);
        }
        
        // 开始游戏
        function startGame() {
            initBoard();
            score = 0;
            level = 1;
            lines = 0;
            dropSpeed = 1000;
            updateScore();
            
            currentPiece = createRandomPiece();
            nextPiece = createRandomPiece();
            displayNextPiece();
            
            gameRunning = true;
            gamePaused = false;
            
            document.getElementById('startBtn').textContent = '重新开始';
            document.getElementById('pauseBtn').disabled = false;
            
            resetDropInterval();
            render();
        }
        
        // 暂停游戏
        function pauseGame() {
            if (!gameRunning) return;
            
            gamePaused = !gamePaused;
            document.getElementById('pauseBtn').textContent = gamePaused ? '继续' : '暂停';
            
            if (!gamePaused) {
                resetDropInterval();
            }
        }
        
        // 游戏结束
        function gameOver() {
            gameRunning = false;
            clearInterval(dropInterval);
            alert(`游戏结束！\n最终分数: ${score}\n等级: ${level}\n消除行数: ${lines}`);
            document.getElementById('pauseBtn').disabled = true;
        }
        
        // 键盘控制
        document.addEventListener('keydown', (e) => {
            if (!gameRunning || gamePaused || !currentPiece) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    currentPiece.moveLeft();
                    render();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    currentPiece.moveRight();
                    render();
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentPiece.moveDown()) {
                        score += 1;
                        updateScore();
                    }
                    render();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    currentPiece.rotate();
                    render();
                    break;
                case ' ':
                    e.preventDefault();
                    currentPiece.hardDrop();
                    render();
                    break;
            }
        });
        
        // 按钮事件
        document.getElementById('startBtn').addEventListener('click', startGame);
        document.getElementById('pauseBtn').addEventListener('click', pauseGame);
        
        // 初始化
        initBoard();
    </script>
</body>
</html>
