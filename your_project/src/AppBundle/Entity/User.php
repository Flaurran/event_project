<?php
namespace AppBundle\Entity;
use AppBundle\EntityInterface\AuthorEntityInterface;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser implements AuthorEntityInterface
{
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $gender;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param $gender
     *
     * @return $this
     *
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return array
     */
    public static function getGenders()
    {
        return [
            'Homme' => self::GENDER_MALE,
            'Femme' => self::GENDER_FEMALE
        ];
    }

    public function getAuthorName()
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN');
    }
}