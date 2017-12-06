<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Resume;
use AppBundle\Form\ResumeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            ->get('doctrine.orm.entities')
            ->getRepository('AppBundle:Post')
            ->getAllPostsWithAuthors();

        return $this->render('AppBundle:App:liste.html.twig', ['list' => $list]);
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

    public function resumeAction(Request $request)
    {
        $form = $this->createForm(ResumeType::class, new Resume());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Yay! Congratulations, form is working!');
        } elseif ($form->isSubmitted() && ! $form->isValid()) {
            $this->addFlash('danger', 'Oh noes! Your form isn\'t working...');
        }

        return $this->render('@App/App/form.html.twig', ['form' => $form->createView()]);
    }
}
