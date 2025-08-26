@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
            <div
              class="d-sm-flex align-items-center justify-content-between mb-4"
            >
              <h1 class="h3 mb-0 text-gray-800">WWTP</h1>
            </div>

<!-- Page Body -->
            <!-- Area Chart -->
            <div class="charts-grid" id="charts-container">
            <!-- Chart 1 -->
            <div class="chart-container" data-chart="chart1">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot blue"></div>
                  <span class="chart-title"
                    >WWTP_PWS_PLT1_PH1_INFLUENT_SKM</span
                  >
                </div>
                <div class="chart-value" id="chart1-value">--</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart1"></canvas>
                <div
                  class="chart-loading"
                  id="chart1-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 2 -->
            <div class="chart-container" data-chart="chart2">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot green"></div>
                  <span class="chart-title"
                    >WWTP_PWS_PLT1_PH2_INFLUENT_LIQ</span
                  >
                </div>
                <div class="chart-value" id="chart2-value">--</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart2"></canvas>
                <div
                  class="chart-loading"
                  id="chart2-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 3 -->
            <div class="chart-container" data-chart="chart3">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot yellow"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH3_BIO_FAT_TSP</span>
                </div>
                <div class="chart-value" id="chart3-value">--</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart3"></canvas>
                <div
                  class="chart-loading"
                  id="chart3-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 4 -->
            <div class="chart-container" data-chart="chart4">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot pink"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH4_COAG_DAF1</span>
                </div>
                <div class="chart-value" id="chart4-value">6.72</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart4"></canvas>
                <div
                  class="chart-loading"
                  id="chart4-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 5 -->
            <div class="chart-container" data-chart="chart5">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot purple"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH5_COAG_DAF2</span>
                </div>
                <div class="chart-value" id="chart5-value">4.26</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart5"></canvas>
                <div
                  class="chart-loading"
                  id="chart5-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 6 -->
            <div class="chart-container" data-chart="chart6">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot black"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH6_NEUTRALZ</span>
                </div>
                <div class="chart-value" id="chart6-value">0</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart6"></canvas>
                <div
                  class="chart-loading"
                  id="chart6-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 7 -->
            <div class="chart-container" data-chart="chart7">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot cyan"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH7_CSTR2</span>
                </div>
                <div class="chart-value" id="chart7-value">4.26</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart7"></canvas>
                <div
                  class="chart-loading"
                  id="chart7-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>

            <!-- Chart 8 -->
            <div class="chart-container" data-chart="chart8">
              <div class="chart-header">
                <div class="chart-legend">
                  <div class="legend-dot brown"></div>
                  <span class="chart-title">WWTP_PWS_PLT1_PH8_EFFLUENT</span>
                </div>
                <div class="chart-value" id="chart8-value">4.26</div>
              </div>
              <div class="chart-wrapper">
                <canvas id="chart8"></canvas>
                <div
                  class="chart-loading"
                  id="chart8-loading"
                  style="display: none"
                >
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
@endsection