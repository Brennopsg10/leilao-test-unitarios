<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

//Arrenge - Given
$leilao = new Leilao('Camaro Amarelo 2012');

$pedro = new Usuario('Pedro2017');
$ana = new Usuario('Ana');

$leilao->recebeLance(new Lance($pedro, 2544));
$leilao->recebeLance(new Lance($ana, 3110));
$leiloeiro = new Avaliador();

//Act - When
$leiloeiro->avalia($leilao);
$maiorValor = $leiloeiro->getMaiorValor();

//Assert - Then
$valorEsperado = 3120;

if($valorEsperado == $maiorValor){
    echo 'Teste Ok';
}
else{
    echo 'TESTE FALHOU';
}