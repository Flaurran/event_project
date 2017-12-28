<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Exception\ParticipantNotFoundException;
use AppBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReadProjectController extends ProjectController
{
    const CHOICE_DECLINE = 0;
    const CHOICE_ACCEPT = 1;

    public function readAction(Request $request, $id)
    {
        /** @var Project $project */
        $project = $this->getProjectManager()->find($id);

        return $this->render('@App/project/read.html.twig', [
            'project' => $project
        ]);
    }

    public function participateAction(Request $request, $slug)
    {
        $participant = $this->getParticipant($slug);
        $project = $participant->getProject();
        $commentForm = $this->getForm(CommentType::class, $request);
        if ($commentForm->isSubmitted()) {
            if ($commentForm->isValid()) {
                /** @var Comment $comment */
                $comment = $commentForm->getData();
                $this->getCommentManager()->create($comment, true, true, $this->getCommentManager()->createArgs($participant, $project));
                $this->addFlash('success', 'Commentaire ajouté avec succès !');
                return $this->redirectToRoute('project_participate', [
                    'slug' => $slug
                ]);
            }
        }
        //TODO: BAD, if 200 comments, load 200 entity with 200 requests
        $this->getCommentManager()->calculate($project->getComments());

        return $this->render('@App/project/participate.html.twig', [
            'project' => $project,
            'participant' => $participant,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param         $slug
     * @param         $choice
     *
     * @return RedirectResponse
     */
    public function choiceAction (Request $request, $slug, $choice)
    {
        if (! in_array($choice, [self::CHOICE_DECLINE, self::CHOICE_ACCEPT])) {
            throw new \InvalidArgumentException('Not valid choice value');
        }

        $participant = $this->getParticipant($slug);
        switch ($choice) {
            case self::CHOICE_DECLINE:
                $this->getParticipantManager()->decline($participant);
                break;
            case self::CHOICE_ACCEPT:
                $this->getParticipantManager()->accepte($participant);
                break;
            default:
                throw new \InvalidArgumentException('Choice value no more exist');
        }

        return $this->redirectToRoute('project_participate', ['slug' => $slug]);
    }

    /**
     * @param $slug
     *
     * @return \AppBundle\Entity\Participant|null
     */
    private function getParticipant($slug)
    {
        $participant = $this->getParticipantManager()->findOneBySlug($slug);
        if (! $participant) {
            throw new ParticipantNotFoundException("Participant with @slug=$slug not found");
        }
        return $participant;
    }
}
