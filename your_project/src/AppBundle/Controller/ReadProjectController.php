<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Project;
use AppBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ReadProjectController extends ProjectController
{
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
        if (! in_array($choice, array_keys(Participant::$statusLabel))) {
            throw new \InvalidArgumentException('Not valid choice value');
        }

        $participant = $this->getParticipant($slug);
        switch ($choice) {
            case Participant::STATUS_REFUSED:
                $this->getParticipantManager()->decline($participant);
                break;
            case Participant::STATUS_ACCEPTED:
                $this->getParticipantManager()->accepte($participant);
                break;
            case Participant::STATUS_MAYBE:
                $this->getParticipantManager()->maybe($participant);
                break;
            default:
                throw new \InvalidArgumentException('Choice value no more exist');
        }

        return $this->redirectToRoute('project_participate', ['slug' => $slug]);
    }

    /**
     * @param Request $request
     * @param         $slug
     * @param         $commentId
     *
     * @return RedirectResponses
     */
    public function participantRemoveCommentAction (Request $request, $slug, $commentId)
    {
        $participant = $this->getParticipant($slug);
        $comment = $this->getCommentManager()->findCommentById($commentId);

        if (! $comment->authorIsParticipant() || $comment->getAuthor() != $participant->getId()) {
            throw new AccessDeniedException('Cannot remove a comment write by another user');
        }

        $this->getCommentManager()->remove($comment);

        return $this->redirectToRoute('project_participate', ['slug' => $participant->getSlug()]);
    }

    /**
     * @param $slug
     *
     * @return \AppBundle\Entity\Participant|null
     * @deprecated use ParticipantManager::getParticipant($slug) instead
     */
    private function getParticipant($slug)
    {
        return $this->getParticipantManager()->getParticipant($slug);
    }
}
