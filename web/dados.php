<?php
include 'config.php';
$aeradorLigado = "
    SELECT 1
    FROM aerador
    WHERE dataHoraFim IS NULL
    LIMIT 1
";

$aeradorLigado = $conn->query($aeradorLigado);

$aeradorLigado = mysqli_fetch_all($aeradorLigado);

$whereSensores = 'TRUE';

if ($_POST['dataInicial'])
    $whereSensores .= " AND DATE_FORMAT(dataHora, '%d/%m/%Y') >= '{$_POST['dataInicial']}'";

if ($_POST['dataFinal'])
    $whereSensores .= " AND DATE_FORMAT(dataHora, '%d/%m/%Y') <= '{$_POST['dataFinal']}'";

$sql = "
    SELECT DATE_FORMAT(dataHora, '%d/%m/%Y %H:%i') dataSensor,
        ROUND(SUM(CASE WHEN descricaoSensor = 'Oxigenio' THEN valorObtidoSonda END)/SUM(CASE WHEN descricaoSensor = 'Oxigenio' THEN 1 END), 2) as Oxigenio,
        ROUND(SUM(CASE WHEN descricaoSensor = 'pH' THEN valorObtidoSonda END)/SUM(CASE WHEN descricaoSensor = 'pH' THEN 1 END), 2) as pH,
        ROUND(SUM(CASE WHEN descricaoSensor = 'Temperatura' THEN valorObtidoSonda END)/SUM(CASE WHEN descricaoSensor = 'Temperatura' THEN 1 END), 2) as Temperatura
    FROM dado_obtido_sensor
    JOIN sensor ON sensor.idSensor = dado_obtido_sensor.idSensor
    WHERE $whereSensores
    GROUP BY 1
    ORDER BY DATE_FORMAT(dataHora, '%d/%m/%Y %H:%i')
";

$dados = $conn->query($sql);

$dados = mysqli_fetch_all($dados, MYSQLI_ASSOC);

$data = [];

foreach ($dados as $dado) {
    $data['oxigenio']['data'][] = $dado['Oxigenio'];
    $data['ph']['data'][] = $dado['pH'];
    $data['temperatura']['data'][] = $dado['Temperatura'];

    $data['oxigenio']['group'][] = $dado['dataSensor'];
    $data['ph']['group'][] = $dado['dataSensor'];
    $data['temperatura']['group'][] = $dado['dataSensor'];
}

$data['oxigenio']['name'] = 'Oxigenio';
$data['ph']['name'] = 'pH';
$data['temperatura']['name'] = 'Temperatura';

$whereTempoAerador = 'TRUE';

if ($_POST['dataInicial'])
    $whereTempoAerador .= " AND DATE_FORMAT(COALESCE(dataHoraInicio, NOW()), '%d/%m/%Y') >= '{$_POST['dataInicial']}'";

if ($_POST['dataFinal'])
    $whereTempoAerador .= " AND DATE_FORMAT(COALESCE(dataHoraInicio, NOW()), '%d/%m/%Y') <= '{$_POST['dataFinal']}'";

$tempoAerador = "
    SELECT DATE_FORMAT(COALESCE(dataHoraInicio, NOW()), '%d/%m/%Y') data,
	    ROUND(SUM(TIMESTAMPDIFF(SECOND, dataHoraInicio, COALESCE(dataHoraFim, NOW())))/60/60, 2) as horas
    FROM aerador
    WHERE $whereTempoAerador
    GROUP BY DATE_FORMAT(COALESCE(dataHoraInicio, NOW()), '%d/%m/%Y')
    ORDER BY DATE_FORMAT(COALESCE(dataHoraInicio, NOW()), '%d/%m/%Y')
";

$tempoAerador = $conn->query($tempoAerador);

$tempoAerador = mysqli_fetch_all($tempoAerador, MYSQLI_ASSOC);
$tempoAeradorGroup = [];
$aux = $tempoAerador;
$tempoAerador = [];
foreach ($aux as $key => $tempo) {
    $tempoAeradorGroup[] = $tempo['data'];

    $tempoAerador['data'][] = [
        $tempo['data'],
        $tempo['horas'],
    ];
    $tempoAerador['name'] = 'Horas';
}

echo json_encode([
    'aeradorLigado'=>!empty($aeradorLigado),
    'sensores'=>$data,
    'tabelaSensores'=>$dados,
    'tempoAerador'=>$tempoAerador,
    'tempoAeradorGroup'=>$tempoAeradorGroup
]);
exit;