<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class CommentController extends BaseController
{
    public function removeAction(Request $request, $id)
    {
        $comment = $this->getCommentManager()->findCommentById($id);
        $projectId = $comment->getProject()->getId();
        $isDeleted = $this->getCommentManager()->remove($comment);

        if ($isDeleted) {
            $this->addFlash('success', 'Commentaire supprimé');
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
