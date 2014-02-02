<?php

    namespace FrontendModule\AntiqueModule;

    /**
     * This presenter handle all actions with authors.
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class AuthorsPresenter extends BasePresenter {
	/* \Nette\Http\SessionSection */

	private $antiqueSession;

	private $authors;
	
	protected function startup() {
	    parent::startup();

	}

	public function actionDefault($id) {
	    $this->authors = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Author')->findBy(array(), array(
		'name' => 'ASC'
	    ));
	}

	public function renderDefault($id) {
	    
	    $this->template->authors = $this->authors;
	    $this->template->id = $id;
	}
    }