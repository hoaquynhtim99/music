<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12 con-md-12 col-lg-6">
        <div class="panel panel-main-music">
            <div class="panel-body">
                <div class="ms-main-couter">
                    <div class="c-icon pull-left">
                        <i class="fa fa-music" aria-hidden="true"></i>
                    </div>
                    <div class="c-val">
                        <span class="c-number"><a href="{LINK_SONGS}">{STAT_BASIC.num_songs}</a></span>
                        <span class="c-text"><a href="{LINK_SONGS}">{LANG.song}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 con-md-12 col-lg-6">
        <div class="panel panel-main-music">
            <div class="panel-body">
                <div class="ms-main-couter">
                    <div class="c-icon pull-left">
                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                    </div>
                    <div class="c-val">
                        <span class="c-number"><a href="{LINK_VIDEOS}">{STAT_BASIC.num_videos}</a></span>
                        <span class="c-text"><a href="{LINK_VIDEOS}">{LANG.video}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 con-md-12 col-lg-6">
        <div class="panel panel-main-music">
            <div class="panel-body">
                <div class="ms-main-couter">
                    <div class="c-icon pull-left">
                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                    </div>
                    <div class="c-val">
                        <span class="c-number"><a href="{LINK_ALBUMS}">{STAT_BASIC.num_albums}</a></span>
                        <span class="c-text"><a href="{LINK_ALBUMS}">{LANG.album}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 con-md-12 col-lg-6">
        <div class="panel panel-main-music">
            <div class="panel-body">
                <div class="ms-main-couter">
                    <div class="c-icon pull-left">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                    </div>
                    <div class="c-val">
                        <span class="c-number"><a href="{LINK_ARTISTS}">{STAT_BASIC.num_artists}</a></span>
                        <span class="c-text"><a href="{LINK_ARTISTS}">{LANG.artist}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/chart/chart.min.js"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-24 col-xs-24">
        <div class="panel panel-main-music">
            <div class="panel-heading">
                {LANG.mainpage_stat_overview}
            </div>
            <div class="panel-body">
                <canvas id="chart-area-overview"></canvas>
                <script>
                $(window).on('load', function() {
                    var config = {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [
                                    {OVERVIEW.song},
                                    {OVERVIEW.video},
                                    {OVERVIEW.album}
                                ],
                                backgroundColor: [
                                    window.chartColors.red,
                                    window.chartColors.orange,
                                    window.chartColors.blue
                                ],
                                label: 'Datasets'
                            }],
                            labels: [
                                '{LANG.song}: {OVERVIEW.song_display}',
                                '{LANG.video}: {OVERVIEW.video_display}',
                                '{LANG.album}: {OVERVIEW.album_display}'
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: '{LANG.mainpage_stat_overview}: {OVERVIEW.total_display}'
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                titleMarginBottom: 5,
                                callbacks: {
                                    title: function(a, b) {
                                        return b.labels[a[0].index].replace(/\: [0-9\.\,]+$/g, "");
                                    },
                                    label: function(a, b) {
                                        return number_format(b.datasets[0].data[a.index], 0, ',', '.') + ' {LANG.hits}';
                                    }
                                }
                            },
                        }
                    };
                    var ctx = document.getElementById('chart-area-overview').getContext('2d');
                    var chart = new Chart(ctx, config);
                });
                </script>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-24 col-xs-24">
        <div class="panel panel-main-music">
            <div class="panel-heading">
                {LANG.mainpage_stat_byyear}
            </div>
            <div class="panel-body">
                <canvas id="chart-area-byyear"></canvas>
                <script>
                $(window).on('load', function() {
                    var config = {
                        type: 'line',
                        data: {
                            labels: [{YEAR_VALUE}],
                            datasets: [{
                                label: '{LANG.song}',
                                fill: false,
                                backgroundColor: window.chartColors.red,
                                borderColor: window.chartColors.red,
                                data: [{YEAR_SONG}]
                            }, {
                                label: '{LANG.video}',
                                fill: false,
                                backgroundColor: window.chartColors.orange,
                                borderColor: window.chartColors.orange,
                                data: [{YEAR_VIDEO}]
                            }, {
                                label: '{LANG.album}',
                                fill: false,
                                backgroundColor: window.chartColors.blue,
                                borderColor: window.chartColors.blue,
                                data: [{YEAR_ALBUM}]
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: '{LANG.mainpage_stat_byyear_title}'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                titleMarginBottom: 5,
                                callbacks: {
                                    title: function(a, b) {
                                        return '{LANG.Year} ' + b.labels[a[0].index];
                                    },
                                    label: function(a, b) {
                                        return b.datasets[a.datasetIndex].label + ' ' + number_format(b.datasets[a.datasetIndex].data[a.index], 0, ',', '.') + ' {LANG.hits}';
                                    }
                                }
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Year}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Hits}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    },
                                    ticks: {
                                        callback: function(label, index, labels) {
                                            return number_format(label, 0, ',', '.');
                                        }
                                    }
                                }]
                            }
                        }
                    };
                    var ctx = document.getElementById('chart-area-byyear').getContext('2d');
                    var chart = new Chart(ctx, config);
                });
                </script>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-24">
        <div class="panel panel-main-music">
            <div class="panel-heading">
                {LANG.mainpage_stat_byday}
            </div>
            <div class="panel-body">
                <canvas id="chart-area-byday"></canvas>
                <script>
                $(window).on('load', function() {
                    var config = {
                        type: 'line',
                        data: {
                            labels: [{DAY_VALUE}],
                            datasets: [{
                                label: '{LANG.song}',
                                fill: false,
                                backgroundColor: window.chartColors.red,
                                borderColor: window.chartColors.red,
                                data: [{DAY_SONG}]
                            }, {
                                label: '{LANG.video}',
                                fill: false,
                                backgroundColor: window.chartColors.orange,
                                borderColor: window.chartColors.orange,
                                data: [{DAY_VIDEO}]
                            }, {
                                label: '{LANG.album}',
                                fill: false,
                                backgroundColor: window.chartColors.blue,
                                borderColor: window.chartColors.blue,
                                data: [{DAY_ALBUM}]
                            }]
                        },
                        options: {
                            responsive: true,
                            aspectRatio: 3.8,
                            title: {
                                display: true,
                                text: '{LANG.mainpage_stat_byday_title} {DAY_STAT_MONTH}'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    title: function(a, b) {
                                        return '{LANG.Day} ' + b.labels[a[0].index] + '/{DAY_STAT_MONTH}';
                                    },
                                    label: function(a, b) {
                                        return b.datasets[a.datasetIndex].label + ' ' + number_format(b.datasets[a.datasetIndex].data[a.index], 0, ',', '.') + ' {LANG.hits}';
                                    }
                                }
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Day}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Hits}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    },
                                    ticks: {
                                        callback: function(label, index, labels) {
                                            return number_format(label, 0, ',', '.');
                                        }
                                    }
                                }]
                            }
                        }
                    };
                    var ctx = document.getElementById('chart-area-byday').getContext('2d');
                    var chart = new Chart(ctx, config);
                });
                </script>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-24">
        <div class="panel panel-main-music">
            <div class="panel-heading">
                {LANG.mainpage_stat_bymonth}
            </div>
            <div class="panel-body">
                <canvas id="chart-area-bymonth"></canvas>
                <script>
                $(window).on('load', function() {
                    var config = {
                        type: 'line',
                        data: {
                            labels: [{MONTH_VALUE}],
                            datasets: [{
                                label: '{LANG.song}',
                                fill: false,
                                backgroundColor: window.chartColors.red,
                                borderColor: window.chartColors.red,
                                data: [{MONTH_SONG}]
                            }, {
                                label: '{LANG.video}',
                                fill: false,
                                backgroundColor: window.chartColors.orange,
                                borderColor: window.chartColors.orange,
                                data: [{MONTH_VIDEO}]
                            }, {
                                label: '{LANG.album}',
                                fill: false,
                                backgroundColor: window.chartColors.blue,
                                borderColor: window.chartColors.blue,
                                data: [{MONTH_ALBUM}]
                            }]
                        },
                        options: {
                            responsive: true,
                            aspectRatio: 3.8,
                            title: {
                                display: true,
                                text: '{LANG.mainpage_stat_bymonth_title} {MONTH_STAT_YEAR}'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    title: function(a, b) {
                                        return '{LANG.Month} ' + b.labels[a[0].index] + '/{MONTH_STAT_YEAR}';
                                    },
                                    label: function(a, b) {
                                        return b.datasets[a.datasetIndex].label + ' ' + number_format(b.datasets[a.datasetIndex].data[a.index], 0, ',', '.') + ' {LANG.hits}';
                                    }
                                }
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Month}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{LANG.Hits}'
                                    },
                                    gridLines: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: false
                                    },
                                    ticks: {
                                        callback: function(label, index, labels) {
                                            return number_format(label, 0, ',', '.');
                                        }
                                    }
                                }]
                            }
                        }
                    };
                    var ctx = document.getElementById('chart-area-bymonth').getContext('2d');
                    var chart = new Chart(ctx, config);
                });
                </script>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
