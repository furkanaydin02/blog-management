<?php

namespace App\Traits\Service;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerServiceTrait
{
    /** @var EntityManagerInterface $_entityManager */
    protected $_entityManager;

    /**
     * @Required
     */
    public function setEntityManager(EntityManagerInterface $_entityManager): self
    {
        $this->_entityManager = $_entityManager;

        return $this;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->_entityManager;
    }

    public function save($entity): void
    {
        $this->_entityManager->persist($entity);
        $this->_entityManager->flush();
    }
}
