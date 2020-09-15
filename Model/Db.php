<?php

$host_db = 'localhost';
$banco_db = 'locadora';
$usuario_db = 'root';
$senha_db = '';

for ($db_connection_try = 1; $db_connection_try <= 30; $db_connection_try++) {
    try {
        $pdo = new PDO("mysql:host=$host_db;dbname=$banco_db", $usuario_db, $senha_db, array(
            PDO::ATTR_PERSISTENT => true
        ));
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break;
    } catch (PDOException $e) {
        usleep(300000); //Pausa em milionésimos de segundo para uma nova tentativa. Nesse caso, 0.3 segundos.
        //echo "Conexão com o banco de dados falhou: " . $e->getMessage();
    }
}

function inserir($tabela, $dados)
{
    global $pdo;

    if (is_array($tabela)) {
        $tabela_string = implode(" ", $tabela);
    } else {
        $tabela_string = $tabela;
    }


    foreach ($dados as $coluna => $valor) {
        $colunas[] = $coluna;
        $colunas_pre[] = ':' . $coluna;
        $valores[$coluna] = $valor;
    }

    $colunas_string = implode(',', $colunas);
    $colunas_pre_string = implode(',', $colunas_pre);

    $query = "INSERT INTO $tabela_string ($colunas_string) VALUES($colunas_pre_string)";

    $statement = $pdo->prepare($query);
    foreach ($colunas as $coluna) {
        $statement->bindValue(":$coluna", $valores[$coluna]);
    }

    $statement->execute();

    $id = $pdo->lastInsertId();
    return $id;
}

//Função abaixo serve para alterar informações no banco de dados usando método PDO
function alterar($tabela, array $dados, $where)
{
    global $pdo;
    foreach ($dados as $coluna => $valor) {
        $colunas[] = $coluna;
        $colunas_pre[] = $coluna . '=:' . $coluna;
        $valores[$coluna] = $valor;
    }

    $colunas_query = implode(', ', $colunas_pre);

    if (is_array($where)) {
        foreach ($where as $coluna => $valor) {
            $where_array2[] = "$coluna=:$coluna";
        }
        $where_string = implode(' and ', $where_array2);
        if ($where_string != '') {
            $where_string = 'WHERE ' . $where_string;
        }
    } else {
        if (is_numeric($where) and $where > 0) {
            $where_string = "WHERE id='$where' ";
        } else {
            if ($where != '') {
                $where_string = 'WHERE ' . $where;
            }
        }
    }

    $query = "UPDATE $tabela SET $colunas_query $where_string";
    $statement = $pdo->prepare($query);
    foreach ($colunas as $coluna) {
        $statement->bindValue(":$coluna", $valores[$coluna]);
    }
    //Protegendo dados se where veio em Array:
    if (is_array($where)) {
        foreach ($where as $coluna => $valor) {
            $statement->bindValue(":$coluna", $valor);
        }
    }
    $statement->execute();
    return true;
}


//Função abaixo serve para deletar informações no banco de dados usando método PDO
function deletar($tabela, $where)
{
    global $pdo;

    $tabela_string = '';
    $where_string = '';

    if (is_array($tabela)) {
        $tabela_string = implode(" INNER JOIN ", $tabela);
    } else {
        $tabela_string = $tabela;
    }

    if (is_array($where)) {
        foreach ($where as $coluna => $valor) {
            $where_array2[] = "$coluna=:$coluna";
        }
        $where_string = implode(' and ', $where_array2);
        if ($where_string != '') {
            $where_string = 'WHERE ' . $where_string;
        }
    } else {
        if (is_numeric($where) and $where > 0) {
            $where_string = "WHERE id='$where' ";
        } else {
            if ($where != '') {
                $where_string = 'WHERE ' . $where;
            }
        }
    }

    if ($where_string != '') {
        $query = "DELETE FROM $tabela_string $where_string";
        $statement = $pdo->prepare($query);

        //Protegendo dados se where veio em Array:
        if (is_array($where)) {
            foreach ($where as $coluna => $valor) {
                $statement->bindValue(":$coluna", $valor);
            }
        }

        $statement->execute();
        return true;
    } else {
        return false;
    }
}

//Função abaixo para ler uma tabela e retornar os dados em array, usando método PDO.
function ler($tabela, $where = '', $order_by = '', $limit = 0)
{
    global $pdo;
    $tabela_string = '';
    $where_string = '';
    $order_by_string = '';
    $limit_string = '';


    if (is_array($tabela)) {
        $tabela_string = implode(" INNER JOIN ", $tabela);
    } else {
        $tabela_string = $tabela;
    }

    if (is_array($where)) {
        foreach ($where as $coluna => $valor) {
            $where_array2[] = "$coluna=:$coluna";
        }
        $where_string = implode(' and ', $where_array2);
        if ($where_string != '') {
            $where_string = 'WHERE ' . $where_string;
        }
    } else {
        if (is_numeric($where) and $where > 0) {
            $where_string = "WHERE id='$where' ";
        } else {
            if ($where != '') {
                $where_string = 'WHERE ' . $where;
            }
        }
    }

    if (is_array($order_by)) {
        foreach ($order_by as $coluna => $valor) {
            if (!is_numeric($coluna)) {
                $order_by_array2[] = $coluna . ' ' . $valor;
            } else {
                $order_by_array2[] = $valor;
            }
            $order_by_string = implode(',', $order_by_array2);
            if ($order_by_string != '') {
                $order_by_string = 'ORDER BY ' . $order_by_string;
            }
        }
    } else {
        if ($order_by != '') {
            $order_by_string = 'ORDER BY ' . $order_by;
        }
    }

    if (is_array($limit)) {
        foreach ($limit as $x) {
            $limit_string = $x;
        }

        if ($limit_string > 0) {
            $limit_string = 'LIMIT ' . $limit_string;
        } else {
            $limit_string = '';
        }
    } else {
        if ($limit > 0) {
            $limit_string = 'LIMIT ' . $limit;
        }
    }

    $query = "SELECT * FROM $tabela_string $where_string $order_by_string $limit_string";
    $statement = $pdo->prepare($query);
    //Protegendo dados se where veio em Array:
    if (is_array($where)) {
        foreach ($where as $coluna => $valor) {
            $statement->bindValue(":$coluna", $valor);
        }
    }
    $statement->execute();

    $dados = $statement->fetchAll(\PDO::FETCH_ASSOC);
    return $dados;
}

function ler_query($query)
{
    global $pdo;
    $statement = $pdo->prepare($query);
    $statement->execute();
    $dados = $statement->fetchAll(\PDO::FETCH_ASSOC);
    return $dados;
}

function executar_query($query)
{
    global $pdo;
    $statement = $pdo->prepare($query);
    return $statement->execute();
}