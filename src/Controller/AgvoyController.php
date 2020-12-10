<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgvoyController extends AbstractController
{
   // /**
   //  * @Route("/agvoy", name="agvoy")
   //  */
   /* public function index()
    {
        return $this->render('agvoy/index.html.twig', [
            'controller_name' => 'AgvoyController',
        ]);
    }*/
    
    /**
     * @Route("/", name = "home", methods="GET")
     */
  
    public function indexAction()
    {
        return $this->redirectToRoute('app_login');
    }
    
    /**
     * Lists all Regions.
     *
     * @Route("/selectregion/", name = "agvoy", methods="GET")
     */
    public function SelectRegion(RegionRepository $regionRepository): Response
    {
        return $this->render('region/SelectRegion.html.twig', [
            'regions' => $regionRepository->findAll(),
        ]);
    }
    
    
    
    /**
     * Show rooms of a region
     *
     * @Route("/{name}/rooms", name="room_region_show", methods="GET")    
     *
     * @param String $name
     */
    public function show($name)
    {
        $regionRepo = $this->getDoctrine()->getRepository('App:Region');
        $region = $regionRepo->findOneBy(['name' => $name]);
        
        if (!$region) {
            throw $this->createNotFoundException('The Region doesnt exist');
        }
        
        $roomies = $region->getRoom();
        return $this->render('room/RoomsInRegion.html.twig', [
            'rooms' => $roomies,'region'=>$region,
        ]);
        
       
        
        
   
    }
    
    
}
