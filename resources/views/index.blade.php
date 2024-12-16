<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .weather-widget {
            background: linear-gradient(to bottom, #4facfe, #00f2fe);
            border-radius: 15px;
            color: white;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        .weather-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }
    </style>
</head>
<body id="weatherApp" class="text-gray-800 flex items-center justify-center min-h-screen transition-all duration-500">
    <div class="p-8 rounded-lg shadow-lg w-full max-w-screen-xl bg-opacity-30 backdrop-blur-md">
        <!-- Header -->
        <h1 class="text-4xl font-bold text-center mb-6 text-white">Weather App</h1>

        <!-- Search Form -->
        <form action="{{ route('index') }}" method="GET" class="mb-8 flex justify-center">
            <input
                type="text"
                name="q"
                value="{{ $query ?? '' }}"
                placeholder="Masukkan nama kota..."
                class="border-none p-4 rounded-l w-3/4 focus:outline-none text-gray-700"
                style="background: rgba(255, 255, 255, 0.6);"
            />
            <button
                type="submit"
                class="bg-blue-500 text-white px-6 py-4 rounded-r hover:bg-blue-600 transition-all">
                Cari
            </button>
        </form>

        <!-- Current Weather Section -->
        <div class="current-weather text-center mb-8 flex justify-center items-center space-x-10">
            @if(isset($weather))
                <div>
                    <h1 class="text-4xl font-bold text-white">{{ $weather['name'] }}</h1>
                    <p class="text-lg font-light mb-4 text-gray-200">{{ now()->format('l, F j, Y') }}</p>
                    <div class="flex items-center space-x-6">
                        <p class="text-6xl font-semibold text-white">{{ $weather['main']['temp'] }}&deg;C</p>
                        <div class="text-left">
                            <p class="capitalize text-lg text-gray-100">{{ $weather['weather'][0]['description'] }}</p>
                            <p class="text-sm text-gray-300">Wind: {{ $weather['wind']['speed'] }} m/s</p>
                            <p class="text-sm text-gray-300">Humidity: {{ $weather['main']['humidity'] }}%</p>
                        </div>
                    </div>
                </div>
                <!-- Current Weather Icon -->
                <div class="flex items-center justify-center">
                    <img 
                        src="http://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@4x.png" 
                        alt="{{ $weather['weather'][0]['description'] }}" 
                        class="w-40 h-40"
                    />
                </div>
            @else
                <p class="text-red-500">Current weather data is unavailable.</p>
            @endif
        </div>

        <!-- Forecast Section -->
        <div class="forecast">
            <h2 class="text-2xl font-bold text-center mb-6 text-white">5-Day Forecast</h2>
            @if(isset($forecast))
                <div class="flex space-x-4 overflow-x-scroll scrollbar-hide">
                    @foreach($forecast['list'] as $data)
                        <div class="weather-card p-4 text-center text-white w-32 flex-shrink-0">
                            <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($data['dt_txt'])->timezone('Asia/Jakarta')->format('D, H:i') }}</p>
                            <img 
                                src="http://openweathermap.org/img/wn/{{ $data['weather'][0]['icon'] }}@2x.png" 
                                alt="{{ $data['weather'][0]['description'] }}" 
                                class="mx-auto mb-2 w-12 h-12"
                            />
                            <p class="font-semibold">{{ $data['main']['temp'] }}&deg;C</p>
                            <p class="capitalize text-xs">{{ $data['weather'][0]['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-red-500">Forecast data is unavailable.</p>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
    
            if (!urlParams.has('q') && !urlParams.has('lat') && !urlParams.has('lon')) {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        window.location.href = `?lat=${latitude}&lon=${longitude}`;
                    }, function (error) {
                        console.error("Error getting location:", error);
                    });
                } else {
                    console.warn("Geolocation tidak didukung di browser ini.");
                }
            }
        });
    
        const weatherCondition = "{{ $weather['weather'][0]['main'] ?? '' }}".toLowerCase();
    
        switch (weatherCondition) {
            case 'clear':
                weatherApp.classList.add('bg-gradient-to-br', 'from-yellow-300', 'to-orange-500');
                break;
            case 'clouds':
                weatherApp.classList.add('bg-gradient-to-br', 'from-gray-400', 'to-gray-600');
                break;
            case 'rain':
                weatherApp.classList.add('bg-gradient-to-br', 'from-blue-300', 'to-gray-700');
                break;
            default:
                weatherApp.classList.add('bg-gradient-to-br', 'from-blue-400', 'to-indigo-600');
        }
    </script>
    
</body>

</html>
