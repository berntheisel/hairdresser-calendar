<?php

namespace App\Controller;

use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/config')]
class ConfigController extends AbstractController
{
    #[Route('/', name: 'config_index', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConfigRepository $configRepository, EntityManagerInterface $entityManager): Response
    {
        $config = $configRepository->find(1);
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('config/edit.html.twig', [
            'config' => $config,
            'form' => $form,
        ]);
    }
}
