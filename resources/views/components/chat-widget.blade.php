<!-- Floating Chat Button -->
<style>
    #chat-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: linear-gradient(135deg, #0d6efd, #3b8efc);
        color: white;
        border: none;
        border-radius: 50%;
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        z-index: 2000;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    #chat-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.35);
    }

    /* Modal style */
    #chat-modal .modal-content {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
    }

    #chat-modal .modal-header {
        background: linear-gradient(135deg, #0d6efd, #3b8efc);
        color: #fff;
        padding: 0.75rem 1rem;
    }

    #chat-modal .modal-header h6 {
        font-weight: 600;
        font-size: 1rem;
    }

    #chat-box {
        height: 420px;
        overflow-y: auto;
        background: #f9fafc;
        padding: 10px;
        scroll-behavior: smooth;
    }

    .chat-bubble {
        padding: 10px 14px;
        border-radius: 16px;
        margin: 6px 0;
        max-width: 80%;
        word-break: break-word;
        font-size: 0.9rem;
        line-height: 1.4;
        animation: fadeIn 0.3s ease;
    }

    .chat-user {
        background: #0d6efd;
        color: #fff;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }

    .chat-admin {
        background: #e9ecef;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }

    .chat-timestamp {
        font-size: 0.7rem;
        opacity: 0.6;
        margin-top: 2px;
    }

    #chat-modal .modal-footer {
        background: #fff;
        border-top: 1px solid #eaeaea;
        padding: 10px;
    }

    #chat-modal input {
        border-radius: 20px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Floating Button -->
<div id="chat-btn" title="Chat Admin">
    <i class="bi bi-chat-dots fs-4"></i>
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chat-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-end modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mb-0"><i class="bi bi-headset"></i> Chat Admin</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div id="chat-box"></div>

            <div class="modal-footer">
                <form id="chat-form" class="w-100 d-flex align-items-center">
                    <input type="hidden" name="receiver_id" value="1"> <!-- ID Admin -->
                    <input type="text" name="message" id="message" class="form-control me-2"
                        placeholder="Ketik pesan..." autocomplete="off">
                    <button class="btn btn-primary rounded-circle px-3">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
    $(function() {
        const authId = {{ Auth::id() }};
        const adminId = 1; // ID admin tujuan
        const chatBox = $('#chat-box');

        // === TOMBOL CHAT ===
        $('#chat-btn').on('click', () => {
            $('#chat-modal').modal('show');
            loadMessages();
        });

        // === PUSHER ===
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        const channel = pusher.subscribe(`private-chat.${authId}`);
        channel.bind('App\\Events\\MessageSent', function(data) {
            appendMessage(data.message, data.sender_id === authId ? 'chat-user' : 'chat-admin');
        });

        // === LOAD PESAN ===
        function loadMessages() {
            $.get("{{ route('chat.fetch', ['user' => '__ID__']) }}".replace('__ID__', adminId),
                function(data) {
                    chatBox.html('');
                    data.forEach(msg => {
                        appendMessage(msg.message, msg.sender_id === authId ? 'chat-user' :
                            'chat-admin',
                            msg.created_at);
                    });
                    scrollBottom();
                });
        }

        // === KIRIM PESAN ===
        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            const msg = $('#message').val().trim();
            if (!msg) return;

            appendMessage(msg, 'chat-user');
            $('#message').val('');
            scrollBottom();

            $.post("{{ route('chat.send') }}", {
                receiver_id: adminId,
                message: msg
            });
        });

        // === TAMBAH PESAN KE CHATBOX ===
        function appendMessage(message, type, time = null) {
            const bubble = `
                <div class="d-flex flex-column ${type === 'chat-user' ? 'align-items-end' : 'align-items-start'}">
                    <div class="chat-bubble ${type}">
                        ${escapeHtml(message)}
                        <div class="chat-timestamp">${time ? formatTime(time) : formatTime(new Date())}</div>
                    </div>
                </div>`;
            chatBox.append(bubble);
        }

        function scrollBottom() {
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }

        function escapeHtml(str) {
            return str.replace(/[&<>"']/g, s => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [s]));
        }

        function formatTime(date) {
            const d = new Date(date);
            return d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    });
</script>
