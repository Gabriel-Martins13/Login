<?php
//Carrega funções e exige que o usuário esteja autenticado
require 'functions.php';
exigir_login();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Painel de Sistema</h1>

        <p>Bem-Vindo, <strong><?= e($_SESSION['usuario_nome']) ?></strong></p>
        <p class="small">E-mail do usuário logado: <?= e($_SESSION['usuario_email']) ?></p>

        <a href="logout.php" class="btn">Sair</a>
        
    </div>
    
</body>
</html>