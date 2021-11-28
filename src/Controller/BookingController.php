<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingController extends AbstractController
{
    #[Route('/bookings', name: 'bookings', methods: ['GET'])]
    public function listBookings(): Response
    {
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();

        if (!$bookings) {
            return $this->json([], 404);
        }

        return $this->json(['data' => $bookings,], 201);
    }

    #[Route('/booking', name: 'createBooking', methods: ['POST'])]
    public function createBooking(Request $request, ValidatorInterface $validator): Response
    {
        $booking = new Booking();

        $this->setDataToBooking($request->request->all(), $booking);

        $errors = $validator->validate($booking);

        if (count($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();

            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->json(['data' => $booking], 201);
    }

    #[Route('/booking/{id}', name: 'readBooking', methods: ['GET'])]
    public function readBooking(int $id, Request $request): Response
    {
        $booking = $this->getDoctrine()->getRepository(Booking::class)->find($id);

        if (!$booking) {
            return $this->json([], 400);
        }

        return $this->json(['data' => $booking], 201);
    }

    #[Route('/booking/{id}', name: 'updateBooking', methods: ['PUT'])]
    public function updateBooking(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $booking = $this->getDoctrine()->getRepository(Booking::class)->find($id);

        if (empty($booking)) {
            return $this->json([], 404);
        }

        //TODO Lade Temporär Customer
        $customerId = (int)$request->request->get('customer');
        if ($customerId) {
            $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

            if ($customer) {
                $request->request->set('customer', $customer);
            } else {
                $request->request->remove('customer');
            }
        }

        $this->setDataToBooking($request->request->all(), $booking);

        $errors = $validator->validate($booking);

        if (count($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();

            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->json(['data' => $booking], 201);
    }

    #[Route('/booking', name: 'deleteBooking', methods: ['DELETE'])]
    public function deleteBooking(): Response
    {
        return $this->json([
            'message' => 'delete',
            'path' => 'src/Controller/BookingController.php',
        ]);
    }

    //TODO REFACTOR
    private function setDataToBooking(array $requestData, Booking $booking)
    {
        foreach ($requestData as $key => $data) {
            $methodName = 'set' . ucfirst($key);
            if (!empty($data) && method_exists($booking, $methodName)) {
                $booking->{$methodName}($data);
            }
        }
    }

}
