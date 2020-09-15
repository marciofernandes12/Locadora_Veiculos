<?php
include '../Model/Carro.php';

$carro =  new Carro();
$input=$_POST['input'];
$resutado = $carro->get_placa($input);


echo json_encode($resutado);