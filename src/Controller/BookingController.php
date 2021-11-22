<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingController extends AbstractController
{
    #[Route('/bookings', name: 'bookings', methods: ['GET'])]
    public function list(): Response
    {
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();

        if (!$bookings) {
            return $this->json(['success' => false], 404);
        }

        return $this->json(['data' => $bookings], 201);
    }

    #[Route('/booking', name: 'addBooking', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): Response
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

    #[Route('/booking/{id}', name: 'updateBooking', methods: ['PUT'])]
    public function update(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $booking = $this->getDoctrine()->getRepository(Booking::class)->find($id);

        if (empty($booking)) {
            return $this->json([], 404);
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
    public function delete(): Response
    {
        return $this->json([
            'message' => 'delete',
            'path' => 'src/Controller/BookingController.php',
        ]);
    }

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
