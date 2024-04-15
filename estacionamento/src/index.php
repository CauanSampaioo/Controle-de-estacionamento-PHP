<?php
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);


require('connection.php');

// Função para tratar a rota '/'
function homeRoute() {
    echo "Funcionou!";
}

// Função para tratar a rota '/sobre'
function aboutRoute() {
    echo "Esta é a página Sobre nós!";
}

// Função para tratar a rota '/contato'
function contactRoute() {
    echo "Entre em contato conosco!";
}

function cadastrarCategorias() {
  require('./src/controller/cadastrar_categorias.php');
}

function listarCategorias() {
  require('./src/controller/listar_categorias.php');
}

function listarVeiculos() {
  require('./src/controller/listar_veiculos.php');
}

function cadastrarVeiculos() {
  require('./src/controller/cadastrar_veiculos.php');
}

//function saidaVeiculo() {
//  require('./src/controller/saida_veiculo.php');
//}

function movimentarVeiculo() {
  require('./src/controller/registrar_movimento_veiculo.php');
}

function listarMovimento() {
  require('./src/controller/listar_movimento_veiculo.php');
}

// Configuração das rotas e funções executadas
$routes = [
    '/' => 'homeRoute',
    '/sobre' => 'aboutRoute',
    '/contato' => 'contactRoute',

    /* Categoria */
    '/cadastrar-categorias' => 'cadastrarCategorias',
    '/listar-categorias' => 'listarCategorias',

    /* Veiculos */
    '/cadastrar-veiculos' => 'cadastrarVeiculos',
    '/listar-veiculos' => 'listarVeiculos',

    /* Movimento de veículos */
    //'/saida-veiculo' => 'saidaVeiculo',
    //'/entrada-veiculo' => 'entradaVeiculo',
    '/movimento-veiculo' => 'movimentarVeiculo',
    '/lista-movimento-veiculo' => 'listarMovimento'
];

// Verifica se a rota solicitada na URL existe no array de rotas
$route = $_SERVER['REQUEST_URI'];
if (array_key_exists($route, $routes)) {
    // Se a rota existir, chama a função correspondente
    $function = $routes[$route];
    $function(); // executa a função da rota
} else {
    // Se a rota não existir, exibe uma mensagem de erro
    echo "Rota não encontrada!";
}
#php -S localhost:8000 rotas.php

//var_dump($_SERVER['REQUEST_URI']);
//var_dump($pdo);
