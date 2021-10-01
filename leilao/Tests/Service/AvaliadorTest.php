<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Avaliador;
use DomainException;
use Exception;

class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;

    //executa o setUp antes de cada teste
    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador;
    }
    /**
     * Undocumented function
     *
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     * 
     */
    public function testAvaliadorDeveEcontrarOMaiorValorDeLance(Leilao $leilao)
    {
        //Act - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();

        //Assert - Then

        self::assertEquals(4500, $maiorValor);
    }

    /**
     * Undocumented function
     *
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     * 
     */
    public function testAvaliadorDeveEcontrarOMenorValorDeLance(Leilao $leilao)
    {

        //Act - When
        $this->leiloeiro->avalia($leilao);
        $menorValor = $this->leiloeiro->getMenorValor();

        //Assert - Then

        self::assertEquals(2544, $menorValor);
    }

    /**
     * 
     * Undocumented function
     *
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     * @group naoExecutar
     */


    public function testAvaliadorDeveBuscarOs3MaioresLances(Leilao $leilao)
    {
        //act - when
        $this->leiloeiro->avalia($leilao);
        $maioresLances = $this->leiloeiro->getMaioresLances();

        //assert - then
        static::assertCount(3, $maioresLances);
        static::assertEquals(4500, $maioresLances[0]->getValor());
        static::assertEquals(3110, $maioresLances[1]->getValor());
        static::assertEquals(2544, $maioresLances[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possivel avaliar leilao vazio');

        $leilao = new Leilao('Barco T17');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Tesla 1M');
        $leilao->recebeLance(new Lance(new Usuario('Brenno'), 5000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public function leilaoEmOrdemCrescente()
    {

        $leilao = new Leilao('Camaro Amarelo 2012');

        $pedro = new Usuario('Pedro2017');
        $ana = new Usuario('Ana');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($pedro, 2544));
        $leilao->recebeLance(new Lance($ana, 3110));
        $leilao->recebeLance(new Lance($joao, 4500));

        return [
            'Ordem Crescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente()
    {


        $leilao = new Leilao('Camaro Amarelo 2012');

        $pedro = new Usuario('Pedro2017');
        $ana = new Usuario('Ana');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($joao, 4500));
        $leilao->recebeLance(new Lance($ana, 3110));
        $leilao->recebeLance(new Lance($pedro, 2544));

        return [
            'Ordem Decrescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria()
    {

        $leilao = new Leilao('Camaro Amarelo 2012');

        $pedro = new Usuario('Pedro2017');
        $ana = new Usuario('Ana');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($joao, 3110));
        $leilao->recebeLance(new Lance($ana, 4500));
        $leilao->recebeLance(new Lance($pedro, 2544));

        return [
            'Ordem Aleatoria' => [$leilao]
        ];
    }
}
