<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customers')]
    public function list(): Response
    {
        $data = [
            'customer' => $this->createCustomer(),
        ];

        return $this->json($data);
    }

    #[Route('/customers', name: 'create')]
    public function createCustomer(): array
    {
        $return = [];

        $customer = new Customer;

        $customer
            ->setFirstname('Max')
            ->setLastname('Mustermann');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($customer);
        $entityManager->flush();

        return [];
    }
}
