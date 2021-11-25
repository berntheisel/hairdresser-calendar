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
            return $this->json([], 404);
        }

        return $this->json(['data' => $customers], 201);
    }

    #[Route('/customer', name: 'createCustomer', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $customer = new Customer;

        $this->setDataToCustomer($request->request->all(), $customer);

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

        return $this->json(['data' => $customer], 201);
    }

    #[Route('/customer/{id}', name: 'readCustomer', methods: ['GET'])]
    public function read(int $id): Response
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->json([], 404);
        }

        return $this->json(['data' => $customer], 201);
    }

    #[Route('/customer/{id}', name: 'updateCustomer', methods: ['PUT'])]
    public function update(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);

        if (empty($customer)) {
            return $this->json([], 404);
        }

        $this->setDataToCustomer($request->request->all(), $customer);

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
        $entityManager->flush();

        return $this->json(['data' => $customer], 201);
    }

    #[Route('/customer', name: 'deleteCustomer', methods: ['DELETE'])]
    public function delete(): Response
    {
        return $this->json([
            'message' => 'delete',
            'path' => 'src/Controller/CustomerController.php',
        ]);
    }

    private function setDataToCustomer(array $requestData, Customer $customer)
    {
        foreach ($requestData as $key => $data) {
            $methodName = 'set' . ucfirst($key);
            if (!empty($data) && method_exists($customer, $methodName)) {
                $customer->{$methodName}($data);
            }
        }
    }
}
