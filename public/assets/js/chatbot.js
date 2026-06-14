/**
 * MacaBot — MacaBae Chatbot Widget
 * Floating chat assistant for member area
 */
(function () {
    'use strict';

    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const ENDPOINT = '/chatbot';
    const STORAGE_KEY = 'macabot_history';

    // ── DOM ──────────────────────────────────────────────────────────────────
    const btn   = document.getElementById('macabot-toggle');
    const panel = document.getElementById('macabot-panel');
    const form  = document.getElementById('macabot-form');
    const input = document.getElementById('macabot-input');
    const body  = document.getElementById('macabot-body');
    const badge = document.getElementById('macabot-badge');

    if (!btn || !panel) return; // not on member page

    let isOpen = false;
    let unread = 0;

    // ── Toggle ───────────────────────────────────────────────────────────────
    btn.addEventListener('click', () => {
        isOpen = !isOpen;
        panel.classList.toggle('macabot-open', isOpen);
        btn.classList.toggle('macabot-active', isOpen);

        if (isOpen) {
            unread = 0;
            badge.textContent = '';
            badge.classList.add('hidden');
            input.focus();
            scrollToBottom();
        }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (isOpen && !panel.contains(e.target) && !btn.contains(e.target)) {
            isOpen = false;
            panel.classList.remove('macabot-open');
            btn.classList.remove('macabot-active');
        }
    });

    // ── Form Submit ───────────────────────────────────────────────────────────
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;

        appendMessage('user', msg);
        input.value = '';
        input.disabled = true;

        const typingId = appendTyping();

        try {
            const res  = await fetch(ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({ message: msg }),
            });
            const data = await res.json();
            removeTyping(typingId);
            appendMessage('bot', data.reply ?? 'Maaf, terjadi kesalahan. 😔');

            if (!isOpen) {
                unread++;
                badge.textContent = unread > 9 ? '9+' : unread;
                badge.classList.remove('hidden');
            }
        } catch {
            removeTyping(typingId);
            appendMessage('bot', 'Koneksi gagal. Coba lagi ya! 🔄');
        } finally {
            input.disabled = false;
            input.focus();
        }
    });

    // ── Render helpers ────────────────────────────────────────────────────────
    function appendMessage(role, html) {
        const wrap = document.createElement('div');
        wrap.className = `macabot-msg macabot-${role}`;

        const bubble = document.createElement('div');
        bubble.className = 'macabot-bubble';
        // Convert newlines to <br> and allow safe HTML links
        bubble.innerHTML = html.replace(/\n/g, '<br>');

        wrap.appendChild(bubble);
        body.appendChild(wrap);
        scrollToBottom();

        // Animate links
        wrap.querySelectorAll('.chatbot-link').forEach(a => {
            a.target = '_self';
        });
    }

    function appendTyping() {
        const id   = 'typing-' + Date.now();
        const wrap = document.createElement('div');
        wrap.className = 'macabot-msg macabot-bot';
        wrap.id = id;
        wrap.innerHTML = `<div class="macabot-bubble macabot-typing">
            <span></span><span></span><span></span>
        </div>`;
        body.appendChild(wrap);
        scrollToBottom();
        return id;
    }

    function removeTyping(id) {
        document.getElementById(id)?.remove();
    }

    function scrollToBottom() {
        requestAnimationFrame(() => { body.scrollTop = body.scrollHeight; });
    }

    // ── Quick replies ─────────────────────────────────────────────────────────
    document.querySelectorAll('.macabot-quick').forEach(btn => {
        btn.addEventListener('click', () => {
            input.value = btn.dataset.msg;
            form.dispatchEvent(new Event('submit'));
        });
    });

    // ── Enter to send ─────────────────────────────────────────────────────────
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
})();
