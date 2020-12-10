<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

   /* /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
  /*  public function new(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }*/
    
    /**
     * @Route("/room/{id}", name="reservation_new", methods={"GET","POST"})
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT')")
     */
    
    public function ReserveRoom(Request $request , $id): Response
    {
        $reservation = new Reservation();
        $em = $this->getDoctrine()->getManager();
        $room = $em->getRepository(Room::class)->findOneBy(['id' => $id]);
        $reservation->setResRoom($room);
        $reservation->setResClient($this->getUser()->getClientAuth());
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            for ($i = 0 ; $i<count($room->getReservations()) ; $i++ ){
                if((date_format($reservation->getDateDebut(), 'Y-m-d') >= date_format( $room->getReservations()[$i]->getDateDebut(),'Y-m-d')) && (date_format($reservation->getDateDebut(), 'Y-m-d') <= date_format( $room->getReservations()[$i]->getDateFin(),'Y-m-d'))){
                    $this->get('session')->getFlashBag()->add('error', 'The room is already reserved for that period');
                    return $this->redirectToRoute('reservation_new', array("id"=>$room->getId()));

                }
                elseif((date_format($reservation->getDateFin(), 'Y-m-d') >= date_format( $room->getReservations()[$i]->getDateDebut(),'Y-m-d')) && (date_format($reservation->getDateFin(),'Y-m-d') <= date_format( $room->getReservations()[$i]->getDateFin(),'Y-m-d'))){
                    $this->get('session')->getFlashBag()->add('error', 'The room is already reserved for that period');
                    return $this->redirectToRoute('reservation_new' , array("id"=>$room->getId()));
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->persist($room);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('message', 'Room reserved successfully!'); //message flash
            
            return $this->redirectToRoute('client_profile',array('id'=>$this->getUser()->getClientAuth()->getId()));
        }
        return $this->render('reservation/new.html.twig', [
            'room' => $room ,
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER') or is_granted('ROLE_CLIENT')")
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT')")
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('message', 'changes saved successfully!'); //message flash
            if($this->getUser()){
                if($this->getUser()->getClientAuth())
                    return $this->redirectToRoute('client_reservations',array('id'=>$this->getUser()->getClientAuth()->getId()));
               else
                        return $this->redirectToRoute('reservation_index');
        }
        return $this->redirectToRoute('reservation_index');
        }
        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT')")
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }
}
