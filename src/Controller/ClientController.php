<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Room;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user=$client->getUser()->addRole('ROLE_CLIENT');
            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->persist($client);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('message', 'Client added successfully!'); //message flash
            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_show", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     *
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('message', 'changes saved successfully!'); //message flash
                
                    if($this->getUser()){
                        if($this->getUser()->getClientAuth())
                            return $this->redirectToRoute('client_profile',array('id'=>$client->getId()));
                         else 
                                return $this->redirectToRoute('client_index');
                                
                    }
                    return $this->redirectToRoute('client_index');

            
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index');
    }
    
    /**
     * @Route("/profile/{id}", name="client_profile", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    public function ClientProfile($id)
    {

        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository(Client::class)->findOneBy(['id' => $id]);
        
        return $this->render('client/clientprofile.html.twig', array(
            'client' => $client,
            
        ));
    }
    
    /**
     * @Route("/{id}/reservations", name="client_reservations", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    
    public function ClientReservations($id)
    {

        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository(Client::class)->findOneBy(['id' => $id]);
        $reservations = $client->getReservations();
        
        return $this->render('reservation/client_reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
    
    /**
     * @Route("/{id}/likes", name="client_likes", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    
    public function ClientLikes($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository(Client::class)->findOneBy(['id' => $id]);
        $likes = $client->getRoom();
        
        return $this->render('client/ClientLikes.html.twig', [
            'rooms' => $likes,
        ]);
    }
    
    
    /**
     * @Route("/likeroom/{id}", name="like_add", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    
    public function addLike($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $room = $em->getRepository(Room::class)->findOneBy(['id' => $id]);
        $client=$this->getUser()->getClientAuth();
        $client->addRoom($room);
        $em->persist($client);
        $em->flush();
        
        return $this->render('room/LikedRoom.html.twig', array('room'=>$room,'value'=>'added to')); 
    }
    
    /**
     * @Route("/deletelike/{id}", name="like_delete", methods="GET")
     *
     * @param String $id
     *
     * @Security("is_granted('ROLE_CLIENT') or is_granted('ROLE_ADMIN')")
     */
    
    public function deleteLike($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $room = $em->getRepository(Room::class)->findOneBy(['id' => $id]);
        $client=$this->getUser()->getClientAuth();
        $client->removeRoom($room);
        $em->persist($client);
        $em->flush();
        
        return $this->render('room/LikedRoom.html.twig', array('room'=>$room,'value'=>'removed from'));
    }
}
