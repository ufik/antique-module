<?php

    namespace AdminModule\AntiqueModule;

use Nette\Application\UI;

    /**
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class AuthorsPresenter extends BasePresenter {
	
	private $author;
	
	private $repository;
	
	public function startup() {
	    parent::startup();
	    
	    $this->repository = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Author');
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

	protected function createComponentAuthorsGrid($name) {

	    $grid = $this->createGrid($this, $name, '\WebCMS\AntiqueModule\Doctrine\Author');

	    $grid->addColumnText('name', 'Name')->setSortable()->setFilterText();
	    
	    $grid->addActionHref("updateAuthor", 'Edit', 'updateAuthor', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-primary ajax', 'data-toggle' => 'modal', 'data-target' => '#myModal', 'data-remote' => 'false'));
	    $grid->addActionHref("deleteAuthor", 'Delete', 'deleteAuthor', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this item?'));

	    return $grid;
	}

	public function createComponentAuthorForm(){
	    $form = $this->createForm();
	    
	    $form->addText('name', 'Name')->setAttribute('class', array('form-control'));
	    $form->addTextArea('description', 'Name')->setAttribute('class', array('form-control'));
	    
	    $form->addSubmit('save', 'Save')->setAttribute('class', array('btn btn-success'));
	    $form->onSuccess[] = callback($this, 'authorFormSubmitted');
	    
	    if($this->author->getId()){
		$form->setDefaults($this->author->toArray());
	    }
	    
	    return $form;
	}
	
	public function authorFormSubmitted($form){
	    $values = $form->getValues();
	    
	    $this->author->setName($values->name);
	    $this->author->setDescription($values->description);
	    
	    if(!$this->author->getId()){
		$this->em->persist($this->author);
	    }
	    
	    $this->em->flush();
	    
	    $this->flashMessage('Author has been updated.', 'success');
	    if (!$this->isAjax()){
		$this->redirect('Authors:default', array(
		    'idPage' => $this->actualPage->getId()
		));
	    }
	}
	
	public function actionDeleteAuthor($idPage, $id) {
	    $this->author = $this->repository->find($id);
	    $this->em->remove($this->author);
	    $this->em->flush();

	    $this->flashMessage('Author has been removed.', 'success');

	    if (!$this->isAjax()){
		$this->redirect('Authors:default', array('idPage' => $idPage));
	    }
	}

	public function actionUpdateAuthor($idPage, $id) {
	    if ($id){
		$this->author = $this->repository->find($id);
	    }else{
		$this->author = new \WebCMS\AntiqueModule\Doctrine\Author();
	    }
	}

	public function renderUpdateAuthor($idPage, $panel) {
	    $this->reloadModalContent();

	    $this->template->author = $this->author;
	    $this->template->idPage = $idPage;
	    $this->template->panel = $panel;
	}
    }