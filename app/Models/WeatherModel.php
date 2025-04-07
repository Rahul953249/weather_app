<?php

namespace App\Models;

use CodeIgniter\Model;

class WeatherModel extends Model
{
    protected $table = 'weather_log';
    protected $allowedFields = ['city', 'temperature', 'condition'];
}
