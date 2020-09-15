<?php
include '../Model/Carro.php';

$carro =  new Carro();

$resutado = $carro->get_tipo();


echo json_encode($resutado);