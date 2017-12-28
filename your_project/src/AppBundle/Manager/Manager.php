<?php
namespace AppBundle\Manager;


use AppBundle\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

abstract class Manager
{
    protected $className;

    /** @var Registry */
    protected $doctrine;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(Registry $doctrine, TokenStorage $tokenStorage, $className = null)
    {
        $this->className = $className;
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param object|null   $entity
     * @param boolean       $save
     * @param boolean       $flush
     * @param array         $args
     *
     * @return mixed
     */
    public function create($entity = null, $save = false, $flush = true, array $args = [])
    {
        if ($entity) {
            $this->canBeSave($entity, $save, $flush);
            return $entity;
        }

        $entity = new $this->className();
        $this->canBeSave($entity, $save, $flush);

        return $entity;
    }

    /**
     * @param object $entity
     * @param bool $save
     * @param bool $flush
     */
    protected function canBeSave($entity, $save, $flush)
    {
        if ($save) {
            $this->save($entity, $flush);
        }
    }
    /**
     * @param      $entity
     * @param bool $flush
     */
    public function save($entity, $flush = true)
    {
        if (! is_object($entity)) {
            throw new \LogicException('Cannot save an non object entity');
        }
        $this->validateCreateEntity($entity);
        $this->doctrine->getManager()->persist($entity);

        if ($flush) {
            $this->doctrine->getManager()->flush();
        }
    }

    /**
     * @param $entity
     */
    public function validateCreateEntity($entity)
    {
        if (! $entity instanceof $this->className) {
            $className = get_class($entity);
            throw new \LogicException("Cannot save an other object than {$this->className}. \"$className\" given");
        }
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param array $args
     *
     * @return object
     */
    public function findOneBy(array $args)
    {
        return $this->getRepository()->findOneBy($args);
    }

    /**
     * @param array $orderBy
     * @return array
     */
    protected function findAll(array $orderBy = [])
    {
        return $this->getRepository()->findAllOrderBy($orderBy);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return array
     */
    protected function findBy(array $criteria, array $orderBy = [])
    {
        return $this->getRepository()->findBy($criteria, $orderBy);
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

    /**
     * @return BaseRepository
     */
    protected function getRepository()
    {
        return $this->doctrine->getRepository($this->className);
    }
    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    static public function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}