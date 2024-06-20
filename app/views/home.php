<h1><?= $titulo ?></h1>

<?php foreach($clientes as $cliente) :  ?>
    <ul>
        <li><?= $cliente  ?> <i class="fa-regular fa-user"></i></li>
    </ul>
<?php endforeach  ?>