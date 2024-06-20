<?php

namespace App\Classes\Models;

use PDO;
use PDOException;

class Database
{
    private $ligacao;

    //====================================================================================================
    private function ligar()
    {
        // Ligando ao banco de dados
        $this->ligacao = new PDO(
            'mysql:' .
                'host=' . MYSQL_SERVER . ';' .
                'dbname=' . MYSQL_DATABASE . ';' .
                'charset=' . MYSQL_CHARSET,
            MYSQL_USER,
            MYSQL_PASSWORD,
            array(PDO::ATTR_PERSISTENT => true)
        );

        // Debug
        $this->ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    //====================================================================================================
    private function desligar()
    {
        $this->ligacao = null;
    }

    //====================================================================================================
    public function select($sql, $parametros = null)
    {

        // Verificando se é uma instrução SELECT
        if (!preg_match('/^SELECT/i', $sql)) {
            die('Base de Dados não é uma instrução SELECT.');
        }

        // Ligando conexão
        $this->ligar();

        $resultados = null;

        // comunicando
        try {

            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
                $resultados = $executar->fetchAll(PDO::FETCH_OBJ);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
                $resultados = $executar->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            // Caso exista erro
            $this->desligar();
            return false;
        }

        // desligando conexão
        $this->desligar();

        // Retornando o resultado da query
        return $resultados;
    }

    //====================================================================================================
    public function insert($sql, $parametros = null)
    {

        // Verificando se é uma instrução INSERT
        if (!preg_match('/^INSERT/i', $sql)) {
            die('Base de Dados - Não é uma instrução INSERT.');
        }

        // Ligando conexão
        $this->ligar();

        // Iniciando Transação
        $this->ligacao->beginTransaction();

        // comunicando
        try {

            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->ligacao->commit();
        } catch (PDOException $e) {

            $this->ligacao->rollBack();
            // Caso exista erro
            $this->desligar();
            return false;
        }

        // desligando conexão
        $this->desligar();
    }

    //====================================================================================================
    public function update($sql, $parametros = null)
    {

        // Verificando se é uma instrução UPDATE
        if (!preg_match('/^UPDATE/i', $sql)) {
            die('Base de Dados - Não é uma instrução UPDATE.');
        }

        // Ligando conexão
        $this->ligar();

        // Iniciando Transação
        $this->ligacao->beginTransaction();

        // comunicando
        try {

            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->ligacao->commit();
        } catch (PDOException $e) {

            $this->ligacao->rollBack();
            // Caso exista erro
            $this->desligar();
            return false;
        }

        // desligando conexão
        $this->desligar();
    }

    //====================================================================================================
    public function delete($sql, $parametros = null)
    {

        // Verificando se é uma instrução DELETE
        if (!preg_match('/^DELETE/i', $sql)) {
            die('Base de Dados - Não é uma instrução DELETE.');
        }

        // Ligando conexão
        $this->ligar();

        // Iniciando Transação
        $this->ligacao->beginTransaction();

        // comunicando
        try {

            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->ligacao->commit();
        } catch (PDOException $e) {

            $this->ligacao->rollBack();
            // Caso exista erro
            $this->desligar();
            return false;
        }

        // desligando conexão
        $this->desligar();
    }

    //====================================================================================================
    public function statement($sql, $parametros = null)
    {

        // Verificando se é não é nenhuma das instruções anteriores
        if (preg_match('/^SELECT|SELECT|UPDATE|DELETE/i', $sql)) {
            die('Base de Dados - Instrução inválida.');
        }

        // Ligando conexão
        $this->ligar();


        // comunicando
        try {

            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
        } catch (PDOException $e) {

            // Caso exista erro
            $this->desligar();
            return false;
        }

        // desligando conexão
        $this->desligar();
    }
}
