<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:App:index.html.twig');
    }

    public function queryAction($name)
    {
        return $this->render('AppBundle:App:query.html.twig', ['name' => ucfirst($name)]);
    }

    public function listPostsAction()
    {
        $list = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->getAllPostsWithAuthors();

        return $this->render('AppBundle:App:list.html.twig', ['list' => $list]);
    }

    public function colorAction($id)
    {
        $formatter = $this->get('AppBundle\Services\ResponseFormatter');
        try {
            $color = $formatter->formatColor($id);
        } catch (\Exception $e) {
            throw $this->createNotFoundException(
                "Error : the color {$id} does not exist. (Full error: {$e->getMessage()}"
            );
        }

        return $this->render('AppBundle:App:color.html.twig', ['color' => $color]);
    }
}
