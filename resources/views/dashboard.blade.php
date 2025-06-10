<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>System Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.3);
            border-radius: 10px;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-100 via-white to-green-100 min-h-screen font-sans antialiased flex items-center justify-center px-4">

    <div class="max-w-6xl mx-auto w-full space-y-12 py-12">

        <!-- Header -->
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-gray-800 mb-4">🖥️ System Dashboard</h1>
            <p class="text-gray-500 text-lg">Monitor system status and notifications in real-time</p>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- System Status Card -->
            <div class="glass rounded-3xl p-12 shadow-2xl flex flex-col items-center justify-center text-center min-h-[460px]
    {{ $status && $status->is_on ? 'bg-green-50' : 'bg-red-50' }}">

                <div class="flex items-center justify-center w-28 h-28 mb-8 rounded-full
        {{ $status && $status->is_on ? 'bg-green-100 text-green-600 animate-pulse' : 'bg-red-100 text-red-600' }}">
                    <span class="text-9xl select-none">
                        {{ $status && $status->is_on ? '🔆' : '⚡️' }}
                    </span>
                </div>

                <h2 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-wide">System Status</h2>

                <p class="text-xl text-gray-800 mb-6">
                    Status:
                    <span class="font-bold
            {{ $status && $status->is_on ? 'text-green-700' : 'text-red-700' }}">
                        {{ $status && $status->is_on ? 'System is ON' : 'System is OFF' }}
                    </span>
                </p>

                <p class="text-sm text-gray-500 italic tracking-wide">
                    Last updated: {{ $status ? $status->updated_at->diffForHumans() : 'N/A' }}
                </p>

            </div>

            <!-- Notifications Card -->
            <div class="glass rounded-3xl p-8 shadow-xl flex flex-col min-h-[440px] border border-yellow-300">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">📢 Notifications</h2>

                @if($notifications->isEmpty())
                    <p class="text-gray-500 italic text-center mt-10">No notifications available.</p>
                @else
                    <div class="space-y-4 overflow-y-auto pr-2 flex-grow max-h-[350px]">
                        @foreach($notifications as $notif)
                            <div
                                class="p-4 rounded-xl border {{ $notif->is_read ? 'bg-gray-100 border-gray-200' : 'bg-yellow-50 border-yellow-300' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $notif->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $notif->message }}</p>

                                        {{-- Tampilkan data JSON dengan style rapi --}}
                                        @php
                                            $data = json_decode($notif->data, true);
                                        @endphp

                                        @if($data)
                                            <div
                                                class="mt-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm text-left text-sm text-gray-700">
                                                <div class="flex flex-wrap gap-4">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-semibold text-gray-900">Sensor:</span>
                                                        <span
                                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $data['sensor'] ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-semibold text-gray-900">Value:</span>
                                                        <span
                                                            class="px-2 py-1 bg-green-100 text-green-800 rounded">{{ $data['value'] ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-semibold text-gray-900">Device:</span>
                                                        <span
                                                            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">{{ $data['device'] ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
                                            <span class="text-green-600 text-sm font-medium">✓ Read</span>
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

    <!-- JS for Status Form -->
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

            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to update system status.');
            }
        });
    </script>

</body>

</html>