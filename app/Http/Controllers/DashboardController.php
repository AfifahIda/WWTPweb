<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\SensorData;

class DashboardController extends Controller
{
    private $stations = [
        'WWTP_PWS.PLC1.PH1_INFLUENT_SKM',
        'WWTP_PWS.PLC1.PH2_INFLUENT_LIQ',
        'WWTP_PWS.PLC1.PH3_BIG_FAT_TRP',
        'WWTP_PWS.PLC1.PH4_COAG_DAF1',
        'WWTP_PWS.PLC1.PH5_COAG_DAF2',
        'WWTP_PWS.PLC1.PH6_NEUTRAL2',
        'WWTP_PWS.PLC1.PH7_CSTR2',
        'WWTP_PWS.PLC1.PH8_EFFLUENT',
        'WWTP_PWS.PLC1.FLOW_SKM',
        'WWTP_PWS.PLC1.FLOW_LIQ',
        'WWTP_PWS.PLC1.FLOW_4',
        'WWTP_PWS.PLC1.FLOW_5',
        'WWTP_PWS.PLC1.FM_KANTIN',
        'WWTP_PWS.PLC1.PH_EQ3',
        'WWTP_PWS.PLC1.LV_EQ1',
        'WWTP_PWS.PLC1.LV_EQ3',
        'WWTP_PWS.PLC1.LV_EMG_TANK',
        'WWTP_PWS.PLC1.CLA',
        'WWTP_PWS.PLC1.CLB',
        'WWTP_PWS.PLC1.CLC',
        'WWTP_PWS.PLC1.CLD',
        'WWTP_PWS.PLC1.CLE',
        'WWTP_PWS.PLC1.CLFK',
        'WWTP_PWS.PLC1.CLGL'
    ];

    /**
     * Display the dashboard
     */
    public function index()
    {
        return view('pages.dashboard', ['stations' => $this->stations]);
    }

    /**
     * Stream realtime data via SSE
     */
    public function stream()
    {
        set_time_limit(0);

        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        while (true) {
            $payload = $this->getLatestData();

            // Kirim data SSE
            echo 'data: ' . json_encode($payload) . "\n\n";
            ob_flush();
            flush();

            sleep(2);
        }
    }

    /**
     * Manual refresh endpoint for dashboard data
     */
    public function refresh()
    {
        try {
            $payload = $this->getLatestData();

            return response()->json($payload);
        } catch (\Exception $e) {
            Log::error('Dashboard refresh error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to refresh data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest data for all stations (shared logic for SSE and manual refresh)
     */
    private function getLatestData()
    {
        $payload = [];

        // Ambil data untuk semua station yang ada di $this->stations
        foreach ($this->stations as $station) {
            $data = SensorData::where('_NAME', $station)
                ->orderBy('_TIMESTAMP', 'desc')
                ->first();

            $payload[$station] = $data ? [
                'value' => (float) $data->_VALUE,
                'timestamp' => $data->_TIMESTAMP,
            ] : [
                'value' => null,
                'timestamp' => null,
            ];
        }

        // === Tambah FLOW_TOTAL (SKM + LIQ + KANTIN) ===
        $flowSKM = $payload['WWTP_PWS.PLC1.FLOW_SKM']['value'] ?? 0;
        $flowLIQ = $payload['WWTP_PWS.PLC1.FLOW_LIQ']['value'] ?? 0;
        $flowKantin = $payload['WWTP_PWS.PLC1.FM_KANTIN']['value'] ?? 0;

        $payload['FLOW_TOTAL'] = [
            'value' => $flowSKM + $flowLIQ + $flowKantin,
            'timestamp' => now()->toDateTimeString(),
        ];

        // === Tambah FEEDING_TOTAL ===
        $flowDAF1 = $payload['WWTP_PWS.PLC1.FLOW_4']['value'] ?? 0;
        $flowDAF2 = $payload['WWTP_PWS.PLC1.FLOW_5']['value'] ?? 0;

        $payload['FEEDING_TOTAL'] = [
            'value' => $flowDAF1 + $flowDAF2,
            'timestamp' => now()->toDateTimeString(),
        ];

        // === Hitung SPACE_LEVEL (EQ1 + EQ3 + EMG) ===
        $capacity = [
            'WWTP_PWS.PLC1.LV_EQ1' => 370,
            'WWTP_PWS.PLC1.LV_EQ3' => 320,
            'WWTP_PWS.PLC1.LV_EMG_TANK' => 390,
        ];

        $eq1 = $payload['WWTP_PWS.PLC1.LV_EQ1']['value'] ?? 0;
        $eq3 = $payload['WWTP_PWS.PLC1.LV_EQ3']['value'] ?? 0;
        $emg = $payload['WWTP_PWS.PLC1.LV_EMG_TANK']['value'] ?? 0;

        $sisaEq1 = max($capacity['WWTP_PWS.PLC1.LV_EQ1'] - ($capacity['WWTP_PWS.PLC1.LV_EQ1'] * $eq1 / 100), 0);
        $sisaEq3 = max($capacity['WWTP_PWS.PLC1.LV_EQ3'] - ($capacity['WWTP_PWS.PLC1.LV_EQ3'] * $eq3 / 100), 0);
        $sisaEmg = max($capacity['WWTP_PWS.PLC1.LV_EMG_TANK'] - ($capacity['WWTP_PWS.PLC1.LV_EMG_TANK'] * $emg / 100), 0);

        $payload['SPACE_LEVEL'] = [
            'value' => $sisaEq1 + $sisaEq3 + $sisaEmg,
            'timestamp' => now()->toDateTimeString(),
        ];

        return $payload;
    }

    /**
     * Get realtime pH data for all stations
     */
    /*public function getPhData()
    {
        try {
            $data = [];
            
            foreach ($this->stations as $station) {
                // Query untuk mendapatkan data terbaru dari database
                // Sesuaikan dengan struktur tabel yang Anda gunakan
                $wwtpData = $this->getLatestPHValue($station, 'wwtp');
                $skmData = $this->getLatestPHValue($station, 'skm');
                
                $data[$station] = [
                    'wwtp' => $wwtpData,
                    'skm' => $skmData,
                    'timestamp' => now()->toISOString()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pH data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest pH value for specific station and section
     */
    /*private function getLatestPHValue($station, $section)
    {
        try {
            // Contoh query - sesuaikan dengan struktur database Anda
            $result = DB::table('pklpolinema')
                ->where('station_name', $station)
                ->where('section', $section)
                ->where('parameter', 'pH')
                ->orderBy('created_at', 'desc')
                ->first();

            return $result ? (float) $result->value : null;

            // Jika menggunakan model Eloquent:
            // return SensorData::where('station_name', $station)
            //     ->where('section', $section)
            //     ->where('parameter', 'pH')
            //     ->latest()
            //     ->value('value');

        } catch (\Exception $e) {
            Log::error("Error fetching pH data for {$station} - {$section}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get pH status based on value
     */
    /*public function getPhStatus($phValue)
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
     * Get historical pH data for charts
     */
    /*public function getHistoricalData(Request $request)
    {
        $station = $request->get('station');
        $section = $request->get('section', 'wwtp');
        $hours = $request->get('hours', 24);

        try {
            $data = DB::table('sensor_data')
                ->where('station_name', $station)
                ->where('section', $section)
                ->where('parameter', 'pH')
                ->where('created_at', '>=', Carbon::now()->subHours($hours))
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($item) {
                    return [
                        'timestamp' => $item->created_at,
                        'value' => (float) $item->value,
                        'status' => $this->getPhStatus((float) $item->value)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data,
                'station' => $station,
                'section' => $section
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching historical data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get alert summary
     */
    /*public function getAlerts()
    {
        try {
            $alerts = DB::table('sensor_data')
                ->select('station_name', 'section', 'value', 'created_at')
                ->where('parameter', 'pH')
                ->where('created_at', '>=', Carbon::now()->subHours(24))
                ->get()
                ->filter(function($item) {
                    $ph = (float) $item->value;
                    return $ph < 6.0 || $ph > 9.0; // Only critical alerts
                })
                ->map(function($item) {
                    return [
                        'station' => $item->station_name,
                        'section' => $item->section,
                        'value' => (float) $item->value,
                        'timestamp' => $item->created_at,
                        'status' => $this->getPhStatus((float) $item->value)
                    ];
                })
                ->sortByDesc('created_at')
                ->take(50);

            return response()->json([
                'success' => true,
                'alerts' => $alerts->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching alerts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new pH reading (untuk testing)
     */
    /*public function storePHReading(Request $request)
    {
        $request->validate([
            'station_name' => 'required|string',
            'section' => 'required|in:wwtp,skm',
            'value' => 'required|numeric|between:0,14'
        ]);

        try {
            DB::table('sensor_data')->insert([
                'station_name' => $request->station_name,
                'section' => $request->section,
                'parameter' => 'pH',
                'value' => $request->value,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'pH reading stored successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error storing pH reading: ' . $e->getMessage()
            ], 500);
        }
    }*/
}
