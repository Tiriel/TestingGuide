<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 04/12/17
 * Time: 12:15
 */

namespace AppBundle\Tests\Services;

use AppBundle\Services\ResponseFormatter;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResponseFormatterTest extends WebTestCase
{
    public function testGetColor()
    {
        // Ce test peut être amélioré pour devenir réellement unitaire
        // TODO: ré écrire ce test
        $formatter = $this->createClient()->getContainer()->get('app.services.response_formatter');
        $this->assertInstanceOf(Response::class, $formatter->getColor(10));
    }

    public function testFormatColor()
    {
        // Ici on va tester le retour de la méthode formatColor de la classe AppBundle\Services\ResponseFormatter.
        // On veut la tester unitairement, mais cette méthode appelle la méthode getColor de la même classe.
        // On va donc mocker la méthode getColor afin de contrôler ce qu'elle renvoie, pour ne tester que la mécanique
        // interne de formatColor

        // On commence par créer l'objet std qui correspond au retour attendu
        $datas                = new \stdClass();
        $datas->id            = 10;
        $datas->name          = 'mimosa';
        $datas->year          = 2009;
        $datas->color         = '#F0C05A';
        $datas->pantone_value = '14-0848';

        // On l'inclue dans un objet Response tel que renvoyé habituellement par la méthode getColor
        $streamed = json_encode(['data' => $datas]);
        $get      = new Response(200, [], new Stream(fopen("data://text/plain,{$streamed}", 'r')));
        // On créé un mock de la classe ResponseFormatter, pour surcharger la méthode getColor
        $formatter = $this->getMockBuilder(ResponseFormatter::class)
                          ->disableOriginalConstructor()
                          ->setMethods(['getColor'])
                          ->getMock();
        // On mock enfin la méthode getColor pour contrôler ses arguments et son retour
        $formatter->expects($this->once())
                  ->method('getColor')
                  ->with($this->equalTo(10))
                  ->willReturn($get);

        // On peut maintenant tester que la méthode formatColor retourne bien les données attendues
        $this->assertEquals($streamed, $formatter->formatColor(10));
    }

    public function testFormatColorWithString()
    {
        $datas                = new \stdClass();
        $datas->id            = 10;
        $datas->name          = 'mimosa';
        $datas->year          = 2009;
        $datas->color         = '#F0C05A';
        $datas->pantone_value = '14-0848';

        $streamed = json_encode(['data' => $datas]);
        $get      = new Response(200, [], new Stream(fopen("data://text/plain,{$streamed}", 'r')));
        $formatter = $this->getMockBuilder(ResponseFormatter::class)
                          ->disableOriginalConstructor()
                          ->setMethods(['getColor'])
                          ->getMock();
        $formatter->expects($this->once())
                  ->method('getColor')
                  ->with($this->equalTo(10))
                  ->willReturn($get);

        $this->assertEquals($datas, $formatter->formatColor('mimosa'));
    }
}
