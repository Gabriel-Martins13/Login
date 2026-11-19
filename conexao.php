<?php
    //Aqui definimos os dados necessários para o PHP
    //conseguir se conectar ao MySQL usnado PDO

    $host = "localhost"; //endereço do servidor MySQL local
    $db   = "sistema_login";//nome do banco de dados
    $user = "root";      //Usuário padrão do Laragon
    $pass = "";        //Senha padrão do Laragon, costuma ser vazia
    $charset = "utf8mb4"; //charset recomendado para suportar caracteres especiais da lingua portuguesa

    //Monta a string de conexão do PDO
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    //Opções importantes da conexão
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //mostra erros como execeção
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //RETORNO EM FORMATO ASSOCIATIVO
        PDO::ATTR_EMULATE_PREPARES => false, //usa prepared statements reais
    ];
    try{
        //tenta criar a conexão com o banco
        $pdo = new PDO($dsn, $user, $pass, $options);
    }catch(PDOException $e){
        //Se algo der errado, exibe a mensagem
        exit('Erro na conexão com o banco: '. $e->getMessage());
    }

?>