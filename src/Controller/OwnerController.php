<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\User;
use App\Form\OwnerType;
use App\Repository\OwnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/owner")
 */
class OwnerController extends AbstractController
{
    /**
     * @Route("/", name="owner_index", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(OwnerRepository $ownerRepository): Response
    {
        return $this->render('owner/index.html.twig', [
            'owners' => $ownerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="owner_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $owner = new Owner();
        
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        
            $entityManager = $this->getDoctrine()->getManager();
           /* if($this->getUser()){
            $user=$owner->getUser()->addRole('ROLE_OWNER');
            $entityManager->persist($user);
            $entityManager->flush();
            }*/
            $entityManager->persist($owner);
            $entityManager->flush();
            
            $this->get('session')->getFlashBag()->add('message', 'Owner added successfully!'); //message flash

            return $this->redirectToRoute('owner_index');
        }
        

        return $this->render('owner/new.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="owner_show", methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')")
     */
    public function show(Owner $owner): Response
    {
        return $this->render('owner/show.html.twig', [
            'owner' => $owner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="owner_edit", methods={"GET","POST"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')")
     */
    public function edit(Request $request, Owner $owner): Response
    {
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            
            $this->get('session')->getFlashBag()->add('message', 'changes saved successfully!'); //message flash
            
            if($this->getUser()){
                if($this->getUser()->getAuthOwner())
                    return $this->redirectToRoute('owner_profile',array('id'=>$owner->getId()));
                else 
                    return $this->redirectToRoute('owner_index');
                    
            }
                return $this->redirectToRoute('owner_index');
            
           
        }

        return $this->render('owner/edit.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="owner_delete", methods={"DELETE"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Owner $owner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$owner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($owner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('owner_index');
    }
    
   
    

    
    /**
     * @Route("/{id}/new", name="owner_new_id", methods="GET")
     *
     * @param String $id
     */
    
    public function relate(Request $request,$id): Response
    {
        $owner = new Owner();
        
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $owner->setUser($user);
            $owner->getUser()->addRole('ROLE_ADMIN');
            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->persist($owner);
            $entityManager->flush();
            
            return $this->redirectToRoute('owner_index');
        }
        
        
        return $this->render('owner/new.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/owners", name="owner_list", methods="GET")
     */
    public function OwnersList()
    {
        
        $owners = $this->getDoctrine()->getRepository('App:Owner')->findAll();
        return $this->render('owner/ownerslist.twig', [
            'owners' => $owners,
        ]);
    }
    
    /**
     * @Route("/ownerprofile/{id}", name="owner_profile", methods="GET")
     *
     * @param String $id
     */
    
    public function OwnerProfile($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $owner = $em->getRepository(Owner::class)->findOneBy(['id' => $id]);
        
        return $this->render('owner/ownerprofile.html.twig', array(
            'owner' => $owner,
        ));
    }
    
    /**
     * @Route("/{id}/room/list", name="room_list", methods="GET")
     *
     * @param String $id
     */
    
    public function OwnerRoomsList($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $owner = $em->getRepository(Owner::class)->findOneBy(['id' => $id]);
        $rooms = $owner->getRoom();
        
        return $this->render('owner/ownerroomlist.html.twig', array(
            'rooms' => $rooms,
            
        ));
    }
    
    
}
