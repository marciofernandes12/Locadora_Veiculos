<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
            integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../assets/style.css">
    <title>Cadastro de Veículos</title>
</head>
<body>
<div class="container">
    <div>
        <h1>Cadastro de Veículos</h1>
    </div>
    <div class="form-content">
        <div class="img-content">
            <figure><img src="../assets/rentacar_89116.svg" alt="" class="img-fluid"></figure>
        </div>

        <form action=".././controller/cadastrar.php" method="POST">
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input required type="text" name="modelo" placeholder="Digite o modelo do carro" class="form-control"
                       autofocus="">
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <select class="form-control " id="marca" name="marca">
                    <option>Selecione uma marca</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tipo:</label>
                <select class="form-control" name="tipo" id="tipo">
                    <option>Selecione um tipo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Ano:</label>
                <input required type="text" name="ano" id="ano" placeholder="Digite o ano do carro" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Placa:</label>
                <input required type="text" name="placa" id="placa" class="form-control">
            </div>
            <input type="submit" value="Cadastrar" class="button-cadastrar">
            <a class="voltar float-right" href="../index.php">Cancelar</a>
        </form>
        <script src="../jquery.mask.js"></script>
        <script>
            $(document).ready(function(){
                $('#placa').mask('AAA-0000',{ placeholder: "EX. ABC-1234"});
                $('#ano').mask('0000',{ placeholder: "EX. 2020"});
                load_marca();
                load_tipo();
            });


            function load_marca() {

                $.ajax({
                    url: '../controller/ajax_load_marca.php',
                    type:'get',
                    dataType: 'json',
                    cache: false,
                    success: (data) =>{


                        const select = data.map((marca)=>{
                            return '<option value="'+marca.id+'">'+marca.nome+'</option>'
                        })

                        $('#marca').append(select)
                    },
                    error: ()=>{
                        alert('error')
                    }
                })
                
            }

            function load_tipo() {

                $.ajax({
                    url: '../controller/ajax_load_tipo.php',
                    type: 'get',
                    dataType: 'json',
                    cache: false,
                    success: (data) => {

                        console.log(data);
                        const select = data.map((marca) => {
                            return '<option value="' + marca.id + '">' + marca.descricao + '</option>'
                        })

                        $('#tipo').append(select)

                    },
                    error: () => {
                        alert('error')
                    }
                })
            }

        </script>



</body>
</html>
