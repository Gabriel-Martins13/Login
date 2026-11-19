<?php
//Carrega banco, funções e configuração de e-mail
require 'conexao.php';
require 'functions.php';
$mailConfig = require 'mail_config.php';

//Variável usada para mostrar link de teste quando não houver SMTP real
$resetLinkTeste = null;

//Verefica envio do formulário
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']?? '');

    //Procura usuário pelo e-mail informado
    $stmt = $pdo->prepare('select * from usuarios where email = ? limit 1');
    $usuario = $stmt->fetch();

    if($usuario){
        //Cria um token aleatório seguro
        $token = bin2hex(random_bytes(32));

        //Define validade de 1 hora para o token
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        //Salva o token na tabela password_resets
        $insert = $pdo->prepare('insert into password_resets (usuario_id, token, expira_em)values(?,?,?)');
        $insert->execute([$usuario['id'], $token,  $expira]);

        //Monta a URL Dinamica baseada no servidr/dominio atual
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https': 'https';

        //Mostra o link que o usuário usará para trocar a senha
        $link='http://localhost/Sistema_Login/app/resetar-senha.php?token=' . urlencode($token);

        //Se estiver configurado para usar e-mail real e existir vendor/autoload.php
        if($mailConfig['usar_email-real'] === true & file_exists(__DIR__ . '/vendor/autoload.php')){
            require __DIR__ .'/vendor/autoload.php';

            //Cria a instância do PHPmailer
            $mail =new PHPMailer\PHPMailer\PHPMailer(true);

            try{
                //Configurações SMTP
                $mail->isSMTP();
                $mail-> Host = $mailConfig['smtp_host'];
                $mail->SMTPauth = true;
                $mail->Username = $mailConfig['smtp_user'];
                $mail->Password = $mailConfig['smtp_pass'];
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTS;
                $mail->Port = $mailConfig['smtp_port'];
                $mail->Charset = 'UTF-8';
                
                //Remetente e destinatário
                $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);

                //Conteúdo do e-mail
                $mail->isHTML(true);
                $mail->Subject ='Recuperação de senha';
                $mail->Body = 'Olá, ' .e($usuario['nome']) . '.<br><br>Clique no link para redefinir sua senha:<br><a href"' . $link . '</a><br><br>Este link expira em 1 hora.';
                $mail->send();
                
                set_flash('sucess','Se o e-mail existir, um link de recuperação foi enviado');
                header('Location:index.php');
                exit;
            }catch(Exception $e){
                set_flash('error', 'Falha ao enviar e-mail. Revise o SMTP.');
            }
        }else{
            //Modo de teste local sem envio real
            $resetLinkTeste = $link;
            set_flash('info', 'Modo de teste ativado. Copie o link abaixo para redefinir a senha.');
        }
    }else{
        //Mensagem generica de segurança
        set_flash('info', 'Se o e-mail existir, um link de recuperação será enviada.');
    }
}
$flash = get_flash();
?> 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Recuperar Senha</h1>

        <?php if($flash): ?>
    <div class="flash <?= e($flash['tipo'])?>"><?= e($flash['mensagem']) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Digite seu e-mail</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Enviar link</button>
        </form>
        <?php if($resetLinkTeste): ?>
            <p class="smail" style="margin-top:15px; word-break:break-all;">Link de teste: <br>
        <a href="<?= e($resetLinkTeste)?>"><?= e($resetLinkTeste)?></a></p>
            <?php endif; ?>
            <div class="links">
                <p><a href="index.php">Voltar ao login</a></p>
            </div>
    </div>
</body>
</html>