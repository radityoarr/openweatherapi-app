<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $apiKey = config('services.openweather.key');

        $weather = null;
        $forecast = null;

        if ($query) {
            $locationParam = ['q' => $query];
        } 
        elseif ($lat && $lon) {
            $locationParam = ['lat' => $lat, 'lon' => $lon];
        } else {
            $locationParam = ['q' => 'Sukolilo'];
        }
        Log::info('Weather API request parameters:', $locationParam);

        $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/weather", array_merge($locationParam, [
            'appid' => $apiKey,
            'units' => 'metric',
        ]));

        if ($weatherResponse->ok()) {
            $weather = $weatherResponse->json();
            Log::info('Current weather data:', $weather);
        } else {
            Log::error('Weather API failed:', ['status' => $weatherResponse->status()]);
        }

        $forecastResponse = Http::get("https://api.openweathermap.org/data/2.5/forecast", array_merge($locationParam, [
            'appid' => $apiKey,
            'units' => 'metric',
        ]));

        if ($forecastResponse->ok()) {
            $forecast = $forecastResponse->json();
            Log::info('Raw forecast data:', $forecast);
        
            $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
            Log::info('Current time (Asia/Jakarta):', ['current_time' => $currentTime]);
        
            $forecast['list'] = array_filter($forecast['list'], function ($data) use ($currentTime) {
                $forecastTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['dt_txt'], 'UTC')
                    ->setTimezone('Asia/Jakarta');
                Log::info('Forecast time:', ['forecast_time' => $forecastTime, 'dt_txt' => $data['dt_txt']]);
                return $forecastTime >= $currentTime;
            });
        
        }
        
        return view('index', compact('weather', 'forecast', 'query'));
    }
}
