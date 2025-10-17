<!-- Floating Chat Widget -->
<style>
    #chat-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 2000;
    }

    #chat-modal .modal-content {
        border-radius: 18px;
        overflow: hidden;
    }

    #chat-box {
        height: 400px;
        overflow-y: auto;
        background: #f9fafc;
    }

    .chat-bubble {
        padding: 8px 12px;
        border-radius: 12px;
        margin-bottom: 6px;
        max-width: 75%;
        word-break: break-word;
    }

    .chat-user {
        background: #0d6efd;
        color: #fff;
        margin-left: auto;
    }

    .chat-admin {
        background: #e9ecef;
        color: #333;
        margin-right: auto;
    }
</style>

<div id="chat-btn"><i class="bi bi-chat-dots fs-4"></i></div>

<div class="modal fade" id="chat-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-end modal-sm">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-headset"></i> Chat Admin</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <div id="chat-box" class="p-2"></div>
            </div>
            <div class="modal-footer">
                <form id="chat-form" class="w-100 d-flex">
                    <input type="hidden" name="receiver_id" value="1"> <!-- id admin -->
                    <input type="text" name="message" id="message" class="form-control me-2"
                        placeholder="Ketik pesan...">
                    <button class="btn btn-primary"><i class="bi bi-send"></i></button>
                </form>
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
    const channel = pusher.subscribe(`private-chat.{{ Auth::id() }}`);
    const chatBox = $('#chat-box');

    $('#chat-btn').on('click', () => $('#chat-modal').modal('show'));

    channel.bind('App\\Events\\MessageSent', function(data) {
        const align = data.sender_id == {{ Auth::id() }} ? 'chat-user ms-auto' : 'chat-admin me-auto';
        chatBox.append(`<div class="chat-bubble ${align}">${data.message}</div>`);
        chatBox.scrollTop(chatBox[0].scrollHeight);
    });

    $('#chat-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const message = $('#message').val().trim();

        if (message === '') return;

        const bubble = `
        <div class="chat-bubble chat-user ms-auto">${message}</div>
    `;
        chatBox.append(bubble);
        chatBox.scrollTop(chatBox[0].scrollHeight);
        $('#message').val('');

        $.post("{{ route('chat.send') }}", form.serialize());
    });
</script>
