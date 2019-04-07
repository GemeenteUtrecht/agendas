<?php

// https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


use App\Entity\Organisatie;
use App\Entity\Persoon;

class OrganisatieFixtures extends Fixture
{
	public const BRON_ORGANISATIE_REFERENCE = 'bron-organisatie';
	public const CONTACT_PERSOON_REFERENCE = 'contact-persoon';
	
    public function load(ObjectManager $manager)
    {
    	// Lets create an example organisation
    	
    	$organisatie = new Organisatie();
    	$organisatie->rsin= "1234.56.789";
    	$organisatie->kvk= "12345678";
    	$organisatie->btw= "NL 1234.56.789.B01";
    	$organisatie->eori= "NL 1234.56.789";
    	$organisatie->telefoon = "123-4567890";
    	$organisatie->email= "info@zuiddrecht.nl";
    	$organisatie->naam = "Gemeente Zuiddrecht";
    	$organisatie->beschrijving = "Gelegen in het prachtige zuidelijke deel van de provincie Drecht";
    	$manager->persist($organisatie);
    	
    	// other fixtures can get this object using the OrganisatieFixtures::BRON_ORGANISATIE_REFERENCEconstant
    	$this->addReference(self::BRON_ORGANISATIE_REFERENCE, $organisatie);
    	
    	// Lets save te created entities
    	$manager->flush();   
    }
}
