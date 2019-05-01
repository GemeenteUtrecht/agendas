<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Agenda
 * 
 * Een Agenda hoort bij een bepaald object, zoals ambtenaar of locatie. Maar kan ook abstract worden gebruikt om een planning toe te voegen aan een algemene resource (bijvoorbeeld zaal of deel-auto) is gebaseerd op de RFC 5545 standaard voor het uitwisselen van agenda's
 *
 * @category   	Entity
 *
 * @author     	Ruben van der Linde <ruben@conduction.nl>
 * @license    	EUPL 1.2 https://opensource.org/licenses/EUPL-1.2 *
 * @version    	1.0
 *
 * @link   		http//:www.conduction.nl
 * @package		Common Ground
 * @subpackage  Agenda
 * 
 *  @ApiResource( 
 *  collectionOperations={
 *  	"get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/agenda",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van Agenda resources op"
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/agenda",
 *  		"openapi_context" = {
 * 				"summary" = "Maak een Agenda resources aan"
 *  		}
 *  	},
 *     "match"={
 *         "method"="GET",
 *         "path"="/agenda/match",
 *         "controller"= HuwelijkController::class,
 *     	   "normalization_context"={"groups"={"match"}},
 *     	   "denormalization_context"={"groups"={"match"}},
 *         "openapi_context" = {
 *         		"summary" = "Vergelijk beschikbaarheid",
 *         		"description" = "Vergelijk een aantal Agenda's en geef een lijst van matchende beschikbaarheid terug",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"200" = {
 *         				"description" = "Nog beschikbare data en tijden"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Agenda niet gevonden"
 *         			}
 *            	}
 *         }
 *     },
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/agendas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een specifieke Agenda resources op"
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/agendas/{id}",
 *  		"openapi_context" = {
 *  			"summary" = "Vervang een specifieke Agenda resources"
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/agendas/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifieke Agenda resources"
 *  		}
 *  	},
 *     "availability"={
 *         "method"="GET",
 *         "path"="/agendas/{id}/beschikbaar",
 *         "controller"= HuwelijkController::class,
 *     	   "normalization_context"={"groups"={"read"}},
 *     	   "denormalization_context"={"groups"={"write"}},
 *         "openapi_context" = {
 *         		"summary" = "Controleer beschikbaarheid",
 *         		"description" = "Controleer welke periodes nog beschikbaar zijn in deze Agenda gedurende een gegeven periode",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"200" = {
 *         				"description" = "Nog beschikbare data en tijden"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Agenda niet gevonden"
 *         			}
 *            	}
 *         }
 *     },
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/agendas/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"read"}},
 *     		"denormalization_context"={"groups"={"write"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van deze Agenda",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"200" = {
 *         				"description" = "Een overzicht van versies"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Agenda niet gevonden"
 *         			}
 *            	}            
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/agendas/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"read"}},
 *     		"denormalization_context"={"groups"={"write"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie teruggedraaien",
 *         		"description" = "Herstel een eerdere versie van deze Agenda. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	},
 *             	"produces" = {
 *         			"application/json"
 *            	},
 *             	"responses" = {
 *         			"202" = {
 *         				"description" = "Teruggedraaid naar eerdere versie"
 *         			},	
 *         			"400" = {
 *         				"description" = "Ongeldige aanvraag"
 *         			},
 *         			"404" = {
 *         				"description" = "Agenda niet gevonden"
 *         			}
 *            	}            
 *         }
 *     }
 *  }
 * )
 * @Gedmo\Loggable(logEntryClass="ActivityLogBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="App\Repository\AgendaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Agenda implements StringableInterface
{
	/**
	 * Het identificatie nummer van deze Agenda <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 * 
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"read"})
	 * @ApiProperty(iri="https://schema.org/identifier")
	 */
	public $id;
	
	/**
	 * De unieke identificatie van deze Agenda binnen de organisatie die ddeze Agenda heeft gecreeerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van ddeze Agenda de organisatie die ddeze Agenda heeft gecreeerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * De organisatie waartoe deze Agenda behoort.
	 *
	 * @var \App\Entity\Organisatie
	 * @ORM\ManyToOne(targetEntity="\App\Entity\Organisatie", cascade={"persist", "remove"}, inversedBy="agendas")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 *
	 */
	public $bronOrganisatie;
	
	/**
	 * De naam van deze Agenda <br /><b>Schema:</b> <a href="https://schema.org/name">https://schema.org/name</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 5,
	 *      max = 255,
	 *      minMessage = "De naam moet tenminste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De naam kan niet langer dan {{ limit }} karakters zijn")
	 * @Groups({"read", "write"})
	 * @ApiProperty(iri="http://schema.org/name")
	 */
	public $naam;
	
	/**
	 * Een beschrijvende tekst over deze Agenda  <br /><b>Schema:</b> <a href="https://schema.org/description">https://schema.org/description</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "text"
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 25,
	 *      max = 2000,
	 *      minMessage = "Your first name must be at least {{ limit }} characters long",
	 *      maxMessage = "Your first name cannot be longer than {{ limit }} characters")
	 *
	 * @ApiProperty(iri="https://schema.org/description")
	 */
	public $beschrijving;
	
	/**
	 * De beschikbare momenten van deze Agenda.
	 *
	 * @var \Doctrine\Common\Collections\Collection|\App\Entity\Agenda\Beschikbaar[]
	 *
	 * @MaxDepth(1)
	 * @ORM\ManyToMany(targetEntity="\App\Entity\Agenda\Beschikbaar", mappedBy="agendas")
	 * @Groups({"read"})
	 *
	 */
	public $beschikbaarheid;
	
	/**
	 * De in deze Agenda opgenomen afspraken.
	 *
	 * @var \Doctrine\Common\Collections\Collection|\App\Entity\Agenda\Afspraak[]
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Agenda\Afspraak", mappedBy="agendas")
	 * @Groups({"read"})
	 * @ApiProperty
	 */
	public $afspraken;
	
		/**
	 * De in deze Agenda opgenomen taken.
	 *
	 * @var \Doctrine\Common\Collections\Collection|\App\Entity\Agenda\Taak[]
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Agenda\Taak", mappedBy="agendas")
	 * @Groups({"read"})
	 * @ApiProperty
	 */
	public $taken;
	
	/**
	 * Het tijdstip waarop deze entiteit is aangemaakt.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"read"})
	 */
	public $registratiedatum;
	
	/**
	 * Het tijdstip waarop deze entiteit voor het laatst is gewijzigd.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Gedmo\Timestampable(on="update")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime",
	 *     nullable	= true
	 * )
	 * @Groups({"read"})
	 */
	public $wijzigingsdatum;	
	
	/**
	 * @return string
	 */
	public function toString(){
		return $this->naam;
	}	
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld logging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten.
	 */
	public function __toString()
	{
		return $this->toString();
	}
		
	public function __construct()
	{
		$this->beschikbaarheid= new ArrayCollection();
		$this->afspraken = new ArrayCollection();
		$this->taken = new ArrayCollection();
	}
}
