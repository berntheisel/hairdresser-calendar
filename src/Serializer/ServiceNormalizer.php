<?php

namespace App\Serializer;

use App\Entity\Service;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ServiceNormalizer implements ContextAwareNormalizerInterface
{

    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Service $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $returnData = [];
        $returnData['type'] = 'service';
        $returnData['id'] = $object->getId();
        $returnData['attributes'] = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'avgDurationInMinutes' => $object->getAvgDurationInMinutes(),
            'price' => $object->getPrice(),
        ];
        $returnData['links'] = [
            'self' => $this->router->generate('readService', ['id' => $object->getId()]),
        ];

        #$this->createRelationLinks($object, $returnData);

        return $returnData;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Service;

    }
}