<div id="loading">Loading&#8230;</div>
<div class="bg"></div>

<div class="auth login">
    <p class="title"><?= APP_NAME ?></p>
    <form method="POST" id="login" novalidate>
        <input type="text" name="email" class="field" required="required" placeholder="E-mail ou usuário" />
        <input type="password" name="password" class="field" required="required" placeholder="Senha" />
        <button>Entrar</button>
    </form>
    <p class="toogle" onclick="$('.register').fadeIn()">Não tenho conta</p>
</div>

<div class="auth register">
    <p class="title">Criar uma conta</p>
    <form method="POST" id="register" novalidate>
        <input type="text" name="username" minlength="5" maxlength="15" class="field" required="required" placeholder="Usuário" />
        <input type="email" name="email" class="field" required="required" placeholder="E-mail" />
        <input type="password" name="password" minlength="8" class="field" required="required" placeholder="Senha" />
        <input type="password" name="repPassword" minlength="8" class="field" required="required" placeholder="Repetir Senha" />
        <button>Criar conta</button>
    </form>
    <p class="toogle" onclick="$('.register').fadeOut()">Já tenho uma conta</p>
</div>