<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProjectManager extends Manager
{

    public function __construct(Registry $doctrine, TokenStorage $tokenStorage, $className)
    {
        parent::__construct($doctrine, $tokenStorage, $className);
    }

    /**
     * @param object $entity
     * @param bool   $save
     * @param bool   $flush
     * @param array  $args
     *
     * @return null
     */
    public function create($entity = null, $save = false, $flush = true, array $args = [])
    {
        if (is_null($entity)) {
            $entity = parent::create();
        } else {
            $this->validateCreateEntity($entity);
        }
        $user = $this->getUser();
        if (!$user) {
            throw new \LogicException('Need authentication to create Project');
        }

        $entity->setCreator($user);
        $this->canBeSave($entity, $save, $flush);

        return $entity;
    }

    /**
     * @param User $user
     *
     * @return Project[]
     */
    public function findByUser(User $user)
    {
        $orderBy = [
            'date' => 'DESC'
        ];
        return $user->hasRole('ROLE_ADMIN') ? $this->findAll($orderBy) : $this->findBy([
            'creator' => $user
        ], $orderBy);
    }
}