<?php

namespace App\Entity\Agenda;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Beschikbaarheid
 * 
 * Beschrijving
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
 * @ApiResource(
 *  collectionOperations={
 *  	"get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/beschikbaar",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een verzameling van Beschikbaarheden op"
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"personen"={"groups"={"write"}},
 *      	"path"="/beschikbaar",
 *  		"openapi_context" = {
 * 				"summary" = "Maak een Beschikbaarheid aan"
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/beschikbaarheden/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifieke Beschikbaarheid op"
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/beschikbaarheden/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifieke Beschikbaarheid"
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/beschikbaarheden/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een Beschikbaarheid"
 *  		}
 *  	},
 *     "log"={
 *         	"method"="GET",
 *         	"path"="/beschikbaarheden/{id}/log",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"read"}},
 *     		"denormalization_context"={"groups"={"write"}},
 *         	"openapi_context" = {
 *         		"summary" = "Logboek inzien",
 *         		"description" = "Geeft een array van eerdere versies en wijzigingen van deze Beschikbaarheid",
 *          	"consumes" = {
 *              	"application/json",
 *               	"text/html",
 *            	}            
 *         }
 *     },
 *     "revert"={
 *         	"method"="POST",
 *         	"path"="/beschikbaarheden/{id}/revert/{version}",
 *          "controller"= HuwelijkController::class,
 *     		"normalization_context"={"groups"={"read"}},
 *     		"denormalization_context"={"groups"={"write"}},
 *         	"openapi_context" = {
 *         		"summary" = "Versie teruggedraaien",
 *         		"description" = "Herstel een eerdere versie van deze Beschikbaarheid. Dit is een destructieve actie die niet ongedaan kan worden gemaakt",
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
 *         				"description" = "Beschikbaarheid niet gevonden"
 *         			}
 *            	}            
 *         }
 *     }
 *  }
 * )
 * @Gedmo\Loggable(logEntryClass="ActivityLogBundle\Entity\LogEntry")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */

class Beschikbaar implements StringableInterface
{
	/**
	 * Het identificatienummer van deze Beschikbaarheid <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
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
	 * De agendas waar deze Beschikbaarheid in staat.
	 *
	 * @var \Doctrine\Common\Collections\Collection|\App\Entity\Product
	 *
	 * @MaxDepth(1)
	 * @ORM\ManyToMany(targetEntity="\App\Entity\Agenda", inversedBy="beschikbaarheid")
	 * @Groups({"read"})
	 *
	 */
	public $agendas;
	
	/**
	 * De naam van deze Beschikbaarheid <br /><b>Schema:</b> <a href="https://schema.org/name">https://schema.org/name</a>
	 *
	 * @var string
	 *
	 * @Gedmo\Translatable
	 * @Gedmo\Versioned
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 5,
	 *      max = 255,
	 *      minMessage = "De naam moet tenminste {{ limit }} karakters lang zijn",
	 *      maxMessage = "De naam kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="Trouwzaal"
	 *         }
	 *     }
	 * )
	 **/
	public $naam;
	
	/**
	 * Een samenvattende tekst over deze Beschikbaarheid  <br /><b>Schema:</b> <a href="https://schema.org/description">https://schema.org/description</a>
	 *
	 * @var string
	 *
	 * @Gedmo\Translatable
	 * @Gedmo\Versioned
	 * @ORM\Column(
	 *     type     = "text", 
	 *     nullable=true
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 25,
	 *      max = 2000,
	 *      minMessage = "Your first name must be at least {{ limit }} characters long",
	 *      maxMessage = "Your first name cannot be longer than {{ limit }} characters")
	 *
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 * 	  iri="https://schema.org/description",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="Deze Beschikbaarheid is alleen te boeken op woensdagen"
	 *         }
	 *     }
	 * )
	 **/
	public $samenvatting;
	
	/**
	 * Een beschrijvende tekst over deze Beschikbaarheid  <br /><b>Schema:</b> <a href="https://schema.org/description">https://schema.org/description</a>
	 *
	 * @var string
	 *
	 * @Gedmo\Translatable
	 * @Gedmo\Versioned
	 * @ORM\Column(
	 *     type     = "text", 
	 *     nullable=true
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 25,
	 *      max = 2000,
	 *      minMessage = "Your first name must be at least {{ limit }} characters long",
	 *      maxMessage = "Your first name cannot be longer than {{ limit }} characters")
	 *
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 * 	  iri="https://schema.org/description",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="Deze uitsterst sfeervolle trouwzaal is de droom van ieder koppel"
	 *         }
	 *     }
	 * )
	 **/
	public $beschrijving;
		
	/**
	 * Het begin van deze Beschikbaarheid.
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime", 
	 *     nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $van;
	
	/**
	 * Het einde van deze Beschikbaarheid
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime", 
	 *     nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $tot;
	
	/**
	 * Herhaal deze Beschikbaarheid
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $herhaal = false;
	
	/**
	 * Maandag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $maandag = false;
	
	/**
	 * Dinsdag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $dinsdag = false;
	
	/**
	 * Woensdag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $woensdag = false;
	
	/**
	 * Donderdag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $donderdag = false;
	
	/**
	 * Vrijdag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $vrijdag = false;
	
	/**
	 * Zaterdag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $zaterdag = false;
	
	/**
	 * Zondag
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"	 
	 * @ORM\Column(
	 *     type     = "boolean"
	 * )
	 * @Groups({"read"})
	 */
	public $zondag = false;
	
	
	/**
	 * Herhaal deze Beschikbaarheid vanaf
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime", 
	 *     nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $herhaalVan;
	
	/**
	 * Herhaal deze Beschikbaarheid tot
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijvoorbeeld "2018-12-31 13:33:05" ofwel "Jaar-dag-maand uur:minuut:seconde"
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime", 
	 *     nullable=true
	 * )
	 * @Groups({"read"})
	 */
	public $herhaalTot;
	
	/**
	 * Het tijdstip waarop deze Beschikbaarheid is aangemaakt.
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
	 * Het tijdstip waarop deze Beschikbaarheid voor het laatst is gewijzigd.
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
     * De eigenaar (applicatie) van dit object, wordt bepaald aan de hand van de geauthenticeerde applicatie die de beschikbaarheid heeft aangemaakt
     * 
     * @var App\Entity\Applicatie $eigenaar
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\Applicatie")
     * @Groups({"read"})
     */
    public $eigenaar;

	
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
		$this->agendas= new ArrayCollection();
	}
	
	
	/**
	 * Add Agenda
	 *
	 * @param  \App\Entity\Agenda $agenda
	 * @return Beschikbaar
	 */
	public function addAgenda(\App\Entity\Agenda $agenda)
	{
		$this->agendas[] = $agenda;
		
		return $this;
	}
	
	/**
	 * Remove Agenda
	 *
	 * @param \App\Entity\Agenda $agenda
	 * @return Beschikbaar
	 */
	public function removeAgenda(\App\Entity\Agenda $agenda)
	{
		$this->agendas->removeElement($$agenda);
		
		return $this;
	}
	
	/**
	 * Get Agendas
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getAgendas()
	{
		return $this->agendas;
	}
}	
