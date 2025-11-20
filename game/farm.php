<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>欢乐养殖场 - 休闲养殖游戏</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4CAF50',
                        secondary: '#8BC34A',
                        accent: '#FF9800',
                        dark: '#388E3C',
                        light: '#E8F5E9',
                        danger: '#F44336',
                    },
                    fontFamily: {
                        game: ['"Comic Sans MS"', '"Marker Felt"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
            .animal-card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-105;
            }
            .btn-primary {
                @apply bg-primary hover:bg-dark text-white font-bold py-2 px-4 rounded-full transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded-full transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50;
            }
            .btn-accent {
                @apply bg-accent hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-full transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-50;
            }
            .resource-bar {
                @apply flex items-center justify-between p-2 bg-light rounded-lg mb-3;
            }
            .game-container {
                @apply max-w-7xl mx-auto p-4 bg-gradient-to-b from-green-50 to-green-100 min-h-screen;
            }
            .progress-bar {
                @apply h-2 bg-gray-200 rounded-full overflow-hidden;
            }
            .progress-value {
                @apply h-full bg-primary rounded-full transition-all duration-500;
            }
            .notification {
                @apply fixed top-4 right-4 bg-white rounded-lg shadow-xl p-4 transform transition-all duration-500 translate-x-full opacity-0;
            }
            .notification.show {
                @apply translate-x-0 opacity-100;
            }
            .animal-category {
                @apply font-bold text-lg mb-3 text-dark border-b border-gray-200 pb-1;
            }
            .backpack-item {
                @apply flex justify-between items-center p-2 border-b border-gray-100 last:border-0;
            }
        }
    </style>
</head>
<body class="font-game text-gray-800">
    <!-- 音频元素 (隐藏) -->
    <audio id="bg-music" loop>
        <source src="https://assets.mixkit.co/music/preview/mixkit-forest-stream-ambience-1242.mp3" type="audio/mpeg">
    </audio>
    <audio id="click-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-select-click-1109.mp3" type="audio/mpeg">
    </audio>
    <audio id="collect-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-arcade-game-jump-coin-216.mp3" type="audio/mpeg">
    </audio>
    <audio id="purchase-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-unlocking-563.mp3" type="audio/mpeg">
    </audio>
    <audio id="upgrade-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-player-jumping-in-a-platform-game-2042.mp3" type="audio/mpeg">
    </audio>
    <audio id="complete-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-achievement-bell-600.mp3" type="audio/mpeg">
    </audio>
    
    <!-- 动物声音 -->
    <audio id="cow-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-cow-moo-1407.mp3" type="audio/mpeg">
    </audio>
    <audio id="sheep-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-sheep-bleating-1409.mp3" type="audio/mpeg">
    </audio>
    <audio id="chicken-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-chicken-clucking-1404.mp3" type="audio/mpeg">
    </audio>
    <audio id="pig-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-pig-oink-1408.mp3" type="audio/mpeg">
    </audio>
    <audio id="duck-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-duck-quacking-1406.mp3" type="audio/mpeg">
    </audio>
    <audio id="fish-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-splashing-water-1399.mp3" type="audio/mpeg">
    </audio>
    <audio id="bee-sound">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-bee-buzz-1394.mp3" type="audio/mpeg">
    </audio>
    
    <div class="game-container">
        <!-- 顶部状态栏 -->
        <header class="mb-6">
            <div class="flex flex-wrap justify-between items-center mb-4">
                <h1 class="text-[clamp(1.8rem,5vw,2.5rem)] font-bold text-dark flex items-center">
                    <i class="fa fa-pagelines mr-3 text-primary"></i>欢乐养殖场
                </h1>
                
                <div class="flex items-center space-x-4 flex-wrap">
                    <div class="resource-bar bg-white px-4">
                        <span class="flex items-center"><i class="fa fa-star text-accent mr-2"></i>等级: <span id="level">1</span></span>
                        <div class="w-32">
                            <div class="progress-bar">
                                <div id="exp-bar" class="progress-value" style="width: 0%"></div>
                            </div>
                            <span class="text-xs text-right block mt-1"><span id="current-exp">0</span>/<span id="next-level-exp">100</span></span>
                        </div>
                    </div>
                    
                    <div class="resource-bar bg-white px-4">
                        <i class="fa fa-money text-green-600 mr-2"></i>
                        <span id="coins">2000</span>
                    </div>
                    
                    <div class="resource-bar bg-white px-4">
                        <i class="fa fa-cutlery text-amber-700 mr-2"></i>
                        <span id="feed">100</span>
                    </div>
                    
                    <button id="btn-sound" class="btn-secondary flex items-center">
                        <i class="fa fa-volume-up mr-1"></i> 音效
                    </button>
                </div>
            </div>
            
            <!-- 导航按钮 -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button id="btn-farm" class="btn-primary flex-1 sm:flex-none"><i class="fa fa-home mr-1"></i> 农场</button>
                <button id="btn-shop" class="btn-secondary flex-1 sm:flex-none"><i class="fa fa-shopping-cart mr-1"></i> 商店</button>
                <button id="btn-upgrades" class="btn-secondary flex-1 sm:flex-none"><i class="fa fa-refresh mr-1"></i> 升级</button>
                <button id="btn-quests" class="btn-secondary flex-1 sm:flex-none"><i class="fa fa-list-alt mr-1"></i> 任务</button>
                <button id="btn-market" class="btn-secondary flex-1 sm:flex-none"><i class="fa fa-exchange mr-1"></i> 市场</button>
                <button id="btn-backpack" class="btn-secondary flex-1 sm:flex-none"><i class="fa fa-briefcase mr-1"></i> 背包</button>
            </div>
        </header>
        
        <!-- 主要游戏区域 -->
        <main>
            <!-- 农场视图 -->
            <section id="farm-view" class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-paw mr-2"></i>我的农场
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="animal-grid">
                        <!-- 动物卡片将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">收获记录</h3>
                    <div id="harvest-log" class="max-h-32 overflow-y-auto text-sm space-y-1">
                        <p class="text-gray-500 italic">暂无收获记录</p>
                    </div>
                </div>
            </section>
            
            <!-- 商店视图 (默认隐藏) -->
            <section id="shop-view" class="mb-8 hidden">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-shopping-cart mr-2"></i>动物商店
                </h2>
                
                <!-- 农场动物 -->
                <div class="mb-6">
                    <h3 class="animal-category">
                        <i class="fa fa-paw mr-2"></i>农场动物
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="farm-animals">
                        <!-- 农场动物将通过JS动态生成 -->
                    </div>
                </div>
                
                <!-- 家禽类 -->
                <div class="mb-6">
                    <h3 class="animal-category">
                        <i class="fa fa-feather mr-2"></i>家禽类
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="poultry-animals">
                        <!-- 家禽类动物将通过JS动态生成 -->
                    </div>
                </div>
                
                <!-- 水产类 -->
                <div class="mb-6">
                    <h3 class="animal-category">
                        <i class="fa fa-tint mr-2"></i>水产类
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="aquatic-animals">
                        <!-- 水产类动物将通过JS动态生成 -->
                    </div>
                </div>
                
                <!-- 特种养殖 -->
                <div class="mb-6">
                    <h3 class="animal-category">
                        <i class="fa fa-leaf mr-2"></i>特种养殖
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="special-animals">
                        <!-- 特种养殖动物将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="mt-6 bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">物品商店</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa fa-cutlery text-amber-700 text-2xl mr-3"></i>
                                <div>
                                    <h4 class="font-bold">饲料包</h4>
                                    <p class="text-sm text-gray-600">10份饲料</p>
                                </div>
                            </div>
                            <button class="btn-accent py-1 px-3 text-sm" data-item="feed" data-amount="10" data-cost="150">
                                <i class="fa fa-money mr-1"></i>150
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa fa-bolt text-yellow-500 text-2xl mr-3"></i>
                                <div>
                                    <h4 class="font-bold">加速剂</h4>
                                    <p class="text-sm text-gray-600">减少动物生长时间50%</p>
                                </div>
                            </div>
                            <button class="btn-accent py-1 px-3 text-sm" data-item="speed" data-amount="1" data-cost="300">
                                <i class="fa fa-money mr-1"></i>300
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- 升级视图 (默认隐藏) -->
            <section id="upgrades-view" class="mb-8 hidden">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-refresh mr-2"></i>农场升级
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <h3 class="font-bold mb-3 text-primary">农场容量升级</h3>
                    <p class="text-gray-600 mb-4">当前容量: <span id="current-capacity">12</span> 只动物</p>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-bold">升级到 <span id="next-capacity">24</span> 容量</p>
                            <p class="text-sm text-gray-600">需要: <span id="capacity-upgrade-cost">3000</span> 金币</p>
                        </div>
                        <button id="upgrade-capacity" class="btn-accent">升级</button>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">产物效率升级</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="efficiency-upgrades">
                        <!-- 效率升级将通过JS动态生成 -->
                    </div>
                </div>
            </section>
            
            <!-- 任务视图 (默认隐藏) -->
            <section id="quests-view" class="mb-8 hidden">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-list-alt mr-2"></i>任务中心
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <h3 class="font-bold mb-3 text-primary">每日任务</h3>
                    <div class="space-y-4" id="daily-quests-container">
                        <!-- 每日任务将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <h3 class="font-bold mb-3 text-primary">成长任务</h3>
                    <div class="space-y-4" id="growth-quests-container">
                        <!-- 成长任务将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">成就</h3>
                    <div class="space-y-3" id="achievements-container">
                        <!-- 成就将通过JS动态生成 -->
                    </div>
                </div>
            </section>
            
            <!-- 市场视图 (默认隐藏) -->
            <section id="market-view" class="mb-8 hidden">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-exchange mr-2"></i>农产品市场
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <h3 class="font-bold mb-3 text-primary">我的库存</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="inventory">
                        <!-- 库存将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">市场行情 (每小时变动)</h3>
                    <div class="space-y-2 text-sm">
                        <!-- 市场行情将通过JS动态生成 -->
                    </div>
                </div>
            </section>
            
            <!-- 背包视图 (默认隐藏) -->
            <section id="backpack-view" class="mb-8 hidden">
                <h2 class="text-xl font-bold mb-4 text-dark flex items-center">
                    <i class="fa fa-briefcase mr-2"></i>我的背包
                </h2>
                
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <h3 class="font-bold mb-3 text-primary">农产品</h3>
                    <div id="backpack-products" class="max-h-64 overflow-y-auto">
                        <!-- 农产品将通过JS动态生成 -->
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-bold mb-3 text-primary">消耗品</h3>
                    <div id="backpack-consumables" class="space-y-3">
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa fa-bolt text-yellow-500 text-2xl mr-3"></i>
                                <div>
                                    <h4 class="font-bold">加速剂</h4>
                                    <p class="text-sm text-gray-600">减少动物生长时间50%</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-3 font-bold">数量: <span id="accelerator-count">0</span></span>
                                <button id="use-accelerator" class="btn-accent py-1 px-3 text-sm">使用</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        
        <!-- 底部信息 -->
        <footer class="mt-8 text-center text-sm text-gray-500">
            <p>欢乐养殖场 &copy; 2023 - 一个有趣的休闲养殖游戏</p>
        </footer>
    </div>
    
    <!-- 通知组件 -->
    <div id="notification" class="notification">
        <p id="notification-message"></p>
    </div>
    
    <script>
        // 音效控制
        const sounds = {
            bgMusic: document.getElementById('bg-music'),
            click: document.getElementById('click-sound'),
            collect: document.getElementById('collect-sound'),
            purchase: document.getElementById('purchase-sound'),
            upgrade: document.getElementById('upgrade-sound'),
            complete: document.getElementById('complete-sound'),
            
            // 动物声音映射
            animalSounds: {
                cow: document.getElementById('cow-sound'),
                sheep: document.getElementById('sheep-sound'),
                chicken: document.getElementById('chicken-sound'),
                pig: document.getElementById('pig-sound'),
                duck: document.getElementById('duck-sound'),
                fish: document.getElementById('fish-sound'),
                bee: document.getElementById('bee-sound'),
                // 默认使用点击音效
                default: document.getElementById('click-sound')
            },
            
            // 播放音效的通用方法
            play: function(sound) {
                if (this.muted) return;
                
                if (typeof sound === 'string') {
                    if (this.animalSounds[sound]) {
                        this.animalSounds[sound].currentTime = 0;
                        this.animalSounds[sound].play().catch(e => console.log("Sound play failed:", e));
                    } else {
                        this.animalSounds.default.currentTime = 0;
                        this.animalSounds.default.play().catch(e => console.log("Sound play failed:", e));
                    }
                } else {
                    sound.currentTime = 0;
                    sound.play().catch(e => console.log("Sound play failed:", e));
                }
            },
            
            toggleMute: function() {
                this.muted = !this.muted;
                if (this.muted) {
                    this.bgMusic.pause();
                    document.getElementById('btn-sound').innerHTML = '<i class="fa fa-volume-off mr-1"></i> 音效';
                } else {
                    this.bgMusic.play().catch(e => console.log("Background music play failed:", e));
                    document.getElementById('btn-sound').innerHTML = '<i class="fa fa-volume-up mr-1"></i> 音效';
                }
            },
            
            muted: false
        };
        
        // 游戏数据
        const gameData = {
            coins: 2000,
            feed: 100,
            level: 1,
            exp: 0,
            nextLevelExp: 100,
            animals: [],
            maxCapacity: 12,
            inventory: {
                eggs: 0,
                milk: 0,
                wool: 0,
                meat: 0,
                honey: 0,
                feathers: 0,
                silk: 0,
                fish: 0,
                shrimp: 0,
                crab: 0,
                frog: 0,
                snail: 0,
                mushroom: 0,
                bamboo: 0,
                fur: 0,
                leather: 0,
                pearls: 0,
                antlers: 0,
                horns: 0,
                ginseng: 0,
                herbs: 0,
                wax: 0,
                pollen: 0,
                larvae: 0
            },
            consumables: {
                accelerator: 0  // 加速剂数量
            },
            efficiency: {
                // 初始化所有产物的效率
                egg: 10, milk: 10, wool: 10, meat: 10, honey: 10, feathers: 10,
                silk: 10, fish: 10, shrimp: 10, crab: 10, frog: 10, snail: 10,
                mushroom: 10, bamboo: 10, fur: 10, leather: 10, pearls: 10,
                antlers: 10, horns: 10, ginseng: 10, herbs: 10, wax: 10,
                pollen: 10, larvae: 10,
                
                // 初始化所有产物的等级
                eggLevel: 1, milkLevel: 1, woolLevel: 1, meatLevel: 1, honeyLevel: 1, feathersLevel: 1,
                silkLevel: 1, fishLevel: 1, shrimpLevel: 1, crabLevel: 1, frogLevel: 1, snailLevel: 1,
                mushroomLevel: 1, bambooLevel: 1, furLevel: 1, leatherLevel: 1, pearlsLevel: 1,
                antlersLevel: 1, hornsLevel: 1, ginsengLevel: 1, herbsLevel: 1, waxLevel: 1,
                pollenLevel: 1, larvaeLevel: 1
            },
            quests: {
                daily: [
                    { id: 1, title: "购买5只动物", target: 5, current: 0, type: "buy", animal: "", reward: 300, completed: false, reset: true },
                    { id: 2, title: "收集20个鸡蛋", target: 20, current: 0, type: "collect", item: "eggs", reward: 400, completed: false, reset: true },
                    { id: 3, title: "出售10份产物", target: 10, current: 0, type: "sell", item: "", reward: 500, completed: false, reset: true },
                    { id: 4, title: "收获5份肉类", target: 5, current: 0, type: "collect", item: "meat", reward: 350, completed: false, reset: true },
                    { id: 5, title: "进行2次升级", target: 2, current: 0, type: "upgrade", item: "", reward: 450, completed: false, reset: true }
                ],
                growth: [
                    { id: 101, title: "达到3级", target: 3, current: 1, type: "level", reward: 800, completed: false, reset: false },
                    { id: 102, title: "解锁10种不同动物", target: 10, current: 0, type: "unlock", animal: "", reward: 1200, completed: false, reset: false },
                    { id: 103, title: "收获100个农产品", target: 100, current: 0, type: "collect", item: "", reward: 1000, completed: false, reset: false },
                    { id: 104, title: "农场容量升级到36", target: 36, current: 12, type: "capacity", reward: 1500, completed: false, reset: false },
                    { id: 105, title: "拥有50000金币", target: 50000, current: 2000, type: "coins", reward: 3000, completed: false, reset: false },
                    { id: 106, title: "所有产物效率达到5级", target: 5, current: 1, type: "efficiency", reward: 5000, completed: false, reset: false },
                    { id: 107, title: "解锁所有水产类动物", target: 6, current: 0, type: "category", category: "aquatic", reward: 2500, completed: false, reset: false },
                    { id: 108, title: "出售总价值100000金币的产物", target: 100000, current: 0, type: "sellvalue", reward: 8000, completed: false, reset: false }
                ]
            },
            achievements: [
                { id: 1, title: "养殖新手", description: "拥有第一只动物", completed: false, reward: 100 },
                { id: 2, title: "勤劳的农夫", description: "收获200个农产品", current: 0, target: 200, completed: false, reward: 800 },
                { id: 3, title: "富有的农场主", description: "拥有20000金币", completed: false, reward: 2000 },
                { id: 4, title: "动物收藏家", description: "拥有10种不同类型的动物", completed: false, reward: 1500 },
                { id: 5, title: "全品类大师", description: "拥有所有类型的动物", completed: false, reward: 5000 },
                { id: 6, title: "市场大亨", description: "出售总价值50000金币的产物", current: 0, target: 50000, completed: false, reward: 3000 },
                { id: 7, title: "升级达人", description: "所有产物效率达到10级", completed: false, reward: 10000 },
                { id: 8, title: "任务专家", description: "完成50个每日任务", current: 0, target: 50, completed: false, reward: 6000 },
                { id: 9, title: "农场大亨", description: "农场容量达到100", completed: false, reward: 8000 },
                { id: 10, title: "养殖大师", description: "达到20级", completed: false, reward: 15000 }
            ],
            harvestLog: [],
            unlockedAnimals: new Set(), // 记录已解锁的动物类型
            unlockedCategories: new Set() // 记录已解锁的动物类别
        };
        
        // 24种动物配置，按类别分组
        const animalTypes = {
            // 农场动物
            cow: {
                name: "奶牛",
                image: "https://picsum.photos/id/1024/300/200",
                price: 800,
                product: "milk",
                productName: "牛奶",
                productionTime: 60,  // 秒
                feedPerProduction: 4,
                baseValue: 50,
                category: "farm",
                sound: "cow"
            },
            sheep: {
                name: "绵羊",
                image: "https://picsum.photos/id/1074/300/200",
                price: 500,
                product: "wool",
                productName: "羊毛",
                productionTime: 45,  // 秒
                feedPerProduction: 3,
                baseValue: 35,
                category: "farm",
                sound: "sheep"
            },
            pig: {
                name: "肉猪",
                image: "https://picsum.photos/id/429/300/200",
                price: 600,
                product: "meat",
                productName: "猪肉",
                productionTime: 75,  // 秒
                feedPerProduction: 5,
                baseValue: 45,
                category: "farm",
                sound: "pig"
            },
            goat: {
                name: "山羊",
                image: "https://picsum.photos/id/1084/300/200",
                price: 700,
                product: "milk",
                productName: "羊奶",
                productionTime: 55,  // 秒
                feedPerProduction: 3,
                baseValue: 40,
                category: "farm",
                sound: "sheep" // 使用绵羊的声音作为近似
            },
            horse: {
                name: "马",
                image: "https://picsum.photos/id/1062/300/200",
                price: 1500,
                product: "leather",
                productName: "皮革",
                productionTime: 120,  // 秒
                feedPerProduction: 6,
                baseValue: 90,
                category: "farm",
                sound: "default"
            },
            rabbit: {
                name: "家兔",
                image: "https://picsum.photos/id/1069/300/200",
                price: 300,
                product: "fur",
                productName: "兔毛",
                productionTime: 35,  // 秒
                feedPerProduction: 2,
                baseValue: 25,
                category: "farm",
                sound: "default"
            },
            
            // 家禽类
            chicken: {
                name: "肉鸡",
                image: "https://picsum.photos/id/1025/300/200",
                price: 200,
                product: "meat",
                productName: "鸡肉",
                productionTime: 30,  // 秒
                feedPerProduction: 2,
                baseValue: 20,
                category: "poultry",
                sound: "chicken"
            },
            layer: {
                name: "蛋鸡",
                image: "https://picsum.photos/id/235/300/200",
                price: 250,
                product: "eggs",
                productName: "鸡蛋",
                productionTime: 25,  // 秒
                feedPerProduction: 2,
                baseValue: 18,
                category: "poultry",
                sound: "chicken"
            },
            duck: {
                name: "鸭子",
                image: "https://picsum.photos/id/1060/300/200",
                price: 350,
                product: "eggs",
                productName: "鸭蛋",
                productionTime: 35,  // 秒
                feedPerProduction: 3,
                baseValue: 28,
                category: "poultry",
                sound: "duck"
            },
            goose: {
                name: "鹅",
                image: "https://picsum.photos/id/1071/300/200",
                price: 500,
                product: "feathers",
                productName: "鹅毛",
                productionTime: 50,  // 秒
                feedPerProduction: 4,
                baseValue: 38,
                category: "poultry",
                sound: "duck" // 使用鸭子的声音作为近似
            },
            turkey: {
                name: "火鸡",
                image: "https://picsum.photos/id/1072/300/200",
                price: 650,
                product: "meat",
                productName: "火鸡肉",
                productionTime: 80,  // 秒
                feedPerProduction: 5,
                baseValue: 55,
                category: "poultry",
                sound: "default"
            },
            quail: {
                name: "鹌鹑",
                image: "https://picsum.photos/id/1073/300/200",
                price: 150,
                product: "eggs",
                productName: "鹌鹑蛋",
                productionTime: 20,  // 秒
                feedPerProduction: 1,
                baseValue: 15,
                category: "poultry",
                sound: "default"
            },
            
            // 水产类
            fish: {
                name: "鲤鱼",
                image: "https://picsum.photos/id/1029/300/200",
                price: 400,
                product: "fish",
                productName: "鱼肉",
                productionTime: 60,  // 秒
                feedPerProduction: 3,
                baseValue: 30,
                category: "aquatic",
                sound: "fish"
            },
            shrimp: {
                name: "对虾",
                image: "https://picsum.photos/id/1080/300/200",
                price: 700,
                product: "shrimp",
                productName: "虾仁",
                productionTime: 70,  // 秒
                feedPerProduction: 4,
                baseValue: 60,
                category: "aquatic",
                sound: "fish" // 使用鱼的声音作为近似
            },
            crab: {
                name: "螃蟹",
                image: "https://picsum.photos/id/1081/300/200",
                price: 900,
                product: "crab",
                productName: "蟹肉",
                productionTime: 90,  // 秒
                feedPerProduction: 5,
                baseValue: 80,
                category: "aquatic",
                sound: "default"
            },
            frog: {
                name: "牛蛙",
                image: "https://picsum.photos/id/1082/300/200",
                price: 500,
                product: "frog",
                productName: "蛙肉",
                productionTime: 50,  // 秒
                feedPerProduction: 3,
                baseValue: 40,
                category: "aquatic",
                sound: "default"
            },
            oyster: {
                name: "牡蛎",
                image: "https://picsum.photos/id/1083/300/200",
                price: 600,
                product: "pearls",
                productName: "珍珠",
                productionTime: 120,  // 秒
                feedPerProduction: 2,
                baseValue: 75,
                category: "aquatic",
                sound: "default"
            },
            snail: {
                name: "田螺",
                image: "https://picsum.photos/id/1085/300/200",
                price: 200,
                product: "snail",
                productName: "螺肉",
                productionTime: 40,  // 秒
                feedPerProduction: 1,
                baseValue: 18,
                category: "aquatic",
                sound: "default"
            },
            
            // 特种养殖
            bee: {
                name: "蜜蜂",
                image: "https://picsum.photos/id/1086/300/200",
                price: 450,
                product: "honey",
                productName: "蜂蜜",
                productionTime: 80,  // 秒
                feedPerProduction: 2,
                baseValue: 45,
                category: "special",
                sound: "bee"
            },
            silkworm: {
                name: "桑蚕",
                image: "https://picsum.photos/id/1087/300/200",
                price: 350,
                product: "silk",
                productName: "蚕丝",
                productionTime: 60,  // 秒
                feedPerProduction: 2,
                baseValue: 50,
                category: "special",
                sound: "default"
            },
            deer: {
                name: "梅花鹿",
                image: "https://picsum.photos/id/1088/300/200",
                price: 1800,
                product: "antlers",
                productName: "鹿茸",
                productionTime: 180,  // 秒
                feedPerProduction: 7,
                baseValue: 150,
                category: "special",
                sound: "default"
            },
            mushroom: {
                name: "香菇",
                image: "https://picsum.photos/id/1089/300/200",
                price: 300,
                product: "mushroom",
                productName: "蘑菇",
                productionTime: 50,  // 秒
                feedPerProduction: 2,
                baseValue: 30,
                category: "special",
                sound: "default"
            },
            bamboo: {
                name: "竹笋",
                image: "https://picsum.photos/id/1090/300/200",
                price: 250,
                product: "bamboo",
                productName: "竹笋",
                productionTime: 65,  // 秒
                feedPerProduction: 1,
                baseValue: 25,
                category: "special",
                sound: "default"
            },
            ginseng: {
                name: "人参",
                image: "https://picsum.photos/id/1091/300/200",
                price: 2000,
                product: "ginseng",
                productName: "人参",
                productionTime: 300,  // 秒
                feedPerProduction: 3,
                baseValue: 200,
                category: "special",
                sound: "default"
            }
        };
        
        // 产物名称映射
        const productNames = {
            eggs: "蛋类",
            milk: "奶类",
            wool: "羊毛",
            meat: "肉类",
            honey: "蜂蜜",
            feathers: "羽毛",
            silk: "丝绸",
            fish: "鱼类",
            shrimp: "虾类",
            crab: "蟹类",
            frog: "蛙类",
            snail: "螺类",
            mushroom: "蘑菇",
            bamboo: "竹笋",
            fur: "皮毛",
            leather: "皮革",
            pearls: "珍珠",
            antlers: "鹿茸",
            horns: "牛角",
            ginseng: "人参",
            herbs: "草药",
            wax: "蜂蜡",
            pollen: "花粉",
            larvae: "幼虫"
        };
        
        // 动物类别名称
        const categoryNames = {
            farm: "农场动物",
            poultry: "家禽类",
            aquatic: "水产类",
            special: "特种养殖"
        };
        
        // DOM元素
        const elements = {
            coins: document.getElementById('coins'),
            feed: document.getElementById('feed'),
            level: document.getElementById('level'),
            currentExp: document.getElementById('current-exp'),
            nextLevelExp: document.getElementById('next-level-exp'),
            expBar: document.getElementById('exp-bar'),
            animalGrid: document.getElementById('animal-grid'),
            harvestLog: document.getElementById('harvest-log'),
            currentCapacity: document.getElementById('current-capacity'),
            nextCapacity: document.getElementById('next-capacity'),
            capacityUpgradeCost: document.getElementById('capacity-upgrade-cost'),
            efficiencyUpgrades: document.getElementById('efficiency-upgrades'),
            inventory: document.getElementById('inventory'),
            dailyQuestsContainer: document.getElementById('daily-quests-container'),
            growthQuestsContainer: document.getElementById('growth-quests-container'),
            achievementsContainer: document.getElementById('achievements-container'),
            notification: document.getElementById('notification'),
            notificationMessage: document.getElementById('notification-message'),
            acceleratorCount: document.getElementById('accelerator-count'),
            backpackProducts: document.getElementById('backpack-products'),
            
            // 动物分类容器
            farmAnimals: document.getElementById('farm-animals'),
            poultryAnimals: document.getElementById('poultry-animals'),
            aquaticAnimals: document.getElementById('aquatic-animals'),
            specialAnimals: document.getElementById('special-animals')
        };
        
        // 视图切换
        document.getElementById('btn-farm').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('farm-view');
        });
        document.getElementById('btn-shop').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('shop-view');
            renderAnimalShop();
        });
        document.getElementById('btn-upgrades').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('upgrades-view');
            renderEfficiencyUpgrades();
        });
        document.getElementById('btn-quests').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('quests-view');
            renderQuests();
            renderAchievements();
        });
        document.getElementById('btn-market').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('market-view');
            updateInventory();
            renderMarketPrices();
        });
        document.getElementById('btn-backpack').addEventListener('click', () => {
            sounds.play(sounds.click);
            showView('backpack-view');
            renderBackpack();
        });
        
        // 音效开关
        document.getElementById('btn-sound').addEventListener('click', () => {
            sounds.toggleMute();
        });
        
        // 使用加速剂
        document.getElementById('use-accelerator').addEventListener('click', () => {
            if (gameData.consumables.accelerator <= 0) {
                showNotification('没有加速剂可以使用！', 'error');
                return;
            }
            
            sounds.play(sounds.click);
            
            // 使用加速剂
            gameData.consumables.accelerator -= 1;
            
            // 对所有动物使用加速剂
            gameData.animals.forEach(animal => {
                const animalInfo = animalTypes[animal.type];
                const timePassed = (new Date().getTime() - animal.lastHarvest) / 1000;
                const remainingTime = Math.max(0, animalInfo.productionTime - timePassed);
                animal.lastHarvest = new Date().getTime() - (timePassed + remainingTime / 2) * 1000;
            });
            
            elements.acceleratorCount.textContent = gameData.consumables.accelerator;
            renderAnimals();
            showNotification('成功使用了加速剂，所有动物生长时间减少50%！');
        });
        
        function showView(viewId) {
            // 隐藏所有视图
            document.querySelectorAll('section[id$="-view"]').forEach(section => {
                section.classList.add('hidden');
            });
            
            // 显示目标视图
            document.getElementById(viewId).classList.remove('hidden');
        }
        
        // 渲染动物商店
        function renderAnimalShop() {
            // 清空所有分类容器
            elements.farmAnimals.innerHTML = '';
            elements.poultryAnimals.innerHTML = '';
            elements.aquaticAnimals.innerHTML = '';
            elements.specialAnimals.innerHTML = '';
            
            // 按类别渲染动物
            Object.keys(animalTypes).forEach(type => {
                const animal = animalTypes[type];
                const card = createAnimalShopCard(type, animal);
                
                switch(animal.category) {
                    case "farm":
                        elements.farmAnimals.appendChild(card);
                        break;
                    case "poultry":
                        elements.poultryAnimals.appendChild(card);
                        break;
                    case "aquatic":
                        elements.aquaticAnimals.appendChild(card);
                        break;
                    case "special":
                        elements.specialAnimals.appendChild(card);
                        break;
                }
            });
            
            // 添加购买事件监听
            document.querySelectorAll('.buy-animal-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const animalType = button.getAttribute('data-animal');
                    buyAnimal(animalType);
                });
            });
        }
        
        // 创建动物商店卡片
        function createAnimalShopCard(type, animal) {
            const card = document.createElement('div');
            card.className = 'animal-card';
            
            card.innerHTML = `
                <img src="${animal.image}" alt="${animal.name}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="font-bold text-lg flex items-center">
                        <i class="fa fa-paw text-green-500 mr-2"></i>${animal.name}
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">产出${animal.productName}，每${animal.productionTime}秒一次</p>
                    <div class="flex justify-between items-center">
                        <span class="text-accent font-bold"><i class="fa fa-money mr-1"></i>${animal.price}</span>
                        <button class="btn-primary py-1 px-3 text-sm buy-animal-btn" data-animal="${type}">购买</button>
                    </div>
                </div>
            `;
            
            return card;
        }
        
        // 购买动物
        function buyAnimal(type) {
            const animal = animalTypes[type];
            
            // 检查是否有足够的金币
            if (gameData.coins < animal.price) {
                showNotification('金币不足，无法购买！', 'error');
                return;
            }
            
            // 检查容量
            if (gameData.animals.length >= gameData.maxCapacity) {
                showNotification('农场已满，请升级容量！', 'error');
                return;
            }
            
            sounds.play(sounds.purchase);
            
            // 扣除金币
            gameData.coins -= animal.price;
            
            // 添加动物
            const newAnimal = {
                id: Date.now(),
                type: type,
                lastHarvest: new Date().getTime(),
                growth: 0,
                isReady: false
            };
            
            gameData.animals.push(newAnimal);
            
            // 记录已解锁的动物类型和类别
            if (!gameData.unlockedAnimals.has(type)) {
                gameData.unlockedAnimals.add(type);
                updateQuestProgress('unlock', '', 1);
                
                // 检查是否解锁了新类别
                const category = animal.category;
                if (!gameData.unlockedCategories.has(category)) {
                    // 检查该类别下是否有至少一种动物被解锁
                    const categoryAnimals = Object.values(animalTypes).filter(a => a.category === category);
                    const hasUnlocked = categoryAnimals.some(a => gameData.unlockedAnimals.has(a.type));
                    
                    if (hasUnlocked) {
                        gameData.unlockedCategories.add(category);
                    }
                }
                
                // 更新类别任务进度
                updateCategoryQuestProgress();
            }
            
            // 更新任务进度
            updateQuestProgress('buy', type, 1);
            
            // 更新成就
            checkAchievements();
            
            // 更新UI
            updateResources();
            renderAnimals();
            
            showNotification(`成功购买了一只${animal.name}！`);
        }
        
        // 购买物品
        document.querySelectorAll('[data-item]').forEach(button => {
            button.addEventListener('click', () => {
                const item = button.getAttribute('data-item');
                const amount = parseInt(button.getAttribute('data-amount'));
                const cost = parseInt(button.getAttribute('data-cost'));
                
                if (gameData.coins < cost) {
                    showNotification('金币不足，无法购买！', 'error');
                    return;
                }
                
                sounds.play(sounds.purchase);
                
                // 扣除金币
                gameData.coins -= cost;
                
                // 添加物品
                if (item === 'feed') {
                    gameData.feed += amount;
                } else if (item === 'speed') {
                    gameData.consumables.accelerator += amount;
                    elements.acceleratorCount.textContent = gameData.consumables.accelerator;
                }
                
                updateResources();
                renderAnimals();
                renderBackpack();
                
                showNotification(`成功购买了${item === 'feed' ? '饲料包' : '加速剂'}！`);
            });
        });
        
        // 收获产品
        function harvestProduct(animalId) {
            const animal = gameData.animals.find(a => a.id === animalId);
            if (!animal || !animal.isReady) return;
            
            const animalInfo = animalTypes[animal.type];
            
            // 检查是否有足够的饲料
            if (gameData.feed < animalInfo.feedPerProduction) {
                showNotification('饲料不足，无法继续生产！', 'error');
                return;
            }
            
            sounds.play(sounds.collect);
            
            // 消耗饲料
            gameData.feed -= animalInfo.feedPerProduction;
            
            // 增加产品
            gameData.inventory[animalInfo.product] += 1;
            
            // 更新任务进度
            updateQuestProgress('collect', animalInfo.product, 1);
            
            // 更新成就
            gameData.achievements.find(a => a.id === 2).current += 1;
            checkAchievements();
            
            // 增加经验
            addExperience(10);
            
            // 记录收获
            addToHarvestLog(`收获了1个${animalInfo.productName}`);
            
            // 重置动物状态
            animal.lastHarvest = new Date().getTime();
            animal.isReady = false;
            animal.growth = 0;
            
            // 更新UI
            updateResources();
            renderAnimals();
            updateInventory();
            renderBackpack();
            renderAchievements();
            
            showNotification(`收获了1个${animalInfo.productName}！`);
        }
        
        // 出售产品
        function sellProduct(type) {
            const amount = gameData.inventory[type];
            if (amount <= 0) {
                showNotification('没有可出售的产品！', 'error');
                return;
            }
            
            sounds.play(sounds.purchase);
            
            // 找出基础价格
            let basePrice = 0;
            // 找到第一个生产该产品的动物作为基准价格
            Object.values(animalTypes).some(animal => {
                if (animal.product === type) {
                    basePrice = animal.baseValue;
                    return true;
                }
                return false;
            });
            
            // 计算价格（考虑效率加成）
            const efficiency = gameData.efficiency[type];
            const totalPrice = Math.round(amount * basePrice * (1 + efficiency / 100));
            
            // 更新金币和库存
            gameData.coins += totalPrice;
            gameData.inventory[type] = 0;
            
            // 更新任务进度
            updateQuestProgress('sell', type, amount);
            gameData.quests.growth.find(q => q.id === 108).current += totalPrice;
            gameData.achievements.find(a => a.id === 6).current += totalPrice;
            
            // 更新成就
            checkAchievements();
            
            // 增加经验
            addExperience(amount * 5);
            
            // 记录收获
            addToHarvestLog(`出售了${amount}个${productNames[type]}，获得${totalPrice}金币`);
            
            // 更新UI
            updateResources();
            updateInventory();
            renderBackpack();
            renderAchievements();
            renderQuests();
            
            showNotification(`成功出售${amount}个${productNames[type]}，获得${totalPrice}金币！`);
        }
        
        // 升级农场容量
        document.getElementById('upgrade-capacity').addEventListener('click', () => {
            const currentCapacity = gameData.maxCapacity;
            const nextCapacity = currentCapacity + 12;
            const upgradeCost = Math.floor(currentCapacity * 250);  // 升级成本随容量增加
            
            if (gameData.coins < upgradeCost) {
                showNotification('金币不足，无法升级！', 'error');
                return;
            }
            
            sounds.play(sounds.upgrade);
            
            // 扣除金币并升级
            gameData.coins -= upgradeCost;
            gameData.maxCapacity = nextCapacity;
            
            // 更新任务进度
            updateQuestProgress('upgrade', '', 1);
            gameData.quests.growth.find(q => q.id === 104).current = nextCapacity;
            
            // 增加经验
            addExperience(50);
            
            // 更新UI
            updateResources();
            updateUpgradeUI();
            renderQuests();
            
            showNotification(`农场容量升级到${nextCapacity}！`);
        });
        
        // 渲染效率升级选项
        function renderEfficiencyUpgrades() {
            elements.efficiencyUpgrades.innerHTML = '';
            
            // 为每种产物创建升级选项
            Object.keys(productNames).forEach(product => {
                const upgradeEl = document.createElement('div');
                upgradeEl.className = "flex justify-between items-center p-3 border border-gray-200 rounded-lg";
                
                const level = gameData.efficiency[`${product}Level`];
                const efficiency = gameData.efficiency[product];
                const upgradeCost = Math.floor(level * 800 + (product === 'ginseng' || product === 'pearls' || product === 'antlers' ? 1000 : 0));
                
                upgradeEl.innerHTML = `
                    <div>
                        <h4 class="font-bold">${productNames[product]}效率</h4>
                        <p class="text-sm text-gray-600">当前等级: <span id="${product}-efficiency-level">${level}</span></p>
                        <p class="text-sm text-gray-600">效果: ${productNames[product]}价值提升 <span id="${product}-efficiency">${efficiency}</span>%</p>
                    </div>
                    <div class="text-right mr-4">
                        <p class="font-bold">升级费用</p>
                        <p class="text-accent"><i class="fa fa-money mr-1"></i><span id="${product}-upgrade-cost">${upgradeCost}</span></p>
                    </div>
                    <button class="btn-accent upgrade-efficiency-btn" data-product="${product}">升级</button>
                `;
                
                elements.efficiencyUpgrades.appendChild(upgradeEl);
            });
            
            // 添加升级事件监听
            document.querySelectorAll('.upgrade-efficiency-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const product = button.getAttribute('data-product');
                    upgradeEfficiency(product);
                });
            });
        }
        
        // 升级产品效率
        function upgradeEfficiency(product) {
            const currentLevel = gameData.efficiency[`${product}Level`];
            const upgradeCost = Math.floor(currentLevel * 800 + (product === 'ginseng' || product === 'pearls' || product === 'antlers' ? 1000 : 0));
            
            if (gameData.coins < upgradeCost) {
                showNotification('金币不足，无法升级！', 'error');
                return;
            }
            
            sounds.play(sounds.upgrade);
            
            // 扣除金币并升级
            gameData.coins -= upgradeCost;
            gameData.efficiency[`${product}Level`] += 1;
            gameData.efficiency[product] += 5;  // 每次升级增加5%效率
            
            // 更新任务进度
            updateQuestProgress('upgrade', '', 1);
            
            // 检查所有产物效率是否达到5级
            checkAllEfficiencyLevels();
            
            // 增加经验
            addExperience(30);
            
            // 更新UI
            updateResources();
            renderEfficiencyUpgrades();
            renderQuests();
            
            showNotification(`${productNames[product]}效率升级到${gameData.efficiency[`${product}Level`]}级！`);
        }
        
        // 检查所有产物效率是否达到指定等级
        function checkAllEfficiencyLevels() {
            const targetLevel = gameData.quests.growth.find(q => q.id === 106).target;
            let allReached = true;
            
            Object.keys(productNames).forEach(product => {
                if (gameData.efficiency[`${product}Level`] < targetLevel) {
                    allReached = false;
                }
            });
            
            if (allReached) {
                gameData.quests.growth.find(q => q.id === 106).current = targetLevel;
            } else {
                // 计算已达到目标等级的产物数量
                let count = 0;
                Object.keys(productNames).forEach(product => {
                    if (gameData.efficiency[`${product}Level`] >= targetLevel) {
                        count++;
                    }
                });
                gameData.quests.growth.find(q => q.id === 106).current = count;
            }
        }
        
        // 完成任务
        function completeQuest(questId, type) {
            const quest = gameData.quests[type].find(q => q.id === questId);
            if (!quest || quest.completed) return;
            
            sounds.play(sounds.complete);
            
            // 标记任务为已完成并给予奖励
            quest.completed = true;
            gameData.coins += quest.reward;
            
            // 更新成就进度
            if (type === 'daily') {
                gameData.achievements.find(a => a.id === 8).current += 1;
                checkAchievements();
            }
            
            // 增加经验
            addExperience(20);
            
            // 更新UI
            updateResources();
            renderQuests();
            renderAchievements();
            
            showNotification(`完成任务"${quest.title}"，获得${quest.reward}金币奖励！`);
        }
        
        // 更新任务进度
        function updateQuestProgress(type, target, amount) {
            // 更新每日任务
            gameData.quests.daily.forEach(quest => {
                updateSingleQuestProgress(quest, type, target, amount);
            });
            
            // 更新成长任务
            gameData.quests.growth.forEach(quest => {
                updateSingleQuestProgress(quest, type, target, amount);
            });
        }
        
        // 更新单个任务进度
        function updateSingleQuestProgress(quest, type, target, amount) {
            if (!quest.completed && quest.type === type) {
                // 特殊处理解锁动物和出售任意产物的任务
                if ((type === 'unlock' && quest.type === 'unlock') || 
                    (type === 'sell' && quest.type === 'sell' && quest.item === '')) {
                    quest.current += amount;
                } 
                // 普通任务
                else if (quest[type === 'buy' ? 'animal' : 'item'] === target || quest[type === 'buy' ? 'animal' : 'item'] === '') {
                    quest.current += amount;
                }
                
                if (quest.current >= quest.target) {
                    quest.current = quest.target;
                }
            }
        }
        
        // 更新类别任务进度
        function updateCategoryQuestProgress() {
            // 检查每个类别任务
            gameData.quests.growth.forEach(quest => {
                if (quest.type === 'category' && !quest.completed) {
                    // 计算该类别下的动物总数
                    const categoryAnimals = Object.values(animalTypes).filter(a => a.category === quest.category);
                    const totalInCategory = categoryAnimals.length;
                    
                    // 计算已解锁的数量
                    let unlockedInCategory = 0;
                    categoryAnimals.forEach(animal => {
                        if (gameData.unlockedAnimals.has(animal.type)) {
                            unlockedInCategory++;
                        }
                    });
                    
                    quest.current = unlockedInCategory;
                    quest.target = totalInCategory;
                }
            });
        }
        
        // 检查成就
        function checkAchievements() {
            // 养殖新手
            if (!gameData.achievements[0].completed && gameData.animals.length > 0) {
                completeAchievement(1);
            }
            
            // 勤劳的农夫
            if (!gameData.achievements[1].completed && 
                gameData.achievements[1].current >= gameData.achievements[1].target) {
                completeAchievement(2);
            }
            
            // 富有的农场主
            if (!gameData.achievements[2].completed && gameData.coins >= 20000) {
                completeAchievement(3);
            }
            
            // 动物收藏家
            if (!gameData.achievements[3].completed && gameData.unlockedAnimals.size >= 10) {
                completeAchievement(4);
            }
            
            // 全品类大师
            if (!gameData.achievements[4].completed && gameData.unlockedAnimals.size >= Object.keys(animalTypes).length) {
                completeAchievement(5);
            }
            
            // 市场大亨
            if (!gameData.achievements[5].completed && 
                gameData.achievements[5].current >= gameData.achievements[5].target) {
                completeAchievement(6);
            }
            
            // 升级达人 - 所有产物效率达到10级
            if (!gameData.achievements[6].completed) {
                let allReached = true;
                Object.keys(productNames).forEach(product => {
                    if (gameData.efficiency[`${product}Level`] < 10) {
                        allReached = false;
                    }
                });
                if (allReached) {
                    completeAchievement(7);
                }
            }
            
            // 任务专家
            if (!gameData.achievements[7].completed && 
                gameData.achievements[7].current >= gameData.achievements[7].target) {
                completeAchievement(8);
            }
            
            // 农场大亨
            if (!gameData.achievements[8].completed && gameData.maxCapacity >= 100) {
                completeAchievement(9);
            }
            
            // 养殖大师
            if (!gameData.achievements[9].completed && gameData.level >= 20) {
                completeAchievement(10);
            }
            
            // 等级任务
            gameData.quests.growth.forEach(quest => {
                if (quest.type === 'level' && !quest.completed) {
                    quest.current = gameData.level;
                }
            });
            
            // 金币任务
            gameData.quests.growth.forEach(quest => {
                if (quest.type === 'coins' && !quest.completed) {
                    quest.current = gameData.coins;
                }
            });
        }
        
        // 完成成就
        function completeAchievement(achievementId) {
            const achievement = gameData.achievements.find(a => a.id === achievementId);
            if (!achievement || achievement.completed) return;
            
            sounds.play(sounds.complete);
            
            achievement.completed = true;
            gameData.coins += achievement.reward;
            
            // 增加经验
            addExperience(50);
            
            showNotification(`解锁成就"${achievement.title}"，获得${achievement.reward}金币奖励！`);
        }
        
        // 添加经验
        function addExperience(amount) {
            gameData.exp += amount;
            
            // 检查是否升级
            while (gameData.exp >= gameData.nextLevelExp) {
                // 升级
                gameData.level += 1;
                gameData.exp -= gameData.nextLevelExp;
                gameData.nextLevelExp = Math.floor(gameData.nextLevelExp * 1.5);
                
                // 升级奖励
                gameData.coins += 100 * gameData.level;
                gameData.feed += 20 * gameData.level;
                
                showNotification(`恭喜升级到${gameData.level}级！获得${100 * gameData.level}金币和${20 * gameData.level}饲料奖励！`);
                
                // 更新任务进度
                updateQuestProgress('level', '', 0);
                checkAchievements();
            }
            
            updateResources();
            renderQuests();
        }
        
        // 添加收获记录
        function addToHarvestLog(message) {
            const time = new Date().toLocaleTimeString();
            gameData.harvestLog.unshift(`[${time}] ${message}`);
            
            // 限制日志长度
            if (gameData.harvestLog.length > 15) {
                gameData.harvestLog.pop();
            }
            
            renderHarvestLog();
        }
        
        // 渲染动物
        function renderAnimals() {
            elements.animalGrid.innerHTML = '';
            
            if (gameData.animals.length === 0) {
                elements.animalGrid.innerHTML = `
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fa fa-paw text-4xl mb-2"></i>
                        <p>你的农场还是空的，去商店购买动物吧！</p>
                    </div>
                `;
                return;
            }
            
            gameData.animals.forEach(animal => {
                const animalInfo = animalTypes[animal.type];
                const card = document.createElement('div');
                card.className = 'animal-card';
                
                const timePassed = (new Date().getTime() - animal.lastHarvest) / 1000;
                const progress = Math.min(100, (timePassed / animalInfo.productionTime) * 100);
                const wasReady = animal.isReady;
                animal.growth = progress;
                animal.isReady = progress >= 100;
                
                // 如果动物刚刚准备好收获，播放对应声音
                if (!wasReady && animal.isReady) {
                    sounds.play(animalInfo.sound);
                }
                
                card.innerHTML = `
                    <img src="${animalInfo.image}" alt="${animalInfo.name}" class="w-full h-32 object-cover">
                    <div class="p-3">
                        <h3 class="font-bold text-center mb-2">${animalInfo.name}</h3>
                        <div class="progress-bar mb-1">
                            <div class="progress-value" style="width: ${progress}%"></div>
                        </div>
                        <p class="text-xs text-center mb-2">
                            ${animal.isReady ? 
                                '<span class="text-green-600">可以收获了！</span>' : 
                                `<span>${Math.ceil(animalInfo.productionTime - timePassed)}秒后可收获</span>`
                            }
                        </p>
                        <button class="btn-primary w-full py-1 text-sm ${!animal.isReady ? 'opacity-50 cursor-not-allowed' : ''}" 
                                onclick="${animal.isReady ? `harvestProduct(${animal.id})` : ''}">
                            ${animal.isReady ? `收获${animalInfo.productName}` : '等待中'}
                        </button>
                    </div>
                `;
                
                elements.animalGrid.appendChild(card);
            });
        }
        
        // 渲染收获日志
        function renderHarvestLog() {
            elements.harvestLog.innerHTML = '';
            
            if (gameData.harvestLog.length === 0) {
                elements.harvestLog.innerHTML = '<p class="text-gray-500 italic">暂无收获记录</p>';
                return;
            }
            
            gameData.harvestLog.forEach(entry => {
                const p = document.createElement('p');
                p.textContent = entry;
                elements.harvestLog.appendChild(p);
            });
        }
        
        // 渲染任务
        function renderQuests() {
            // 渲染每日任务
            elements.dailyQuestsContainer.innerHTML = '';
            gameData.quests.daily.forEach(quest => {
                renderSingleQuest(quest, 'daily');
            });
            
            // 渲染成长任务
            elements.growthQuestsContainer.innerHTML = '';
            gameData.quests.growth.forEach(quest => {
                renderSingleQuest(quest, 'growth');
            });
        }
        
        // 渲染单个任务
        function renderSingleQuest(quest, type) {
            const questEl = document.createElement('div');
            questEl.className = `flex flex-col ${quest.completed ? 'opacity-70' : ''}`;
            
            let progress = 0;
            // 特殊处理不同类型任务的进度计算
            if (quest.type === 'level' || quest.type === 'coins' || quest.type === 'capacity' || quest.type === 'sellvalue') {
                progress = Math.min(100, (quest.current / quest.target) * 100);
            } else {
                progress = Math.min(100, (quest.current / quest.target) * 100);
            }
            
            questEl.innerHTML = `
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold">${quest.title}</h3>
                    <span class="text-accent font-bold"><i class="fa fa-money mr-1"></i>${quest.reward}</span>
                </div>
                <div class="progress-bar mb-1">
                    <div class="progress-value" style="width: ${progress}%"></div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm">
                        ${quest.type === 'level' || quest.type === 'coins' || quest.type === 'capacity' || quest.type === 'sellvalue' ?
                            `${quest.current.toLocaleString()}/${quest.target.toLocaleString()}` : 
                            `${quest.current}/${quest.target}`
                        }
                    </span>
                    <button class="btn-primary py-1 px-3 text-sm ${!quest.completed || quest.current < quest.target ? 'opacity-50 cursor-not-allowed' : ''}"
                            onclick="${quest.completed || quest.current < quest.target ? '' : `completeQuest(${quest.id}, '${type}')`}">
                        ${quest.completed ? '已完成' : quest.current >= quest.target ? '领取奖励' : '进行中'}
                    </button>
                </div>
            `;
            
            if (type === 'daily') {
                elements.dailyQuestsContainer.appendChild(questEl);
            } else {
                elements.growthQuestsContainer.appendChild(questEl);
            }
        }
        
        // 渲染成就
        function renderAchievements() {
            elements.achievementsContainer.innerHTML = '';
            
            gameData.achievements.forEach(achievement => {
                const achievementEl = document.createElement('div');
                achievementEl.className = `flex justify-between items-center p-3 border border-gray-200 rounded-lg ${achievement.completed ? 'opacity-70' : ''}`;
                
                let progressHtml = '';
                if (achievement.id === 2 || achievement.id === 6 || achievement.id === 8) {  // 有进度的成就
                    const progress = Math.min(100, (achievement.current / achievement.target) * 100);
                    progressHtml = `
                        <div class="w-full mt-1">
                            <div class="progress-bar">
                                <div class="progress-value" style="width: ${progress}%"></div>
                            </div>
                            <span class="text-xs text-right block mt-1">${achievement.current}/${achievement.target}</span>
                        </div>
                    `;
                }
                
                achievementEl.innerHTML = `
                    <div class="flex-1 mr-4">
                        <h4 class="font-bold flex items-center">
                            ${achievement.completed ? 
                                '<i class="fa fa-trophy text-yellow-500 mr-2"></i>' : 
                                '<i class="fa fa-lock text-gray-400 mr-2"></i>'
                            }
                            ${achievement.title}
                        </h4>
                        <p class="text-sm text-gray-600">${achievement.description}</p>
                        ${progressHtml}
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold">奖励</p>
                        <p class="text-accent"><i class="fa fa-money mr-1"></i>${achievement.reward}</p>
                        ${achievement.completed ? '<span class="text-xs text-green-600">已解锁</span>' : ''}
                    </div>
                `;
                
                elements.achievementsContainer.appendChild(achievementEl);
            });
        }
        
        // 渲染背包
        function renderBackpack() {
            elements.backpackProducts.innerHTML = '';
            
            let hasProducts = false;
            
            // 显示所有农产品，包括数量为0但有对应动物的
            Object.keys(gameData.inventory).forEach(product => {
                // 检查是否有生产该产品的动物
                const hasProducer = Object.values(animalTypes).some(animal => animal.product === product);
                
                if (gameData.inventory[product] > 0 || hasProducer) {
                    hasProducts = true;
                    
                    const itemEl = document.createElement('div');
                    itemEl.className = "backpack-item";
                    
                    itemEl.innerHTML = `
                        <div class="flex items-center">
                            <i class="${getProductIcon(product)} text-xl mr-3"></i>
                            <span>${productNames[product]}</span>
                        </div>
                        <span class="font-bold">${gameData.inventory[product]}</span>
                    `;
                    
                    elements.backpackProducts.appendChild(itemEl);
                }
            });
            
            if (!hasProducts) {
                elements.backpackProducts.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <i class="fa fa-shopping-basket text-2xl mb-2"></i>
                        <p>你的背包里还没有农产品</p>
                    </div>
                `;
            }
            
            // 更新消耗品数量
            elements.acceleratorCount.textContent = gameData.consumables.accelerator;
        }
        
        // 更新资源显示
        function updateResources() {
            elements.coins.textContent = gameData.coins.toLocaleString();
            elements.feed.textContent = gameData.feed;
            elements.level.textContent = gameData.level;
            elements.currentExp.textContent = gameData.exp;
            elements.nextLevelExp.textContent = gameData.nextLevelExp;
            elements.expBar.style.width = `${(gameData.exp / gameData.nextLevelExp) * 100}%`;
            
            updateInventory();
            updateUpgradeUI();
        }
        
        // 更新库存显示
        function updateInventory() {
            elements.inventory.innerHTML = '';
            
            // 只显示有库存的产物
            let hasItems = false;
            Object.keys(gameData.inventory).forEach(product => {
                if (gameData.inventory[product] > 0 || Object.values(gameData.animals).some(a => animalTypes[a.type].product === product)) {
                    hasItems = true;
                    
                    // 找出基础价格
                    let basePrice = 0;
                    Object.values(animalTypes).some(animal => {
                        if (animal.product === product) {
                            basePrice = animal.baseValue;
                            return true;
                        }
                        return false;
                    });
                    
                    // 计算价格（考虑效率加成）
                    const efficiency = gameData.efficiency[product];
                    const sellPrice = Math.round(basePrice * (1 + efficiency / 100));
                    
                    const itemEl = document.createElement('div');
                    itemEl.className = "flex justify-between items-center p-3 border border-gray-200 rounded-lg";
                    
                    itemEl.innerHTML = `
                        <div class="flex items-center">
                            <i class="${getProductIcon(product)} text-2xl mr-3"></i>
                            <div>
                                <h4 class="font-bold">${productNames[product]}</h4>
                                <p class="text-sm text-gray-600">数量: <span>${gameData.inventory[product]}</span></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm">单价: <span>${sellPrice}</span> 金币</p>
                            <button class="btn-primary py-1 px-3 text-sm mt-1 sell-product-btn" data-product="${product}" 
                                    ${gameData.inventory[product] <= 0 ? 'disabled' : ''}>
                                全部出售
                            </button>
                        </div>
                    `;
                    
                    elements.inventory.appendChild(itemEl);
                }
            });
            
            if (!hasItems) {
                elements.inventory.innerHTML = `
                    <div class="col-span-full text-center py-6 text-gray-500">
                        <i class="fa fa-shopping-basket text-3xl mb-2"></i>
                        <p>你的库存是空的，去收获一些产物吧！</p>
                    </div>
                `;
            } else {
                // 添加出售事件监听
                document.querySelectorAll('.sell-product-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const product = button.getAttribute('data-product');
                        sellProduct(product);
                    });
                });
            }
        }
        
        // 获取产物对应的图标
        function getProductIcon(product) {
            const icons = {
                eggs: "fa-circle-o text-yellow-500",
                milk: "fa-tint text-blue-300",
                wool: "fa-cloud text-gray-300",
                meat: "fa-cutlery text-red-500",
                honey: "fa-star text-yellow-500",
                feathers: "fa-feather text-gray-200",
                silk: "fa-thread text-pink-300",
                fish: "fa-anchor text-blue-500",
                shrimp: "fa-anchor text-orange-500",
                crab: "fa-anchor text-red-600",
                frog: "fa-leaf text-green-500",
                snail: "fa-circle-o text-brown-500",
                mushroom: "fa-leaf text-gray-400",
                bamboo: "fa-tree text-green-700",
                fur: "fa-paw text-amber-700",
                leather: "fa-shield text-brown-600",
                pearls: "fa-diamond text-blue-200",
                antlers: "fa-sort-amount-asc text-brown-700",
                horns: "fa-sort-amount-asc text-gray-600",
                ginseng: "fa-leaf text-red-700",
                herbs: "fa-leaf text-green-600",
                wax: "fa-circle-o text-yellow-300",
                pollen: "fa-circle-o text-yellow-400",
                larvae: "fa-bug text-green-400"
            };
            
            return icons[product] || "fa-circle text-gray-500";
        }
        
        // 渲染市场价格
        function renderMarketPrices() {
            const marketContainer = elements.inventory.parentElement.nextElementSibling.querySelector('.space-y-2');
            marketContainer.innerHTML = '';
            
            // 为每种产物生成市场行情
            Object.keys(productNames).forEach(product => {
                // 随机生成价格趋势
                const trends = ['稳定', '上涨中', '下跌中'];
                const trend = trends[Math.floor(Math.random() * trends.length)];
                const trendClass = trend === '上涨中' ? 'text-green-600' : trend === '下跌中' ? 'text-red-600' : 'text-gray-600';
                const trendIcon = trend === '上涨中' ? 'fa-arrow-up' : trend === '下跌中' ? 'fa-arrow-down' : 'fa-minus';
                
                const priceEl = document.createElement('div');
                priceEl.className = "flex justify-between items-center p-2 border-b border-gray-100";
                
                priceEl.innerHTML = `
                    <span>${productNames[product]}价格</span>
                    <span class="${trendClass}"><i class="fa ${trendIcon} mr-1"></i>${trend}</span>
                `;
                
                marketContainer.appendChild(priceEl);
            });
        }
        
        // 更新升级界面
        function updateUpgradeUI() {
            elements.currentCapacity.textContent = gameData.maxCapacity;
            elements.nextCapacity.textContent = gameData.maxCapacity + 12;
            elements.capacityUpgradeCost.textContent = Math.floor(gameData.maxCapacity * 250).toLocaleString();
        }
        
        // 显示通知
        function showNotification(message, type = 'success') {
            elements.notificationMessage.textContent = message;
            elements.notification.className = `notification ${type === 'error' ? 'border-l-4 border-danger' : 'border-l-4 border-primary'}`;
            elements.notification.classList.add('show');
            
            setTimeout(() => {
                elements.notification.classList.remove('show');
            }, 3000);
        }
        
        // 定时更新动物状态
        setInterval(() => {
            renderAnimals();
        }, 1000);
        
        // 定时更新市场价格
        setInterval(() => {
            if (!document.getElementById('market-view').classList.contains('hidden')) {
                renderMarketPrices();
            }
        }, 3600000); // 每小时更新一次
        
        // 初始化游戏
        function initGame() {
            // 尝试播放背景音乐
            sounds.bgMusic.play().catch(e => console.log("Background music autoplay prevented:", e));
            
            updateResources();
            renderAnimals();
            renderHarvestLog();
            renderQuests();
            renderAchievements();
            renderEfficiencyUpgrades();
            renderBackpack();
            
            // 给新玩家的提示
            setTimeout(() => {
                showNotification('欢迎来到欢乐养殖场！点击商店购买你的第一只动物吧！');
            }, 1000);
        }
        
        // 启动游戏
        initGame();
    </script>
</body>
</html>
