<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingsServices;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\BookingsServicesRepository;
use App\Repository\ConfigRepository;
use App\Repository\CustomerRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ServiceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/booking')]
class BookingController extends AbstractController
{

    #[Route('/ajax-add-service-row', name: 'ajax_add_service_row', methods: ['POST'])]
    public function ajaxAddServiceRow(Request $request, ServiceRepository $serviceRepository, EmployeeRepository $employeeRepository)
    {
        $serviceCounter = $request->request->get('serviceCounter');
        $services = $serviceRepository->findAll();
        $employees = $employeeRepository->findAll();

        return $this->renderForm('booking/ajax_add_service_row.html.twig', [
            'services' => $services,
            'employees' => $employees,
            'serviceCounter' => $serviceCounter
        ]);
    }

    #[Route('/{id}/ajax-load-service-rows', name: 'ajax_load_service_rows', methods: ['POST'])]
    public function ajaxLoadServiceRows(
        $id,
        BookingsServicesRepository $bookingsServicesRepository,
        ServiceRepository $serviceRepository,
        EmployeeRepository $employeeRepository
    )
    {
        $serviceRows = $bookingsServicesRepository->findBy(['booking' => $id]);
        $services = $serviceRepository->findAll();
        $employees = $employeeRepository->findAll();

        return $this->renderForm('booking/ajax_load_service_rows.html.twig', [
            'serviceRows' => $serviceRows,
            'services' => $services,
            'employees' => $employees,
            'service_counter' => count($serviceRows)
        ]);
    }


    #[Route('/', name: 'booking_index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'booking_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        CustomerRepository     $customerRepository,
        ServiceRepository      $serviceRepository,
        EmployeeRepository     $employeeRepository,
        ConfigRepository       $configRepository
    ): Response
    {
        $booking = new Booking();

        $services = $serviceRepository->findAll();
        $customers = $customerRepository->findAll();
        $config = $configRepository->find(1);

        if ($request->request->count() && $request->request->get('booking')['add_booking'] === "1") {

            $bookingData = $request->request->get('booking');
            $servicesDataRows = $request->request->get('serviceRow');
            $customer = $customerRepository->find($bookingData['customer']);

            // Save Booking
            $booking->setCustomer($customer);
            $booking->setStart((new DateTime())->setTimestamp(strtotime($bookingData['start'])));
            $booking->setNote($bookingData['note']);
            $entityManager->persist($booking);
            $entityManager->flush();

            // Save Services
            $sort = 1;
            foreach ($servicesDataRows as $servicesDataRow) {
                $bookingsServices = new BookingsServices();

                $employee = $employeeRepository->find((int)$servicesDataRow['employee']);
                $service = $serviceRepository->find((int)$servicesDataRow['service']);

                $bookingsServices->setBooking($booking);
                $bookingsServices->setService($service);
                $bookingsServices->setEmployee($employee);
                $bookingsServices->setDurationInMinutes((int)$servicesDataRow['durationInMinutes']);
                $bookingsServices->setSort($sort);
                $sort++;

                $entityManager->persist($bookingsServices);
                $entityManager->flush();
            }
            return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/new.html.twig', [
            'config' => $config,
            'booking' => $booking,
            'customers' => $customers,
            'services' => $services,
        ]);
    }

    #[Route('/{id}/edit', name: 'booking_edit', methods: ['GET', 'POST'])]
    public function edit(
        $id,
        Request $request,
        EntityManagerInterface $entityManager,
        BookingRepository $bookingRepository,
        ServiceRepository $serviceRepository,
        EmployeeRepository $employeeRepository,
        BookingsServicesRepository $bookingsServicesRepository,
        ConfigRepository $configRepository
    ): Response
    {
        $booking = $bookingRepository->find($id);
        $services = $serviceRepository->findAll();
        $config = $configRepository->find(1);

        if ($request->request->count() && $request->request->get('booking')['edit_booking'] === "1") {
            $bookingData = $request->request->get('booking');
            $servicesDataRows = $request->request->get('serviceRow');

            // Save Booking
            $booking->setStart((new DateTime())->setTimestamp(strtotime($bookingData['start'])));
            $booking->setNote($bookingData['note']);
            $entityManager->persist($booking);
            $entityManager->flush();

            // Delete old Services
            $oldServices = $bookingsServicesRepository->findBy(['booking' => $booking->getId()]);
            foreach ($oldServices as $oldService) {
                $entityManager->remove($oldService);
                $entityManager->flush();
            }

            // Save new Services
            $sort = 1;
            foreach ($servicesDataRows as $servicesDataRow) {
                $bookingsServices = new BookingsServices();

                $employee = $employeeRepository->find((int)$servicesDataRow['employee']);
                $service = $serviceRepository->find((int)$servicesDataRow['service']);

                $bookingsServices->setBooking($booking);
                $bookingsServices->setService($service);
                $bookingsServices->setEmployee($employee);
                $bookingsServices->setDurationInMinutes((int)$servicesDataRow['durationInMinutes']);
                $bookingsServices->setSort($sort);
                $sort++;

                $entityManager->persist($bookingsServices);
                $entityManager->flush();
            }
            return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'config' => $config,
            'booking' => $booking,
            'services' => $services
        ]);
    }

    #[Route('/{id}', name: 'booking_delete', methods: ['POST'])]
    public function delete(Request $request, Booking $booking, BookingsServicesRepository $bookingsServicesRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            // Delete services for booking
            $services = $bookingsServicesRepository->findBy(['booking' => $booking->getId()]);
            foreach ($services as $service) {
                $entityManager->remove($service);
                $entityManager->flush();
            }

            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
