<?php

namespace App\Controller;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'services', methods: ['GET'])]
    public function listServices(): Response
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();

        if (!$services) {
            return $this->json([], 404);
        }

        return $this->json(['data' => $services], 201);
    }

    #[Route('/service', name: 'createService', methods: ['POST'])]
    public function createService(Request $request, ValidatorInterface $validator): Response
    {
        $service = new Service();

        $this->setDataToService($request->request->all(), $service);

        $errors = $validator->validate($service);

        if (count($errors) > 0) {
            $errorMessages = [];
            /** @var ConstraintViolation $violation */
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($service);
        $entityManager->flush();

        return $this->json(['data' => $service], 201);
    }

    #[Route('/service/{id}', name: 'readService', methods: ['GET'])]
    public function readService(int $id): Response
    {
        $service = $this->getDoctrine()->getRepository(Service::class)->find($id);

        if (!$service) {
            return $this->json([], 404);
        }

        return $this->json(['data' => $service], 201);
    }

    #[Route('/service/{id}', name: 'updateService', methods: ['PUT'])]
    public function updateService(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $service = $this->getDoctrine()->getRepository(Service::class)->find($id);

        if (empty($service)) {
            return $this->json([], 404);
        }

        $this->setDataToService($request->request->all(), $service);

        $errors = $validator->validate($service);

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

        return $this->json(['data' => $service], 201);
    }

    #[Route('/service', name: 'deleteService', methods: ['DELETE'])]
    public function deleteService(): Response
    {
        return $this->json([
            'message' => 'delete',
            'path' => 'src/Controller/ServiceController.php',
        ]);
    }

    //TODO REFACTOR
    private function setDataToService(array $requestData, Service $service)
    {
        foreach ($requestData as $key => $data) {
            $methodName = 'set' . ucfirst($key);
            if (!empty($data) && method_exists($service, $methodName)) {
                $service->{$methodName}($data);
            }
        }
    }

}
