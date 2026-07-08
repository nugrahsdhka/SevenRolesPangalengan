// resources/js/chatbot.js
import '../css/chatbot.css';

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('chat-toggle');
    const chatWindow = document.getElementById('chat-window');
    const closeBtn = document.getElementById('chat-close');
    const chatBody = document.getElementById('chat-body');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send');
    const typingEl = document.getElementById('chat-typing');
    const resizeHandle = document.getElementById('chat-resize-handle');

    // Token usage indicator elements
    const tokenIndicator = document.getElementById('chat-token-indicator');
    const tokenBarFill = document.getElementById('token-bar-fill');
    const tokenPercentText = document.getElementById('token-percent-text');
    let countdownInterval = null;

    const CHAT_ENDPOINT = '/api/chat';
    let isTyping = false;

    // Session & User Name
    let sessionId = localStorage.getItem('chat_session_id');
    if (!sessionId) {
        sessionId = 'sess_' + Math.random().toString(36).substring(2, 15);
        localStorage.setItem('chat_session_id', sessionId);
    }

    let userName = localStorage.getItem('chat_user_name');

    const scrollToBottom = () => {
        chatBody.scrollTop = chatBody.scrollHeight;
    };

    const formatTime = () => {
        const now = new Date();
        return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    };

    const escapeHtml = (text) => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const formatReply = (text) => {
        // Bold
        text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        // Italic
        text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');
        // Bullet points
        text = text.replace(/^- (.+)$/gm, '<li>$1</li>');
        text = text.replace(/(<li>.*<\/li>(\n?))+/g, '<ul>$&</ul>');
        // Emoji stays as is
        // Newlines
        text = text.replace(/\n/g, '<br>');
        return text;
    };

    const addBubble = (text, sender = 'bot') => {
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble chat-bubble-${sender}`;

        const content = sender === 'bot' ? formatReply(text) : escapeHtml(text);

        bubble.innerHTML = `
            <div class="bubble-content">${content}</div>
            <span class="bubble-time">${formatTime()}</span>
        `;
        chatBody.appendChild(bubble);
        scrollToBottom();
    };

    const showTyping = () => {
        isTyping = true;
        typingEl.classList.remove('hidden');
        scrollToBottom();
    };

    const hideTyping = () => {
        isTyping = false;
        typingEl.classList.add('hidden');
    };

    // Simulasi typing delay realistis
    const simulateTyping = (text, callback) => {
        const wordCount = text.split(/\s+/).length;
        const delayMs = Math.min(Math.max((wordCount / 250) * 60 * 1000, 600), 3000);

        showTyping();
        setTimeout(() => {
            hideTyping();
            callback();
        }, delayMs);
    };

    const openChat = () => {
        chatWindow.classList.remove('hidden');
        toggleBtn.style.transform = 'scale(0)';
        toggleBtn.style.opacity = '0';
        setTimeout(() => { toggleBtn.style.display = 'none'; }, 200);
        chatInput.focus();
    };

    const closeChat = () => {
        chatWindow.classList.add('hidden');
        toggleBtn.style.display = 'flex';
        setTimeout(() => {
            toggleBtn.style.transform = 'scale(1)';
            toggleBtn.style.opacity = '1';
        }, 10);
    };

    // Extract name from message
    const extractName = (text) => {
        const patterns = [
            /nama\s+(?:aku|saya|gw|gue)\s+(.+)/i,
            /aku\s+(.+)/i,
            /saya\s+(.+)/i,
            /nama\s+(.+)/i,
        ];

        for (const pattern of patterns) {
            const match = text.match(pattern);
            if (match && match[1]) {
                return match[1].trim().split(/\s+/)[0]; // Ambil nama pertama aja
            }
        }
        return null;
    };

    // ===== Token Usage Indicator =====

    const updateTokenIndicator = (usage) => {
        if (!usage || !usage.limit) return;

        clearInterval(countdownInterval);
        tokenIndicator.classList.remove('hidden', 'token-exhausted');

        const percent = Math.min(usage.percent_used, 100);
        tokenBarFill.style.width = `${percent}%`;
        tokenPercentText.textContent = `${percent}%`;

        tokenBarFill.classList.remove('token-warn', 'token-danger');
        if (percent >= 90) {
            tokenBarFill.classList.add('token-danger');
        } else if (percent >= 70) {
            tokenBarFill.classList.add('token-warn');
        }
    };

    const startResetCountdown = (seconds) => {
        clearInterval(countdownInterval);
        let remaining = Math.max(Math.floor(seconds), 1);

        chatInput.disabled = true;
        sendBtn.disabled = true;
        tokenIndicator.classList.remove('hidden');
        tokenIndicator.classList.add('token-exhausted');

        const tick = () => {
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                chatInput.disabled = false;
                sendBtn.disabled = false;
                tokenIndicator.classList.remove('token-exhausted');
                tokenPercentText.textContent = 'Siap lagi ✅';
                setTimeout(() => tokenIndicator.classList.add('hidden'), 3000);
                return;
            }
            const m = Math.floor(remaining / 60);
            const s = remaining % 60;
            tokenPercentText.textContent = `Reset ${m}:${String(s).padStart(2, '0')}`;
            remaining--;
        };
        tick();
        countdownInterval = setInterval(tick, 1000);
    };

    const sendMessage = async () => {
        const message = chatInput.value.trim();
        if (!message || isTyping || chatInput.disabled) return;

        // Check if user introduces themselves
        const extractedName = extractName(message);
        if (extractedName && !userName) {
            userName = extractedName;
            localStorage.setItem('chat_user_name', userName);
        }

        addBubble(message, 'user');
        chatInput.value = '';
        showTyping();

        try {
            const response = await fetch(CHAT_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'X-Session-Id': sessionId,
                    'X-User-Name': userName || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json().catch(() => ({}));

            // Token/rate limit habis
            if (response.status === 429) {
                hideTyping();
                const resetText = data.token_usage?.reset_readable || 'beberapa saat';
                addBubble(`Waduh, token API lagi abis nih 😅 Coba lagi dalam ${resetText} ya!`, 'bot');
                if (data.token_usage) {
                    updateTokenIndicator(data.token_usage);
                    startResetCountdown(data.token_usage.reset_in_seconds);
                }
                return;
            }

            if (!response.ok) {
                throw new Error(data.message || `HTTP ${response.status}`);
            }

            if (data.token_usage) {
                updateTokenIndicator(data.token_usage);
            }

            const reply = data.reply ?? 'Maaf, terjadi kesalahan pada server.';
            simulateTyping(reply, () => addBubble(reply, 'bot'));

        } catch (error) {
            hideTyping();
            console.error('Chat error:', error);
            setTimeout(() => {
                addBubble('Aduh, otakku lagi ngelag nih 😅 Coba lagi bentar ya!', 'bot');
            }, 300);
        }
    };

    // ===== Resize Window =====
    const RESIZE_STORAGE_KEY = 'chat_window_size';
    const MIN_W = 320;
    const MAX_W = 560;
    const MIN_H = 380;
    const MAX_H = 720;

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

    const viewportMaxWidth = () => window.innerWidth - 40;
    const viewportMaxHeight = () => window.innerHeight - 120;

    const applySize = (width, height) => {
        const w = clamp(width, MIN_W, Math.min(MAX_W, viewportMaxWidth()));
        const h = clamp(height, MIN_H, Math.min(MAX_H, viewportMaxHeight()));
        chatWindow.style.width = `${w}px`;
        chatWindow.style.height = `${h}px`;
        return { w, h };
    };

    const saveSize = (width, height) => {
        localStorage.setItem(RESIZE_STORAGE_KEY, JSON.stringify({ w: width, h: height }));
    };

    // Restore saved size (kalau ada & viewport bukan mobile)
    if (window.innerWidth > 480) {
        try {
            const saved = JSON.parse(localStorage.getItem(RESIZE_STORAGE_KEY) || 'null');
            if (saved && saved.w && saved.h) applySize(saved.w, saved.h);
        } catch (e) { /* abaikan data korup */ }
    }

    let isResizing = false;
    let startX = 0;
    let startY = 0;
    let startW = 0;
    let startH = 0;

    const getPoint = (e) => (e.touches ? e.touches[0] : e);

    const onResizeStart = (e) => {
        if (window.innerWidth <= 480) return; // resize dimatikan di mobile
        isResizing = true;
        const point = getPoint(e);
        startX = point.clientX;
        startY = point.clientY;
        startW = chatWindow.offsetWidth;
        startH = chatWindow.offsetHeight;
        resizeHandle.classList.add('is-resizing');
        document.body.style.userSelect = 'none';
        e.preventDefault();
    };

    const onResizeMove = (e) => {
        if (!isResizing) return;
        const point = getPoint(e);
        const dx = point.clientX - startX; // geser kanan = lebih lebar
        const dy = startY - point.clientY; // geser atas = lebih tinggi
        applySize(startW + dx, startH + dy);
        e.preventDefault();
    };

    const onResizeEnd = () => {
        if (!isResizing) return;
        isResizing = false;
        resizeHandle.classList.remove('is-resizing');
        document.body.style.userSelect = '';
        saveSize(chatWindow.offsetWidth, chatWindow.offsetHeight);
    };

    resizeHandle.addEventListener('mousedown', onResizeStart);
    resizeHandle.addEventListener('touchstart', onResizeStart, { passive: false });
    window.addEventListener('mousemove', onResizeMove, { passive: false });
    window.addEventListener('touchmove', onResizeMove, { passive: false });
    window.addEventListener('mouseup', onResizeEnd);
    window.addEventListener('touchend', onResizeEnd);

    // Jaga ukuran tetap valid saat viewport berubah (rotate/resize browser)
    window.addEventListener('resize', () => {
        applySize(chatWindow.offsetWidth, chatWindow.offsetHeight);
    });

    // Event listeners
    toggleBtn.addEventListener('click', openChat);
    closeBtn.addEventListener('click', closeChat);
    sendBtn.addEventListener('click', sendMessage);

    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (chatWindow.classList.contains('hidden')) return;
        const isInsideChat = chatWindow.contains(e.target);
        const isToggleBtn = toggleBtn.contains(e.target) || toggleBtn === e.target;
        if (!isInsideChat && !isToggleBtn) closeChat();
    });

    // Initial welcome bubble with real timestamp
    const firstBubbleTime = document.querySelector('.chat-bubble-bot .bubble-time');
    if (firstBubbleTime) {
        firstBubbleTime.textContent = formatTime();
    }
});