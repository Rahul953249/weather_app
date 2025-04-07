<?php

namespace App\Controllers;
use App\Models\WeatherModel;
use CodeIgniter\Controller;

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

    public function details()
    {
        return view('weather_details');
    }

    public function saveWeatherDetails()
    {
        $data = $this->request->getJSON(true);
        $db = \Config\Database::connect();
        $builder = $db->table('detailed_weather_logs');

        $builder->insert([
            'city' => $data['city'],
            'temperature' => $data['temperature'],
            'humidity' => $data['humidity'],
            'wind_speed' => $data['wind_speed'],
            'condition' => $data['condition'], // make sure to use backticks or rename the column if necessary
            'sunrise_time' => $data['sunrise'],
            'sunset_time' => $data['sunset']
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
