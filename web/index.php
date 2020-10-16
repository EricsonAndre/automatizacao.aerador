<script src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

<script src="js/jquery.mask.min.js"></script>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-4 text-center aerador"></div>
    <div class="col-md-4 text-center" id="time" style="font-size: 300%"></div>
    <div class="col-md-4 text-center">
        <input name="dataInicial" class="data" id="dataInicial">
        <input name="dataFinal" class="data" id="dataFinal">
    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-4" id="graficoOxigenio"></div>
    <div class="col-md-4" id="graficoPh"></div>
    <div class="col-md-4" id="graficoTemperatura"></div>
</div>

<div class="row" style="margin-top: 30px; min-height: 300px;">
    <div class="col-md-4" style="max-height: 300px; overflow-y: scroll;">
        <table style="margin-left: 10px" class="table">
            <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Oxigênio</th>
                <th>pH</th>
                <th>Temperatura</th>
            </tr>
            </thead>
            <tbody id="tabela"></tbody>
        </table>
    </div>
    <div class="col-md-8" id="graficoAerador"></div>
</div>

<script type="application/javascript">
    $(document).ready(function() {
        var ObjDatePickerDataInicial = $('#dataInicial').val(moment().subtract(7, 'days').format('DD/MM/YYYY'));
        var ObjDatePickerDataFinal = $('#dataFinal').val(moment().format('DD/MM/YYYY'));

        var filtros = {
            dataInicial: ObjDatePickerDataInicial.val(),
            dataFinal: ObjDatePickerDataFinal.val()
        };

        $('#dataInicial, #dataFinal').datetimepicker({
            format: 'DD/MM/YYYY',
            useCurrent: false
        }).mask('00/00/0000');

        $('#dataInicial, #dataFinal').on('blur', function() {
            filtros.dataInicial = $('#dataInicial').val()
            filtros.dataFinal = $('#dataFinal').val()

            if (filtros.dataInicial || filtros.dataFinal)
                buscaDados();
        })

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
            t = setTimeout(function() {
                startTime()
            }, 500);
        }

        startTime();
        function montaGrafico(id, dados, titulo, unidade, cor) {
            for (var i in dados.data) {
                dados.data[i] = parseFloat(dados.data[i]);
            }

            Highcharts.chart(id, {
                chart: {
                    type: 'line',
                    zoomType: 'x',
                },
                title: {
                    text: titulo
                },
                tooltip: {
                    formatter: function () {
                        return '<b>' +this.key +': ' +this.y +' '+ unidade;
                    }
                },
                xAxis: {
                    categories: dados.group
                },
                yAxis: {
                    title: {
                        text: false
                    },
                },
                plotOptions: {
                    line: {
                        color: cor
                    }
                },
                legend: false,
                lang: {
                    downloadJPEG: 'EXPORTAR JPEG',
                    downloadPNG: 'EXPORTAR PNG',
                    downloadPDF: 'EXPORTAR PDF'
                },
                exporting: {
                    filename: 'GraficoMonitorChecklist',
                    buttons: {
                        contextButton: {
                            menuItems: [
                                'downloadJPEG',
                                'downloadPNG',
                                'downloadPDF'
                            ]
                        }
                    }
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                series: [dados]
            }, function(chart) { // on complete
                if (!dados.data)
                    chart.renderer.text('Sem Dados', 140, 120)
                        .css({
                            color: 'black',
                            fontSize: '16px'
                        })
                        .add();
            });
        }

        var buscaDados = function() {
            $.ajax({
                url: 'dados.php',
                type: 'POST',
                data: filtros,
                success: function (retorno) {
                    var dados = JSON.parse(retorno);

                    montaGrafico('graficoOxigenio', dados.sensores.oxigenio, 'Oxigênio', 'mg/L', '#0000ff');
                    montaGrafico('graficoPh', dados.sensores.ph, 'pH', '', '#ff0000');
                    montaGrafico('graficoTemperatura', dados.sensores.temperatura, 'Temperatura', '°C', '#e09d00');

                    if (dados.aeradorLigado)
                        $('.aerador').html('Aerador Ligado<br>' + '<i class="fa fa-circle" style="color: green; font-size: 500%;"></i>')
                    else
                        $('.aerador').html('Aerador Desligado<br>' + '<i class="fa fa-circle" style="color: red; font-size: 500%;"></i>')

                    const table = document.getElementById("tabela");
                    table.innerHTML = '';
                    dados.tabelaSensores.forEach( item => {
                        let row = table.insertRow();
                        let date = row.insertCell(0);
                        date.innerHTML = item.dataSensor;

                        let oxigenio = row.insertCell(1);
                        oxigenio.innerHTML = item.Oxigenio + ' mg/L';
                        oxigenio.textAlign = "right";

                        let ph = row.insertCell(2);
                        ph.innerHTML = item.pH;
                        ph.textAlign = "right";

                        let temperatura = row.insertCell(3);
                        temperatura.innerHTML = item.Temperatura + ' °C';
                        temperatura.textAlign = "right";
                    });

                    for (var i in dados.tempoAerador.data) {
                        dados.tempoAerador.data[i] = parseFloat(dados.tempoAerador.data[i][1]);
                    }

                    Highcharts.chart('graficoAerador', {
                        chart: {
                            type: 'column',
                            zoomType: 'x',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false
                        },
                        title: {
                            text: 'Tempo Aerador Ligado (h)'
                        },
                        tooltip: {
                            formatter: function () {
                                return '<b>' +this.key +': ' +this.y +' h';
                            }
                        },
                        xAxis: {
                            categories: dados.tempoAeradorGroup
                        },
                        yAxis: {
                            title: {
                                text: false
                            },
                        },
                        plotOptions: {
                            column: {
                                color: 'green'
                            }
                        },
                        legend: false,
                        lang: {
                            downloadJPEG: 'EXPORTAR JPEG',
                            downloadPNG: 'EXPORTAR PNG',
                            downloadPDF: 'EXPORTAR PDF'
                        },
                        exporting: {
                            filename: 'GraficoMonitorChecklist',
                            buttons: {
                                contextButton: {
                                    menuItems: [
                                        'downloadJPEG',
                                        'downloadPNG',
                                        'downloadPDF'
                                    ]
                                }
                            }
                        },
                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                        },
                        series: [{
                            name: 'Horas',
                            data: dados.tempoAerador.data ? dados.tempoAerador.data : dados.tempoAerador
                        }],
                    });
                }
            })

            setTimeout(function() {
                buscaDados()
            }, 50000);
        }

        buscaDados();
    })
</script>
<style type="text/css">
    .table {
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: white;
        margin-top: -5px;
        border-top: 0px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        padding: 8px 16px;
    }
    th {
        background:#eee;
    }

    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>