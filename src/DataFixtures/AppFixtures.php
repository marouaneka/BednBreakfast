<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;
use App\Entity\Room;
use App\Entity\Owner;

class AppFixtures extends Fixture
{
    
    public const IDF_REGION_REFERENCE = 'idf-region';
    public const IDF_REGION_REFERENCE2 = 'idf-region2';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
//START OF A LINE IN REGION
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("La region francaise capitale");
        $manager->persist($region);
        $manager->flush();
//END OF A LINE IN REGION
        



//START OF A LINE IN ROOM       
      $this->addReference(self::IDF_REGION_REFERENCE, $region);
        
        // ...
        $owner=new Owner();
        $owner->setFamilyname("kachouri");
        $owner->setCountry("en");
        $room = new Room();
        $room->setCapacity(4);
        $room->setPrice(48);
        $room->setAddress("rue charles");
        $room->setSummary("Beau poulailler ancien a Evry");
        $room->setDescription("tres joli espace sur paille");
        $room->setOwner($owner);
       $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));
        $manager->persist($room);
        $manager->flush();
//END OF A LINE IN ROOM

        
//START OF A LINE IN REGION
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Bretagne");
        $region->setPresentation("Ayant Rennes comme prefecture");
        $manager->persist($region);
        $manager->flush();
        //END OF A LINE IN REGION
        
        
        
        
        //START OF A LINE IN ROOM
        $this->addReference(self::IDF_REGION_REFERENCE2, $region);
        
        // ...
        $owner=new Owner();
        $owner->setFamilyname("Lagrange");
        $owner->setCountry("fr");
        $room = new Room();
        $room->setCapacity(6);
        $room->setPrice(120);
        $room->setAddress("Cotes-d'Armor");
        $room->setSummary("Grande Villa avec piscine");
        $room->setDescription("Parfait pour vacances en famille");
        $room->setOwner($owner);
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE2));
        $manager->persist($room);
        $manager->flush();
        //END OF A LINE IN ROOM
        
    }
}
