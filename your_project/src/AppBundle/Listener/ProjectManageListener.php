<?php
namespace AppBundle\Listener;

use AppBundle\Controller\ManageProjectController;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Exception\ProjectNotFoundException;
use AppBundle\Manager\ProjectManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 */
class ProjectManageListener
{
    protected $projectParamKey = 'id';

    /** @var  ProjectManager */
    protected $projectManager;

    /** @var  TokenStorage */
    protected $tokenStorage;

    public function __construct(ProjectManager $projectManager, TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->projectManager = $projectManager;
    }

    /**
     * @param FilterControllerEvent $event
     *
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerInformation = $event->getController();
        $controller = $controllerInformation[0];
        $params = $event->getRequest()->attributes->get('_route_params');

        if (is_array($controllerInformation)
            && isset($params[$this->projectParamKey])
            && (
                $controller instanceof ManageProjectController
            )
        ) {
            if (! $this->tokenStorage->getToken() || ! $this->tokenStorage->getToken()->getUser()) {
                throw new AccessDeniedException('Access denied');
            }
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            //TODO: setCurrentProject($project)
            /** @var Project $project */
            $project
                = $this->projectManager->find($params[$this->projectParamKey]);
            if (! $project) {
                throw new ProjectNotFoundException(
                    sprintf('Could not find project "%s"',
                        $params[$this->projectParamKey])
                );
            }

            if (! $user->hasRole('ROLE_ADMIN') && $project->getCreator()->getId() !== $user->getId()) {
                throw new AccessDeniedException('Access denied');
            }
        }
    }
}
