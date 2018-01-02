<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ParticipantController extends BaseController {

    public function removeAction(Request $request, $id)
    {
        $participant = $this->getParticipantManager()->findParticipantById($id);
        $projectId = $participant->getProject()->getId();
        $isDeleted = $this->getParticipantManager()->remove($participant);

        if ($isDeleted) {
            $this->addFlash('success', 'Participant supprimé');
        }

        $referer = $request->server->get('HTTP_REFERER');

        if ($referer === $this->generateUrl('homepage')) {
            if ($this->getUser()) {
                $referer = $this->generateUrl('project_manage', ['id' => $projectId]);
            }
        }

        return $this->redirect($referer);
    }
}