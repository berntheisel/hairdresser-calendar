<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customers', methods: ['GET'])]
    public function list(): Response
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();

        if (!$customers) {
            return $this->json(['success' => false], 404);
        }

        return $this->json($customers);
    }

    #[Route('/customer', name: 'customer')]
    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $customer = new Customer;

        $customer
            ->setFirstname($request->request->get('firstname'))
            ->setLastname($request->request->get('lastname'))
            ->setPhone($request->request->get('phone'));

        $errors = $validator->validate($customer);

        if (count($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();

            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($customer);
        $entityManager->flush();

        return $this->json($customer, 201);
    }
}
