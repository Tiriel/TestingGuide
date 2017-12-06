<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 27/11/17
 * Time: 15:31
 */

namespace AppBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostRepositoryTest extends WebTestCase
{
    public function testGetAllPostsWithAuthors()
    {
        // Pour ce test, des fixtures (données de test) sont nécessaires.
        // Normalement, elles sont automatiquement installées grâce aux scripts composer
        // (voir composer.json)
        $client = static::createClient();
        $repo = $client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Post');
        $list = $repo->getAllPostsWithAuthors();

        // On teste simplement que les données en base (fixtures) correspondent à ce qui est attendu
        $this->assertEquals('Sir, if you\'ll not be needing me, I\'ll close down for awhile.', $list[1]->getTitle());
        $this->assertEquals('waldothud@plugh.com', $list[2]->getAuthor()->getEmail());
    }
}