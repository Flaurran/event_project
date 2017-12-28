<?php
namespace AppBundle\EntityInterface;

interface AuthorInterface
{
    const AUTHOR_TYPE_USER = 0;
    const AUTHOR_TYPE_PARTICIPANT = 1;

    public function getAuthor();

    public function setAuthor($author);

    public function getAuthorType();

    public function setAuthorType($authorType);
}