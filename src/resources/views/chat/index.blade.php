<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reverb-cli // chat session</title>
    <!-- Tailwind CSS CDN untuk instant rendering tanpa build step -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        mono: ['JetBrains Mono', 'Fira Code', 'Courier New', 'monospace'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<!-- Pustaka Pusher & Laravel Echo -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.js"></script>

<script>
    // Inisialisasi Echo untuk Reverb
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: 'my-app-key',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        enabledTransports: ['ws'],
    });
    // Mendengarkan siaran real-time dari channel 'chat'
    window.Echo.channel('chat-channel')
    .listen('.message.sent', (e) => {
        console.log('Event diterima:', e);
        
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex flex-col space-y-1';
        msgDiv.innerHTML = `
            <div class="flex items-center space-x-2 text-xs">
                <span class="text-amber-400">[${e.message.user.name}]</span>
                <span class="text-zinc-600 text-[10px]">${new Date(e.message.created_at).toLocaleTimeString()}</span>
            </div>
            <div class="pl-4 text-zinc-300 whitespace-pre-wrap leading-relaxed border-l border-zinc-800">
                ${e.message.message}
            </div>
        `;
        chatContainer.appendChild(msgDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
<body class="bg-zinc-950 text-zinc-100 font-mono h-full flex flex-col justify-between selection:bg-zinc-800 selection:text-zinc-100">

    <!-- Terminal Header -->
    <header class="border-b border-zinc-800 bg-zinc-900/50 px-4 py-3 flex justify-between items-center text-xs tracking-wider">
        <div class="flex items-center space-x-2">
            <span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
            <span class="text-zinc-400">SESSION:</span>
            <span class="text-emerald-400 font-bold">@ {{ auth()->user()->name }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-zinc-500 hidden sm:inline">PROTO: REVERB-WS</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-zinc-400 hover:text-red-400 transition-colors uppercase cursor-pointer">[exit]</button>
            </form>
        </div>
    </header>

    <!-- Chat Output Stream (Terminal Screen) -->
    <main id="chat-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-3 text-sm">
        <div class="text-zinc-600 text-xs pb-2 border-b border-zinc-900">
            > system initialized. connected to reverb broadcast channel. type payload below.
        </div>
        
        @foreach($messages as $msg)
            <div class="flex flex-col space-y-1">
                <div class="flex items-center space-x-2 text-xs">
                    <span class="{{ $msg->user_id === auth()->id() ? 'text-emerald-400' : 'text-amber-400' }}">
                        [{{ $msg->user->name }}]
                    </span>
                    <span class="text-zinc-600 text-[10px]">{{ $msg->created_at->format('H:i:s') }}</span>
                </div>
                <div class="pl-4 text-zinc-300 whitespace-pre-wrap leading-relaxed border-l border-zinc-800">
                    {{ $msg->message }}
                </div>
            </div>
        @endforeach
    </main>

    <!-- Terminal Input Prompt -->
    <footer class="border-t border-zinc-800 bg-zinc-900/80 p-3 md:p-4">
        <form id="chat-form" class="flex items-center space-x-3 max-w-5xl mx-auto">
            @csrf
            <span class="text-emerald-500 font-bold select-none">&gt;</span>
            <input 
                type="text" 
                id="message-input" 
                placeholder="ketik pesan atau perintah..." 
                autocomplete="off"
                autofocus
                class="flex-1 bg-transparent text-zinc-100 placeholder-zinc-600 focus:outline-none text-sm tracking-wide"
                required
            >
            <button type="submit" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white px-4 py-1.5 text-xs rounded border border-zinc-700 transition-all uppercase tracking-wider cursor-pointer">
                Send [↵]
            </button>
        </form>
    </footer>

    <!-- Terminal Interaction Script -->
    <script>
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;

        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });

                if (response.ok) {
                    const data = await response.json();
                    messageInput.value = '';
                    
                    // Render pesan baru secara instan ke layar terminal
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'flex flex-col space-y-1';
                    msgDiv.innerHTML = `
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="text-emerald-400">[${data.message.user.name}]</span>
                            <span class="text-zinc-600 text-[10px]">${new Date().toLocaleTimeString()}</span>
                        </div>
                        <div class="pl-4 text-zinc-300 whitespace-pre-wrap leading-relaxed border-l border-zinc-800">
                            ${data.message.message}
                        </div>
                    `;
                    chatContainer.appendChild(msgDiv);
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                } else {
                    const err = await response.json();
                    console.error('Validation error:', err);
                }
            } catch (error) {
                console.error('Network error:', error);
            }
        });
    </script>
</body>
</html>