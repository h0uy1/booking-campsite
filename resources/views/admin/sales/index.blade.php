<x-admin>
    <div class="space-y-6 pb-12" x-data="salesDashboard()">
        <!-- Header Controls -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Sales Dashboard</h1>
            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-gray-200">
                <span class="text-sm text-gray-500 font-medium">Filter Year:</span>
                <select x-model="selectedYear" @change="updateCharts()" class="text-sm border-none focus:ring-0 text-gray-800 font-bold bg-transparent py-0 pl-1 pr-6 cursor-pointer">
                    <template x-for="year in chartData.years" :key="year">
                        <option :value="year" x-text="year"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- Comparative Analysis Header Row (5 Cards) -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            
            <div class="rounded-lg shadow-sm p-4 text-center text-white flex flex-col justify-center" style="background-color: #2c5f78;">
                <div class="text-2xl font-bold">RM {{ number_format($selectedYearTotal) }}</div>
                <div class="text-xs opacity-90 mt-1 uppercase tracking-widest">Current Year Sales</div>
            </div>

            <div class="rounded-lg shadow-sm p-4 text-center text-white flex flex-col justify-center" style="background-color: #2d7a78;">
                <div class="text-2xl font-bold">RM {{ number_format($lastMonthTotal) }}</div>
                <div class="text-xs opacity-90 mt-1 uppercase tracking-widest">Last Month Sales</div>
            </div>

            <div class="rounded-lg shadow-sm p-4 text-center text-white flex flex-col justify-center" style="background-color: #b57724;">
                <div class="text-2xl font-bold">{{ $moMGrowth > 0 ? '+' : '' }}{{ number_format($moMGrowth, 2) }}%</div>
                <div class="text-xs opacity-90 mt-1 uppercase tracking-widest">MoM Growth %</div>
            </div>

            <div class="rounded-lg shadow-sm p-4 text-center text-white flex flex-col justify-center" style="background-color: #5d8c32;">
                <div class="text-2xl font-bold">RM {{ number_format($prevYearTotal) }}</div>
                <div class="text-xs opacity-90 mt-1 uppercase tracking-widest">Past Year Sales</div>
            </div>

            <div class="rounded-lg shadow-sm p-4 text-center text-white flex flex-col justify-center" style="background-color: #3b6b8b;">
                <div class="text-2xl font-bold">{{ $yoYGrowth > 0 ? '+' : '' }}{{ number_format($yoYGrowth, 2) }}%</div>
                <div class="text-xs opacity-90 mt-1 uppercase tracking-widest">YoY Growth %</div>
            </div>

        </div>

        <!-- Top Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Key Metrics (Dark Blue) -->
            <div class="rounded-[20px] shadow-md p-6 flex flex-col justify-between" style="background-color: #3b6b8b;">
                <!-- Metric 1 -->
                <div class="flex items-center mb-6">
                    <div class="mr-4 text-white opacity-80">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $ytdCustomersCount }}</div>
                        <div class="text-sm text-white opacity-80 mt-1">New Customers YTD</div>
                    </div>
                </div>
                <!-- Metric 2 -->
                <div class="flex items-center mb-6">
                    <div class="mr-4 text-white opacity-80">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">RM {{ number_format($selectedYearTotal) }}</div>
                        <div class="text-sm text-white opacity-80 mt-1">Sales Revenue YTD</div>
                    </div>
                </div>
                <!-- Metric 3 -->
                <div class="flex items-center">
                    <div class="mr-4 text-white opacity-80">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white leading-none">{{ $ytdBookingsCount }}</div>
                        <div class="text-sm text-white opacity-80 mt-1">Total Bookings YTD</div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Doughnut Target (Dark Green) -->
            <div class="rounded-[20px] shadow-md p-6 flex flex-col items-center justify-center text-center" style="background-color: #5d8c32;">
                <div class="relative w-40 h-40">
                    <canvas id="targetChart"></canvas>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-bold text-white">RM {{ number_format($selectedYearTotal) }}</div>
                    <div class="text-white opacity-80 text-sm mt-1">Total Realized Revenue YTD</div>
                </div>
            </div>

            <!-- Card 3: Yearly Revenue Bar Chart (Bright Green) -->
            <div class="rounded-[20px] shadow-md p-6 flex flex-col" style="background-color: #9cb115;">
                <div class="text-3xl font-bold text-white">RM {{ number_format($totalAllTime) }}</div>
                <div class="text-white opacity-80 text-sm mt-1">Total All-Time Revenue</div>
                <div class="flex-grow mt-6 relative h-32 w-full">
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Middle Grid -->
        <div class="grid grid-cols-1 md:grid-cols-10 gap-6">
            
            <!-- Card 4: Sparklines (White) - 7 parts -->
            <div class="md:col-span-7 bg-white rounded-[20px] shadow-sm p-6 border border-gray-100 flex flex-col justify-between">
                
                <div class="mb-4">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <div class="text-xl font-bold text-gray-800" style="color: #3b6b8b;">RM {{ number_format($avgRevenue) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Average revenue per booking</div>
                        </div>
                    </div>
                    <div class="h-10 w-full relative">
                        <canvas id="sparkline1"></canvas>
                    </div>
                    <div class="border-b border-gray-100 mt-4"></div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <div class="text-xl font-bold text-gray-800" style="color: #5d8c32;">{{ $ytdBookingsCount > 0 ? number_format($selectedYearTotal / $ytdBookingsCount, 0) : 0 }}</div>
                            <div class="text-xs text-gray-500 mt-1">Average Customer Lifetime Value</div>
                        </div>
                    </div>
                    <div class="h-10 w-full relative">
                        <canvas id="sparkline2"></canvas>
                    </div>
                    <div class="border-b border-gray-100 mt-4"></div>
                </div>

                <div>
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <div class="text-xl font-bold text-gray-800" style="color: #9cb115;">RM 0</div>
                            <div class="text-xs text-gray-500 mt-1">Customer Acquisition Cost</div>
                        </div>
                    </div>
                    <div class="h-8 w-full relative">
                        <canvas id="sparkline3"></canvas>
                    </div>
                </div>

            </div>

            <!-- Card 6: Sales Country Performance (White) - 3 parts -->
            <div class="md:col-span-3 bg-white rounded-[20px] shadow-sm p-6 border border-gray-100 flex flex-col">
                <h3 class="text-base font-bold text-gray-700 mb-4">Revenue by Package Type</h3>
                
                <div class="relative w-full h-40 flex justify-center mt-2">
                    <canvas id="pieChart"></canvas>
                </div>

                <div class="mt-8 space-y-3">
                    <template x-for="(label, index) in chartData.tent_labels" :key="index">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2" :style="'background-color: ' + getPieColor(index)"></span>
                                <span class="text-gray-600" x-text="label"></span>
                            </div>
                            <div class="font-medium text-gray-800" x-text="'RM ' + formatCurrency(chartData.tent_sales[index])"></div>
                        </div>
                    </template>
                    <div x-show="chartData.tent_labels.length === 0" class="text-sm text-gray-500 text-center italic mt-4">
                        No sales data for this year.
                    </div>
                </div>
            </div>

        </div>

        <!-- Card 5: Monthly Sales Growth (White, Full Width) -->
        <div class="bg-white rounded-[20px] shadow-sm p-6 border border-gray-100 flex flex-col">
            <h3 class="text-base font-bold text-gray-700 mb-6">Monthly Sales (<span x-text="selectedYear"></span>)</h3>
            
            <div class="flex-grow w-full relative" style="height: 250px;">
                <canvas id="monthlyChart"></canvas>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-100">
                @php
                    // Calculate simple growth percentage vs last year (mocked for UI if no data)
                    $prevYearTotal = $yearlySales[$selectedYear - 1] ?? ($yearlySales[$selectedYear] ?? 0);
                    $growth = $prevYearTotal > 0 ? (($selectedYearTotal - $prevYearTotal) / $prevYearTotal) * 100 : 100;
                @endphp
                <div class="text-4xl font-bold" style="color: #3b6b8b;">{{ number_format($growth, 0) }}%</div>
                <div class="text-xs text-gray-500 mt-1">Sales Growth vs Previous Year</div>
            </div>
        </div>
    </div>

    <!-- Scripts for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('salesDashboard', () => ({
                chartData: @json($chartData),
                selectedYear: '{{ $selectedYear }}',
                yearlyChartInstance: null,
                monthlyChartInstance: null,
                pieChartInstance: null,
                targetChartInstance: null,
                spark1: null, spark2: null, spark3: null,
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                
                pieColors: ['#3b6b8b', '#9cb115', '#5d8c32', '#f59e0b', '#3b82f6'],

                formatCurrency(value) {
                    return parseFloat(value).toLocaleString('en-US');
                },

                getPieColor(index) {
                    return this.pieColors[index % this.pieColors.length];
                },

                init() {
                    this.setupDefaults();
                    this.$nextTick(() => {
                        this.renderYearlyChart();
                        this.renderTargetDoughnut();
                        this.renderMonthlyChart();
                        this.renderPieChart();
                        this.renderSparklines();
                    });
                },

                setupDefaults() {
                    Chart.defaults.font.family = "'Inter', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
                    Chart.defaults.color = 'rgba(255, 255, 255, 0.7)';
                    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(255, 255, 255, 0.9)';
                    Chart.defaults.plugins.tooltip.titleColor = '#333';
                    Chart.defaults.plugins.tooltip.bodyColor = '#555';
                    Chart.defaults.plugins.tooltip.borderColor = '#e5e7eb';
                    Chart.defaults.plugins.tooltip.borderWidth = 1;
                },

                updateCharts() {
                    // Refetch page with new year query string to reload server data easily
                    window.location.href = '?year=' + this.selectedYear;
                },

                renderYearlyChart() {
                    const ctx = document.getElementById('yearlyChart').getContext('2d');
                    this.yearlyChartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.chartData.years,
                            datasets: [{
                                data: this.chartData.yearly_totals,
                                backgroundColor: '#ffffff',
                                hoverBackgroundColor: 'rgba(255, 255, 255, 0.8)',
                                barThickness: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { display: false, min: 0 },
                                x: { 
                                    grid: { display: false, drawBorder: true, color: 'rgba(255,255,255,0.4)', tickColor: 'transparent' },
                                    ticks: { color: '#ffffff', font: { size: 10 } },
                                    border: { display: true, color: 'white' }
                                }
                            }
                        }
                    });
                },

                renderTargetDoughnut() {
                    const ctx = document.getElementById('targetChart').getContext('2d');
                    this.targetChartInstance = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [75, 25], // Visual mock for above target
                                backgroundColor: ['#8ac94f', 'rgba(0,0,0,0.15)'],
                                borderWidth: 0,
                                cutout: '75%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { tooltip: { enabled: false } },
                            animation: { animateScale: true }
                        }
                    });
                },

                renderMonthlyChart() {
                    const ctx = document.getElementById('monthlyChart').getContext('2d');
                    const yearDataObj = this.chartData.monthly_sales[this.selectedYear] || {};
                    const monthlyValues = [];
                    for(let i = 1; i <= 12; i++) {
                        monthlyValues.push(yearDataObj[i] || 0);
                    }

                    // Create gradient for the mountain fill
                    let gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    gradient.addColorStop(0, 'rgba(59, 107, 139, 0.5)'); // Top opacity
                    gradient.addColorStop(1, 'rgba(59, 107, 139, 0.05)'); // Bottom fade

                    this.monthlyChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.months,
                            datasets: [
                                {
                                    label: 'Sales',
                                    data: monthlyValues,
                                    borderColor: '#3b6b8b', 
                                    backgroundColor: gradient,
                                    borderWidth: 2,
                                    pointBackgroundColor: '#fff',
                                    pointBorderColor: '#3b6b8b',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    fill: true,
                                    tension: 0.4 // Smooth curves
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false },
                                tooltip: { callbacks: { label: function(context) { return 'RM ' + context.parsed.y.toLocaleString(); } } }
                            },
                            scales: {
                                y: { 
                                    display: true, 
                                    min: 0,
                                    grid: {
                                        display: true,
                                        color: '#f3f4f6', // Very faint grey grid lines
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: { size: 10 },
                                        callback: function(value) { return 'RM ' + (value > 999 ? (value/1000).toFixed(0) + 'k' : value); }
                                    }
                                },
                                x: { 
                                    grid: { display: false },
                                    ticks: { 
                                        color: '#9ca3af', 
                                        font: { size: 9 },
                                        maxRotation: 0,
                                        minRotation: 0
                                    },
                                    border: { display: false }
                                }
                            }
                        }
                    });
                },

                renderPieChart() {
                    if(this.chartData.tent_labels.length === 0) return;
                    
                    const ctx = document.getElementById('pieChart').getContext('2d');
                    this.pieChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: this.chartData.tent_labels,
                            datasets: [{
                                data: this.chartData.tent_sales,
                                backgroundColor: this.pieColors,
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false } 
                            }
                        }
                    });
                },

                renderSparklines() {
                    // Utility to make simple sparklines
                    const buildSparkline = (id, color, type, dataArr, fillOpacity = 0) => {
                        const ctx = document.getElementById(id).getContext('2d');
                        let bg = color;
                        if (type === 'line' && fillOpacity > 0) {
                            bg = ctx.createLinearGradient(0,0,0,40);
                            bg.addColorStop(0, color + '66'); // ~40% opacity
                            bg.addColorStop(1, color + '00');
                        }
                        return new Chart(ctx, {
                            type: type,
                            data: {
                                labels: ['1','2','3','4','5','6'],
                                datasets: [{
                                    data: dataArr,
                                    borderColor: color,
                                    backgroundColor: bg,
                                    borderWidth: type === 'line' ? 2 : 0,
                                    fill: fillOpacity > 0,
                                    pointRadius: type === 'line' ? 2 : 0,
                                    pointBackgroundColor: color,
                                    barThickness: 12
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false }, tooltip:{enabled:false} },
                                scales: { x: { display: false }, y: { display: false, min: 0 } }
                            }
                        });
                    };

                    // Simulated data for sparklines to match styling
                    this.spark1 = buildSparkline('sparkline1', '#3b6b8b', 'line', [50, 150, 115, 75, 300, 190], 1);
                    this.spark2 = buildSparkline('sparkline2', '#5d8c32', 'line', [50, 150, 115, 75, 300, 190], 0);
                    this.spark3 = buildSparkline('sparkline3', '#9cb115', 'bar', [50, 150, 115, 75, 300, 190], 1);
                }
            }));
        });
    </script>
</x-admin>
