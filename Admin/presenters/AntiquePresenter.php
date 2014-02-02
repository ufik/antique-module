<?php

namespace AdminModule\AntiqueModule;

/**
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class AntiquePresenter extends \AdminModule\BasePresenter {

    protected function startup() {
	parent::startup();
    }

    protected function beforeRender() {
	parent::beforeRender();
    }

    public function actionDefault($idPage) {

    }

    public function renderDefault($idPage) {
	$this->reloadContent();

	$this->template->idPage = $idPage;
    }
}    