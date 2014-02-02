<?php

    namespace AdminModule\AntiqueModule;

    /**
     * Description of SettingsPresenter
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class SettingsPresenter extends BasePresenter {

	protected function startup() {
	    parent::startup();
	}

	protected function beforeRender() {
	    parent::beforeRender();
	}

	public function actionDefault($idPage) {
	    
	}

	public function createComponentSettingsForm() {

	    $settings = array();
	    
	    $settings[] = $this->settings->get('Order email subject', 'antiqueModule', 'text', array());
	    $settings[] = $this->settings->get('Order email', 'antiqueModule', 'textarea', array());

	    return $this->createSettingsForm($settings);
	}

	public function renderDefault($idPage) {
	    $this->reloadContent();

	    $this->template->config = $this->settings->getSection('antiqueModule');
	    $this->template->idPage = $idPage;
	}
    }