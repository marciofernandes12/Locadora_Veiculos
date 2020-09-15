<?php
    include '../Model/Carro.php';

    $carro =  new Carro();

    $resutado = $carro->get_marca();


    echo json_encode($resutado);
