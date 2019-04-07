<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Agenda;

class AgendaController
{
	public function __invoke(Agenda $data): Agenda
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}