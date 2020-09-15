<?php
include '../Model/Carro.php';
$c= new Carro();
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $tipo = $_POST['tipo'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];
    //Verifica se os campos foram preenchidos
    if (!empty($modelo) && !empty($marca) && !empty($tipo) && !empty($ano) && !empty($placa)) {
//        $c->conectar("locadora", "localhost ", "root", "");

        if ($c->cadastrar($modelo, $marca, $tipo, $ano, $placa))//Cadastra o carro.
        {
            echo '<script>alert("Cadastrado com sucesso!");window.location.href="../index.php";</script>';
        } else echo '<script>alert("Carro já cadastrado!");window.location.href="../View/cadastro.php"</script>';


}
