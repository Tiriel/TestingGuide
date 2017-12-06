<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 28/11/17
 * Time: 17:36
 */

namespace AppBundle\Tests\Services;

use AppBundle\Services\GuzzleHelper;
use AppBundle\Services\ReqresCaller;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReqresCallerTest extends WebTestCase
{
    public function testCallResourcePrependsSlashIfOmmitted()
    {
        $guzStub = $this->getMockBuilder(GuzzleHelper::class)
                        ->setMethods(['__call'])
                        ->getMock();
        $guzStub->method('__call')
                ->with(
                    $this->equalTo('get'),
                    $this->equalTo(['https://reqres.in/api/users', []])
                )
                ->willReturn(true);

        $reqres = $this->getMockBuilder(ReqresCaller::class)
                       ->setConstructorArgs([$guzStub])
                       ->getMock();

        $this->assertEquals(
            true,
            $this->invokeMethod($reqres, 'callResource', ['get', 'users'])
        );
    }

    public function testCallResourceReturnsPromiseWhenCalledWithAsyncMethodName()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        try {
            $result = $this->invokeMethod($caller, 'callResource', ['get', '/users', [], true]);
        } catch (\Exception $e) {
        }

        // callResource return invokes handleResponse. If a value is returned, handleResponse was called
        $this->assertInstanceOf(PromiseInterface::class, $result);
    }

    public function testCallResourcePromiseIsRejectedWhenProblemWithQuery()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        // If rejected, promise will throw
        $this->expectException(RequestException::class);
        $result = $this->invokeMethod($caller, 'callResource', ['get', '/users/52', [], true]);
        $result->wait();
    }

    public function testCallResourcePromiseIsResolvedWhenQueryFound()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        $result = $this->invokeMethod($caller, 'callResource', ['get', '/users/5', [], true]);
        $result->then(
            function (ResponseInterface $res) {
                $this->assertInstanceOf(ResponseInterface::class, $res);
                $this->assertEquals(5, $res->getBody()->data->id);
            }
        );
        $result->wait();
    }

    public function testCallResourceReturnsResponseWhenCalledWithSyncMethod()
    {
        // Ici on cherche à tester unitairement une méthode privée.
        // Ce n'est pas une pratique idéale, mais elle peut se justifier dans le cas de méthodes
        // réellement critiques et centrales.
        // On commence pour cela par créer un mock du service GuzzleHelper, qui doit être injecté
        // dans ReqresCaller, afin de contrôler le retour de la méthode __call
        $guzStub = $this->createMock(GuzzleHelper::class);
        $guzStub->method('__call')
                ->with(
                    $this->equalTo('get'),
                    $this->equalTo(['https://reqres.in/api/users', []])
                )
                ->willReturn(new Response(200));

        // On mock ici ReqresCaller en un injectant notre mock GuzzleHelper
        $reqres = $this->getMockBuilder(ReqresCaller::class)
                       ->setConstructorArgs([$guzStub])
                       ->getMock();

        // On utilise ensuite la méthode invokeMethod (définie en bas de ce fichier) afin d'invoquer
        // la méthode privée callResource de manière unitaire.
        $result = $this->invokeMethod($reqres, 'callResource', ['get', '/users']);

        // On verifie enfin la conformité des résultats.
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    // Les tests suivants sont plus des tests d'intégration que des tests fonctionnels.
    // On teste en fait si la méthode callResource retransmet bien les exceptions lancées
    // par Guzzle, et les réponses de l'API distante.
    // Tests créés à titre d'exemple, perfectibles.

    public function testCallResourceThrowsWhenProblemWithQuery()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        // If statusCode >= 400, Guzzle will throw
        $this->expectException(RequestException::class);
        $this->invokeMethod($caller, 'callResource', ['get', '/users/52']);
    }

    public function testListResourceReturnsResponseContainingDataArray()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        $result = $this->invokeMethod($caller, 'listResource', ['users']);
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertThat(json_decode($result->getBody()->getContents())->data, $this->isType('array'));
    }

    public function testGetResourceReturnsResponseContainingDataObject()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        $result = $this->invokeMethod($caller, 'getResource', ['users', 5]);
        $decRes = json_decode($result->getBody()->getContents());
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertThat($decRes->data, $this->isType('object'));
        $this->assertEquals(5, $decRes->data->id);
    }

    public function testPostResourceReturnsResponseWithStatus201IfOk()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        $result = $this->invokeMethod($caller, 'postResource', ['users', ['name' => 'john doe', 'job' => 'none']]);
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testPutResourceReturnsResponseWithStatus200IfOk()
    {
        $client = static::createClient();
        $caller = $client->getContainer()->get('app.services.reqres_caller');

        $result = $this->invokeMethod($caller, 'putResource', ['users', 2, ['name' => 'john doe', 'job' => 'none']]);
        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function invokeMethod($object, $method, array $params = [])
    {
        // Cette méthode sert à invoquer des méthodes privées comme si elles étaient publiques.
        // ATTENTION, ceci est une pratique qu'il convient d'éviter au maximum. Les méthodes privées
        // sont normalement faites pour le rester. Il vaut mieux les tester indirectement grâce aux méthodes
        // publiques qui les appellent.
        // "Don't expose your privates"
        $ref = new \ReflectionClass(get_class($object));
        $met = $ref->getMethod($method);
        $met->setAccessible(true);

        return $met->invokeArgs($object, $params);
    }
}
