<?php

require_once 'Proexc/Controller/Action.php';

require_once 'Coordenador.php';
require_once 'Projeto.php';
require_once 'Curso.php';
require_once 'Evento.php';

class IndexController extends Proexc_Controller_Action {
	
	
	
	function init()
    {
    	
		parent::init();
        $this->view->title = 'index';
		
    }

    function indexAction()
    {
    }

    function listProjetosAction() {
		$this->view->title = 'Projetos Inacabados';
    	
    	$projeto = new Projeto();
		$this->view->projetos = $projeto->fetchUnvalidatedByCoordenador($this->user->id);
		
    	$this->render();
	}
	
	function listValidatedProjetosAction() {
		$this->view->title = 'Projetos Existentes';
		
    	$projeto = new Projeto();
		$this->view->projetos = $projeto->fetchValidatedByCoordenador($this->user->id);
		
    	$this->render();
	}
	
    function listCursosAction() {
		$this->view->title = 'Cursos Inacabados';
    	
    	$curso = new Curso();
		$this->view->cursos = $curso->fetchUnvalidatedByCoordenador($this->user->id);
		
    	$this->render();
	}

    function listValidatedCursosAction() {
		$this->view->title = 'Cursos Existentes';
    	
    	$curso = new Curso();
		$this->view->cursos = $curso->fetchValidatedByCoordenador($this->user->id);
		
    	$this->render();
	}
	
	function listEventosAction() {
		$this->view->title = 'Eventos Inacabados';
    	
    	$evento = new Evento();
		$this->view->eventos = $evento->fetchUnvalidatedByCoordenador($this->user->id);
		
    	$this->render();
	}

	function listValidatedEventosAction() {
		$this->view->title = 'Eventos Existentes';
    	
    	$evento = new Evento();
		$this->view->eventos = $evento->fetchValidatedByCoordenador($this->user->id);
		
    	$this->render();
	}
}