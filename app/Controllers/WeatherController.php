<?php

namespace App\Controllers;
use App\Models\WeatherModel;

class WeatherController extends BaseController
{
    public function index()
    {
        return view('weather_home');
    }

    public function getWeather()
    {
        $city = $this->request->getVar('city');
        $apiKey = 'ebb26c5e7fe37c3d77a0a87ee9770299';
        $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey . "&units=metric";

        $weatherData = file_get_contents($url);
        return $this->response->setJSON(json_decode($weatherData));
    }

    public function saveWeather()
    {
        $data = $this->request->getJSON(true);
        $model = new WeatherModel();
        $model->insert([
            'city' => $data['city'],
            'temperature' => $data['temperature'],
            'condition' => $data['condition']
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
