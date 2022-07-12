<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal;

use Mautic\IntegrationsBundle\Entity\ObjectMapping;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\UpdatedObjectMappingDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Order\ObjectChangeDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\Internal\ObjectHelper\ObjectHelperInterface;
use MauticPlugin\MauticEcommerceBundle\Entity\Product as ProductEntity;
use MauticPlugin\MauticEcommerceBundle\Entity\ProductRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ProductObjectHelper implements ObjectHelperInterface
{
    private ProductRepository $productRepository;
    private PropertyAccessor $propertyAccessor;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param ObjectChangeDAO[] $objects
     *
     * @return ObjectMapping[]
     */
    public function create(array $objects): array
    {
        $objectMappings = [];

        foreach ($objects as $object) {
            $product = new ProductEntity();

            foreach ($object->getFields() as $field) {
                if (!$this->propertyAccessor->isWritable($product, $field->getName())) {
                    continue;
                }

                $normalizedValue = $field->getValue()->getNormalizedValue();
                if ($field->getValue()->getType() === 'datetime') {
                    $normalizedValue = new \DateTimeImmutable($normalizedValue);
                }

                $this->propertyAccessor->setValue($product, $field->getName(), $normalizedValue);
            }

            $this->productRepository->saveEntity($product);
            $this->productRepository->detachEntity($product);

            $objectMapping = new ObjectMapping();
            $objectMapping->setLastSyncDate($object->getChangeDateTime())
                ->setIntegration($object->getIntegration())
                ->setIntegrationObjectName($object->getMappedObject())
                ->setIntegrationObjectId($object->getMappedObjectId())
                ->setInternalObjectName(Product::NAME)
                ->setInternalObjectId($product->getId());
            $objectMappings[] = $objectMapping;
        }

        return $objectMappings;
    }

    /**
     * @param ObjectChangeDAO[] $objects
     *
     * @return UpdatedObjectMappingDAO[]
     */
    public function update(array $ids, array $objects): array
    {
        $updatedMappedObjects = [];

        /** @var ProductEntity[] $companies */
        $products = $this->productRepository->getEntities(['ids' => $ids]);

        foreach ($products as $product) {
            $changedObject = $objects[$product->getId()];
            $fields = $changedObject->getFields();

            foreach ($fields as $field) {
                if (!$this->propertyAccessor->isWritable($product, $field->getName())) {
                    continue;
                }

                $normalizedValue = $field->getValue()->getNormalizedValue();
                if ($field->getValue()->getType() === 'datetime') {
                    $normalizedValue = new \DateTimeImmutable($normalizedValue);
                }

                $this->propertyAccessor->setValue($product, $field->getName(), $normalizedValue);
            }

            // Integration name and ID are stored in the change's mappedObject/mappedObjectId
            $updatedMappedObjects[] = new UpdatedObjectMappingDAO(
                $changedObject->getIntegration(),
                $changedObject->getMappedObject(),
                $changedObject->getMappedObjectId(),
                $changedObject->getChangeDateTime()
            );
        }

        return $updatedMappedObjects;
    }

    public function findObjectsBetweenDates(\DateTimeInterface $from, \DateTimeInterface $to, $start, $limit): array
    {
        throw new \RuntimeException('Partial Syncing is not supported yet.');
    }

    public function findObjectsByIds(array $ids): array
    {
        throw new \RuntimeException('Partial Syncing is not supported yet.');
    }

    public function findObjectsByFieldValues(array $fields): array
    {
        throw new \RuntimeException('Partial Syncing is not supported yet.');
    }
}
