<?php
// Arquivos de apoio
require 'config.php';
require 'functions.php';

// Recebe o token pela URL ou pelo formulário
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

// Quando o formulário de redefinição for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    // Validações
    if ($senha === '' || $confirmar === '') {
        set_flash('error', 'Preencha os dois campos de senha.');
    } elseif ($senha !== $confirmar) {
        set_flash('error', 'As senhas não coincidem.');
    } elseif (strlen($senha) < 6) {
        set_flash('error', 'A nova senha deve ter pelo menos 6 caracteres.');
    } else {
        // Verifica se o token existe, não foi usado e ainda está válido
        $stmt = $pdo->prepare('SELECT * FROM password_resets WHERE token = ? AND usado = 0 AND expira_em >= NOW() LIMIT 1');
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if ($reset) {
            // Criptografa a nova senha
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            // Atualiza a senha do usuário
            $updateUser = $pdo->prepare('UPDATE usuarios SET senha = ? WHERE id = ?');
            $updateUser->execute([$hash, $reset['usuario_id']]);

            // Marca o token como usado
            $updateReset = $pdo->prepare('UPDATE password_resets SET usado = 1 WHERE id = ?');
            $updateReset->execute([$reset['id']]);

            // Volta para o login com mensagem de sucesso
            set_flash('success', 'Senha redefinida com sucesso. Faça o login.');
            header('Location: index.php');
            exit;
        } else {
            set_flash('error', 'Token inválido, expirado ou já utilizado.');
        }
    }
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Redefinir Senha</h1>

    <?php if ($flash): ?>
        <div class="flash <?= e($flash['tipo']) ?>"><?= e($flash['mensagem']) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="token" value="<?= e($token) ?>">

        <label for="senha">Nova senha</label>
        <input type="password" name="senha" id="senha" required>

        <label for="confirmar_senha">Confirmar nova senha</label>
        <input type="password" name="confirmar_senha" id="confirmar_senha" required>

        <button type="submit">Salvar nova senha</button>
    </form>

    <div class="links">
        <p><a href="index.php">Voltar ao login</a></p>
    </div>
</div>
</body>
</html>
