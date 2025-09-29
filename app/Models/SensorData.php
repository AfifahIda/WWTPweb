<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'logger_polinema';
    
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    '_NAME',
    '_NUMERICID',
    '_VALUE',
    '_TIMESTAMP',
    '_QUALITY',
];


    protected $casts = [
        '_VALUE' => 'decimal:2',
        '_TIMESTAMP' => 'datetime'
    ];

    /**
     * Get pH status based on value
     */
    /*public static function getPhStatus($phValue)
    {
        if ($phValue < 6.0 || $phValue > 9.0) {
            return 'critical';
        } elseif (($phValue >= 6.0 && $phValue < 6.5) || ($phValue > 8.5 && $phValue <= 9.0)) {
            return 'warning';
        } else {
            return 'normal';
        }
    }

    /**
     * Get latest pH readings for all stations
     */
    /*public static function getLatestPhReadings()
    {
        $stations = [
            'PH1_INFLUENT_SKM', 'PH2_INFLUENT_LIQ', 'PH3_BIG_FAT_TRP',
            'PH4_COAG_DAF1', 'PH5_COAG_DAF2', 'PH6_NEUTRAL2',
            'PH7_CSTR2', 'PH8_EFFLUENT'
        ];

        $data = [];
        
        foreach ($stations as $station) {
            $wwtpReading = self::where('station_name', $station)
                ->where('section', 'wwtp')
                ->where('parameter', 'pH')
                ->latest()
                ->first();
                
            $skmReading = self::where('station_name', $station)
                ->where('section', 'skm')
                ->where('parameter', 'pH')
                ->latest()
                ->first();

            $data[$station] = [
                'wwtp' => $wwtpReading ? $wwtpReading->value : null,
                'skm' => $skmReading ? $skmReading->value : null,
                'wwtp_status' => $wwtpReading ? self::getPhStatus($wwtpReading->value) : null,
                'skm_status' => $skmReading ? self::getPhStatus($skmReading->value) : null,
                'wwtp_timestamp' => $wwtpReading ? $wwtpReading->created_at : null,
                'skm_timestamp' => $skmReading ? $skmReading->created_at : null,
            ];
        }

        return $data;
    }

    /**
     * Get critical alerts in last 24 hours
     */
    /*public static function getCriticalAlerts($hours = 24)
    {
        return self::where('parameter', 'pH')
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->whereRaw('(value < 6.0 OR value > 9.0)')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'station' => $item->station_name,
                    'section' => $item->section,
                    'value' => $item->value,
                    'status' => self::getPhStatus($item->value),
                    'timestamp' => $item->created_at->toISOString(),
                    'time_ago' => $item->created_at->diffForHumans()
                ];
            });
    }

    /**
     * Get historical data for specific station
     */
    /*public static function getHistoricalData($station, $section = 'wwtp', $hours = 24)
    {
        return self::where('station_name', $station)
            ->where('section', $section)
            ->where('parameter', 'pH')
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'timestamp' => $item->created_at->toISOString(),
                    'value' => $item->value,
                    'status' => self::getPhStatus($item->value)
                ];
            });
    }

    /**
     * Store new pH reading with automatic status calculation
     */
    /*public static function storePHReading($stationName, $section, $value, $notes = null)
    {
        $status = self::getPhStatus($value);
        
        return self::create([
            'station_name' => $stationName,
            'section' => $section,
            'parameter' => 'pH',
            'value' => $value,
            'unit' => 'pH',
            'status' => $status,
            'notes' => $notes
        ]);
    }

    /**
     * Scope for filtering by station
     */
    /*public function scopeStation($query, $stationName)
    {
        return $query->where('station_name', $stationName);
    }

    /**
     * Scope for filtering by section
     */
    /*public function scopeSection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope for filtering by parameter
     */
    /*public function scopeParameter($query, $parameter)
    {
        return $query->where('parameter', $parameter);
    }

    /**
     * Scope for recent data
     */
    /*public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours($hours));
    }

    /**
     * Scope for critical values
     */
    /*public function scopeCritical($query)
    {
        return $query->where(function($q) {
            $q->where('value', '<', 6.0)
              ->orWhere('value', '>', 9.0);
        });
    }*/
}