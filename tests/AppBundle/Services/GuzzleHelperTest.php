<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 27/11/17
 * Time: 17:17
 */

namespace AppBundle\Tests\Services;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GuzzleHelperTest extends WebTestCase
{
    public function testCall()
    {
        // Création de l'object Mock
        // On créé un mock de l'objet dont on veut vérifier l'état.
        // En l'occurrence, on veut tester si la méthode GuzzleHelper::__call() fonctionne,
        // c'est à dire si elle appelle bien la méthode demandée dans GuzzleHttp\Client.
        // On mock donc GuzzleHttp\Client pour espionner les appels de fonction.
        $guzzleMock = $this->getMockBuilder(Client::class)
                           ->disableOriginalConstructor()
                           ->disableOriginalClone()
                           ->disallowMockingUnknownTypes()
                           ->setMethods(
                               ['get', 'getAsync', 'post', 'postAsync', 'put', 'putAsync', 'delete', 'deleteAsync']
                           )
                           ->getMock();
        $guzzleMock->expects($this->once())
                   ->method('get')
                   ->with('http://dummy.com/api');
        $guzzleMock->expects($this->once())
                   ->method('postAsync')
                   ->with(
                       $this->equalTo('http://dummy.com/api/post'),
                       $this->equalTo(['body' => 'dummy body'])
                   );

        $client = static::createClient();
        $helper = $client->getContainer()->get('app.services.guzzle_helper');
        $helper->setClient($guzzleMock);

        $helper->get('http://dummy.com/api');
        $helper->postAsync('http://dummy.com/api/post', ['body' => 'dummy body']);
    }

    public function testCallThrowsWithInvalidMethod()
    {
        // Ici on vérifie que la méthode __call lance bien une exception
        // lorsque la méthode demandée n'est pas disponible
        $this->expectException(\RuntimeException::class);

        $client = static::createClient();
        $helper = $client->getContainer()->get('app.services.guzzle_helper');
        $helper->trace('http://dummy.com/api');
    }

    public function testCallThrowsWithInvalidArguments()
    {
        // On vérifie aussi qu'une exception est lancée si les arguments ne sont pas valides
        $this->expectException(\InvalidArgumentException::class);

        $client = static::createClient();
        $helper = $client->getContainer()->get('app.services.guzzle_helper');
        $helper->get();
        $helper->get(['http://dummy.com']);
        $helper->get('http://dummy.com/api', 'foo');
        $helper->get('http://dummy.com/api', ['foo'], 'bar');
    }

    public function testGetClass()
    {
        $client = static::createClient();
        $helper = $client->getContainer()->get('app.services.guzzle_helper');

        $this->assertContains($helper->getClass(), 'AppBundle\Services\GuzzleHelper');
    }
}















