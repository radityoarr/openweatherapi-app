<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        } elseif ($lat && $lon) {
            $locationParam = ['lat' => $lat, 'lon' => $lon];
        } else {
            $locationParam = ['q' => 'Sukolilo'];
        }

        $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/weather", array_merge($locationParam, [
            'appid' => $apiKey,
            'units' => 'metric',
        ]));

        if ($weatherResponse->ok()) {
            $weather = $weatherResponse->json();
        }

        $forecastResponse = Http::get("https://api.openweathermap.org/data/2.5/forecast", array_merge($locationParam, [
            'appid' => $apiKey,
            'units' => 'metric',
        ]));

        if ($forecastResponse->ok()) {
            $forecast = $forecastResponse->json();

            foreach ($forecast['list'] as &$data) {
                $utcTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['dt_txt'], 'UTC');
                $data['dt_txt'] = $utcTime->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            }
        }

        return view('index', compact('weather', 'forecast', 'query'));
    }
}
