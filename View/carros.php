<?php
include "../Model/Carro.php";

$carro = new Carro();

$carros = $carro->get_carros();
?>
<!doctype html>
<html>
<head>
    <title>Locadora Unity</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
            integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/styles.css">

</head>
<body>
<div class="container">

    <div>
        <h1>Carros disponiveis</h1><br>
    </div>
    <div>
        <a class="voltar" href="cadastro.php">Cadastrar Carro</a>
    </div>
    <div><input type="search" id="search" onkeypress="recebe_placa()"></div>
    <table class="table table-bordered table-dark" id="table">
        <thead class="thead-dark">
            <tr>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Ano</th>
                <th>Placa</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($carros

                 as $carro => $value) {
            ?>
            <tr>
                <td><?= $value['Modelo'] ?></td>
                <td><?= $value['Nome'] ?></td>
                <td><?= $value['descricao'] ?></td>
                <td><?= $value['Ano'] ?></td>
                <td><?= $value['Placa'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function recebe_placa() {
        let input = $('#search').val()
        $.ajax({
            url: '../controller/ajax_search_placa.php',
            type: 'post',
            dataType: 'json',
            data: {input: input},
            cache: false,
            success: (data) => {

                console.log(data);
                const select = data.map((placa) => {
                    return `<tr> <td>${placa.Modelo}</td><td>${placa.Nome}</td>
                    <td>${placa.descricao}</td><td>${placa.Ano}</td><td>${placa.Placa}</td></tr>`
                })

                if (select == ''){
                    $('#table tbody').html('<tr><td colspan="5"> Nenhum resultado encontrado </td></tr>')
                } else {
                    $('#table tbody').html(select)
                }
            },
            error: () => {
                alert('error')
            }
        })
    }

</script>
</body>
</html>