<?php
    //Carrega a conexão com o banco e as funções
    require 'conexao.php';
    require 'functions.php';

    //Se já tiver logado, redireciona direto para o painel
    if(usuario_logado()){
        header('Location: painel.php');
        exit;
    }

    //Verifica se o formulário foi enviado por POST
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //recebe os dados digitados no formulário
        $email = trim($_POST['email']?? '');
        $senha = $_POST['senha']?? '';

        //Busca o usuário pelo e-mail informado
        $stmt = $pdo->prepare('select * from usuarios where email = ? limit 1');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        //Verifica se o usuário existe e se senha confere com o hash salvo noo banco
        if($usuario && password_verify($senha, $usuario['senha'])){
            //Guarda dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];

            //Redireciona para a área interna
            header('Location: painel.php');
            exit;
        }
        //Caso o login falhe, grava mensagem e volta para a tela inicial
        set_flash('error','E-mail ou senha inválidos.');
        header('Location: index.php');
        exit;
    }
    //Lê mensagem temporaria, se existir
    $flash = get_flash();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Tela de Login</h1>
        <?php if($flash): ?>
            <div class="flash <?= e($flash['tipo']) ?>"> <?= e($flash['mensagem']) ?> </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</botton>
        </form>
        <div class="links">
            <p><a href="cadastro.php">Criar conta</a></p>
            <p><a href="esqueci-senha.php">Esqueci minha senha</a></p>
        </div>
    </div>

    
</body>
</html>
