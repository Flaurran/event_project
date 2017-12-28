<?php
namespace AppBundle\Component\DependencyInjection;

trait ApplicationAccessTrait
{
    /**
     * @return \AppBundle\Manager\ProjectManager
     */
    public function getProjectManager()
    {
        return $this->get('app.manager.project');
    }

    /**
     * @return \AppBundle\Manager\ParticipantManager
     */
    public function getParticipantManager()
    {
        return $this->get('app.manager.participant');
    }

    /**
     * @return \AppBundle\Manager\CommentManager
     */
    public function getCommentManager()
    {
        return $this->get('app.manager.comment');
    }
}