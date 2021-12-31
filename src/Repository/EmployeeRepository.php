<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('firstname' => 'ASC', 'lastname' => 'ASC'));
    }

    // /**
    //  * @return Employee[] Returns an array of Employee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAllBookingsForEmployees(string $startDayTime, string $endDayTime, \DateTime $date): array
    {
        //TODO parameter für date, starttime, endtime
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                times.time,
                employee.id as employee_id,
                employee.firstname as employee_name,
                employee.color as employee_color,
                booking_details.booking_id,
                booking_details.booking_start,
                booking_details.service_start,
                booking_details.duration_in_minutes,
                booking_details.sort,
                booking_details.booking_note,
                booking_details.service_id,
                booking_details.service_name,
                booking_details.customer_id,
                booking_details.customer_name
            FROM
                employee
                left outer JOIN times ON 1=1 AND times.time >= '" . $startDayTime . "' AND times.time <= '" . $endDayTime . "' 
                LEFT OUTER JOIN (
                    SELECT
                        booking.id AS booking_id,
                        booking.`start` AS booking_start,
                        case when service_start_time.cumulative_duration IS NOT NULL then DATE_ADD(booking.start, INTERVAL service_start_time.cumulative_duration MINUTE) else booking.start end AS service_start,
                        bookings_services.duration_in_minutes,
                        bookings_services.sort,
                        booking.note AS booking_note,
                        bookings_services.service_id,
                        service.name AS service_name,
                        bookings_services.employee_id,
                        customer.id as customer_id,
                        concat(customer.lastname, ', ', customer.firstname) as customer_name
                    FROM
                        booking
                        INNER JOIN customer on booking.customer_id = customer.id
                        INNER JOIN bookings_services ON booking.id = bookings_services.booking_id
                        INNER JOIN service ON bookings_services.service_id = service.id
                        LEFT OUTER JOIN (
                            SELECT
                                bookings_services.booking_id,
                                bookings_services.sort,
                                bookings_services.duration_in_minutes,
                                SUM(bookings_services.duration_in_minutes) OVER (PARTITION BY bookings_services.booking_id ORDER BY bookings_services.booking_id, bookings_services.sort) AS cumulative_duration
                            FROM
                                bookings_services
                            ORDER BY
                                bookings_services.booking_id,
                                bookings_services.sort
                        ) AS service_start_time ON bookings_services.booking_id = service_start_time.booking_id AND bookings_services.sort = service_start_time.sort + 1
                    WHERE 
                        year(booking.start) = :year AND 
                        month(booking.start) = :month AND 
                        day(booking.start) = :day
                ) AS booking_details ON 
                    employee.id = booking_details.employee_id AND 
                    HOUR(times.time) = HOUR(booking_details.service_start) and 
                    MINUTE(times.time) = MINUTE(booking_details.service_start)  
            ORDER BY
                employee.firstname,
                times.time
            ";
        $stmt = $conn->prepare($sql);
        $results = $stmt->executeQuery([
            'year' => 2021,
            'month' => 12,
            'day' => 31,
        ])->fetchAllAssociative();

        $data = [];
        $current_employee_id = '0';
        $next_time_slot = strtotime($startDayTime);
        foreach ($results as $result) {
            if (!empty($result['employee_id']) && empty($data[$result['employee_id']])) {

                if ($current_employee_id !== $result['employee_id']) {
                    $current_employee_id = $result['employee_id'];
                    $next_time_slot = strtotime($startDayTime);
                }


                $data[$result['employee_id']] = [
                    'employee_id' => $result['employee_id'],
                    'employee_firstname' => $result['employee_name'],
                    'employee_color' => $result['employee_color']
                ];
            }

            if (!empty($result['time']) && strtotime($result['time']) >= $next_time_slot) {
                $minutes_to_add = $result['duration_in_minutes'] ?? 0;

                $data[$result['employee_id']]['time'][$result['time']] = [];

                if (!empty($result['booking_id'])) {
                    $data[$result['employee_id']]['time'][$result['time']][$result['booking_id']] = [
                        'booking_id' => $result['booking_id'],
                        'booking_start' => $result['booking_start'],
                        'booking_note' => $result['booking_note'],
                        'service_start' => $result['service_start'],
                        'customer_id' => $result['customer_id'],
                        'customer_name' => $result['customer_name'],
                        'service_id' => $result['service_id'],
                        'service_name' => $result['service_name'],
                        'duration_in_minutes' => $result['duration_in_minutes'],
                        'sort' => $result['sort'],
                    ];
                }

                $next_time_slot = strtotime($result['time'] . ' + ' . $minutes_to_add . ' minutes');
            }
        }

        return $data;
    }
}
