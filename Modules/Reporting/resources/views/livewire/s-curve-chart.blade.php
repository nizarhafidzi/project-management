<div>
    {{-- ── Chart Header ── --}}
    <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-w-8 tw-h-8 tw-bg-cyan-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-4 tw-h-4 tw-text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">S-Curve</h2>
        </div>

        {{-- Period Toggle --}}
        <div class="tw-inline-flex tw-rounded-lg tw-bg-gray-100 tw-p-1">
            <button wire:click="switchPeriod('weekly')"
                    class="tw-px-4 tw-py-1.5 tw-rounded-md tw-text-sm tw-font-medium tw-transition-all tw-duration-200
                        {{ $period === 'weekly'
                            ? 'tw-bg-white tw-text-gray-900 tw-shadow-sm'
                            : 'tw-text-gray-500 hover:tw-text-gray-700' }}">
                Weekly
            </button>
            <button wire:click="switchPeriod('monthly')"
                    class="tw-px-4 tw-py-1.5 tw-rounded-md tw-text-sm tw-font-medium tw-transition-all tw-duration-200
                        {{ $period === 'monthly'
                            ? 'tw-bg-white tw-text-gray-900 tw-shadow-sm'
                            : 'tw-text-gray-500 hover:tw-text-gray-700' }}">
                Monthly
            </button>
        </div>
    </div>

    {{-- ── Chart Container ── --}}
    <div
        x-data="sCurveChart(@js($chartData))"
        x-init="initChart()"
        @chart-data-updated.window="updateChart($event.detail.data)"
        wire:ignore
    >
        <div x-ref="chartEl" class="tw-w-full" style="min-height: 450px;"></div>
    </div>
</div>

@script
<script>
    Alpine.data('sCurveChart', (initialData) => ({
        chart: null,
        chartData: initialData,

        initChart() {
            const ApexCharts = window.ApexCharts;
            if (!ApexCharts) {
                console.error('ApexCharts not loaded');
                return;
            }

            const options = this.buildOptions(this.chartData);
            this.chart = new ApexCharts(this.$refs.chartEl, options);
            this.chart.render();
        },

        updateChart(newData) {
            this.chartData = newData;
            if (this.chart) {
                this.chart.updateOptions(this.buildOptions(newData));
            }
        },

        buildOptions(data) {
            return {
                chart: {
                    type: 'area',
                    height: 450,
                    fontFamily: 'Figtree, sans-serif',
                    toolbar: {
                        show: true,
                        autoSelected: 'zoom',
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 600,
                    },
                },
                series: [
                    {
                        name: 'Planned Progress',
                        data: data.planned,
                    },
                    {
                        name: 'Actual Progress',
                        data: data.actual,
                    },
                ],
                xaxis: {
                    categories: data.labels,
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '12px',
                        },
                        rotate: -45,
                        rotateAlways: data.labels.length > 8,
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '12px',
                        },
                        formatter: (val) => val + '%',
                    },
                },
                colors: ['#3b82f6', '#10b981'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100],
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                },
                markers: {
                    size: 5,
                    strokeWidth: 0,
                    hover: { size: 7 },
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: (val) => val !== null ? val.toFixed(1) + '%' : 'N/A',
                    },
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        size: 8,
                        shape: 'circle',
                    },
                    itemMargin: { horizontal: 16, vertical: 8 },
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 4,
                    padding: { left: 10, right: 10, top: 0, bottom: 0 },
                },
                dataLabels: { enabled: false },
            };
        }
    }));
</script>
@endscript
