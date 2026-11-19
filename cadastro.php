<?php

require 'conexao.php';
require 'functions.php';

//Verifica se o formulario foi enviados
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //recebe os dados digitados
    $nome = trim($_POST['nome']?? '');
    $email = trim($_POST['email']?? '');
    $senha = $_POST['senha']?? '';
    $confirmar = $_POST['confirmar_senha']?? '';

    //Validações
    if($nome === '' || $email === '' || $senha === '' || $confirmar === ''){
        set_flash('error','Preencha todos os campos.');
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        set_flash('error',"Informe um e-mail válido");
    }elseif($senha !== $confirmar){
        set_flash('error', 'As senhas não coincidem.');
    }elseif(strlen($senha) < 6){
        set_flash('error', 'A senha deve ter pelo menos 6 caracteres');
    }else{
        //Verificar se já existe usuário com esse e-mail
        $check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
        $check->execute([$email]);

        if($check->fetch()){
            set_flash('error', 'Este e-mail já está cadastrado');
        }else{
            //Criptografa a senha antes de salvar
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            //Insere o novo usuário no banco
            $stmt = $pdo->prepare('INSERT INTO usuarios(nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $hash]);

            //Mostra ,ensagem e manda para o login
            set_flash('success', 'Cadastro realizado com sucesso. Faça o login.');
            header('Location: index.php');
            exit;
        }
    }
    //Se cair em alguma validação com erro, volta para o cadastro
    header('Location: cadastro.php');
    exit;
}
$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if($flash): ?>
            <div class="flash <?= e($flash['tipo']) ?>"><?= e($flash['mensagem']) ?></div>
        <?php endif;?>

        <form method="POST">
             <label for="nome">Nome</label>
             <input type="text" name="nome" id="nome" required>

             <label for="email">E-mail</label>
             <input type="text" name="email" id="email" required>

             <label for="senha">Senha</label>
             <input type="password" name="senha" id="senha" required>

             <label for="confirmar_senha">Confirmar senha</label>
             <input type="password" name="confirmar_senha" id="confirmar_senha" required>

             <button type="submit">Cadastrar</button>
        </form>
        <div class="links">
            <p><a href="index.php">Volta ao login</a></p>
        </div>
    </div>
</body>
</html>