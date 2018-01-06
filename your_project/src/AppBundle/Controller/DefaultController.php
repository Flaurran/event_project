<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:default:homepage.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir'))
                .DIRECTORY_SEPARATOR,
        ]);
    }

    public function aboutAction(Request $request)
    {
        return $this->render('AppBundle:default:about.html.twig');
    }

    public function contactAction(Request $request)
    {
        return $this->render('AppBundle:default:contact.html.twig');
    }
}
