<!-- Chat Window -->
<div id="chat-window" class="chat-window hidden" role="dialog" aria-label="Chat AI Rumah Stunting">

    <!-- Resize Handle (drag pojok kanan-atas untuk mengubah ukuran) -->
    <div id="chat-resize-handle" class="chat-resize-handle" aria-hidden="true" title="Tarik untuk ubah ukuran">
        <span></span>
    </div>

    <!-- Header -->
    <div class="chat-header">
        <div class="chat-header-info">
            <div class="chat-avatar">
                <svg class="w-6 h-6" viewBox="0 0 40 40" fill="none">
                    <circle cx="16" cy="18" r="2.5" fill="white"/>
                    <circle cx="24" cy="18" r="2.5" fill="white"/>
                    <path d="M15 24c1.5 2 3.5 3 5 3s3.5-1 5-3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="chat-header-text">
                <h2 class="chat-title">StunAI</h2>
                <div class="chat-status">
                    <span class="status-dot"></span>
                    <span>Online</span>
                </div>
            </div>
        </div>
        <button id="chat-close" class="chat-close-btn" aria-label="Tutup chat">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M17.9542 4.77253C18.3056 4.42106 18.8761 4.42106 19.2276 4.77253C19.579 5.12401 19.579 5.69452 19.2276 6.04597L13.2735 12.0001L19.2276 17.9542C19.5791 18.3056 19.5791 18.8761 19.2276 19.2276C18.8761 19.5791 18.3056 19.5791 17.9542 19.2276L12.0001 13.2735L6.04595 19.2276C5.69451 19.5791 5.12399 19.579 4.77252 19.2276C4.42104 18.8761 4.42104 18.3056 4.77252 17.9542L10.7266 12.0001L4.77252 6.04597C4.42104 5.6945 4.42104 5.124 4.77252 4.77253C5.12399 4.42107 5.69448 4.42106 6.04595 4.77253L12.0001 10.7266L17.9542 4.77253Z" fill="currentColor"/>
            </svg>
        </button>
    </div>

    <!-- Token Usage Indicator -->
    <div id="chat-token-indicator" class="chat-token-indicator hidden">
        <div class="token-bar-track">
            <div id="token-bar-fill" class="token-bar-fill"></div>
        </div>
        <span id="token-percent-text" class="token-percent-text">0%</span>
    </div>

    <!-- Chat Body -->
    <div id="chat-body" class="chat-body" role="log" aria-live="polite">

        <!-- AI Message -->
        <div class="chat-bubble chat-bubble-bot">
            <div class="bubble-content">
                Halo! 👋 Saya StunAI. Saat ini saya masih dalam tahap pengembangan, jadi beberapa jawaban mungkin belum sepenuhnya akurat. Ada yang bisa saya bantu?
            </div>
            <span class="bubble-time">Sekarang</span>
        </div>

    </div>

    <!-- Typing Indicator -->
    <div id="chat-typing" class="chat-typing hidden" aria-label="AI sedang mengetik">
        <div class="typing-bubble">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Input Area -->
    <div class="chat-footer">
        <div class="chat-input-wrapper">
            <input
                id="chat-input"
                type="text"
                placeholder="Tulis pesan..."
                class="chat-input"
                autocomplete="off"
                aria-label="Pesan">
            <button id="chat-send" class="chat-send-btn" aria-label="Kirim pesan">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M16.5364 10.1636C16.8879 10.5151 16.8879 11.0849 16.5364 11.4364C16.1849 11.7879 15.6151 11.7879 15.2636 11.4364L12.9 9.07281V17.1C12.9 17.597 12.4971 18 12 18C11.503 18 11.1 17.597 11.1 17.1V9.07281L8.73641 11.4364C8.38494 11.7879 7.81509 11.7879 7.46362 11.4364C7.11214 11.0849 7.11214 10.5151 7.46362 10.1636L11.3636 6.2636C11.7151 5.91211 12.2849 5.91211 12.6364 6.2636L16.5364 10.1636Z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Floating Toggle Button -->
<div class="group fixed bottom-5 left-5 z-[1200]">
    <div class="relative flex items-center h-12">
        <!-- Tooltip -->
        <span
            class="absolute left-full ml-3 px-3 py-1.5
                   bg-white/90 backdrop-blur-md text-primary-500 text-xs font-semibold rounded-md shadow-md
                   opacity-0 translate-x-2
                   group-hover:opacity-100 group-hover:translate-x-0
                   transition-all duration-300 ease-out whitespace-nowrap
                   pointer-events-none">
            Chat dengan StunAI
        </span>

        <!-- Tombol utama -->
        <button id="chat-toggle" class="chat-toggle-btn" aria-label="Buka chat">
            <div class="toggle-icon">
                <svg viewBox="0 0 40 40" fill="none">
                    <circle cx="16" cy="18" r="2.5" fill="white"/>
                    <circle cx="24" cy="18" r="2.5" fill="white"/>
                    <path d="M15 24c1.5 2 3.5 3 5 3s3.5-1 5-3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <line x1="20" y1="8" x2="20" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="20" cy="6" r="2" fill="white"/>
                </svg>
            </div>
            <span class="toggle-ping" aria-hidden="true"></span>
        </button>
    </div>
</div>

@vite('resources/js/chatbot.js')