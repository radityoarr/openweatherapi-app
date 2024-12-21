<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Preferences</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Manage Preferences</h1>
        
        <form action="{{ route('settings.update', request()->only(['q', 'lat', 'lon'])) }}" method="POST">
            @csrf
            @method('POST')

            <!-- Temperature Unit -->
            <div class="mb-4">
                <label for="temperature_unit" class="block mb-2 font-semibold">Temperature Unit</label>
                <select id="temperature_unit" name="temperature_unit" class="w-full p-2 bg-gray-700 rounded">
                    <option value="celsius" {{ session('temperature_unit') === 'celsius' ? 'selected' : '' }}>Celsius</option>
                    <option value="fahrenheit" {{ session('temperature_unit') === 'fahrenheit' ? 'selected' : '' }}>Fahrenheit</option>
                </select>
            </div>

            <!-- Show/Hide Options -->
            <div class="mb-4">
                <label class="block mb-2 font-semibold">Show/Hide Options</label>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="show_humidity" class="mr-2"
                               {{ session('show_humidity', true) ? 'checked' : '' }}>
                        Show Humidity
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="show_windspeed" class="mr-2"
                               {{ session('show_windspeed', true) ? 'checked' : '' }}>
                        Show Wind Speed
                    </label>
                </div>
            </div>

            <!-- Color Mode -->
            <div class="mb-4">
                <label for="color_mode" class="block mb-2 font-semibold">Color Mode</label>
                <select id="color_mode" name="color_mode" class="w-full p-2 bg-gray-700 rounded">
                    <option value="default" {{ session('color_mode', 'default') === 'default' ? 'selected' : '' }}>Default</option>
                    <option value="custom" {{ session('color_mode', 'default') === 'custom' ? 'selected' : '' }}>Custom RGB</option>
                </select>
            </div>

            <!-- Custom Colors -->
            <div id="customColors" class="mb-4 space-y-4" style="display: {{ session('color_mode', 'default') === 'custom' ? 'block' : 'none' }}">
                <div>
                    <label for="clear_color" class="block mb-2 font-semibold">Clear Sky</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="clear_color_picker" value="{{ session('clear_color_hex', '#FFDF00') }}" class="w-12 h-12 p-0 border-none cursor-pointer">
                        <input type="text" name="clear_color" id="clear_color" class="w-full p-2 bg-gray-700 rounded" 
                            value="{{ session('clear_color', '255, 223, 0') }}" readonly>
                    </div>
                </div>
                <div>
                    <label for="clouds_color" class="block mb-2 font-semibold">Clouds</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="clouds_color_picker" value="{{ session('clouds_color_hex', '#808080') }}" class="w-12 h-12 p-0 border-none cursor-pointer">
                        <input type="text" name="clouds_color" id="clouds_color" class="w-full p-2 bg-gray-700 rounded" 
                            value="{{ session('clouds_color', '128, 128, 128') }}" readonly>
                    </div>
                </div>
                <div>
                    <label for="rain_color" class="block mb-2 font-semibold">Rain</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="rain_color_picker" value="{{ session('rain_color_hex', '#0064FF') }}" class="w-12 h-12 p-0 border-none cursor-pointer">
                        <input type="text" name="rain_color" id="rain_color" class="w-full p-2 bg-gray-700 rounded" 
                            value="{{ session('rain_color', '0, 100, 255') }}" readonly>
                    </div>
                </div>
            </div>


            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
                Save Preferences
            </button>
        </form>

        <div class="mt-4 text-center">
            <a 
                href="{{ route('index', request()->only(['q', 'lat', 'lon'])) }}" 
                class="text-blue-400 hover:underline">
                Back to Weather
            </a>
        </div>        
    </div>

    <script>
        document.getElementById('color_mode').addEventListener('change', function () {
            document.getElementById('customColors').style.display = this.value === 'custom' ? 'block' : 'none';
        });
    </script>

    <script>
        function hexToRgb(hex) {
            const bigint = parseInt(hex.slice(1), 16);
            const r = (bigint >> 16) & 255;
            const g = (bigint >> 8) & 255;
            const b = bigint & 255;
            return `${r}, ${g}, ${b}`;
        }
    
        document.addEventListener('DOMContentLoaded', () => {
            const colorPickers = [
                { picker: 'clear_color_picker', input: 'clear_color' },
                { picker: 'clouds_color_picker', input: 'clouds_color' },
                { picker: 'rain_color_picker', input: 'rain_color' },
            ];
    
            colorPickers.forEach(({ picker, input }) => {
                const pickerElement = document.getElementById(picker);
                const inputElement = document.getElementById(input);
    
                pickerElement.addEventListener('input', (event) => {
                    const hex = event.target.value;
                    const rgb = hexToRgb(hex);
                    inputElement.value = rgb;
                });
            });
        });
    </script>
    
</body>
</html>
