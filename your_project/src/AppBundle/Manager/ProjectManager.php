<?php
namespace AppBundle\Manager;

use AppBundle\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProjectManager extends Manager {

    public function __construct(Registry $doctrine, TokenStorage $tokenStorage)
    {
        parent::__construct(Project::class, $doctrine, $tokenStorage);
    }

    public function create($entity)
    {
        if (! $entity instanceof Project) {
            $classname = get_class($entity);
            throw new \LogicException('Entity must be instance of ' . Project::class . ". $classname given.");
        }
        $user = $this->getUser();
        if (! $user) {
            throw new \LogicException('Need authentication to create Project');
        }

        $entity->setCreator($user);
        $this->save($entity);
    }
}