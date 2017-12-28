<?php
namespace AppBundle\Controller;


use AppBundle\Component\DependencyInjection\ApplicationAccessTrait;
use Fixtures\Bundles\XmlBundle\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Tests\TestBundle;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{
    use ApplicationAccessTrait;

    public function getForm($type, Request $request, $data = null, array $options = array())
    {
        $form = parent::createForm($type, $data, $options);
        $form->handleRequest($request);

        return $form;
    }

    public function createForm($type, $data = null, array $options = array())
    {
        throw new \RuntimeException('Not use this function, use BaseController::getForm instead');
    }
}