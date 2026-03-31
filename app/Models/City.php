<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country'];

    public function weatherLogs()
    {
        return $this->hasMany(WeatherLog::class);
    }

    public function latestWeatherLog()
    {
        return $this->hasOne(WeatherLog::class)->latestOfMany('fetched_at');
    }
}
