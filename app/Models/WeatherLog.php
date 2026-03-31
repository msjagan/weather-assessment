<?php

namespace App\Models;

use App\Events\WeatherUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    use HasFactory;

    protected $casts = [
        'fetched_at' => 'datetime',
    ];

    protected $fillable = [
        'city_id',
        'temperature',
        'humidity',
        'wind_speed',
        'description',
        'fetched_at'
    ];

    // Task 3: Event Listener on booted()
    protected static function booted(): void
    {
        static::created(function (WeatherLog $weatherLog): void {
            $previousLog = WeatherLog::where('city_id', $weatherLog->city_id)
                ->where('id', '!=', $weatherLog->id)
                ->orderBy('fetched_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if (!$previousLog) {
                return;
            }

            $oldTemp = (float) $previousLog->temperature;
            $newTemp = (float) $weatherLog->temperature;

            if (abs($newTemp - $oldTemp) > 3) {
                event(new WeatherUpdated($weatherLog->city, $oldTemp, $newTemp));
            }
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
