<div id="loading">Loading&#8230;</div>

<div id="inbox" class="column">
    <p class="title">Conversas</p>
    <input type="text" maxlength="15" name="search" class="searchField" onkeyup="search()" placeholder="Pesquisar utilizador" />
    <div id="searchContainer">

    </div>
    <div class="container"></div>
</div>

<div id="chat" class="column"></div>

<div id="profile" class="column">
    <p class="title">Perfil</p>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" id="uploadPic">
            <input type='file' name="imgInp" accept="image/x-png,image/jpeg" id="imgInp" hidden />
            <div class="pictureContainer">
                <img id="userImg" src="assets/profilePics/<?= $user->picture ?>" />
                <label for="imgInp"></label>
            </div>
        </form>

        <p class="name"><?= $user->username ?></p>
        <p class="row">Online <?= timing(strtotime($user->status)) ?></p>
        <p class="row">Membro desde <?= date('d/m/Y', strtotime($user->created_at)) ?></p>
    </div>
    <div class="menu">
        <button onclick="logout()">Sair</button>
    </div>
</div>