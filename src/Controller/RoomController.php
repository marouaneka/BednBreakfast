<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="room_index", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $room = new Room();
        if($this->getUser()){
            if ($this->getUser()->getAuthOwner()){
                 $room->setOwner($this->getUser()->getAuthOwner());
            }
        }
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('message', 'Room added successfully!'); //message flash
            if($this->getUser()){
                if($this->getUser()->getAuthOwner())
                   return $this->redirectToRoute('room_list',array('id'=>$this->getUser()->getAuthOwner()->getId()));
             else
                    return $this->redirectToRoute('room_index');
            }
            return $this->redirectToRoute('room_index');
          
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

   /* /**
     * @Route("/{id}", name="room_show", methods={"GET"})
     */
   /* public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }*/
    
    /**
     * @Route("/{id}", name="room_show", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_OWNER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_CLIENT')")
     */
    
    public function RoomShow($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $room = $em->getRepository(Room::class)->findOneBy(['id' => $id]);
        
        return $this->render('room/NoCRUDRoom.twig', array(
            'room' => $room,
            
        ));
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_OWNER') or is_granted('ROLE_ADMIN')")
     *
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('message', 'changes saved successfully!'); //message flash
            if($this->getUser()){
                if($this->getUser()->getAuthOwner())
                    return $this->redirectToRoute('room_list',array('id'=>$this->getUser()->getAuthOwner()->getId()));
                else
                        return $this->redirectToRoute('room_index');
                        
            }
            return $this->redirectToRoute('room_index');
            
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }
    
   /* /**
     * @Route("/{id}/owneredit", name="room_owneredit", methods={"GET","POST"})
     */
  /*  public function Owneredit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->remove('owner');                            #Removing the owner field
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('owner_list');
        }
        
        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     *
     * @Security("is_granted('ROLE_OWNER') or is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }
}
