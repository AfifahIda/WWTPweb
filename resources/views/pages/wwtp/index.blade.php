@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">WWTP</h1>
    </div>

    <!-- Page Body -->

    <!-- Area Chart -->
    <h1 class="h2 mb-0 text-gray-800">pH</h1>
    <div class="charts-grid" id="charts-container">
        <!-- Chart 1 -->
        <div class="chart-container" data-chart="chart1">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot blue"></div>
                    <span class="chart-title">pH Influent SKM</span>
                </div>
                <div class="chart-value" id="chart1-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart1"></canvas>
                <div class="chart-loading" id="chart1-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="chart-container" data-chart="chart2">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot red"></div>
                    <span class="chart-title">pH Influent Liquid</span>
                </div>
                <div class="chart-value" id="chart2-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart2"></canvas>
                <div class="chart-loading" id="chart2-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 3 -->
        <div class="chart-container" data-chart="chart3">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot green"></div>
                    <span class="chart-title">pH Big Fat Trap</span>
                </div>
                <div class="chart-value" id="chart3-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart3"></canvas>
                <div class="chart-loading" id="chart3-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 4 -->
        <div class="chart-container" data-chart="chart4">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot purple"></div>
                    <span class="chart-title">pH EQ3</span>
                </div>
                <div class="chart-value" id="chart4-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart4"></canvas>
                <div class="chart-loading" id="chart4-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 5 -->
        <div class="chart-container" data-chart="chart5">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot yellow"></div>
                    <span class="chart-title">pH DAF 1</span>
                </div>
                <div class="chart-value" id="chart5-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart5"></canvas>
                <div class="chart-loading" id="chart5-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 6 -->
        <div class="chart-container" data-chart="chart6">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot grey"></div>
                    <span class="chart-title">pH DAF 2</span>
                </div>
                <div class="chart-value" id="chart6-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart6"></canvas>
                <div class="chart-loading" id="chart6-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 7 -->
        <div class="chart-container" data-chart="chart7">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot black"></div>
                    <span class="chart-title">pH Neutral2</span>
                </div>
                <div class="chart-value" id="chart7-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart7"></canvas>
                <div class="chart-loading" id="chart7-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 8 -->
        <div class="chart-container" data-chart="chart8">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot orange"></div>
                    <span class="chart-title">pH Effluent</span>
                </div>
                <div class="chart-value" id="chart8-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart8"></canvas>
                <div class="chart-loading" id="chart8-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        </div>

        <br> </br>
        <h1 class="h2 mb-0 text-gray-800">Flow</h1>
        <div class="charts-grid" id="charts-container">
        <!-- Chart 9 - Flow Total -->
        <div class="chart-container" data-chart="chart9">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot cyan"></div>
                    <span class="chart-title">Flow Total</span>
                </div>
                <div class="chart-value" id="chart9-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart9"></canvas>
                <div class="chart-loading" id="chart9-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 10 - Flow SKM -->
        <div class="chart-container" data-chart="chart10">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot pink"></div>
                    <span class="chart-title">Flow SKM</span>
                </div>
                <div class="chart-value" id="chart10-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart10"></canvas>
                <div class="chart-loading" id="chart10-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 11 - Flow Liquid -->
        <div class="chart-container" data-chart="chart11">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot brown"></div>
                    <span class="chart-title">Flow Liquid</span>
                </div>
                <div class="chart-value" id="chart11-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart11"></canvas>
                <div class="chart-loading" id="chart11-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <!-- Chart 12 - Flow Kantin -->
        <div class="chart-container" data-chart="chart12">
            <div class="chart-header">
                <div class="chart-legend">
                    <div class="legend-dot lime"></div>
                    <span class="chart-title">Flow Kantin</span>
                </div>
                <div class="chart-value" id="chart12-value">--</div>
            </div>
            <div class="chart-wrapper">
                <canvas id="chart12"></canvas>
                <div class="chart-loading" id="chart12-loading" style="display: none">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
