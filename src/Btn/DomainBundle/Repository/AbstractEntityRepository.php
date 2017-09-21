<?php

namespace Btn\DomainBundle\Repository;

use Btn\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Id\UuidGenerator;
use Btn\Domain\ValueObject\Id;

abstract class AbstractEntityRepository extends EntityRepository
{
    private $idGenerator;

    /**
     * {@inheritdoc}
     */
    public function byId(Id $id)
    {
        $entity = $this->find($id->value());
        if (!$entity) {
            throw EntityNotFoundException::byId($id);
        }

        $this->validateEntityClass($entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function removeById(Id $id)
    {
        $this->doRemove($this->byId($id));
    }

    /**
     * @return string
     */
    public function generateId()
    {
        if (!$this->idGenerator) {
            $this->idGenerator = new UuidGenerator();
        }

        return Id::create($this->idGenerator->generate($this->getEntityManager(), null));
    }

    /**
     * @param $entity
     */
    protected function doSave($entity)
    {
        $this->validateEntityClass($entity);

        $em = $this->getEntityManager();

        $em->persist($entity);
        $em->flush();
    }

    /**
     * @param $entity
     */
    protected function doRemove($entity)
    {
        $this->validateEntityClass($entity);

        $em = $this->getEntityManager();

        $em->remove($entity);
        $em->flush();
    }

    /**
     * @param $entity
     *
     * @throws \Exception
     */
    abstract protected function validateEntityClass($entity);
}
