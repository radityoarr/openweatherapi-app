<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-400 to-indigo-600 text-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
        <!-- Header Section -->
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-700">Weather App</h1>

        <!-- Search Form -->
        <form action="{{ route('index') }}" method="GET" class="mb-6 flex space-x-4 justify-center">
            <input 
                type="text" 
                name="q" 
                value="{{ $query ?? '' }}" 
                placeholder="Masukkan nama kota..." 
                class="border p-3 rounded w-3/4 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button 
                type="submit" 
                class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Cari
            </button>
        </form>

        <!-- Weather Now Section -->
        <div class="text-center mb-6">
            @if(isset($weather))
                <h2 class="text-xl font-bold">Cuaca Saat Ini di {{ $weather['name'] }}</h2>
                <p class="text-lg">Suhu: <strong>{{ $weather['main']['temp'] }}&deg;C</strong></p>
                <p class="text-lg">Kondisi: <strong>{{ $weather['weather'][0]['description'] }}</strong></p>
            @else
                <p class="text-red-500">Data cuaca tidak tersedia.</p>
            @endif
        </div>

        <!-- Forecast Section -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Forecast 5 Hari ke Depan</h3>
            @if(isset($forecast))
                <div class="flex space-x-4 overflow-x-scroll scrollbar-hide">
                    @foreach($forecast['list'] as $data)
                        <div class="bg-gray-100 p-4 rounded shadow w-48 flex-shrink-0">
                            <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($data['dt_txt'])->format('d M Y H:i') }}</p>
                            <p>Suhu: {{ $data['main']['temp'] }}&deg;C</p>
                            <p>Kondisi: {{ $data['weather'][0]['description'] }}</p>
                            <p>Angin: {{ $data['wind']['speed'] }} m/s</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-red-500">Data forecast tidak tersedia.</p>
            @endif
        </div>
    </div>

    <script>
        if (navigator.geolocation) {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('q')) { 
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    if (!urlParams.has('lat') || !urlParams.has('lon')) {
                        window.location.href = `/?lat=${lat}&lon=${lon}`;
                    }
                }, function(error) {
                    console.error('Geolocation Error:', error);
                });
            }
        } else {
            console.error('Geolocation is not supported by this browser.');
        }
    </script>
    
</body>
</html>
