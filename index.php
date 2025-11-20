<!DOCTYPE html>
<html lang="zh-CN" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的作品集网站</title>
    <!-- 加载 Tailwind CSS 来快速实现美观的样式 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // 启用 class 策略的暗模式
        }
    </script>
    <style>
        /* 使用 Inter 字体 */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        /* 简单的卡片悬停效果 - 增强版 */
        .portfolio-card {
            transition: all 0.3s ease-in-out;
        }
        .portfolio-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        /* 首页英雄部分的淡入动画 */
        .hero-fade-in {
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* 通用滚动触发动画 */
        .fade-in-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }
        .fade-in-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* 表单输入焦点动画 */
        input:focus, textarea:focus {
            transform: scale(1.01); 
            transition: transform 0.2s ease-in-out;
        }
        
        /* 暗模式调整 */
        html.dark .bg-gray-100 { background-color: #1f2937; color: #f9fafb; } 
        html.dark .text-gray-800 { color: #f9fafb; }
        html.dark .bg-white { background-color: #374151; }
        html.dark .text-gray-600 { color: #d1d5db; }
        html.dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.1), 0 4px 6px -2px rgba(255, 255, 255, 0.05); }
        html.dark .bg-gray-800 { background-color: #111827; }
        html.dark .text-gray-300 { color: #9ca3af; }
        html.dark .bg-blue-600 { background-color: #1d4ed8; }
        html.dark .hover\:bg-blue-700:hover { background-color: #1e40af; }
        html.dark .bg-gray-200 { background-color: #4b5563; }
        html.dark .hover\:bg-gray-300:hover { background-color: #6b7280; }
        html.dark .text-gray-900 { color: #e5e7eb; }
        html.dark .dark\:text-gray-100 { color: #f3f4f6; }
        html.dark .dark\:text-blue-400 { color: #60a5fa; }
        html.dark .dark\:hover\:text-blue-300:hover { color: #93c5fd; }
        html.dark .dark\:text-green-400 { color: #4ade80; }
        html.dark .dark\:hover\:text-green-300:hover { color: #86efac; }
        html.dark .dark\:bg-gray-700 { background-color: #374151; }
        html.dark .dark\:bg-gray-600 { background-color: #4b5563; }
        html.dark .dark\:text-gray-200 { color: #e5e7eb; }
        html.dark .dark\:hover\:bg-gray-500:hover { background-color: #6b7280; }
        html.dark .dark\:bg-gray-900 { background-color: #111827; }
        html.dark .dark\:text-gray-400 { color: #9ca3af; }
        html.dark .dark\:hover\:text-gray-300:hover { color: #d1d5db; }

        /* 动态粒子背景样式 */
        #particle-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* 确保在内容后面 */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- 新增：动态粒子背景画布 -->
    <canvas id="particle-canvas"></canvas>

    <!-- 1. 导航栏 -->
    <nav class="bg-white shadow-md sticky top-0 z-50 dark:bg-gray-800">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="?page=home" class="text-2xl font-bold text-gray-900 hover:scale-110 transition-transform duration-300 nav-link dark:text-gray-100" data-page="home"><img src="logo.jpg" alt="我的Logo" class="h-[60px] w-auto inline-block">

            <div class="space-x-4 flex items-center">
                <!-- 导航链接 (使用 nav-link class 触发 SPA 路由) -->
                <a href="?page=home" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 nav-link dark:text-gray-300 dark:hover:text-gray-100" data-page="home">首页</a>
                <a href="?page=portfolio" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 nav-link dark:text-gray-300 dark:hover:text-gray-100" data-page="portfolio">作品集</a>
                <a href="?page=about" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 nav-link dark:text-gray-300 dark:hover:text-gray-100" data-page="about">关于我</a>
                <a href="?page=contact" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 nav-link dark:text-gray-300 dark:hover:text-gray-100" data-page="contact">联系我</a>
                
                <!-- 登入链接：不使用 nav-link class，保证进行完整的浏览器重定向 -->
                <a href="login.php" class="text-gray-600 hover:text-gray-900 transition-colors duration-300 dark:text-gray-300 dark:hover:text-gray-100">登入</a>
                
                <!-- 暗模式切换按钮 -->
                <button id="theme-toggle" class="ml-4 text-gray-600 hover:text-gray-900 transition-colors duration-300 dark:text-gray-300 dark:hover:text-gray-100">
                    <span class="sr-only">切换主题</span>
                    <svg id="light-icon" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg id="dark-icon" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- 2. 主体内容 (将由 JS 填充) -->
    <main class="container mx-auto px-6 py-12 min-h-screen" id="main-content">
        <!-- 内容将由 JavaScript 动态插入这里 -->
    </main>

    <!-- 3. 页脚 (使用 JS 动态设置年份) -->
    <footer class="bg-gray-800 text-gray-300 py-8 mt-12 dark:bg-gray-900">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; <span id="current-year"></span> 我的作品集网站. 保留所有权利.</p>
        </div>
    </footer>


    <!-- 增强的 JavaScript -->
    <script>
        // --- 1. 数据 (已移除详情模态框所需的数据) ---

        const projects = [
            { title: '项目一：电商网站', category: 'Web Development', description: '一个完整的在线购物平台，使用 Laravel 和 Vue.js 构建。', img: 'https://placehold.co/600x400/3498db/ffffff?text=电商平台', url: 'project1.php' },
            { title: '项目二：移动应用', category: 'Mobile Development', description: '一个跨平台的任务管理应用。', img: 'https://placehold.co/600x400/2ecc71/ffffff?text=移动应用', url: 'project2.php' },
            { title: '项目三：数据可视化', category: 'Data & Analytics', description: '使用 D3.js 展示复杂的销售数据。', img: 'https://placehold.co/600x400/e74c3c/ffffff?text=数据可视化', url: 'project3.php' },
            { title: '项目四：个人博客', category: 'Web Development', description: '一个使用 PHP 和 MySQL 搭建的博客。', img: 'https://placehold.co/600x400/f39c12/ffffff?text=个人博客', url: 'project4.php' },
            { title: '项目五：AI 聊天机器人', category: 'AI/ML Integration', description: '集成 OpenAI API 的智能客服机器人。', img: 'https://placehold.co/600x400/9b59b6/ffffff?text=AI机器人', url: 'project5.php' },
            { title: '项目六：机器学习预测应用', category: 'Machine Learning', description: '基于 ML 的房价预测工具。', img: 'https://placehold.co/600x400/ff9900/ffffff?text=ML预测', url: 'project6.php' },
            { title: '项目七：游戏开发', category: 'Game Development', description: 'Unity 2D 冒险游戏。', img: 'https://placehold.co/600x400/00ff00/ffffff?text=游戏', url: 'project7.php' },
            { title: '项目八：区块链投票系统', category: 'Blockchain Development', description: '去中心化投票 DApp，使用 Ethereum 和 Solidity。', img: 'https://placehold.co/600x400/4b0082/ffffff?text=区块链投票', url: 'project8.php' }
        ];

        // 页面内容的 HTML 模板 (新增 about 和 contact 页面)
        const pageTemplates = {
            'home': `
                <div class="text-center py-20 hero-fade-in">
                    <h1 class="text-6xl font-bold text-gray-900 mb-4 fade-in-scroll dark:text-gray-100">欢迎来到我的网站</h1>
                    <p class="text-2xl text-gray-600 mb-8 fade-in-scroll dark:text-gray-300">我是一名开发者和设计师，热衷于构建出色的 Web 应用。</p>
                    <a href="?page=portfolio" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-all duration-300 hover:shadow-lg transform hover:scale-105 nav-link dark:bg-blue-700 dark:hover:bg-blue-800" data-page="portfolio">查看我的作品</a>
                    <a href="login.php" class="ml-4 bg-gray-200 text-gray-800 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-300 transition-all duration-300 hover:shadow-lg transform hover:scale-105 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">登录 / 更多</a>
                </div>
            `,
            'portfolio': `
                <h1 class="text-4xl font-bold mb-8 text-center fade-in-scroll dark:text-gray-100">我的作品集</h1>
                <p class="text-xl text-gray-600 mb-12 text-center fade-in-scroll dark:text-gray-300">这里是我引以为傲的一些项目。</p>
                <div id="portfolio-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- 项目卡片将由 JS 插入这里 -->
                </div>
            `,
            'about': `
                <div class="max-w-4xl mx-auto py-12">
                    <h1 class="text-5xl font-extrabold text-gray-900 mb-6 border-b pb-2 fade-in-scroll dark:text-gray-100">关于我</h1>
                    
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="md:w-1/3 fade-in-scroll">
                            <img src="https://placehold.co/300x400/34495e/ffffff?text=个人照片" alt="个人照片" class="rounded-xl shadow-2xl w-full h-auto object-cover transform hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="md:w-2/3 space-y-6 text-lg text-gray-700 dark:text-gray-300 fade-in-scroll">
                            <p class="mt-4 leading-relaxed">
                                我是一名充满激情的全栈开发者，拥有 <span class="font-bold text-blue-600 dark:text-blue-400">5 年</span> 的 Web 应用开发经验。我专注于创造高性能、用户友好的数字产品。
                            </p>
                            <p class="leading-relaxed">
                                我的核心技能涵盖前端技术（如 React, Vue.js, Tailwind CSS）和后端技术（如 PHP/Laravel, Node.js, Firebase/MySQL）。我热衷于从概念到部署的整个开发生命周期。
                            </p>
                            <h2 class="text-3xl font-semibold mt-8 mb-4 text-gray-900 dark:text-gray-100">我的专长</h2>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4 list-none p-0">
                                <li class="flex items-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    高性能 Web 应用
                                </li>
                                <li class="flex items-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    响应式用户界面设计
                                </li>
                                <li class="flex items-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    云服务 (AWS/Firebase) 部署
                                </li>
                                <li class="flex items-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    API 设计与集成
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            `,
            'contact': `
                <div class="max-w-2xl mx-auto py-12 text-center">
                    <h1 class="text-5xl font-extrabold text-gray-900 mb-4 fade-in-scroll dark:text-gray-100">联系我</h1>
                    <p class="text-xl text-gray-600 mb-10 fade-in-scroll dark:text-gray-300">
                        无论是项目合作还是简单问好，我都期待听到你的声音。
                    </p>
                    
                    <div id="contact-form-container" class="bg-white p-8 rounded-xl shadow-2xl fade-in-scroll dark:bg-gray-700 text-left">
                        <!-- 注意: onsubmit 调用了新的全局函数 handleContactSubmit -->
                        <form id="contact-form" onsubmit="handleContactSubmit(event)">
                            <div class="mb-6">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-200">姓名</label>
                                <input type="text" id="name" name="name" required class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="你的名字">
                            </div>
                            <div class="mb-6">
                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-200">邮箱</label>
                                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="你的邮箱地址">
                            </div>
                            <div class="mb-8">
                                <label for="message" class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-200">留言</label>
                                <textarea id="message" name="message" rows="5" required class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="你想要讨论什么?"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-blue-700 transition-all duration-300 transform hover:scale-[1.01] dark:bg-blue-700 dark:hover:bg-blue-800">
                                发送消息
                            </button>
                            
                            <div id="success-message" class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg hidden dark:bg-green-900 dark:border-green-700 dark:text-green-300" role="alert">
                                您的消息已成功发送！我会尽快回复您。
                            </div>
                        </form>
                    </div>
                </div>
            `
        };

        // --- 2. 核心功能 ---

        const mainContent = document.getElementById('main-content');

        /**
         * 渲染页面内容
         * @param {string} page - 'home' 或 'portfolio'
         */
        function renderPage(page) {
            if (!page || !pageTemplates[page]) {
                page = 'home'; // 默认页面
            }

            // 1. 注入 HTML 模板
            mainContent.innerHTML = pageTemplates[page];

            // 2. 如果是作品集，动态生成卡片 (替换 PHP foreach)
            if (page === 'portfolio') {
                const grid = document.getElementById('portfolio-grid');
                let projectHTML = '';
                projects.forEach((project) => {
                    projectHTML += `
                        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 portfolio-card fade-in-scroll dark:bg-gray-700">
                            <img src="${escapeHTML(project.img)}" alt="${escapeHTML(project.title)}" class="w-full h-48 object-cover hover:brightness-110 transition-brightness duration-300">
                            <div class="p-6">
                                <span class="text-sm font-medium text-blue-500 dark:text-blue-300 mb-1 block">${escapeHTML(project.category)}</span>
                                <h3 class="text-2xl font-semibold mb-2 dark:text-gray-100">${escapeHTML(project.title)}</h3>
                                <p class="text-gray-600 dark:text-gray-300">${escapeHTML(project.description)}</p>
                                <!-- 
                                    只保留 "访问项目" 链接。
                                -->
                                <a href="${escapeHTML(project.url)}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 transition-colors duration-300 font-semibold dark:text-blue-400 dark:hover:text-blue-300">访问项目 &rarr;</a>
                            </div>
                        </div>
                    `;
                });
                grid.innerHTML = projectHTML;
            }

            // 3. 更新导航栏高亮
            updateNavHighlight(page);

            // 4. 触发新内容的滚动动画
            handleScrollAnimation();

            // 5. 更新浏览器历史记录 (用于 SPA 导航)
            const currentUrl = new URL(window.location);
            if (currentUrl.searchParams.get('page') !== page) {
                currentUrl.searchParams.set('page', page);
                window.history.pushState({page: page}, '', currentUrl.toString());
            }
        }

        // 辅助函数：防止 XSS
        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m];
            });
        }

        // --- 3. 辅助功能 ---

        // 模拟联系表单提交处理
        function handleContactSubmit(event) {
            event.preventDefault(); // 阻止实际表单提交

            const form = document.getElementById('contact-form');
            const successMessage = document.getElementById('success-message');

            // 模拟数据收集
            const formData = new FormData(form);
            console.log("Mock Form Submission Data:", Object.fromEntries(formData.entries()));
            
            // 显示成功信息并重置表单
            form.reset();
            form.style.display = 'none'; // 暂时隐藏表单
            successMessage.classList.remove('hidden');

            // 5 秒后，隐藏信息并显示表单
            setTimeout(() => {
                successMessage.classList.add('hidden');
                form.style.display = 'block';
            }, 5000); 
        }
        // 将函数挂载到全局 window 对象上
        window.handleContactSubmit = handleContactSubmit;


        // 更新导航高亮
        function updateNavHighlight(activePage) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('font-semibold', 'text-blue-600', 'dark:text-blue-400');
                if (link.dataset.page === activePage) {
                    link.classList.add('font-semibold', 'text-blue-600', 'dark:text-blue-400');
                }
            });
        }

        
        // 滚动触发动画
        function handleScrollAnimation() {
            const elements = document.querySelectorAll('.fade-in-scroll');
            elements.forEach(el => {
                const rect = el.getBoundingClientRect();
                // 检查元素是否在视口中
                if (rect.top < window.innerHeight - 100 && rect.bottom > 100) {
                    el.classList.add('visible');
                }
            });
        }
        window.addEventListener('scroll', handleScrollAnimation);

        // --- 4. 暗黑模式与粒子背景 ---
        const html = document.documentElement;
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('light-icon');
        const darkIcon = document.getElementById('dark-icon');
        
        // 粒子背景配置
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        let particleColor, lineColor;

        // 根据主题更新粒子颜色
        function setCanvasColors() {
            if (document.documentElement.classList.contains('dark')) {
                particleColor = 'rgba(200, 200, 200, 0.3)';
                lineColor = 'rgba(200, 200, 200, 0.1)';
            } else {
                particleColor = 'rgba(100, 100, 100, 0.3)';
                lineColor = 'rgba(100, 100, 100, 0.1)';
            }
        }
        
        // Particle 类定义
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = Math.random() * 1 - 0.5;
                this.speedY = Math.random() * 1 - 0.5;
            }
            update() {
                if (this.x > canvas.width || this.x < 0) this.speedX *= -1;
                if (this.y > canvas.height || this.y < 0) this.speedY *= -1;
                this.x += this.speedX;
                this.y += this.speedY;
            }
            draw() {
                ctx.fillStyle = particleColor;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        
        // 粒子初始化函数
        function initParticles() {
            particles = [];
            let particleCount = (canvas.width * canvas.height) / 12000;
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }
        
        // 粒子连接函数
        function connectParticles() {
            for (let a = 0; a < particles.length; a++) {
                for (let b = a + 1; b < particles.length; b++) {
                    let distance = Math.sqrt(
                        Math.pow(particles[a].x - particles[b].x, 2) +
                        Math.pow(particles[a].y - particles[b].y, 2)
                    );
                    if (distance < 130) {
                        ctx.strokeStyle = lineColor;
                        ctx.lineWidth = 0.3;
                        ctx.beginPath();
                        ctx.moveTo(particles[a].x, particles[a].y);
                        ctx.lineTo(particles[b].x, particles[b].y);
                        ctx.stroke();
                    }
                }
            }
        }
        
        // 画布尺寸调整和粒子初始化
        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            initParticles();
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas(); // 初始调整大小

        // 动画循环
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let particle of particles) {
                particle.update();
                particle.draw();
            }
            connectParticles();
            requestAnimationFrame(animate);
        }
        // 确保动画在页面加载后启动
        window.addEventListener('load', animate);

        // 暗黑模式控制
        function updateThemeUI(isDark) {
            if (isDark) {
                html.classList.add('dark');
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                html.classList.remove('dark');
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }
            setCanvasColors();
        }

        // 检查本地存储或系统偏好
        let isDarkMode = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        updateThemeUI(isDarkMode); 

        // 切换事件
        themeToggle.addEventListener('click', () => {
            isDarkMode = !html.classList.contains('dark');
            localStorage.theme = isDarkMode ? 'dark' : 'light';
            updateThemeUI(isDarkMode);
        });

        // 监听系统主题变化
        if (window.matchMedia) {
             window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                 // 仅当用户没有手动设置主题时才跟随系统
                 if (!('theme' in localStorage)) {
                     updateThemeUI(e.matches);
                 }
             });
        }
        
        // --- 5. 事件监听与初始化 ---

        // 监听导航点击
        document.addEventListener('click', function(e) {
            // 只有拥有 nav-link class 且 data-page 属性的链接才会被 SPA 路由处理
            const link = e.target.closest('.nav-link');
            if (link && link.dataset.page) {
                e.preventDefault(); // 阻止链接默认跳转
                const page = link.dataset.page;
                renderPage(page);
            }
        });

        // 处理浏览器后退/前进（popstate）
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.page) {
                renderPage(e.state.page);
            } else {
                // 处理初始状态 (e.g. 刷新页面)
                const params = new URLSearchParams(window.location.search);
                renderPage(params.get('page') || 'home');
            }
        });

        // 页面初始加载
        document.addEventListener('DOMContentLoaded', () => {
            // 1. 设置动态年份
            document.getElementById('current-year').textContent = new Date().getFullYear();

            // 2. 根据 URL 加载初始页面
            const params = new URLSearchParams(window.location.search);
            const initialPage = params.get('page') || 'home';
            renderPage(initialPage);
            
            // 确保初始加载时历史状态正确
            if (!window.history.state || window.history.state.page !== initialPage) {
                 window.history.replaceState({page: initialPage}, '', `?page=${initialPage}`);
            }
        });

    </script>

</body>
</html>
