@extends('layouts.app')
@section('title', 'Chat Admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4 border-end">
                <h5 class="fw-bold mb-3"><i class="bi bi-people"></i> Pelanggan</h5>
                <ul class="list-group" id="user-list">
                    @foreach ($users as $u)
                        <li class="list-group-item d-flex justify-content-between align-items-center user-item"
                            data-id="{{ $u->id }}">
                            {{ $u->nama }}
                            <span class="badge bg-success">Online</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <span id="chat-title">Pilih pengguna</span>
                    </div>
                    <div id="admin-chat-box" class="p-3" style="height:400px; overflow-y:auto;"></div>
                    <div class="card-footer">
                        <form id="admin-chat-form" class="d-flex">
                            @csrf
                            <input type="hidden" id="receiver_id" name="receiver_id">
                            <input type="text" id="admin-message" name="message" class="form-control me-2"
                                placeholder="Ketik pesan...">
                            <button class="btn btn-primary"><i class="bi bi-send"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script>
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });
        const userId = {{ Auth::id() }};
        const channel = pusher.subscribe(`private-chat.${userId}`);
        const chatBox = $('#admin-chat-box');
        let currentUserId = null;

        $('.user-item').click(function() {
            currentUserId = $(this).data('id');
            $('#receiver_id').val(currentUserId);
            $('#chat-title').text($(this).text());
            $(this).removeClass('bg-light border-start border-3 border-primary');
            $(this).find('.notif-badge').remove();
            chatBox.html('<div class="text-muted small">Memuat riwayat...</div>');
            $.get(`/chat/${currentUserId}`, function(data) {
                chatBox.html('');
                data.messages.forEach(msg => {
                    const align = msg.sender_id == userId ? 'text-end text-white bg-primary' :
                        'bg-light';
                    chatBox.append(
                        `<div class="p-2 rounded mb-1 ${align}" style="display:inline-block;">${msg.message}</div><br>`
                    );
                });
                chatBox.scrollTop(chatBox[0].scrollHeight);
            });
        });

        channel.bind('App\\Events\\MessageSent', function(data) {
            const align = data.sender_id == userId ? 'text-end text-white bg-primary' : 'bg-light';

            // 1️⃣ Jika sedang buka room pengirim → langsung tampilkan
            if (currentUserId && (data.sender_id == currentUserId || data.receiver_id == currentUserId)) {
                chatBox.append(
                    `<div class="p-2 rounded mb-1 ${align}" style="display:inline-block;">${data.message}</div><br>`
                );
                chatBox.scrollTop(chatBox[0].scrollHeight);
            } else {
                const userItem = $(`.user-item[data-id="${data.sender_id}"]`);
                if (userItem.length) {
                    userItem.addClass('bg-light border-start border-3 border-primary'); // highlight
                    userItem.append('<span class="badge bg-danger ms-2 notif-badge">Baru</span>');
                }

                // 🔔 Bunyi notifikasi ringan
                const notifSound = new Audio(
                    'https://cdn.pixabay.com/download/audio/2022/03/15/audio_9b0e64e3c5.mp3');
                notifSound.play();

                // 🧠 Jika admin sedang lihat chat lain, tampilkan toast mini (opsional)
                if (!document.hasFocus()) {
                    new Notification('Pesan Baru', {
                        body: `Pesan baru dari user #${data.sender_id}`
                    });
                }
            }
        });


        $('#admin-chat-form').on('submit', function(e) {
            e.preventDefault();
            if (!currentUserId) return;
            $.post("{{ route('chat.send') }}", $(this).serialize(), () => $('#admin-message').val(''));
        });
    </script>
@endsection
