<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use DomainException;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

    public function testLeilaoNaoDeveReceberMaisDeUmLanceDoMesmoUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuario nao pode propor 2 lances consecutivos');
        $ana = new Usuario('Ana');

        $leilao =  new Leilao('variante');
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuario nao pode propor mais de 5 lances por leilao');

        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 100));
        $leilao->recebeLance(new Lance($maria, 150));
        $leilao->recebeLance(new Lance($joao, 200));
        $leilao->recebeLance(new Lance($maria, 250));
        $leilao->recebeLance(new Lance($joao, 300));
        $leilao->recebeLance(new Lance($maria, 350));
        $leilao->recebeLance(new Lance($joao, 400));
        $leilao->recebeLance(new Lance($maria, 450));
        $leilao->recebeLance(new Lance($joao, 500));
        $leilao->recebeLance(new Lance($maria, 550));

        $leilao->recebeLance(new Lance($joao, 600));

    }

    /**
     * @dataProvider gerarLances
     */

    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ) {
        static::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $valor) {
            static::assertEquals($valor, $leilao->getLances()[$i]->getValor());
        }
    }

    public function gerarLances()
    {
        $jorge = new Usuario('Jorge');
        $luiza = new Usuario('Luiza');

        $leilaoCom2Lances = new Leilao('Uno 2012');
        $leilaoCom2Lances->recebeLance(new Lance($jorge, 2000));
        $leilaoCom2Lances->recebeLance(new Lance($luiza, 3000));

        $henrique = new Usuario('henrique');

        $leilao2Com1Lance = new Leilao('Tesla 21');
        $leilao2Com1Lance->recebeLance(new Lance($henrique, 2000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [2000, 3000]],
            '1-lance' => [1, $leilao2Com1Lance, [2000]]
        ];
    }

    public function gerarLancesRepetidosPorUsuario()
    {
        $ana = new Usuario('Ana');

        $leilao =  new Leilao('variante');
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        return [
            [1, $leilao, 1000]
        ];
    }
}
