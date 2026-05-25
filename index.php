<?php
// index.php – главная страница техподдержки с интерактивными статьями
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Техническая поддержка</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(-45deg, #667eea, #764ba2, #6B8DD6, #8E37D7);
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
            display: flex;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .bg-decoration {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }
        .bg-decoration:nth-child(1) {
            width: 400px; height: 400px;
            background: #ffffff;
            top: -100px; left: -100px;
            animation: float1 20s ease-in-out infinite;
        }
        .bg-decoration:nth-child(2) {
            width: 300px; height: 300px;
            background: #ffffff;
            bottom: -80px; right: -80px;
            animation: float2 25s ease-in-out infinite;
        }
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 60px) scale(1.1); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-40px, -50px) scale(1.15); }
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: 0.2s;
            padding-bottom: 40px;
        }
        @keyframes slideUpFade {
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 16px;
            text-align: center;
        }
        .section-subtitle {
            text-align: center;
            color: #5a5a7a;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1), inset 0 0 0 1px rgba(255,255,255,0.5);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .card .icon {
            font-size: 36px;
            margin-bottom: 12px;
            display: block;
        }
        .card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        .card p {
            font-size: 14px;
            color: #4a4a6a;
            line-height: 1.5;
        }

        /* Модальное окно статьи */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(6px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.25);
            padding: 40px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal {
            transform: scale(1);
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 20px;
            font-size: 28px;
            color: #888;
            cursor: pointer;
            background: none;
            border: none;
            line-height: 1;
            transition: color 0.2s;
        }
        .modal-close:hover {
            color: #333;
        }
        .modal h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .modal .article-content {
            font-size: 16px;
            line-height: 1.7;
            color: #2d2d44;
        }
        .modal .article-content p {
            margin-bottom: 16px;
        }
        .modal .article-content ul {
            margin: 16px 0;
            padding-left: 20px;
        }
        .modal .article-content li {
            margin-bottom: 8px;
        }

        /* Форма */
        .form-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15), inset 0 0 0 1px rgba(255,255,255,0.5);
            padding: 40px 36px;
            transition: all 0.3s;
        }
        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .form-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        .form-header p {
            color: #5a5a7a;
            font-size: 15px;
        }

        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #2d2d44;
            margin-bottom: 6px;
        }
        .input-wrapper { position: relative; }
        .input-wrapper .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
            transition: color 0.2s;
            pointer-events: none;
        }
        .input-wrapper textarea ~ .field-icon {
            top: 18px;
            transform: none;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 2px solid #e0e0f0;
            border-radius: 14px;
            font-size: 15px;
            background: rgba(255,255,255,0.8);
            transition: all 0.25s;
            outline: none;
            color: #1a1a2e;
            resize: vertical;
            font-family: inherit;
        }
        .form-group textarea { min-height: 120px; padding-top: 14px; }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #6b6bff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(107,107,255,0.15);
        }
        .form-group input:focus + .field-icon,
        .form-group textarea:focus ~ .field-icon { color: #6b6bff; }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
            margin-top: 8px;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(102,126,234,0.5); }
        .submit-btn:active { transform: translateY(1px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
            transform: scale(0);
            animation: rippleEffect 0.6s linear;
            pointer-events: none;
        }
        @keyframes rippleEffect {
            to { transform: scale(4); opacity: 0; }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            padding: 16px 20px;
            border-radius: 14px;
            font-weight: 500;
            font-size: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .toast.success {
            background: rgba(230, 249, 237, 0.95);
            color: #0b5e2e;
            border: 1px solid #b0e5c2;
        }
        .toast.error {
            background: rgba(255, 240, 240, 0.95);
            color: #b91c1c;
            border: 1px solid #fbc4c4;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 600px) {
            .container { padding: 10px; }
            .form-card { padding: 24px 20px; border-radius: 20px; }
            .form-header h1 { font-size: 24px; }
            .modal { padding: 24px; border-radius: 20px; }
        }
    </style>
</head>
<body>

<div class="bg-decoration"></div>
<div class="bg-decoration"></div>

<div class="toast-container" id="toastContainer"></div>

<!-- Модальное окно для статей -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <button class="modal-close" id="modalClose">&times;</button>
        <h2 id="modalTitle"></h2>
        <div class="article-content" id="modalContent"></div>
    </div>
</div>

<div class="container">
    <!-- ПОПУЛЯРНЫЕ ПРОБЛЕМЫ -->
    <div>
        <div class="section-title">🔥 Популярные сегодня проблемы</div>
        <div class="section-subtitle">Нажмите на проблему, чтобы быстро создать заявку</div>
        <div class="cards-grid" id="popularProblems">
            <div class="card" data-subject="Не работает принтер" data-description="Принтер HP LaserJet не отвечает, не печатает, индикатор мигает красным.">
                <span class="icon">🖨️</span>
                <h3>Принтер не печатает</h3>
                <p>Многие жалуются на принтер HP на 3 этаже.</p>
            </div>
            <div class="card" data-subject="Проблемы с интернетом" data-description="Нет доступа в сеть, сайты не открываются, Wi-Fi пропадает.">
                <span class="icon">🌐</span>
                <h3>Интернет отсутствует</h3>
                <p>Перебои с сетью в кабинете 412.</p>
            </div>
            <div class="card" data-subject="Не включается компьютер" data-description="Системный блок не стартует, чёрный экран, нет реакции на кнопку.">
                <span class="icon">💻</span>
                <h3>Компьютер не включается</h3>
                <p>Несколько обращений из бухгалтерии.</p>
            </div>
        </div>
    </div>

    <!-- ПОЛЕЗНЫЕ СТАТЬИ (динамические) -->
    <div>
        <div class="section-title">📚 Полезные статьи</div>
        <div class="section-subtitle">Советы и инструкции от службы поддержки</div>
        <div class="cards-grid" id="articlesContainer">
            <!-- Заполняется через JavaScript -->
        </div>
    </div>

    <!-- ШАБЛОНЫ ЗАЯВОК -->
    <div>
        <div class="section-title">📋 Шаблоны заявок</div>
        <div class="section-subtitle">Быстро заполните форму с готовым описанием</div>
        <div class="cards-grid" id="templates">
            <div class="card" data-subject="Замена картриджа" data-description="Требуется замена картриджа в принтере HP LaserJet. Модель картриджа: CF283A.">
                <span class="icon">🖋️</span>
                <h3>Замена картриджа</h3>
                <p>Картридж закончился, требуется новый.</p>
            </div>
            <div class="card" data-subject="Настройка почты" data-description="Не могу настроить корпоративную почту на телефоне. Прошу помочь с настройками.">
                <span class="icon">📧</span>
                <h3>Настройка почты</h3>
                <p>Помощь с почтовым клиентом на смартфоне.</p>
            </div>
            <div class="card" data-subject="Заявка на списание техники" data-description="Списывается монитор Samsung S22D300, инв. № 1234. Причина: не включается.">
                <span class="icon">🗑️</span>
                <h3>Списание техники</h3>
                <p>Оформление акта на утилизацию.</p>
            </div>
        </div>
    </div>

    <!-- ФОРМА -->
    <div class="form-card" id="formCard">
        <div class="form-header">
            <h1>📬 Новая заявка</h1>
            <p>Опишите проблему — и мы немедленно приступим к решению</p>
        </div>

        <form id="supportForm" novalidate>
            <div class="form-group">
                <label for="name">Ваше имя</label>
                <div class="input-wrapper">
                    <input type="text" id="name" name="name" required placeholder="Иван Петров">
                    <span class="field-icon">👤</span>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Тема заявки</label>
                <div class="input-wrapper">
                    <input type="text" id="subject" name="subject" required placeholder="Не работает принтер">
                    <span class="field-icon">📌</span>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Описание проблемы</label>
                <div class="input-wrapper">
                    <textarea id="description" name="description" required placeholder="Подробно опишите, что случилось..."></textarea>
                    <span class="field-icon">📝</span>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Отправить заявку
            </button>
        </form>
    </div>
</div>

<script>
    // ========== МАССИВ ПОЛЕЗНЫХ СТАТЕЙ ==========
    const articles = [
        {
            id: 1,
            icon: '🛡️',
            title: 'Как защитить корпоративные данные',
            short: 'Основные правила безопасности при работе с конфиденциальной информацией.',
            full: `
                <p>Защита данных — обязанность каждого сотрудника. Следуйте этим простым правилам, чтобы предотвратить утечки и кибератаки:</p>
                <ul>
                    <li><strong>Блокируйте экран</strong> (Win + L) каждый раз, когда отходите от рабочего места.</li>
                    <li><strong>Не передавайте пароли</strong> через мессенджеры или по телефону — используйте только корпоративный менеджер паролей.</li>
                    <li><strong>Проверяйте ссылки</strong> в письмах: наведите курсор, чтобы увидеть реальный URL. Не открывайте подозрительные вложения.</li>
                    <li><strong>Используйте только одобренные USB-накопители</strong>, выданные IT-отделом. Сторонние флешки могут содержать вредоносное ПО.</li>
                    <li><strong>Регулярно обновляйте ПО</strong> — включайте автоматические обновления операционной системы и офисных программ.</li>
                </ul>
                <p>При малейшем подозрении на вирус или фишинг немедленно сообщите в техподдержку через форму заявки.</p>
            `
        },
        {
            id: 2,
            icon: '⚡',
            title: 'Ускорение работы компьютера',
            short: 'Простые шаги для повышения производительности вашего ПК.',
            full: `
                <p>Если компьютер стал медленно работать, попробуйте эти действия до обращения в поддержку:</p>
                <ul>
                    <li><strong>Перезагрузите компьютер</strong> — это решает до 30% проблем с производительностью.</li>
                    <li><strong>Закройте лишние вкладки браузера</strong> и программы, которые не используются. Особенно это касается Chrome — он потребляет много оперативной памяти.</li>
                    <li><strong>Проверьте автозагрузку</strong>: нажмите Ctrl+Shift+Esc → вкладка «Автозагрузка» → отключите ненужные программы.</li>
                    <li><strong>Очистите временные файлы</strong>: откройте «Параметры» → «Система» → «Память» → «Временные файлы» → удалите.</li>
                    <li><strong>Проверьте на вирусы</strong> — запустите полное сканирование Windows Defender.</li>
                </ul>
                <p>Если после этих шагов компьютер всё ещё тормозит, создайте заявку, указав модель устройства и описание проблемы.</p>
            `
        },
        {
            id: 3,
            icon: '🔄',
            title: 'Восстановление пароля учётной записи',
            short: 'Инструкция по самостоятельному сбросу пароля.',
            full: `
                <p>Забыли пароль от учётной записи Windows или корпоративных сервисов? Воспользуйтесь инструкцией:</p>
                <ul>
                    <li><strong>Корпоративный портал (Битрикс24)</strong>: на странице входа нажмите «Забыли пароль?» и следуйте инструкциям. Ссылка для сброса придёт на вашу рабочую почту.</li>
                    <li><strong>Windows</strong>: если вы не можете войти, обратитесь к администратору или воспользуйтесь диском сброса пароля (если он был создан заранее).</li>
                    <li><strong>Почта/Office 365</strong>: сброс пароля возможен через IT-отдел. Самостоятельный сброс доступен, если вы предварительно настроили альтернативный email или телефон.</li>
                </ul>
                <p>Если не удаётся восстановить доступ самостоятельно, заполните заявку с темой «Восстановление пароля» и укажите вашу учётную запись.</p>
            `
        }
    ];

    // ========== ГЕНЕРАЦИЯ КАРТОЧЕК СТАТЕЙ ==========
    const articlesContainer = document.getElementById('articlesContainer');
    function renderArticles() {
        articlesContainer.innerHTML = articles.map(article => `
            <div class="card" data-article-id="${article.id}">
                <span class="icon">${article.icon}</span>
                <h3>${article.title}</h3>
                <p>${article.short}</p>
            </div>
        `).join('');
    }
    renderArticles();

    // ========== МОДАЛЬНОЕ ОКНО СТАТЬИ ==========
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    const modalClose = document.getElementById('modalClose');

    function openArticleModal(articleId) {
        const article = articles.find(a => a.id === articleId);
        if (!article) return;
        modalTitle.innerHTML = `${article.icon} ${article.title}`;
        modalContent.innerHTML = article.full;
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Делегирование кликов на контейнере статей
    articlesContainer.addEventListener('click', (e) => {
        const card = e.target.closest('.card');
        if (!card) return;
        const articleId = parseInt(card.dataset.articleId);
        if (articleId) openArticleModal(articleId);
    });

    modalClose.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });
    // Закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalOverlay.classList.contains('active')) closeModal();
    });

    // ========== ЗАПОЛНЕНИЕ ФОРМЫ ИЗ КАРТОЧЕК ПРОБЛЕМ/ШАБЛОНОВ ==========
    function fillForm(subject, description) {
        document.getElementById('subject').value = subject;
        document.getElementById('description').value = description;
        document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('formCard').style.boxShadow = '0 0 0 4px rgba(102,126,234,0.3)';
        setTimeout(() => {
            document.getElementById('formCard').style.boxShadow = '';
        }, 1500);
    }

    document.querySelectorAll('#popularProblems .card').forEach(card => {
        card.addEventListener('click', () => fillForm(card.dataset.subject, card.dataset.description));
    });
    document.querySelectorAll('#templates .card').forEach(card => {
        card.addEventListener('click', () => fillForm(card.dataset.subject, card.dataset.description));
    });

    // ========== ОТПРАВКА ФОРМЫ ==========
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        const rect = submitBtn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
        ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
        submitBtn.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
    });

    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<span>${type === 'success' ? '✅' : '⚠️'}</span> ${message}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease forwards';
            toast.addEventListener('animationend', () => toast.remove());
        }, 4000);
    }

    document.getElementById('supportForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const description = document.getElementById('description').value.trim();
        if (!name || !subject || !description) {
            showToast('Пожалуйста, заполните все поля.', 'error');
            return;
        }
        const btn = document.getElementById('submitBtn');
        const originalText = btn.textContent;
        btn.textContent = 'Отправка...';
        btn.disabled = true;
        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('subject', subject);
            formData.append('description', description);
            const response = await fetch('submit.php', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                showToast(`Заявка №${data.lead_id} успешно создана!`, 'success');
                document.getElementById('supportForm').reset();
            } else {
                showToast(data.error || 'Неизвестная ошибка.', 'error');
            }
        } catch (error) {
            showToast('Ошибка соединения с сервером.', 'error');
        } finally {
            btn.textContent = originalText;
            btn.disabled = false;
        }
    });
</script>

</body>
</html>