<?php
namespace AppBundle\Controller;


use AppBundle\Component\DependencyInjection\ApplicationAccessTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    use ApplicationAccessTrait;
}