<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Associates extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'affiliate_id',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    // Scope to use a MySQL function to filter associates out of range
    public function scopeInRange(Builder $query, float $range, float $latitude, float $longitude): void
    {
        $query->whereRaw("calculate_distance($latitude, $longitude, latitude, longitude) <= $range");
    }

    /*
    * Determines if an associate is within a given distance away from a lat/long point
    * Can be used to determine individually if an associate is within range,
    * but is much slower than the MySQL function when filtering groups
    */
    public function isWithinRange(float $range, float $longitude, float $latitude)
    {
        // Convert degrees to radians
        $longitude = deg2rad($longitude);
        $latitude = deg2rad($latitude);

        // Haversine formula
        $diffLatitude = deg2rad($this->latitude) - $latitude;
        $diffLongitude = deg2rad($this->longitude) - $longitude;
        $a = pow(sin($diffLatitude / 2), 2) +
            (cos($latitude) * cos(deg2rad($this->latitude))) *
            pow(sin($diffLongitude / 2), 2)
        ;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = 6371 * $c;  // Earth's radius in kilometers

        return $distance <= $range;
    }
}
