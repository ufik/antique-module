<?php

namespace AdminModule\AntiqueModule;

use Nette\Application\UI;

/**
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class AuthorsPresenter extends BasePresenter {

    public function startup() {
	parent::startup();
    }

    public function actionDefault($idPage) {

    }

    protected function beforeRender() {
	parent::beforeRender();
    }

    public function renderDefault($idPage) {
	$this->reloadContent();

	$this->template->idPage = $idPage;
    }
}