<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<?php
$periode = _get('periode');
if(!$periode)
    $periode = $db->get_var("SELECT periode FROM tb_laporan ORDER BY periode DESC LIMIT 1");
?>

<h1>Dashboard</h1>
<div class="mb-3">
    <form class="form-inline">
        <input type="hidden" name="m" value="home" />
        <div class="form-group">
            <label class="mr-2">Periode</label>
            <select class="form-control" name="periode" onchange="this.form.submit()">
                <?= get_periode_option($periode) ?>
            </select>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-3 mb-4" <?= is_hidden('kriteria') ?>>
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Kriteria</div>
                        <div class="h2 mb-2 font-weight-bold text-gray-800">
                            <?= $db->get_var("SELECT COUNT(*) FROM tb_kriteria") ?>
                        </div>

                        <div><a class="btn btn-sm btn-primary" href="?m=kriteria">Selengkapnya</a></div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4" <?= is_hidden('alternatif') ?>>
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Alternatif</div>
                        <div class="h2 mb-2 font-weight-bold text-gray-800">
                            <?= $db->get_var("SELECT COUNT(*) FROM tb_alternatif") ?>
                        </div>

                        <div><a class="btn btn-sm btn-success" href="?m=alternatif">Selengkapnya</a></div>

                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4" <?= is_hidden('laporan') ?>>
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Stunting</div>
                        <div class="h2 mb-2 font-weight-bold text-gray-800">
                            <?= $db->get_var("SELECT COUNT(*) FROM tb_laporan WHERE hasil='Stunting' AND periode='$periode'") ?>
                        </div>
                        <div><a class="btn btn-sm btn-danger" href="?m=laporan">Selengkapnya</a></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4" <?= is_hidden('laporan') ?>>
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Normal</div>
                        <div class="h2 mb-2 font-weight-bold text-gray-800">
                            <?= $db->get_var("SELECT COUNT(*) FROM tb_laporan WHERE hasil='Normal' AND periode='$periode'") ?>
                        </div>
                        <div><a class="btn btn-sm btn-info" href="?m=laporan">Selengkapnya</a></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row mb-3">
    <div class="col-md-6" <?= is_hidden('laporan') ?>>
        <div class="card">
            <div class="card-header">
                <strong>Hasil FAHP TOPSIS</strong>
            </div>
            <div class="card-body">
                <div id="container1"></div>
            </div>
        </div>
        <?php
        $categories = array();
        $data_total = array();

        $rows = $db->get_results("SELECT * FROM tb_laporan l INNER jOIN tb_alternatif a ON a.kode_alternatif=l.kode_alternatif WHERE periode='$periode' ORDER BY total DESC");
        foreach ($rows as $row) {
            $categories[$row->kode_alternatif] = $row->nama_balita;
            $data_total[$row->kode_alternatif] = $row->total * 1;
        }
        $categories = json_encode(array_values($categories));
        $data_total = json_encode(array_values($data_total));
        ?>
        <script>
            Highcharts.chart('container1', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: <?= $categories ?>,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total',
                    data: <?= $data_total ?>

                }]
            });
        </script>
    </div>
    <div class="col-md-6" <?= is_hidden('laporan') ?>>
        <div class="card">
            <div class="card-header">
                <strong>Grafik Pemantauan Individu</strong>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <select class="form-control" id="balitaSelect" style="width: 100%;">
                        <option value="">-- Pilih Balita --</option>
                        <?= get_alternatif_option('') ?>
                    </select>
                </div>
                <div id="container2"></div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function () {
                // Ensure jQuery is available
                if (typeof $ !== 'undefined') {
                    $('#balitaSelect').change(function () {
                        const id = $(this).val();
                        if (!id) return;

                        $.getJSON('get_chart_data.php', { kode_alternatif: id }, function (res) {
                            if (res.error) {
                                console.error(res.error);
                                return;
                            }
                            Highcharts.chart('container2', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Grafik Pemantauan Individu'
                                },
                                subtitle: {
                                    text: 'Total Nilai per Periode'
                                },
                                xAxis: {
                                    categories: res.categories,
                                    title: {
                                        text: 'Periode'
                                    }
                                },
                                yAxis: {
                                    min: 0,
                                    max: 1,
                                    title: {
                                        text: 'Total'
                                    },
                                    plotLines: [{ // Changed to plotLines as requested
                                        color: '#FF0000', // Red line for visibility or standard black? "garis biasa" implies neutral or visible. Let's use red or dark grey.
                                        width: 2,
                                        value: 0.1,
                                        zIndex: 5,
                                        label: {
                                            text: '0.1',
                                            align: 'right',
                                            y: -5
                                        }
                                    }]
                                },
                                tooltip: {
                                    formatter: function () {
                                        // Skip tooltip for the dummy series
                                        if(this.series.name === 'Stunting') return false;
                                        
                                        return '<b>' + this.x + '</b><br/>' +
                                            this.series.name + ': ' + this.y + '<br/>' +
                                            'Status: ' + (this.point.hasil || '-');
                                    }
                                },
                                legend: {
                                    // Default layout is fine
                                },
                                series: [{
                                    name: 'Total',
                                    data: res.data,
                                    color: '#438AFE' // Ensure default is blue
                                }, {
                                    name: 'Stunting',
                                    color: '#ffc107', // Yellow for legend
                                    data: [], // Empty data, acts as legend item
                                    marker: {
                                        symbol: 'circle' // Force circle symbol in legend (Highcharts default for column is square, but user asked for "bulat"? "bulat warna kuning")
                                        // Column chart legend usually is square. If user wants "bulat", maybe forcing type: 'scatter' for this dummy series helps.
                                    },
                                    type: 'scatter' // Use scatter to get a circle symbol in the legend
                                }]
                            });
                        });
                    });
                } else {
                    console.error('jQuery not loaded');
                }
            });
        </script>
    </div>
</div>