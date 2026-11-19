<?php
    //Inicia a sessão para limpá-la
    session_start();

    //Remove todas as variáveis da sessão
    session_unset();

    //Destroi a sessão atual
    session_destroy();

    //Redirecion o usuário para tela de login
    header('Location: index.php');
    exit;
?>