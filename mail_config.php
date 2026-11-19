<?php
//==========================
//CONFIGURAÇÃO DE ENVIO DE E-MAIL
//==========================
//Quando usar PHPMailer de verdade, altere os dados abaixo
//Enquanto isso, deixe usar_email_real = false para testar localmente.

return[
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'stmp_user' => 'seuemail@gmail.com',
    'smtp_pass' => 'sua_senha_de_app',
    'from_email' => 'seuemail@gmail.com',
    'from_name' => 'Sistema de Login',
    'usar_email_real' => false
];
?>