<div class="container">
    <div class="row justify-content-center align-items-center my-5">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <h3 class="text-center tituloRegistro">Novo Cliente</h3>
            <form action="?a=registrarCliente" method="post">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar a senha</label>
                    <input type="password" name="senha2" class="form-control" placeholder="Confirmar a senha" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="endereco" class="form-control" placeholder="Endereço" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cidade</label>
                    <input type="text" name="cidade" class="form-control" placeholder="Cidade" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Telefone</label>
                    <input type="tel" name="telefone" class="form-control" placeholder="Telefone">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn bntSubmit">Cadastrar</button>
                </div>

                <?php if(isset($_SESSION['erro'])) : ?>
                    <div class="alert alert-danger text-danger text-center">
                        <?= $_SESSION['erro'] ?>
                        <?php unset($_SESSION['erro']) ?>
                    </div>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>