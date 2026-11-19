<?php
//==============================================
// Funções Auxiliares do Projeto
//==============================================

//Inicia a sessão se ela ainda não estiver iniciada
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

//Função para caracteres especiais
function e($valor){
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'utf-8');
}

//Salva uma mensagem temporária na sessão
function set_flash($tipo, $mensagem){
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem'=> $mensagem];
}

//Recupera a mensagem temporária e a remove da sessão
function get_flash(){
    if(!isset($_SESSION['flash'])){
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

//Verifica se existe um usuário logado
function usuario_logado(){
    return isset($_SESSION['usuario_id']);
}

//Bloque acesso a páginas privadas caso não haja login
function exigir_login(){
    if(!usuario_logado()){
        header('Location: index.php');
        exit;
    }
}