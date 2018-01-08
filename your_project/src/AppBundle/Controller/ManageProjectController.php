<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Project;
use AppBundle\Form\CommentType;
use AppBundle\Form\ParticipantType;
use AppBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ManageProjectController extends ProjectController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function manageAction(Request $request, $id)
    {
        /** @var Project $project */
        $project = $this->getProjectManager()->find($id);
        $form = $this->getForm(ProjectType::class, $request, $project);
        $commentForm = $this->getForm(CommentType::class, $request, null);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $project = $form->getData();
                $this->getProjectManager()->save($project, true);
                $this->addFlash('success', 'Projet enregistré avec succès !');
            }
        }

        if ($commentForm->isSubmitted()) {
            if ($commentForm->isValid()) {
                /** @var Comment $comment */
                $comment = $commentForm->getData();
                $this->getCommentManager()->create($comment, true, true, $this->getCommentManager()->createArgs($this->getUser(), $project));
                $this->addFlash('success', 'Commentaire ajouté avec succès !');
                return $this->redirectToRoute('project_manage', [
                    'id' => $project->getId()
                ]);
            }
        }

        return $this->render('AppBundle:project:manage.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function updateAjaxAction(Request $request, $id)
    {
        try {
            $project = $this->getProjectManager()->find($id);
            $field = $request->request->get('name');
            $value = $request->request->get('value');
            $project->{'set' . ucfirst($field)}($value);
            $this->getProjectManager()->save($project);
        } catch(\Exception $e) {
            return new JsonResponse('Error occurred. ' . $e->getMessage());
        }
        return new JsonResponse([
            'url' => $this->generateUrl('project_manage', ['id' => $id])
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm(ProjectType::class, $request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Project $project */
                $project = $form->getData();
                $this->getProjectManager()->create($project, true);
                $this->addFlash('success', 'Projet créé avec succès !');

                return $this->redirectToRoute('project_manage', [
                    'id' => $project->getId(),
                ]);
            }
        }

        return $this->render('AppBundle:project:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function addParticipantAction(Request $request, $id)
    {
        /** @var Project $project */
        $project = $this->getProjectManager()->find($id);
        //Check if exist on ProjectManageListener

        $form = $this->getForm(ParticipantType::class, $request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Participant $participant */
                $participant = $form->getData();
                $userByEmail = $this->getUserManager()->findOneByEmail($participant->getMail());
                if ($project->getCreator()->getEmail() == $participant->getMail()) {
                    $this->addFlash('error', 'Vous ne pouvez pas vous inviter en tant que participant si vous êtes le créateur...');
                    return $this->redirectToRoute('project_add_participant', ['id' => $id]);
                } else if (is_null($userByEmail) && is_null($participant->getFirstname())) {
                    $this->addFlash('error', "L'email ne correspondant pas à un email d'utilisateur, le prénom est obligatoire");
                    return $this->redirectToRoute('project_add_participant', ['id' => $id]);
                }
                $this->getParticipantManager()->create($participant, true, true, ['project' => $project]);
                $this->addFlash('success', 'Participant ajouté avec succès');
                return $this->redirectToRoute('project_manage', ['id' => $id]);
            }
        }

        return $this->render('AppBundle:project:add_participant.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request, $id)
    {
        /** @var Project $project */
        $project = $this->getProjectManager()->find($id);

        $this->getProjectManager()->remove($project);

        $this->addFlash('success','Projet supprimé avec succès !');

        return $this->redirectToRoute('project_index');
    }
}