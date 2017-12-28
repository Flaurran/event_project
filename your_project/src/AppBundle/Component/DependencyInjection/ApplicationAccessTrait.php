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
}