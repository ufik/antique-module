<?php

    namespace FrontendModule\AntiqueModule;

    /**
     * This presenter handle all actions with authors.
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class AuthorsPresenter extends BasePresenter {
	/* \Nette\Http\SessionSection */

	private $antiqueSession;

	protected function startup() {
	    parent::startup();

	}

	public function actionDefault($id) {
	  
	}

	public function renderDefault($id) {

	    $this->template->id = $id;
	}
    }