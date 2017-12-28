<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $projects = $this->getProjectManager()->findByUser($this->getUser());

        return $this->render('@App/project/list.html.twig', [
            'projects' => $projects
        ]);
    }
}
