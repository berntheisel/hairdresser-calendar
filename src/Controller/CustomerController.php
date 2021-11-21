<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function add(Request $request): Response
    {
        $customer = new Customer;

        $customer
            ->setFirstname($request->request->get('firstname'))
            ->setLastname($request->request->get('lastname'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($customer);
        $entityManager->flush();

        if($customer->getId()){
            return $this->json($customer, 201);
        }

        return $this->json([], 400);
    }
}
