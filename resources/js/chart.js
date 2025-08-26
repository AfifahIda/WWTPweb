// Charts Component - assets/js/components/charts.js

class ChartsComponent {
    constructor(app) {
        this.app = app;
        this.charts = {};
        this.maxDataPoints = AppConfig.realTime.maxDataPoints;
        this.isInitialized = false;

        // Chart.js default configuration
        Chart.defaults.font.family =
            'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        Chart.defaults.color = "#6c757d";
        Chart.defaults.borderColor = "rgba(0, 0, 0, 0.05)";
    }

    async init() {
        try {
            AppConfig.log("info", "Initializing Charts Component...");

            // Create all charts
            await this.createAllCharts();

            // Setup event listeners
            this.setupEventListeners();

            // Load initial data
            await this.loadInitialData();

            this.isInitialized = true;
            AppConfig.log("info", "Charts Component initialized successfully");
        } catch (error) {
            AppConfig.log(
                "error",
                "Failed to initialize Charts Component",
                error
            );
            throw error;
        }
    }

    async createAllCharts() {
        const sensors = AppConfig.getAllSensors();

        for (const sensor of sensors) {
            await this.createChart(sensor);
        }
    }

    async createChart(sensorConfig) {
        const ctx = document.getElementById(sensorConfig.id);
        if (!ctx) {
            AppConfig.log(
                "warn",
                `Canvas element not found for sensor: ${sensorConfig.id}`
            );
            return;
        }

        try {
            // Show loading state
            this.showChartLoading(sensorConfig.id, true);

            // Generate initial data
            const initialData = this.generateInitialData();

            // Create chart configuration
            const config = this.createChartConfig(sensorConfig, initialData);

            // Create Chart.js instance
            this.charts[sensorConfig.id] = new Chart(ctx, config);

            // Setup chart-specific event listeners
            this.setupChartEventListeners(sensorConfig.id);

            // Hide loading state
            this.showChartLoading(sensorConfig.id, false);

            AppConfig.log(
                "debug",
                `Chart created successfully: ${sensorConfig.id}`
            );
        } catch (error) {
            AppConfig.log(
                "error",
                `Failed to create chart: ${sensorConfig.id}`,
                error
            );
            this.showChartError(sensorConfig.id, "Failed to create chart");
        }
    }

    createChartConfig(sensorConfig, initialData) {
        return {
            type: "line",
            data: {
                labels: initialData.labels,
                datasets: [
                    {
                        label: sensorConfig.displayName,
                        data: initialData.data,
                        borderColor: sensorConfig.color,
                        backgroundColor: this.hexToRgba(
                            sensorConfig.color,
                            0.1
                        ),
                        borderWidth: AppConfig.charts.borderWidth,
                        fill: true,
                        tension: AppConfig.charts.tension,
                        pointRadius: AppConfig.charts.pointRadius,
                        pointHoverRadius: AppConfig.charts.pointHoverRadius,
                        pointBackgroundColor: sensorConfig.color,
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: sensorConfig.color,
                        pointHoverBorderColor: "#fff",
                        pointHoverBorderWidth: 3,
                    },
                ],
            },
            options: this.createChartOptions(sensorConfig),
        };
    }

    createChartOptions(sensorConfig) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: "index",
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: "rgba(255, 255, 255, 0.95)",
                    titleColor: "#333",
                    bodyColor: "#666",
                    borderColor: "#e9ecef",
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    titleFont: {
                        size: 12,
                        weight: "600",
                    },
                    bodyFont: {
                        size: 11,
                    },
                    padding: 12,
                    callbacks: {
                        title: (tooltipItems) => {
                            return tooltipItems[0].label;
                        },
                        label: (context) => {
                            return `${context.parsed.y.toFixed(2)} ${
                                sensorConfig.unit
                            }`;
                        },
                        afterLabel: (context) => {
                            const value = context.parsed.y;
                            const config = sensorConfig;

                            if (config.type === "above") {
                                if (value >= config.criticalThreshold) {
                                    return "Status: CRITICAL";
                                } else if (value >= config.warningThreshold) {
                                    return "Status: WARNING";
                                } else {
                                    return "Status: NORMAL";
                                }
                            } else {
                                if (value <= config.criticalThreshold) {
                                    return "Status: CRITICAL";
                                } else if (value <= config.warningThreshold) {
                                    return "Status: WARNING";
                                } else {
                                    return "Status: NORMAL";
                                }
                            }
                        },
                    },
                },
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: AppConfig.charts.textColor,
                        font: {
                            size: 10,
                        },
                        maxTicksLimit: 6,
                        maxRotation: 0,
                    },
                },
                y: {
                    display: true,
                    beginAtZero: sensorConfig.minValue === 0,
                    min: sensorConfig.minValue,
                    max: sensorConfig.maxValue,
                    grid: {
                        color: AppConfig.charts.gridColor,
                        borderDash: [2, 2],
                    },
                    ticks: {
                        color: AppConfig.charts.textColor,
                        font: {
                            size: 10,
                        },
                        maxTicksLimit: 5,
                        callback: function (value) {
                            return value.toFixed(1);
                        },
                    },
                },
            },
            animation: {
                duration: AppConfig.charts.animationDuration,
                easing: AppConfig.charts.animationEasing,
            },
            onHover: (event, activeElements) => {
                this.handleChartHover(sensorConfig.id, activeElements);
            },
            onClick: (event, activeElements) => {
                this.handleChartClick(sensorConfig.id, activeElements);
            },
        };
    }

    generateInitialData() {
        const labels = [];
        const data = [];
        const now = new Date();

        for (let i = this.maxDataPoints - 1; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 30 * 60 * 1000); // 30 minutes intervals
            labels.push(this.formatTime(time));
            data.push(null); // Start with null values
        }

        return { labels, data };
    }

    formatTime(date) {
        return date.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        });
    }

    async loadInitialData() {
        try {
            AppConfig.log("debug", "Loading initial chart data...");

            // Load historical data for all sensors
            const response = await api.getHistoricalData(2); // Last 2 hours

            if (response.success && response.data) {
                this.updateAllChartsWithHistoricalData(response.data);
            }
        } catch (error) {
            AppConfig.log("error", "Failed to load initial chart data", error);
            // Continue with empty charts - real-time data will populate them
        }
    }

    updateAllChartsWithHistoricalData(historicalData) {
        Object.keys(historicalData).forEach((chartId) => {
            if (this.charts[chartId] && historicalData[chartId]) {
                this.updateChartWithHistoricalData(
                    chartId,
                    historicalData[chartId]
                );
            }
        });
    }

    updateChartWithHistoricalData(chartId, data) {
        const chart = this.charts[chartId];
        if (!chart || !Array.isArray(data)) return;

        const labels = [];
        const values = [];

        // Take only the most recent data points
        const recentData = data.slice(-this.maxDataPoints);

        recentData.forEach((point) => {
            const time = new Date(point.timestamp);
            labels.push(this.formatTime(time));
            values.push(point.value);
        });

        // Update chart data
        chart.data.labels = labels;
        chart.data.datasets[0].data = values;
        chart.update("none"); // No animation for historical data

        AppConfig.log("debug", `Historical data loaded for chart: ${chartId}`);
    }

    updateChartData(chartId, newValue) {
        const chart = this.charts[chartId];
        if (!chart) {
            AppConfig.log("warn", `Chart not found: ${chartId}`);
            return;
        }

        try {
            const now = new Date();
            const timeLabel = this.formatTime(now);

            // Add new data point
            chart.data.labels.push(timeLabel);
            chart.data.datasets[0].data.push(newValue);

            // Remove old data points if we exceed max
            if (chart.data.labels.length > this.maxDataPoints) {
                chart.data.labels.shift();
                chart.data.datasets[0].data.shift();
            }

            // Update chart with smooth animation
            chart.update("none"); // No animation for real-time updates

            // Update the value display
            this.updateValueDisplay(chartId, newValue);

            // Update status indicator
            this.updateStatusIndicator(chartId, newValue);

            AppConfig.log(
                "debug",
                `Chart data updated: ${chartId} = ${newValue}`
            );
        } catch (error) {
            AppConfig.log(
                "error",
                `Failed to update chart data: ${chartId}`,
                error
            );
        }
    }

    updateValueDisplay(chartId, value) {
        const valueElement = document.getElementById(`${chartId}-value`);
        if (valueElement) {
            // Animate value change
            const currentValue = parseFloat(valueElement.textContent) || 0;
            this.animateValue(valueElement, currentValue, value, 500);
        }
    }

    updateStatusIndicator(chartId, value) {
        const container = document.querySelector(`[data-chart="${chartId}"]`);
        if (!container) return;

        const sensorConfig = AppConfig.getSensor(chartId);
        if (!sensorConfig) return;

        let status = "normal";

        if (sensorConfig.type === "above") {
            if (value >= sensorConfig.criticalThreshold) {
                status = "critical";
            } else if (value >= sensorConfig.warningThreshold) {
                status = "warning";
            }
        } else {
            if (value <= sensorConfig.criticalThreshold) {
                status = "critical";
            } else if (value <= sensorConfig.warningThreshold) {
                status = "warning";
            }
        }

        // Update status indicator
        let indicator = container.querySelector(".chart-status");
        if (!indicator) {
            indicator = document.createElement("div");
            indicator.className = "chart-status";
            container.appendChild(indicator);
        }

        indicator.className = `chart-status ${status}`;
    }

    animateValue(element, start, end, duration) {
        const startTime = performance.now();
        const diff = end - start;

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function
            const easedProgress = this.easeOutQuart(progress);
            const currentValue = start + diff * easedProgress;

            element.textContent = currentValue.toFixed(2);

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    }

    easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }

    showChartLoading(chartId, show) {
        const loadingElement = document.getElementById(`${chartId}-loading`);
        if (loadingElement) {
            loadingElement.style.display = show ? "flex" : "none";
        }
    }

    showChartError(chartId, message) {
        const container = document.querySelector(
            `[data-chart="${chartId}"] .chart-wrapper`
        );
        if (!container) return;

        container.innerHTML = `
            <div class="chart-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="chart-error-message">${message}</div>
                <button class="chart-error-retry" onclick="window.wwtpApp.components.charts.retryChart('${chartId}')">
                    <i class="fas fa-redo"></i> Retry
                </button>
            </div>
        `;
    }

    async retryChart(chartId) {
        const sensorConfig = AppConfig.getSensor(chartId);
        if (sensorConfig) {
            await this.createChart(sensorConfig);
        }
    }

    setupEventListeners() {
        // Listen for data updates
        document.addEventListener("data:update", (event) => {
            if (event.detail && event.detail.chartData) {
                this.handleDataUpdate(event.detail.chartData);
            }
        });

        // Listen for chart resize
        document.addEventListener("window:resize", () => {
            this.handleResize();
        });

        // Listen for theme changes
        document.addEventListener("theme:changed", () => {
            this.updateChartsTheme();
        });
    }

    setupChartEventListeners(chartId) {
        const container = document.querySelector(`[data-chart="${chartId}"]`);
        if (!container) return;

        // Add hover effects
        container.addEventListener("mouseenter", () => {
            container.style.transform = "translateY(-2px)";
        });

        container.addEventListener("mouseleave", () => {
            container.style.transform = "translateY(0)";
        });
    }

    handleDataUpdate(chartData) {
        Object.keys(chartData).forEach((chartId) => {
            if (this.charts[chartId]) {
                this.updateChartData(chartId, chartData[chartId]);
            }
        });
    }

    handleResize() {
        // Delay resize to avoid too many calls
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            Object.values(this.charts).forEach((chart) => {
                chart.resize();
            });
        }, 100);
    }

    handleChartHover(chartId, activeElements) {
        // Could add custom hover behavior here
        AppConfig.log("debug", `Chart hover: ${chartId}`, activeElements);
    }

    handleChartClick(chartId, activeElements) {
        if (activeElements.length > 0) {
            const element = activeElements[0];
            const chart = this.charts[chartId];
            const dataPoint =
                chart.data.datasets[element.datasetIndex].data[element.index];
            const label = chart.data.labels[element.index];

            AppConfig.log(
                "info",
                `Chart clicked: ${chartId} at ${label} with value ${dataPoint}`
            );

            // Could open detailed view or show additional information
            this.showDataPointDetails(chartId, label, dataPoint);
        }
    }

    showDataPointDetails(chartId, label, value) {
        const sensorConfig = AppConfig.getSensor(chartId);
        if (!sensorConfig) return;

        // Show tooltip or modal with detailed information
        const tooltip = document.createElement("div");
        tooltip.className = "data-point-tooltip";
        tooltip.innerHTML = `
            <h4>${sensorConfig.displayName}</h4>
            <p><strong>Time:</strong> ${label}</p>
            <p><strong>Value:</strong> ${value} ${sensorConfig.unit}</p>
            <p><strong>Status:</strong> ${this.getStatusText(
                sensorConfig,
                value
            )}</p>
        `;

        // Position and show tooltip
        document.body.appendChild(tooltip);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (tooltip.parentElement) {
                tooltip.remove();
            }
        }, 3000);
    }

    getStatusText(sensorConfig, value) {
        if (sensorConfig.type === "above") {
            if (value >= sensorConfig.criticalThreshold) return "CRITICAL";
            if (value >= sensorConfig.warningThreshold) return "WARNING";
            return "NORMAL";
        } else {
            if (value <= sensorConfig.criticalThreshold) return "CRITICAL";
            if (value <= sensorConfig.warningThreshold) return "WARNING";
            return "NORMAL";
        }
    }

    updateChartsTheme() {
        // Update chart colors based on theme
        const isDark =
            document.documentElement.getAttribute("data-theme") === "dark";

        Object.values(this.charts).forEach((chart) => {
            chart.options.scales.x.ticks.color = isDark ? "#b3b3b3" : "#6c757d";
            chart.options.scales.y.ticks.color = isDark ? "#b3b3b3" : "#6c757d";
            chart.options.scales.y.grid.color = isDark
                ? "rgba(255, 255, 255, 0.05)"
                : "rgba(0, 0, 0, 0.05)";
            chart.update("none");
        });
    }

    // Utility methods
    hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);

        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // Public methods for external use
    getChart(chartId) {
        return this.charts[chartId] || null;
    }

    getAllCharts() {
        return { ...this.charts };
    }

    getSettings() {
        return {
            maxDataPoints: this.maxDataPoints,
            // Add other settings here
        };
    }

    setSettings(settings) {
        if (settings.maxDataPoints) {
            this.maxDataPoints = settings.maxDataPoints;
        }
        // Apply other settings
    }

    async refresh() {
        try {
            await this.loadInitialData();
        } catch (error) {
            AppConfig.log("error", "Failed to refresh charts", error);
            throw error;
        }
    }

    onResize() {
        this.handleResize();
    }

    onVisibilityChange(isVisible) {
        if (isVisible) {
            // Resume chart animations
            Object.values(this.charts).forEach((chart) => {
                chart.options.animation.duration =
                    AppConfig.charts.animationDuration;
            });
        } else {
            // Disable animations when not visible to save resources
            Object.values(this.charts).forEach((chart) => {
                chart.options.animation.duration = 0;
            });
        }
    }

    destroy() {
        AppConfig.log("info", "Destroying Charts Component...");

        // Destroy all Chart.js instances
        Object.values(this.charts).forEach((chart) => {
            chart.destroy();
        });

        // Clear references
        this.charts = {};

        // Clear timeouts
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
        }

        this.isInitialized = false;
        AppConfig.log("info", "Charts Component destroyed");
    }
}

// Make component globally available
window.ChartsComponent = ChartsComponent;
