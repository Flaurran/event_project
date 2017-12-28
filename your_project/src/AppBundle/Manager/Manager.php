<?php
namespace AppBundle\Manager;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

abstract class Manager
{
    protected $className;

    /** @var Registry */
    protected $doctrine;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct($className, Registry $doctrine, TokenStorage $tokenStorage)
    {
        $this->className = $className;
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public abstract function create($entity);

    /**
     * @param      $entity
     * @param bool $flush
     */
    public function save($entity, $flush = true)
    {
        if (! is_object($entity)) {
            throw new \LogicException('Cannot save an non object entity');
        }
        if (! $entity instanceof $this->className) {
            $className = get_class($entity);
            throw new \LogicException("Cannot save an other object than {$this->className}. \"$className\" given");
        }
        $this->doctrine->getManager()->persist($entity);

        if ($flush) {
            $this->doctrine->getManager()->flush();
        }
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function find($id)
    {
        return $this->doctrine->getRepository($this->className)->find($id);
    }

    /* HELPER */
    /**
     * @return mixed
     */
    protected function getUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $user;
    }
}