@extends('layouts.homepage')
@section('title', 'Chat Realtime')

@section('content')
    <style>
        body {
            background-color: #f8f9fc;
        }

        .chat-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .chat-sidebar {
            border-right: 1px solid #eaeaea;
            height: 70vh;
            overflow-y: auto;
        }

        .chat-header {
            font-weight: 600;
            font-size: 1.1rem;
            background: #0d6efd;
            color: #fff;
            padding: 12px 16px;
        }

        .chat-body {
            height: 55vh;
            overflow-y: auto;
            background: #f9fafc;
            padding: 15px;
        }

        .chat-footer {
            background: #fff;
            border-top: 1px solid #eaeaea;
            padding: 10px;
        }

        .chat-bubble {
            padding: 10px 14px;
            border-radius: 18px;
            max-width: 75%;
            word-wrap: break-word;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .chat-bubble.me {
            background: #0d6efd;
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .chat-bubble.other {
            background: #f1f1f1;
            color: #333;
            border-bottom-left-radius: 4px;
        }

        .chat-timestamp {
            font-size: 0.75rem;
            opacity: 0.6;
            margin-top: 4px;
        }

        .user-item {
            cursor: pointer;
            transition: all 0.2s ease;

            transition: background 0.2s ease, color 0.2s ease;
        }

        .user-item:hover,
        .user-item.active {
            background-color: #e9f1ff !important;
            border: 1px solid #0d6efd !important;
        }

        .user-item.active .fw-semibold {
            color: #0d6efd !important;
        }

        .user-item.active small {
            color: #4a4a4a !important;
        }

        .msg-status i {
            font-size: 1.1rem;
            vertical-align: middle;
            margin-left: 4px;
            transition: color 0.2s ease, transform 0.2s ease;
            opacity: 0.9;
        }

        .chat-bubble.me .msg-status i {
            color: #e8f0ff;
        }

        .chat-bubble.me .msg-status i.bi-check2-all.text-info {
            color: #00b0ff !important;/
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .search-input {
            border-radius: 30px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .chat-bubble img {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .chat-bubble a {
            font-size: 0.9rem;
            color: #fff;
        }

        .chat-bubble.other a {
            color: #0d6efd;
        }

        #typing-indicator {
            font-style: italic;
            animation: blink 1.2s infinite;
        }

        #file-preview img {
            max-height: 70px;
            border-radius: 8px;
        }

        #file-preview a {
            text-decoration: none;
            color: #333;
            font-size: 0.9rem;
        }


        @keyframes blink {

            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }
        }
    </style>

    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="chat-container row g-0">
                    <!-- Sidebar -->
                    <div class="col-md-4 chat-sidebar p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0"><i class="bi bi-chat-dots text-primary me-2"></i>Pesan</h5>
                        </div>

                        @if ($auth->role === 'admin')
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" id="search-user" class="form-control border-start-0 search-input"
                                    placeholder="Cari pengguna...">
                            </div>
                        @endif

                        <ul class="list-group" id="user-list">
                            @foreach ($users as $u)
                                <li class="list-group-item d-flex align-items-center justify-content-between user-item"
                                    data-id="{{ $u->id }}" data-name="{{ strtolower($u->nama ?? $u->name) }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar">
                                            <i class="bi bi-person-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $u->nama ?? $u->name }}</div>
                                            <small class="text-muted">{{ ucfirst($u->role) }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge rounded-pill bg-secondary unread-{{ $u->id }}"
                                            style="display:none">0</span>
                                        <span class="status-dot status-dot-{{ $u->id }}"
                                            style="background:#ccc"></span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <p id="no-user" class="text-center text-muted small mt-2" style="display:none;">Tidak ada pengguna
                            ditemukan</p>
                    </div>

                    <!-- Chat Window -->
                    <div class="col-md-8 d-flex flex-column">
                        <div class="chat-header d-flex justify-content-between align-items-center">
                            <span id="chat-title">Pilih pengguna</span>
                            <small class="opacity-75"><i class="bi bi-shield-lock"></i></small>
                        </div>

                        <div class="chat-body flex-grow-1" id="chat-box">
                            <div class="text-muted small text-center mt-5">💬 Belum ada percakapan.</div>
                        </div>
                        <div id="typing-indicator" class="text-muted small ps-3 py-1" style="display:none;">
                            <i class="bi bi-pencil"></i> Sedang mengetik...
                        </div>

                        <div class="chat-footer">
                            <form id="chat-form" class="d-flex gap-2" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="receiver_id">
                                <label for="chat-file"
                                    class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-paperclip"></i>
                                </label>
                                <input type="file" id="chat-file" style="display:none;">
                                <input type="text" id="chat-input" class="form-control rounded-pill"
                                    placeholder="Ketik pesan..." disabled>
                                <button class="btn btn-primary rounded-circle px-3" id="btn-send" disabled>
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </form>
                            <div id="file-preview" class="mt-2 px-2" style="display:none;">
                                <div class="border rounded p-2 d-flex align-items-center justify-content-between bg-light">
                                    <div id="file-preview-content" class="d-flex align-items-center gap-2"></div>
                                    <button type="button" id="remove-preview"
                                        class="btn btn-sm btn-outline-danger rounded-circle">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>

    <script>
        $(document).ready(function() {
            const authId = {{ (int) $auth->id }};
            const adminId = {{ (int) ($admin->id ?? 0) }};

            let activeUserId = null;
            const csrf = $('meta[name="csrf-token"]').attr('content');

            // === SETUP PUSHER ===
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

            const chatChannel = pusher.subscribe('private-chat.' + authId);
            const presence = pusher.subscribe('presence-online');

            // === PESAN MASUK ===
            chatChannel.bind('message.sent', function(m) {
                if (activeUserId && (m.sender_id === activeUserId || m.receiver_id === activeUserId)) {
                    appendMessage(m, m.sender_id === authId ? 'me' : 'other');
                    scrollBottom();
                } else {
                    const partnerId = (m.sender_id === authId) ? m.receiver_id : m.sender_id;
                    const badge = $('.unread-' + partnerId);
                    if (badge.length) badge.show().text(parseInt(badge.text() || '0') + 1);
                }

                if (m.is_read && m.sender_id === authId) {
                    $('.chat-bubble.me .msg-status i')
                        .attr('class', 'bi bi-check2-all text-info');
                }
            });

            chatChannel.bind('message.read', function(data) {
                console.log('Pesan dibaca oleh:', data.reader_id);

                $('.chat-bubble.me .msg-status i')
                    .attr('class', 'bi bi-check2-all text-info'); // 💙 dua ceklis
            });


            // === STATUS ONLINE ===
            presence.bind('pusher:subscription_succeeded', members => {
                members.each(member => setOnline(member.id, true));
            });
            presence.bind('pusher:member_added', member => setOnline(member.id, true));
            presence.bind('pusher:member_removed', member => setOnline(member.id, false));

            function setOnline(uid, status) {
                const dot = $('.status-dot-' + uid);
                dot.css('background', status ? '#28a745' : '#ccc');
            }

            // === PILIH USER ===
            $('.user-item').on('click', function() {
                $('.user-item').removeClass('active');
                $(this).addClass('active');

                const uid = $(this).data('id');
                openChat(uid);
            });

            function openChat(uid) {
                activeUserId = uid;
                $('#receiver_id').val(uid);
                $('#chat-input, #btn-send').prop('disabled', false);

                const li = $('.user-item[data-id="' + uid + '"]');
                $('#chat-title').text(li.find('.fw-semibold').text() || ('User #' + uid));

                const badge = $('.unread-' + uid);
                badge.text('0').hide();

                axios.get("{{ route('chat.fetch', ['user' => '__ID__']) }}".replace('__ID__', uid))
                    .then(res => {
                        const box = $('#chat-box');
                        box.empty();
                        res.data.forEach(m => appendMessage(m, m.sender_id === authId ? 'me' : 'other'));
                        scrollBottom();
                    });

                axios.post("{{ route('chat.read', ['user' => '__ID__']) }}".replace('__ID__', uid))
                    .catch(() => console.warn('Gagal update read status'));
            }

            // === KIRIM PESAN ===
            $('#chat-form').on('submit', function(e) {
                e.preventDefault();
                const receiver = $('#receiver_id').val();
                const text = $('#chat-input').val().trim();
                const file = $('#chat-file')[0].files[0];
                if (!receiver || (!text && !file)) return;

                const tempId = 'temp-' + Date.now();
                appendMessage({
                    id: tempId,
                    message: text || (file ? file.name : ''),
                    created_at: new Date().toISOString(),
                    status: 'sending',
                    file_path: file ? URL.createObjectURL(file) : null,
                    file_type: file ? file.type : null
                }, 'me');

                scrollBottom();
                $('#chat-input').val('');
                $('#chat-file').val('');

                const formData = new FormData();
                formData.append('receiver_id', receiver);
                if (text) formData.append('message', text);
                if (file) formData.append('file', file);

                axios.post("{{ route('chat.send') }}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        const bubble = $('.chat-bubble[data-id="' + tempId + '"]');
                        bubble.attr('data-id', res.data.id);
                        bubble.find('.msg-status').html('<i class="bi bi-check2"></i>');
                    })
                    .catch(() => {
                        const bubble = $('.chat-bubble[data-id="' + tempId + '"]');
                        bubble.addClass('bg-danger');
                        bubble.find('.msg-status').html('<i class="bi bi-exclamation-triangle"></i>');
                    });
            });

            // === TAMPILKAN PESAN ===
            function appendMessage(m, who) {
                const box = $('#chat-box');
                const align = who === 'me' ? 'align-items-end' : 'align-items-start';
                const bubble = $('<div class="chat-bubble ' + who + '"></div>').attr('data-id', m.id);

                // status icon
                let icon = '';
                if (who === 'me') {
                    if (m.status === 'sending') icon = '<i class="bi bi-hourglass-split text-white-50"></i>';
                    else if (!m.is_read) icon = '<i class="bi bi-check2 text-white-50"></i>';
                    else icon = '<i class="bi bi-check2-all text-info"></i>';
                }

                let content = '';
                if (m.file_path) {
                    const ext = (m.file_type || '').split('/')[0];
                    if (ext === 'image') {
                        content =
                            `<img src="/storage/${m.file_path}" class="img-fluid rounded mb-2" style="max-width:200px;">`;
                    } else {
                        content = `
            <a href="/storage/${m.file_path}" target="_blank" class="d-flex align-items-center text-decoration-none mb-2">
                <i class="bi bi-paperclip me-2"></i> ${m.file_name || 'File'}
            </a>
        `;
                    }
                }

                content += `<div>${escapeHtml(m.message || '')}</div>`;

                bubble.html(`
    <div>${content}</div>
    <div class="d-flex align-items-center justify-content-end gap-1 chat-timestamp">
        ${new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
        <span class="msg-status">${icon}</span>
    </div>
`);


                const wrap = $('<div class="d-flex flex-column mb-2 ' + align + '"></div>').append(bubble);
                box.append(wrap);
            }

            function scrollBottom() {
                const box = $('#chat-box');
                box.scrollTop(box.prop('scrollHeight'));
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

            // === FILTER USER (ADMIN SAJA) ===
            $('#search-user').on('input', function() {
                const keyword = $(this).val().trim().toLowerCase();
                let visible = 0;

                $('#user-list .user-item').each(function() {
                    const name = $(this).find('.fw-semibold').text().toLowerCase();
                    const role = $(this).find('small').text().toLowerCase();
                    const match = name.includes(keyword) || role.includes(keyword);

                    if (match) {
                        $(this).css({
                            display: 'flex',
                            visibility: 'visible',
                            height: ''
                        });
                        visible++;
                    } else {
                        $(this).css({
                            display: 'none',
                            visibility: 'hidden',
                            height: '0'
                        });
                    }
                });

                $('#no-user').toggle(visible === 0);
            });

            // === KETIK (REALTIME) ===
            let typingTimeout;
            let lastTypingTime = 0;

            $('#chat-input').on('input', function() {
                const receiver = $('#receiver_id').val();
                const now = Date.now();

                if (receiver && now - lastTypingTime > 1000) {
                    axios.post("{{ route('chat.typing') }}", {
                        receiver_id: receiver
                    });
                    lastTypingTime = now;
                }

                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => {
                    axios.post("{{ route('chat.stopTyping') }}", {
                        receiver_id: receiver
                    });
                }, 1500);
            });

            // === TERIMA EVENT TYPING ===
            chatChannel.bind('user.typing', function(data) {
                if (activeUserId && data.sender_id === activeUserId) {
                    $('#typing-indicator').stop(true, true).fadeIn(200);
                }
            });

            chatChannel.bind('user.stoptyping', function(data) {
                if (activeUserId && data.sender_id === activeUserId) {
                    $('#typing-indicator').stop(true, true).fadeOut(200);
                }
            });

            // === PREVIEW FILE SEBELUM KIRIM ===
            $('#chat-file').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    $('#file-preview').hide();
                    return;
                }

                const ext = file.type.split('/')[0];
                let content = '';

                if (ext === 'image') {
                    const url = URL.createObjectURL(file);
                    content = `<img src="${url}" alt="Preview" class="me-2">`;
                } else {
                    content = `<i class="bi bi-paperclip me-2"></i> ${file.name}`;
                }

                $('#file-preview-content').html(content);
                $('#file-preview').slideDown(150);
            });

            // === HAPUS PREVIEW FILE ===
            $('#remove-preview').on('click', function() {
                $('#chat-file').val('');
                $('#file-preview').slideUp(150);
            });

            if ("{{ $auth->role }}" === "customer" && adminId) {
                openChat(adminId);
                $('.chat-sidebar').hide();
                $('.col-md-8').removeClass('col-md-8').addClass('col-12');
                $('#chat-title').text('Chat dengan Admin');
            }

            // === KEEP ONLINE ===
            setInterval(() => axios.post("{{ route('chat.ping') }}"), 60000);
        });
    </script>
@endpush
