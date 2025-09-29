@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Overview Monitoring Dashboard WWTP</h1>
        <button id="refreshBtn" class="btn btn-primary btn-sm" title="Refresh Data">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    <h1 class="h2 mb-0 text-gray-800">pH</h1>
    <div class="row" id="wwtpCards">
        {{-- Card 1 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH1_INFLUENT_SKM" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH Influent SKM
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH2_INFLUENT_LIQ" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH Influent Liquid
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH3_BIG_FAT_TRP" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH Big Fat Trap
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH_EQ3" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH EQ3
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH4_COAG_DAF1" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH DAF 1
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 6 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH5_COAG_DAF2" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH DAF 2
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 7 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH6_NEUTRAL2" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH Neutral2
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 8 --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.PH8_EFFLUENT" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    pH Effluent
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="lastUpdate" style="display: none;"></div>

    <h1 class="h2 mb-0 text-gray-800">Flow</h1>
    <div class="row" id="wwtpCards">
        {{-- Card 1 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="FLOW_TOTAL" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Total Influent
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.FLOW_SKM" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Flow Influent SKM
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.FLOW_LIQ" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Flow Influent Liquid
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Card 4 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.FM_KANTIN" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Flow Influent Kantin
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="FEEDING_TOTAL" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Total Feeding
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 6 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.FLOW_4" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Flow Feeding DAF1
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 7 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.FLOW_5" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Flow Feeding DAF2
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5 flow --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="SPACE_LEVEL" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    Space Level
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="lastUpdate" style="display: none;"></div>

    <h1 class="h2 mb-0 text-gray-800">CIP UHT</h1>
    <div class="row" id="wwtpCards">
        {{-- Card cip --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLA" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane A
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLB" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane B
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLC" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane C
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLD" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane D
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLE" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane E
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLFK" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane F & K
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 ph-card" data-station="WWTP_PWS.PLC1.CLGL" data-section="wwtp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="font-weight-bold mb-1 station-label">
                                    CIP Lane G & L
                                </h6>
                            </div>
                            <div class="h4 mb-0 font-weight-bold ph-value" aria-live="polite">
                                <span class="loading">Loading...</span>
                            </div>
                            <div class="text-xs status-text mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="lastUpdate" style="display: none;"></div>
    @endsection

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    @endpush

    @push('scripts')
        <script>
            // Configuration untuk JS
            window.dashboardConfig = {
                updateInterval: 5000,
                csrfToken: '{{ csrf_token() }}'
            };
        </script>
        <script src="{{ asset('template/js/dashboard.js') }}"></script>
    @endpush
