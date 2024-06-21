<?php

namespace App\Classes\Requests;

use App\Classes\Helpers\Store;
use App\Classes\Models\Database;

class RegistrarClientesRequest extends Database
{
    public function validarRegistro($post)
    {
        $email = strtolower(trim($post['email']));
        $senha = trim($post['senha']);
        $senha2 = trim($post['senha2']);
        $nome = trim($post['nome']);
        $endereco = trim($post['endereco']);
        $cidade = trim($post['cidade']);
        $telefone = trim($post['telefone']);

        $parametros = [':email' => $email];
        $select = $this->select('SELECT email FROM clientes WHERE email = :email', $parametros);

        // Validação de email e verificando se o email já possui registro.
        if (count($select) > 0) {
            $_SESSION['erro'] = 'Email já cadastrado.';
            header('Location: index.php?a=novoCliente');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = 'Formato do email inválido.';
            header('Location: index.php?a=novoCliente');
            return;
        }

        // Verificação de senha
        if ($senha !== $senha2) {
            $_SESSION['erro'] = 'As senhas não se correspondem.';
            header('Location: index.php?a=novoCliente');
            return;
        }

        // Verificação de campos vazios
        if (empty($nome)) {
            $_SESSION['erro'] = 'O campo nome é obrigátorio.';
            header('Location: index.php?a=novoCliente');
            return;
        }

        if (empty($endereco)) {
            $_SESSION['erro'] = 'O campo endereco é obrigátorio.';
            header('Location: index.php?a=novoCliente');
            return;
        }

        if (empty($cidade)) {
            $_SESSION['erro'] = 'O campo cidade é obrigátorio.';
            header('Location: index.php?a=novoCliente');
            return;
        }
        
        // Passando os dados já validados para salvar no banco de dados
        $dados = [
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':nome' => $nome,
            ':endereco' => $endereco,
            ':cidade' => $cidade,
            ':telefone' => $telefone,
            ':purl' => Store::criarHash(),
            ':ativo' => 0

        ];

        return $dados;
    
    }
}