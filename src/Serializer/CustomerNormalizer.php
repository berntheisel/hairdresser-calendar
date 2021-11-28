<?php

namespace App\Serializer;

use App\Entity\Customer;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class CustomerNormalizer implements ContextAwareNormalizerInterface
{

    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Customer $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $returnData = [];
        $returnData['type'] = 'customer';
        $returnData['id'] = $object->getId();
        $returnData['attributes'] = [
            'id' => $object->getId(),
            'firstname' => $object->getFirstname(),
            'lastname' => $object->getLastname(),
            'mobile' => $object->getMobile(),
            'phone' => $object->getPhone(),
            'email' => $object->getEmail(),
            'birthday' => $object->getBirthday(),
        ];
        $returnData['links'] = [
            'self' => $this->router->generate('readCustomer', ['id' => $object->getId()]),
        ];

        #$this->createRelationLinks($object, $returnData);

        return $returnData;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Customer;

    }
}