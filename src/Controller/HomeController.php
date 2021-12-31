<?php

namespace App\Controller;


use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(EmployeeRepository $employeeRepository, ServiceRepository $serviceRepository, ConfigRepository $configRepository): Response
    {
        $config = $configRepository->find(1);
        $employees = $employeeRepository->getAllBookingsForEmployees($config->getDayStartTime(), $config->getDayEndTime(), new \DateTime());
        $times = $serviceRepository->getAllServiceTimes($config->getDayStartTime(), $config->getDayEndTime());

        return $this->render('home/index.html.twig', [
            'employees' => $employees,
            'times' => $times
        ]);

    }
}
