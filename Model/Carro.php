<?php
include "Db.php";
class Carro
{

    public function cadastrar($modelo, $marca, $tipo,$ano,$placa)
    {
        global $pdo;
        global $msgErro;
        //verifica se já existe o cadastro.
        $sql = $pdo->prepare("SELECT id FROM carros WHERE placa = :placa");
        $sql->bindValue(":placa", $placa);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return false; //cadastro existente.
        } else {
            try {
                //Se o cadastro não existe, cadastrar novo carro.
                $sql = $pdo->prepare("INSERT INTO carros (modelo, marca, tipo, ano,placa) values (:modelo, :marca, :tipo,:ano,:placa)");
                $sql->bindValue(":marca", $marca);
                $sql->bindValue(":modelo", $modelo);
                $sql->bindValue(":tipo", $tipo);
                $sql->bindValue(":ano", $ano);
                $sql->bindValue(":placa", $placa);
                $sql->execute();
                return true;
            } catch (PDOException $e) {
                echo "Erro:" . $e->getMessage();
            }
        }
    }

    public function get_marca(){

        global $pdo;
        global $msgErro;
        //verifica se já existe o cadastro.
        $sql = $pdo->prepare("SELECT * FROM marca");
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

		return $resultado;
    }

    public function get_tipo(){

        global $pdo;
        global $msgErro;
        //verifica se já existe o cadastro.
        $sql = $pdo->prepare("SELECT * FROM tipo");
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    public function get_carros()
    {
        global $pdo;
        global $msgErro;
        //verifica se já existe o cadastro.
        $sql = $pdo->prepare("SELECT C.Modelo,C.Ano,C.Placa, M.Nome,T.descricao FROM carros as C join  marca as m ON C.Marca = M.id join tipo as t on C.tipo = t.id");
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    public function get_placa($input)
    {
        global $pdo;
        global $msgErro;
        //verifica se já existe o cadastro.
        $sql = $pdo->prepare("SELECT C.Modelo,C.Ano,C.Placa, M.Nome,T.descricao FROM carros as C 
        join  marca as m ON C.Marca = M.id 
        join tipo as t on C.tipo = t.id 
        where C.placa like '%$input%'");
        $sql->execute();

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }
}