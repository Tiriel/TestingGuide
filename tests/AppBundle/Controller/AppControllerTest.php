<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    // Les tests de cette classe sont des tests fonctionnels: nous vérifions le fonctionnement complet
    // du workflow. En effet, les méthodes de controller eux-même contiennent *normalement* trop peu de logique
    // pour que les tester unitairement soit pertinent. Nous en profitons donc pour faire les tests d'intégration
    public function testIndexContainsHelloWorld()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }

    public function testQueryRespondsWithParameter()
    {
        $client = static::createClient();
        $client->request('GET', '/query/quux');

        $this->assertContains('Hello Quux', $client->getResponse()->getContent());
    }

    public function testQueryThrowsWhenNotCalledWithLetters()
    {
        $client = static::createClient();
        $client->request('GET', '/query/1234');

        $this->assertContains('ResourceNotFoundException', $client->getResponse()->getContent());
    }

    public function testListContainsTitles()
    {
        $client = static::createClient();
        $client->request('GET', '/list');

        $this->assertContains(
            'How long before you can make the jump to light speed?',
            $client->getResponse()->getContent()
        );
    }

    public function testColorContainsProperColor()
    {
        $client = static::createClient();
        $client->request('GET', '/color/10');

        $this->assertContains(
            'mimosa',
            $client->getResponse()->getContent()
        );
    }

    public function testFormIsDisplayed()
    {
        $client = static::createClient();
        $client->request('GET', '/form');

        $this->assertContains(
            'Just fill it.',
            $client->getResponse()->getContent()
        );
        $this->assertContains(
            '>Send</button>',
            $client->getResponse()->getContent()
        );
    }

    public function testFormReturnsErrorWithBadData()
    {
        $formDatas = [
            'appbundle_resume' => [
                'firstname' => 'John',
                'lastname'  => 'Doe',
                'age'       => 30,
                'symfony'   => false,
                'position'  => 'Symfony Developer',
                'comment'   => '',
                'test'      => '',
            ]
        ];
        $client = static::createClient();
        $client->request('POST', '/form', $formDatas);

        $this->assertContains(
            'Oh noes!',
            $client->getResponse()->getContent()
        );
    }

    public function testError404NotFoundOnRandomRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/foobar');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
