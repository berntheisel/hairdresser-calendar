<?php

namespace App\Serializer;

use App\Entity\Booking;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class BookingNormalizer implements ContextAwareNormalizerInterface
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Booking $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $returnData = [];
        $returnData['type'] = 'booking';
        $returnData['id'] = $object->getId();
        $returnData['attributes'] = [
            'start' => $object->getStart(),
            'note' => $object->getNote(),
        ];
        $returnData['links'] = [
            'self' => $this->router->generate('readBooking', ['id' => $object->getId()]),
        ];

        $this->createRelationLinks($object, $returnData);

        return $returnData;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Booking;
    }

    protected function createRelationLinks(Booking $booking, array &$returnData) :void
    {
        if($booking->getCustomer()){
            $returnData['relationships'] = [
                'customer' => [
                    'links' => [
                        'related' => $this->router->generate('readCustomer', ['id' => $booking->getCustomer()->getId()])
                    ]
                ]
            ];
        }
    }
}