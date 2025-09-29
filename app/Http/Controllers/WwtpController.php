<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WWTPController extends Controller
{
    private $chartConfig = [
        // pH Charts
        'chart1' => ['name' => 'WWTP_PWS.PLC1.PH1_INFLUENT_SKM', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart2' => ['name' => 'WWTP_PWS.PLC1.PH2_INFLUENT_LIQ', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart3' => ['name' => 'WWTP_PWS.PLC1.PH3_BIG_FAT_TRP', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart4' => ['name' => 'WWTP_PWS.PLC1.PH_EQ3', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart5' => ['name' => 'WWTP_PWS.PLC1.PH4_COAG_DAF1', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart6' => ['name' => 'WWTP_PWS.PLC1.PH5_COAG_DAF2', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart7' => ['name' => 'WWTP_PWS.PLC1.PH6_NEUTRAL2', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],
        'chart8' => ['name' => 'WWTP_PWS.PLC1.PH8_EFFLUENT', 'min' => 0.0, 'max' => 14.0, 'unit' => 'pH', 'type' => 'ph'],

        // Flow Charts
        'chart10' => ['name' => 'WWTP_PWS.PLC1.FLOW_SKM', 'min' => 0.0, 'max' => 150.0, 'unit' => 'm³/h', 'type' => 'flow'],
        'chart11' => ['name' => 'WWTP_PWS.PLC1.FLOW_LIQ', 'min' => 0.0, 'max' => 150.0, 'unit' => 'm³/h', 'type' => 'flow'],
        'chart12' => ['name' => 'WWTP_PWS.PLC1.FM_KANTIN', 'min' => 0.0, 'max' => 150.0, 'unit' => 'm³/h', 'type' => 'flow']
    ];

    public function index()
    {
        return view('pages.wwtp.index');
    }

    private function sanitizeValue($val, $config)
    {
        if ($val === null || $val === '') return null;
        $val = str_replace(',', '.', trim($val));
        if (!is_numeric($val)) return null;
        $num = (float) $val;
        return round(max($config['min'], min($config['max'], $num)), 2);
    }

    // ===================== SSE Stream =====================
    public function stream()
    {
        return response()->stream(function () {
            while (true) {
                try {
                    $charts = [];
                    foreach ($this->chartConfig as $chartId => $config) {
                        $value = DB::table('logger_polinema')
                            ->where('_NAME', $config['name'])
                            ->orderByDesc('_TIMESTAMP')
                            ->value('_VALUE');
                        $charts[$chartId] = array_merge($config, [
                            'value' => $this->sanitizeValue($value, $config),
                            'timestamp' => now('Asia/Jakarta')->format('H:i:s')
                        ]);
                    }

                    
                    $payload = [
                        'charts' => $charts,
                        'system_status' => 'operational',
                        'last_updated' => now('Asia/Jakarta')->toDateTimeString()
                    ];

                    echo "data: " . json_encode($payload) . "\n\n";
                    ob_flush(); flush();
                    if (connection_aborted()) break;
                    sleep(2);
                } catch (\Exception $e) {
                    Log::error("SSE Stream error: " . $e->getMessage());
                    sleep(5);
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no'
        ]);
    }

    // ===================== Guaranteed Stream =====================
    public function streamData(Request $request)
    {
        set_time_limit(0);
        ignore_user_abort(false);

        return response()->stream(function () {
            while (true) {
                try {
                    $data = $this->generateGuaranteedWWTPData();
                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush(); flush();
                    sleep(2);
                    if (connection_aborted()) break;
                } catch (\Exception $e) {
                    Log::error("StreamData error: " . $e->getMessage());
                    sleep(2);
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    private function generateGuaranteedWWTPData()
    {
        $charts = [];
        foreach ($this->chartConfig as $chartId => $config) {
            $record = DB::table('logger_polinema')
                ->where('_NAME', $config['name'])
                ->orderByDesc('_TIMESTAMP')
                ->first();

            $value = $record ? $this->sanitizeValue($record->_VALUE, $config) : null;
            $timestamp = $record ? Carbon::parse($record->_TIMESTAMP)->format('Y-m-d H:i:s') : now('Asia/Jakarta')->toDateTimeString();

            $charts[$chartId] = array_merge($config, [
                'value' => $value,
                'timestamp' => $timestamp
            ]);
        }

        return [
            'charts' => $charts,
            'system_status' => 'operational',
            'last_updated' => now('Asia/Jakarta')->toDateTimeString()
        ];
    }

    // ===================== Historical Data =====================
public function getHistoricalData(Request $request)
{
    $hours = max(1, min(24, intval($request->get('hours', 5))));
    $now = Carbon::now('Asia/Jakarta');
    $startTime = $now->copy()->subHours($hours);

    // buat label setiap 30 menit
    $labels = [];
    $time = $startTime->copy()->minute(0)->second(0);
    while ($time <= $now) {
        $labels[] = $time->format('H:i');
        $time->addMinutes(30);
    }

    $rows = DB::table('logger_polinema')
        ->whereIn('_NAME', array_column($this->chartConfig, 'name'))
        ->whereBetween('_TIMESTAMP', [$startTime, $now])
        ->orderBy('_TIMESTAMP')
        ->get();

    $dataMap = [];
foreach ($rows as $row) {
    // Parse sekali saja dan set zona Asia/Jakarta
    $timeObj = Carbon::parse($row->_TIMESTAMP, 'Asia/Jakarta');

    // Bulatkan ke bawah ke 0 atau 30 menit
    $roundedMinutes = $timeObj->minute >= 30 ? 30 : 0;

    // Buat string slot, misalnya "10:30"
    $slot = $timeObj->copy()
        ->minute($roundedMinutes)
        ->second(0)
        ->format('H:i');

    $config = collect($this->chartConfig)->firstWhere('name', $row->_NAME);
    if ($config) {
        $dataMap[$row->_NAME][$slot][] =
            $this->sanitizeValue($row->_VALUE, $config);
    }
}


    $datasets = [];
    foreach ($this->chartConfig as $config) {
        $sensorName = $config['name'];
        $data = [];
        foreach ($labels as $label) {
            if (!empty($dataMap[$sensorName][$label])) {
                // ambil rata-rata slot 30 menit
                $slotValues = $dataMap[$sensorName][$label];
                $data[] = end($slotValues);   // ⬅️ pakai nilai terakhir saja
            } else {
                $data[] = null;
            }
        }
        $datasets[] = [
            'label' => $sensorName,
            'unit'  => $config['unit'],
            'data'  => $data,
            'min'   => $config['min'],
            'max'   => $config['max'],
            'type'  => $config['type']
        ];
    }

    return response()->json([
        'success' => true,
        'labels'  => $labels,
        'datasets'=> $datasets,
        'period'  => "{$hours} hours",
        'total_points' => count($labels)
    ]);
}


    // ===================== Chart Config =====================
    public function getChartConfig()
    {
        $phCharts = [];
        $flowCharts = [];

        foreach ($this->chartConfig as $chartId => $config) {
            $chartInfo = [
                'id' => $chartId,
                'name' => $config['name'],
                'unit' => $config['unit'],
                'min' => $config['min'],
                'max' => $config['max'],
                'type' => $config['type']
            ];
            if ($config['type'] === 'ph') $phCharts[] = $chartInfo;
            else $flowCharts[] = $chartInfo;
        }

        return response()->json([
            'total_charts' => count($this->chartConfig),
            'ph_charts' => $phCharts,
            'flow_charts' => $flowCharts
        ]);
    }

    // ===================== Simple Data Fallback =====================
    public function getSimpleData()
    {
        try {
            return response()->json($this->generateGuaranteedWWTPData());
        } catch (\Exception $e) {
            Log::error("Simple data error: " . $e->getMessage());
            return response()->json([
                'charts' => [],
                'system_status' => 'error',
                'error' => 'Data temporarily unavailable',
                'last_updated' => now('Asia/Jakarta')->toDateTimeString()
            ]);
        }
    }

    // ===================== System Status =====================
    public function getSystemStatus()
    {
        try {
            $activeCount = 0;
            $sensorStatus = [];

            foreach ($this->chartConfig as $chartId => $config) {
                $lastRecord = DB::table('logger_polinema')
                    ->where('_NAME', $config['name'])
                    ->orderByDesc('_TIMESTAMP')
                    ->first();

                if ($lastRecord) {
                    $lastUpdate = Carbon::parse($lastRecord->_TIMESTAMP)->timezone('Asia/Jakarta');
                    $minutesAgo = $lastUpdate->diffInMinutes(now());

                    $status = 'active';
                    if ($minutesAgo > 5) $status = 'inactive';
                    elseif ($minutesAgo > 2) $status = 'warning';
                    else $activeCount++;

                    $sensorStatus[$chartId] = [
                        'name' => $config['name'],
                        'type' => $config['type'],
                        'unit' => $config['unit'],
                        'status' => $status,
                        'last_value' => $this->sanitizeValue($lastRecord->_VALUE, $config),
                        'last_update' => $lastUpdate->format('Y-m-d H:i:s'),
                        'minutes_ago' => $minutesAgo
                    ];
                } else {
                    $sensorStatus[$chartId] = [
                        'name' => $config['name'],
                        'type' => $config['type'],
                        'unit' => $config['unit'],
                        'status' => 'no_data',
                        'last_value' => null,
                        'last_update' => null,
                        'minutes_ago' => null
                    ];
                }
            }

            $totalSensors = count($this->chartConfig);
            $healthPercentage = ($activeCount / $totalSensors) * 100;

            return response()->json([
                'status' => $healthPercentage > 75 ? 'operational' : ($healthPercentage > 50 ? 'degraded' : 'critical'),
                'uptime' => number_format($healthPercentage, 1) . '%',
                'connected_sensors' => $activeCount,
                'total_sensors' => $totalSensors,
                'sensor_details' => $sensorStatus,
                'data_quality' => [
                    'percentage' => $healthPercentage,
                    'quality' => $healthPercentage > 75 ? 'good' : ($healthPercentage > 50 ? 'fair' : 'poor')
                ],
                'last_check' => now('Asia/Jakarta')->toDateTimeString(),
                'alerts' => $this->getSystemAlerts($sensorStatus)
            ]);
        } catch (\Exception $e) {
            Log::error("System status error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to retrieve system status',
                'timestamp' => now('Asia/Jakarta')->toDateTimeString()
            ], 500);
        }
    }

    private function getSystemAlerts($sensorStatus)
    {
        $alerts = [];
        foreach ($sensorStatus as $chartId => $status) {
            if ($status['status'] === 'inactive') {
                $alerts[] = [
                    'type' => 'error',
                    'sensor' => $chartId,
                    'message' => "Sensor {$chartId} has been inactive for more than 5 minutes"
                ];
            } elseif ($status['status'] === 'warning') {
                $alerts[] = [
                    'type' => 'warning',
                    'sensor' => $chartId,
                    'message' => "Sensor {$chartId} data delay detected"
                ];
            }

            if ($status['last_value'] !== null) {
                if ($status['type'] === 'ph') {
                    if ($status['last_value'] < 4.0 || $status['last_value'] > 10.0) {
                        $alerts[] = [
                            'type' => 'critical',
                            'sensor' => $chartId,
                            'message' => "pH extreme ({$status['last_value']}) detected"
                        ];
                    } elseif ($status['last_value'] < 6.0 || $status['last_value'] > 9.0) {
                        $alerts[] = [
                            'type' => 'warning',
                            'sensor' => $chartId,
                            'message' => "pH out of optimal range ({$status['last_value']})"
                        ];
                    }
                } elseif ($status['type'] === 'flow') {
                    if ($status['last_value'] > 90.0) {
                        $alerts[] = [
                            'type' => 'warning',
                            'sensor' => $chartId,
                            'message' => "High flow detected ({$status['last_value']} m³/h)"
                        ];
                    } elseif ($status['last_value'] < 1.0 && strpos($status['name'], 'TOTAL') !== false) {
                        $alerts[] = [
                            'type' => 'warning',
                            'sensor' => $chartId,
                            'message' => "Very low total flow ({$status['last_value']} m³/h)"
                        ];
                    }
                }
            }
        }
        return $alerts;
    }

    /**
     * Force chart value untuk testing/debugging
     */
    public function forceChart(Request $request, $chartId)
    {
        if (!isset($this->chartConfig[$chartId])) {
            return response()->json(['error' => 'Chart not found'], 404);
        }

        $value = $request->get('value', null);
        $config = $this->chartConfig[$chartId];

        // Set default value based on chart type if not provided
        if ($value === null) {
            $value = $config['type'] === 'ph' ? 7.0 : 0.0;
        }

        // Validate and clamp value to appropriate range
        $value = floatval($value);
        if (!is_numeric($value) || is_nan($value) || is_infinite($value)) {
            $value = $config['type'] === 'ph' ? 7.0 : 0.0;
        }

        $clampedValue = max($config['min'], min($config['max'], $value));

        // Optionally update database for testing
        if ($request->get('persist', false)) {
            try {
                DB::table('logger_polinema')->insert([
                    '_NAME' => $config['name'],
                    '_VALUE' => $clampedValue,
                    '_TIMESTAMP' => now(),
                    '_QUALITY' => 'FORCED'
                ]);
            } catch (\Exception $e) {
                Log::warning("Could not persist forced value: " . $e->getMessage());
            }
        }

        return response()->json([
            'chart' => $chartId,
            'forced_value' => $clampedValue,
            'unit' => $config['unit'],
            'type' => $config['type'],
            'min' => $config['min'],
            'max' => $config['max'],
            'persisted' => $request->get('persist', false),
            'timestamp' => now('Asia/Jakarta')->toISOString()
        ]);
    }

    /**
     * Export data untuk laporan
     */
    public function exportData(Request $request)
    {
        $hours = max(1, min(720, intval($request->get('hours', 24)))); // Max 30 days
        $format = $request->get('format', 'json'); // json, csv

        $now = Carbon::now('Asia/Jakarta');
        $startTime = $now->copy()->subHours($hours);

        try {
            $data = DB::table('logger_polinema')
                ->whereIn('_NAME', array_column($this->chartConfig, 'name'))
                ->whereBetween('_TIMESTAMP', [$startTime, $now])
                ->orderBy('_TIMESTAMP', 'asc')
                ->get();

            if ($format === 'csv') {
                $csv = "Timestamp,Sensor,Value,Unit,Type\n";
                foreach ($data as $row) {
                    $config = collect($this->chartConfig)->firstWhere('name', $row->_NAME);
                    if ($config) {
                        $value = $this->sanitizeValue($row->_VALUE, $config);
                        $csv .= "{$row->_TIMESTAMP},{$row->_NAME},{$value},{$config['unit']},{$config['type']}\n";
                    }
                }

                return response($csv)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename="wwtp_data_' . now()->format('YmdHis') . '.csv"');
            }

            // Default JSON format
            $formattedData = $data->map(function ($row) {
                $config = collect($this->chartConfig)->firstWhere('name', $row->_NAME);
                if ($config) {
                    return [
                        'timestamp' => $row->_TIMESTAMP,
                        'sensor' => $row->_NAME,
                        'value' => $this->sanitizeValue($row->_VALUE, $config),
                        'unit' => $config['unit'],
                        'type' => $config['type']
                    ];
                }
                return null;
            })->filter();

            return response()->json([
                'success' => true,
                'period' => "{$hours} hours",
                'start_time' => $startTime->toDateTimeString(),
                'end_time' => $now->toDateTimeString(),
                'total_records' => $formattedData->count(),
                'sensor_types' => [
                    'ph_sensors' => collect($this->chartConfig)->where('type', 'ph')->count(),
                    'flow_sensors' => collect($this->chartConfig)->where('type', 'flow')->count()
                ],
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            Log::error("Export data error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to export data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
