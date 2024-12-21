<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function update(Request $request)
    {
        session([
            'temperature_unit' => $request->input('temperature_unit', 'celsius'),
            'show_humidity' => $request->has('show_humidity'),
            'show_windspeed' => $request->has('show_windspeed'),
            'color_mode' => $request->input('color_mode', 'default'),
        ]);
    
        if ($request->input('color_mode') === 'custom') {
            session([
                'clear_color' => $request->input('clear_color', '255, 223, 0'),
                'clouds_color' => $request->input('clouds_color', '128, 128, 128'),
                'rain_color' => $request->input('rain_color', '0, 100, 255'),
                'clear_color_hex' => $this->rgbToHex($request->input('clear_color', '255, 223, 0')),
                'clouds_color_hex' => $this->rgbToHex($request->input('clouds_color', '128, 128, 128')),
                'rain_color_hex' => $this->rgbToHex($request->input('rain_color', '0, 100, 255')),
            ]);
        }
    
        return redirect()->route('index', $request->only(['q', 'lat', 'lon']))
        ->with('success', 'Preferences updated successfully.');
    }
    
    private function rgbToHex($rgb)
    {
        $rgbArray = explode(',', $rgb);
        return sprintf("#%02x%02x%02x", $rgbArray[0], $rgbArray[1], $rgbArray[2]);
    }    
    
}
