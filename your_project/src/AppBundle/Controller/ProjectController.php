<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return new Response('Not implement');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $project = $this->getProjectManager()->find($id);
        $form = $this->createForm(ProjectType::class, $project);

        return $this->render('AppBundle:project:edit.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ProjectType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Project $project */
                $project = $form->getData();
                $this->getProjectManager()->create($project);
                $this->addFlash('success', 'Projet créé avec succès !');
                return $this->redirectToRoute('project_edit', [
                    'id' => $project->getId()
                ]);
            }
        }

        return $this->render('AppBundle:project:create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
