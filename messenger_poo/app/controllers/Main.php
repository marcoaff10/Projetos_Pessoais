<?php

namespace messenger\Controllers;

use messenger\Controllers\BaseController;
use messenger\Models\Models;

class Main extends BaseController
{

    //============================================================================================================
    public function index()
    {

        // caso haja um usuário na sessão mandamos ele direto para a tela de inbox
        if (check_session()) {
            $this->inbox();
            return;
        }


        $this->view('layouts/header');
        $this->view('auth');
        $this->view('layouts/footer');
    }

    //============================================================================================================
    public function inbox($search = [])
    {

        // caso não haja um usuário na sessão mandamos ele para a tela de login
        if (!check_session()) {
            $this->index();
            return;
        }
        // se houver um usuário na sessão passamos seus dados para a tela de inbox
        $data['user'] = $_SESSION['user'];


        $this->view('layouts/header');
        $this->view('inbox', $data);
        $this->view('layouts/footer');
    }

    //============================================================================================================
    public function register()
    {

        // checando se houve uma requisição post 
       if  ($_SERVER['REQUEST_METHOD'] != 'POST') {
            die(header("HTTP/1.0 401 Envie o formulário de registro"));
            return;
        }

        // verificando se recebemos os campos do formulário
        if (!isset($_POST['username']) && !isset($post['email']) && !isset($post['password']) && !isset($post['repPassword'])) {
            die(header("HTTP/1.0 401 401 Envie o formulário de registro"));
            return;
        }

        // definindo valores do formulário em variáveis
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repPassword = $_POST['repPassword'];


        // validações do formulário de registro
        if (empty($username)) {
            die(header("HTTP/1.0 401 Preencha o campo de Usuario."));
            return;
        }

        if (empty($email)) {
            die(header("HTTP/1.0 401 Preencha o campo de E-mail."));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die(header("HTTP/1.0 401 E-mail invalido."));
            return;
        }

        if (empty($password)) {
            die(header("HTTP/1.0 401 Preencha o campo de Senha."));
            return;
        }

        if (strlen($password) < 8) {
            die(header("HTTP/1.0 401 A Senha deve ter no minimo 8 caracteres."));
            return;
        }

        if (empty($repPassword)) {
            die(header("HTTP/1.0 401Preencha a sua senha novamente."));
            return;
        }

        if ($repPassword != $password) {
            die(header("HTTP/1.0 401 Senhas diferentes."));
            return;
        }

        // encriptando senha
        $password = password_hash($password, PASSWORD_DEFAULT);

        // definindo token e secure
        $token = bin2hex(openssl_random_pseudo_bytes(20));
        $secure = rand(100000000, 999999999);


        $model = new Models();
        $results = $model->check_exists_username($username);

        // verificando se já existe o mesmo username de outro usuário
        if (!$results) {
            die(header("HTTP/1.0 401 Ja existe um usuario com esse username."));
            return;
        }

        // verificando se já existe um email cadastrado por ouro usuário.
        $results = $model->check_exists_email($email);
        if (!$results) {
            die(header("HTTP/1.0 401 E-mail ja cadastrado em outra conta."));
            return;
        }

        // salvando na base de dados
        $model->register_user($username, $email, $password, $token, $secure);

        $results = $model->data_user($username);

        $_SESSION['user'] = $results['data'];

        if (isset($_SESSION['user'])) {
            $this->inbox();
            return;
        }
    }

    //============================================================================================================
    public function login()
    {

        // checando se houve uma requisição post 
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        // verificando se recebemos os campos do formulário
        if (!isset($_POST['email']) && !isset($_POST['password'])) {
            $this->index();
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            die(header("HTTP/1.0 401 Preencha o campo de E-mail."));
            return;
        }

        if (empty($password)) {
            die(header("HTTP/1.0 401 Preencha o campo de Senha."));
            return;
        }

        $model = new Models();
        $results = $model->check_login($email, $password);

        if (!$results) {
            die(header("HTTP/1.0 401 Senha incorreta."));
            return;
        }

        $results = $model->data_user($email);

        $_SESSION['user'] = $results['data'];
        $this->inbox();
    }

    //============================================================================================================
    public function logout()
    {
        if (!check_session()) {
            $this->index();
            return;
        }

        unset($_SESSION['user']);
        $this->index();
    }

    //============================================================================================================
    public function search($search)
    {

        $model = new Models();
        $results = $model->search($search, $_SESSION['user']->id);

        if ($results->affected_rows == 0) {
            echo '<p class="noResults">Sem resultados</p>';
        } else {
            foreach ($results->results as $friends) {
?>
                <div class="row" id="search" onclick="$('#searchContainer').hide(); chat('<?= $friends->id ?>');">
                    <img src="assets/profilePics/<?= $friends->picture ?>" />
                    <p><?= $friends->username ?></p>
                </div>
            <?php
            }
        }
    }

    //============================================================================================================
    public function load_inbox()
    {
        $model = new Models();
        $results = $model->select_conversation($_SESSION['user']->id);

        if ($results->affected_rows == 0) {
            echo '<div class="empty"><p>Pesquise um utilizador e começe um chat!</p></div>';
            return;
        }

        foreach ($results->results as $inbox) {
            $results = $model->data_friend($inbox->friend_user);

            if ($results->affected_rows != 0) {
                $friend = $results->results[0];
            ?>
                <div class="chat <?= $inbox->unread == 1 ? 'new' : '' ?>" onclick="chat('<?= $friend->id ?>')">
                    <img src="assets/profilePics/<?= $friend->picture ?>" />
                    <p><?= $friend->username ?></p>
                </div>
            <?php
            }
        }
    }

    //============================================================================================================
    public function update_profile()
    {
        if (!check_session()) {
            $this->index();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->inbox();
            return;
        }

        $img_name = $_SESSION['user']->username . "_" . rand(999, 999999) . $_FILES['imgInp']['name'];

        $img_temp = $_FILES['imgInp']['tmp_name'];
        $img_path = "assets/profilePics/";

        if (!is_uploaded_file($img_temp)) {
            die(header("HTTP/1.0 401 Erro ao carregar imagem"));
            return;
        }

        if (!move_uploaded_file($img_temp, $img_path . $img_name)) {
            die(header("HTTP/1.0 401 Erro ao guardar imagem"));
            return;
        }

        $model = new Models();

        $model->update_profile_picture($img_name, $_SESSION['user']->id);
        $_SESSION['user']->picture = $img_name;
        $this->inbox();
    }

    //============================================================================================================
    public function chat($id)
    {
        // verificando se o id não é o mesmo do usuário ativo na sessão
        if ($id > 0) {
            $friend_id = $id;

            // buscando dados do usuário cujo Id corresponde
            $model = new Models();
            $friend_chat = $model->data_friend($friend_id);
            $friend = $friend_chat->results[0];

            // devolvendo os dados para a div chat
        ?>
            <div class="topMenu">
                <img class="imgProfile" src="assets/profilePics/<?= $friend->picture ?>" />
                <p class="title"><?= $friend->username ?>
                    <span class="statusFriend">Online <?= timing(strtotime($friend->status)) ?></span>
                </p>
                
                <img class="imgClose" src="assets/img/close.png" onclick="chat()" />
            </div>

            <div class="innerContainer"></div>

            <form method="POST" enctype="multipart/form-data" id="sendMessage">
                <input type="number" value="<?= $friend->id ?>" name="id" hidden />
                <input type="text" maxlength="500" name="message" id="messageInput" placeholder="Escreva aqui a sua mensagem" />
                <input type='file' name="image" accept="image/x-png,image/jpeg" id="sendImage" hidden />
                <label for="sendImage"><img src="assets/img/image.png" /></label>
            </form>

            <script>
                // chamando o metodo para criar uma conversa entre os dois usuários 
                function sendMessage() {
                    var form = new FormData($("#sendMessage")[0]);
                    $.ajax({
                        type: 'post',
                        url: '?ct=main&mt=send_message',
                        data: form,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $("#sendMessage")[0].reset();
                        },
                        error: function(error) {
                            console.log(error);
                            Swal.fire({
                                title: 'Mensagem não enviada',
                                text: error.statusText,
                                icon: 'error',
                                confirmButtonText: 'Tentar novamente'
                            })
                        }
                    });
                }

                // chamando a função através da tecla enter
                $("#messageInput").on('keyup', function(e) {
                    if (e.keyCode === 13 && ($("#messageInput").val().length > 0)) {
                        sendMessage();

                    }
                });


                // chamando a função quando selecionamos uma foto
                $("#sendImage").change(function() {
                    sendMessage();

                });

                // atualizando o chat a cada 0,1 segundos
                setInterval(() => {
                    $.ajax({
                        url: '?ct=main&mt=retriever&id=<?= $friend_id ?>',
                        success: function(data) {
                            $('#chat .innerContainer').html(data);
                            $('#chat .innerContainer').scrollTop($('#chat .innerContainer').prop("scrollHeight"));
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Erro de chat',
                                text: error.statusText,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }, 300);
            </script>

            <?php
        }
    }

    //============================================================================================================
    public function send_message()
    {
        // checando se está definido os valores do formulário de envio de menssagem
        if (isset($_POST['message']) && isset($_POST["id"])) {

            // atribuindo os valores recebidos a variáveis
            $friend_id = $_POST['id'];
            $message = $_POST['message'];
            $image = "";

            // buscando dados do usuário
            $model = new Models();
            $results = $model->data_friend($friend_id);
            $username = $results->results[0]->username;

            // tratando imagem recebida e salvando no servidor
            if ($_FILES['image']['error'] <= 0) {
                $image = $username . "_MESSAGE_" . rand(999, 999999) . $_FILES['image']['name'];
                $img_temp = $_FILES['image']['tmp_name'];
                $img_path = "assets/uploads/";
                if (is_uploaded_file($img_temp)) {
                    if (move_uploaded_file($img_temp, $img_path . $image)) {
                        echo "ok";
                    } else {
                        die(header("HTTP/1.0 401 Erro ao guardar imagem"));
                    }
                } else {
                    die(header("HTTP/1.0 401 Erro ao carregar imagem"));
                }
            } elseif (empty($friend_id) || empty($message)) {
                die(header("HTTP/1.0 401 Escreva uma mensagem"));
            }

            // checando se já existe conversa entre os dois usuários
            $model = new Models();
            $results = $model->check_conversation($_SESSION['user']->id, $friend_id);

            if ($results->affected_rows < 1) {

                //Criando conversa caso não haja conversa entre os dois usuários
                $unread = 0;
                $model->create_conversation($_SESSION['user']->id, $friend_id, $unread);

                // criando tambem a conversa para o outro usuário
                $unread = 1;
                $model->create_conversation_friend($_SESSION['user']->id, $friend_id, $unread);
            } else {

                // Atualizando conversa caso ela já exista
                $unread = 1;
                $model->update_conversation($_SESSION['user']->id, $friend_id, $unread);
            }

            $model->create_chat($_SESSION['user']->id, $friend_id, $message, $image);
        } else {
            die(header("HTTP/1.0 401 Faltam parametros"));
        }
    }

    //============================================================================================================
    public function retriever($id)
    {

        if (isset($_GET['id'])) {


            $model = new Models();

            $friend_id = $_GET['id'];
            $uid = $_SESSION['user']->id;
            $friend_user = $model->data_friend($friend_id);

            $friend_user = $friend_user->results[0];


            $result = $model->select_chat($friend_id, $uid);


            if ($result['status']) {
                $results = $result['chat'];
                foreach ($results as $message) {
                    if ($message->sender == $uid && $message->image != "") {
            ?>
                        <div class="row sent">
                            <img src="assets/uploads/<?= $message->image ?>" />
                        </div>
                    <?php
                    } elseif ($message->sender == $uid) {
                    ?>
                        <div class="row sent">
                            <p><?= $message->message ?></p>
                        </div>
                    <?php
                    } elseif ($message->image != "") {
                    ?>
                        <div class="row recieved">
                            <img src="assets/uploads/<?= $message->image ?>" />
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="row recieved">
                            <p><?= $message->message ?></p>
                        </div>
<?php
                    }
                }

                $unread = 0;
                $model->update_conversation($uid, $friend_id, $unread);
            } else {
                echo '<p class="info">Envie a sua primeira mensagem para ' . $friend_user->username . '</p>';
            }
        }
    }
}
