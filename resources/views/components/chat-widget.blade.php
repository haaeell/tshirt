<div id="chat-widget">
    <style>
        /* ==== FAB ==== */
        #widget-fab {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .25);
            cursor: pointer;
            z-index: 9999;
            transition: transform .2s
        }

        #widget-fab:hover {
            transform: scale(1.1)
        }

        /* ==== POPUP ==== */
        #widget-popup {
            position: fixed;
            bottom: 90px;
            right: 25px;
            width: 340px;
            max-height: 520px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
            background: #fff;
            display: none;
            flex-direction: column;
            z-index: 9998;
            animation: widget-slideUp .25s ease
        }

        @keyframes widget-slideUp {
            from {
                transform: translateY(20px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        .widget-header {
            background: #0d6efd;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .widget-body {
            flex: 1;
            background: #f9fafc;
            overflow-y: auto;
            padding: 12px;
            display: flex;
            flex-direction: column
        }

        .widget-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 8px;
            gap: 6px
        }

        .widget-bubble {
            padding: 9px 13px;
            border-radius: 16px;
            max-width: 80%;
            word-wrap: break-word;
            font-size: .9rem;
            line-height: 1.4;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08)
        }

        .widget-bubble.me {
            background: #0d6efd;
            color: #fff;
            margin-left: auto;
            border-bottom-right-radius: 4px
        }

        .widget-bubble.other {
            background: #e9ecef;
            color: #333;
            border-bottom-left-radius: 4px
        }

        .widget-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover
        }

        .widget-time {
            font-size: .75rem;
            opacity: .7;
            margin-top: 2px;
            text-align: right
        }

        .widget-status {
            margin-left: 3px;
            font-size: .8rem;
            vertical-align: middle
        }

        .widget-footer {
            padding: 8px;
            border-top: 1px solid #eee;
            background: #fff
        }

        .widget-footer form {
            display: flex;
            align-items: center;
            gap: 6px
        }

        .widget-footer input {
            flex: 1;
            border-radius: 20px
        }

        #widget-file-preview {
            display: none;
            background: #f8f9fa;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 5px 8px;
            margin-top: 6px;
            font-size: .85rem
        }

        #widget-file-preview img {
            max-height: 60px;
            border-radius: 6px
        }

        #widget-typing {
            display: none;
            font-style: italic;
            color: #777;
            font-size: .8rem;
            padding: 4px 10px;
            animation: widget-blink 1.2s infinite
        }

        @keyframes widget-blink {

            0%,
            100% {
                opacity: .4
            }

            50% {
                opacity: 1
            }
        }
    </style>

    <div id="widget-fab"><i class="bi bi-chat-dots-fill"></i></div>

    <div id="widget-popup">
        <div class="widget-header">
            <span><i class="bi bi-person-fill me-1"></i> Chat dengan Admin</span>
            <button id="widget-close" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-x"></i></button>
        </div>

        <div class="widget-body" id="widget-chat-box">
            <div class="text-center text-muted mt-4">💬 Belum ada pesan.</div>
        </div>

        <div id="widget-typing"><i class="bi bi-pencil"></i> Admin sedang mengetik...</div>

        <div class="widget-footer">
            <form id="widget-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="widget-receiver" value="{{ $admin->id ?? 1 }}">
                <label for="widget-file"
                    class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-paperclip"></i>
                </label>
                <input type="file" id="widget-file" style="display:none;">
                <input type="text" id="widget-input" class="form-control" placeholder="Ketik pesan...">
                <button class="btn btn-primary rounded-circle px-3" id="widget-send">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
            <div id="widget-file-preview"></div>
        </div>
    </div>

    <!-- dependen -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>

    <script>
        (function() {
            // ====== VAR DASAR ======
            const authId = {{ (int) $auth->id }};
            const adminId = {{ (int) ($admin->id ?? 0) }};
            const adminAvatar = "https://i.pravatar.cc/150?img=12";
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const popup = document.getElementById('widget-popup');
            const fab = document.getElementById('widget-fab');
            const closeBtn = document.getElementById('widget-close');
            const box = document.getElementById('widget-chat-box');
            const input = document.getElementById('widget-input');
            const typingEl = document.getElementById('widget-typing');
            const fileInput = document.getElementById('widget-file');
            const preview = document.getElementById('widget-file-preview');

            // ====== PUSHER (PRIVATE) + AUTH LARAVEL ======
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER', 'ap1') }}',
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            });
            const chat = pusher.subscribe('private-chat.' + authId);

            // ====== UI POPUP ======
            fab.addEventListener('click', () => {
                popup.style.display = 'flex';
                fetchMessages();
                // mark as read ketika dibuka
                axios.post("{{ route('chat.read', ['user' => '__ID__']) }}".replace('__ID__', adminId)).catch(
                    () => {});
            });
            closeBtn.addEventListener('click', () => popup.style.display = 'none');

            // ====== FETCH HISTORY ======
            function fetchMessages() {
                axios.get("{{ route('chat.fetch', ['user' => '__ID__']) }}".replace('__ID__', adminId))
                    .then(res => {
                        box.innerHTML = '';
                        if (!res.data.length) {
                            box.innerHTML = '<div class="text-center text-muted mt-4">💬 Belum ada pesan.</div>';
                            return;
                        }
                        res.data.forEach(m => appendMsg(m, m.sender_id === authId ? 'me' : 'other'));
                        scrollBottom();
                    });
            }

            // ====== RENDER BUBBLE ======
            function appendMsg(m, who) {
                const row = document.createElement('div');
                row.className = 'widget-row ' + (who === 'me' ? 'justify-content-end' : '');

                const bubble = document.createElement('div');
                bubble.className = 'widget-bubble ' + who;

                let statusIcon = '';
                if (who === 'me') {
                    if (m.status === 'sending') statusIcon =
                        '<i class="bi bi-hourglass-split text-white-50 widget-status"></i>';
                    else if (!m.is_read) statusIcon = '<i class="bi bi-check2 text-white-50 widget-status"></i>';
                    else statusIcon = '<i class="bi bi-check2-all text-info widget-status"></i>';
                }

                let content = '';
                if (m.file_path) {
                    const kind = (m.file_type || '').split('/')[0];
                    if (kind === 'image') {
                        content +=
                            `<img src="/storage/${m.file_path}" class="img-fluid rounded mb-2" style="max-width:200px;">`;
                    } else {
                        const linkColor = who === 'me' ? 'text-light' : 'text-primary';
                        content +=
                            `<a href="/storage/${m.file_path}" target="_blank" class="text-decoration-none ${linkColor}"><i class="bi bi-paperclip me-1"></i>${m.file_name||'File'}</a>`;
                    }
                }
                content += `<div>${(m.message||'')}</div>`;

                bubble.innerHTML = `
            ${content}
            <div class="widget-time">
              ${new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})}
              ${statusIcon}
            </div>
          `;

                if (who === 'other') {
                    const avatar = document.createElement('img');
                    avatar.src = adminAvatar;
                    avatar.className = 'widget-avatar';
                    row.appendChild(avatar);
                }
                row.appendChild(bubble);
                box.appendChild(row);
            }

            function scrollBottom() {
                box.scrollTop = box.scrollHeight;
            }

            // ====== RECEIVE EVENTS ======
            chat.bind('message.sent', m => {
                // tampilkan hanya pesan terkait admin <-> user
                if (m.sender_id === adminId || m.receiver_id === adminId) {
                    appendMsg(m, m.sender_id === authId ? 'me' : 'other');
                    scrollBottom();
                }
            });

            chat.bind('message.read', () => {
                // ubah ikon ceklis jadi biru (read)
                box.querySelectorAll('.widget-bubble.me .widget-status').forEach(i => {
                    i.outerHTML = '<i class="bi bi-check2-all text-info widget-status"></i>';
                });
            });

            // ====== TYPING INDICATOR (INI YANG NGGAK MUNCUL KEMARIN) ======
            // Terima event
            chat.bind('user.typing', d => {
                if (d.sender_id === adminId) typingEl.style.display = 'block';
            });
            chat.bind('user.stoptyping', d => {
                if (d.sender_id === adminId) typingEl.style.display = 'none';
            });

            // Kirim event
            let typingTimer;
            input.addEventListener('input', () => {
                axios.post("{{ route('chat.typing') }}", {
                    receiver_id: adminId
                }).catch(() => {});
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    axios.post("{{ route('chat.stopTyping') }}", {
                        receiver_id: adminId
                    }).catch(() => {});
                }, 1200);
            });

            // ====== KIRIM PESAN ======
            document.getElementById('widget-form').addEventListener('submit', e => {
                e.preventDefault();
                const text = input.value.trim();
                const file = fileInput.files[0];
                if (!text && !file) return;

                const temp = {
                    message: text,
                    created_at: new Date().toISOString(),
                    sender_id: authId,
                    status: 'sending'
                };
                appendMsg(temp, 'me');
                scrollBottom();

                const fd = new FormData();
                fd.append('receiver_id', adminId);
                if (text) fd.append('message', text);
                if (file) fd.append('file', file);

                input.value = '';
                fileInput.value = '';
                preview.style.display = 'none';

                axios.post("{{ route('chat.send') }}", fd, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(() => {
                        // ganti ikon jadi satu ceklis
                        const statuses = box.querySelectorAll('.widget-bubble.me .widget-status');
                        if (statuses.length) {
                            statuses[statuses.length - 1].outerHTML =
                                '<i class="bi bi-check2 widget-status text-white-50"></i>';
                        }
                    });
            });

            // ====== PREVIEW FILE ======
            fileInput.addEventListener('change', e => {
                const f = e.target.files[0];
                if (!f) {
                    preview.style.display = 'none';
                    return;
                }
                preview.innerHTML = f.type.startsWith('image/') ? `<img src="${URL.createObjectURL(f)}">` :
                    `<i class="bi bi-paperclip me-1"></i>${f.name}`;
                preview.style.display = 'block';
            });
        })();
    </script>
</div>
