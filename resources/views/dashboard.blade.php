<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>System Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-100 via-white to-green-100 min-h-screen font-sans antialiased
    flex items-center justify-center">

    <div class="max-w-6xl mx-auto px-6 py-10 space-y-12 w-full">

        {{-- Header --}}
        <div class="text-center max-w-xl mx-auto mb-10">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-2">🖥️ Dashboard System</h1>
            <p class="text-gray-500 text-lg">Monitor system status & latest notifications in real-time.</p>
        </div>

        {{-- Grid: 2 Column Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- System Status Card --}}
            <div class="glass rounded-3xl shadow-xl p-12 border border-blue-200 min-h-[420px]
                flex flex-col items-center text-center
                {{ $status && $status->is_on ? 'bg-green-50' : 'bg-red-50' }}">

                <!-- Icon besar -->
                <div class="text-9xl mb-8 {{ $status && $status->is_on ? 'text-green-600' : 'text-red-600' }}">
                    {{ $status && $status->is_on ? '🔆' : '⚡️' }}
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-3">🔌 System Status</h2>

                <p class="text-gray-700 text-xl mb-5">
                    Status:
                    <span class="font-bold {{ $status && $status->is_on ? 'text-green-600' : 'text-red-600' }}">
                        {{ $status && $status->is_on ? 'System is ON' : 'System is OFF' }}
                    </span>
                </p>

                <!-- Info tambahan -->
                <p class="text-gray-500 text-sm italic mb-10">
                    Last updated: {{ $status ? $status->updated_at->diffForHumans() : 'N/A' }}
                </p>

                <form method="POST" action="/api/system-status" id="status-form" class="w-full max-w-xs mx-auto">
                    @csrf
                    <input type="hidden" name="is_on" value="{{ $status && $status->is_on ? 0 : 1 }}">
                    <button
                        class="w-full px-6 py-4 rounded-xl font-semibold text-white text-lg transition-all duration-300
                        {{ $status && $status->is_on ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                        {{ $status && $status->is_on ? 'Turn OFF' : 'Turn ON' }}
                    </button>
                </form>
            </div>

            {{-- Notifications Card --}}
            <div class="glass rounded-3xl shadow-xl p-10 border border-yellow-200 min-h-[420px] flex flex-col">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">📢 Notifications</h2>

                @if($notifications->isEmpty())
                    <p class="text-gray-500 italic text-center mt-10">No notifications available.</p>
                @else
                    <div class="space-y-5 overflow-y-auto pr-3 flex-grow max-h-[370px]">
                        @foreach($notifications as $notif)
                            <div
                                class="p-4 rounded-2xl border transition
                                        {{ $notif->is_read ? 'bg-gray-100 border-gray-200' : 'bg-yellow-50 border-yellow-300' }}">

                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-lg text-gray-800">{{ $notif->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $notif->message }}</p>
                                        <p class="text-xs text-gray-400 mt-1 italic">{{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="pl-4 flex-shrink-0">
                                        @if(!$notif->is_read)
                                            <form method="POST" action="/api/notifications/{{ $notif->id }}/read">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-sm text-blue-600 hover:underline">Mark as Read</button>
                                            </form>
                                        @else
                                            <span class="text-green-500 font-medium text-sm">✓ Read</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Optional JS Form Submit --}}
    <script>
        document.getElementById('status-form')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const response = await fetch('/api/system-status', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData
            });

            console.log('Response status:', response.status);
            const data = await response.json().catch(() => null);
            console.log('Response data:', data);

            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to update system status');
            }
        });
    </script>

</body>

</html>