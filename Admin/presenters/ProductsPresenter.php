<?php

    namespace AdminModule\AntiqueModule;

use Nette\Application\UI;

    /**
     * Description of ProductsPresenter
     *
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class ProductsPresenter extends BasePresenter {

	private $categoryRepository;
	private $repository;

	/* @var \WebCMS\AntiqueModule\Doctrine\Product */
	private $product;

	/* @var \WebCMS\AntiqueModule\Doctrine\ProductVariant */
	private $variant;
	private $photos;
	
	private $parameter;

	protected function beforeRender() {
	    parent::beforeRender();
	}

	public function renderDefault($idPage) {
	    $this->reloadContent();

	    $this->template->idPage = $idPage;
	}

	protected function startup() {
	    parent::startup();

	    $this->categoryRepository = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Category');
	    $this->repository = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Product');
	}

	protected function createComponentProductForm() {

	    $hierarchy = $this->categoryRepository->getTreeForSelect(array(
		array('by' => 'root', 'dir' => 'ASC'),
		array('by' => 'lft', 'dir' => 'ASC')
		), array(
		'language = ' . $this->state->language->getId()
	    ));
	    
	    $auth = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Author')->findBy(array(), array('name' => 'ASC'));
	    
	    $authors = array(0 => 'Pick autor');
	    foreach($auth as $author){
		$authors[$author->getId()] = $author->getName();
	    }
	    
	    $form = $this->createForm();
	    $form->addText('title', 'Name')->setAttribute('class', 'form-control')->setRequired('Please fill in a name.');
	    $form->addText('slug', 'SEO adresa url')->setAttribute('class', 'form-control');
	    $form->addText('metaTitle', 'SEO title')->setAttribute('class', 'form-control');
	    $form->addText('metaDescription', 'SEO description')->setAttribute('class', 'form-control');
	    $form->addText('metaKeywords', 'SEO keywords')->setAttribute('class', 'form-control');
	    $form->addCheckbox('hide', 'Hide');
	    $form->addText('price', 'Price')->setAttribute('class', 'form-control');
	    $form->addText('vat', 'Vat')->setAttribute('class', 'form-control');
	    $form->addSelect('author', 'Author')->setTranslator(NULL)->setItems($authors)->setAttribute('class', 'form-control');
	    $form->addMultiSelect('categories', 'Categories')->setTranslator(NULL)->setItems($hierarchy)->setAttribute('class', 'form-control');
	    $form->addTextArea('description')->setAttribute('class', 'form-control editor');

	    $form->addSubmit('save', 'Save')->setAttribute('class', 'btn btn-success');

	    $form->onSuccess[] = callback($this, 'productFormSubmitted');

	    if ($this->product) {
		$defaults = $this->product->toArray();

		$defaultCategories = array();
		foreach ($this->product->getCategories() as $c) {
		    $defaultCategories[] = $c->getId();
		}

		$defaults['categories'] = $defaultCategories;
		$form->setDefaults($defaults);
	    }

	    return $form;
	}

	public function productFormSubmitted(UI\Form $form) {
	    $values = $form->getValues();

	    $this->product->setTitle($values->title);

	    if (!empty($values->slug)) {
		$this->product->setSlug($values->slug);
	    }
	    
	    if(!empty($values->author)){
		$author = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Author')->find($values->author);
	    }else{
		$author = null;
	    }
	    
	    $this->product->setMetaTitle($values->metaTitle);
	    $this->product->setMetaDescription($values->metaDescription);
	    $this->product->setMetaKeywords($values->metaKeywords);
	    $this->product->setLanguage($this->state->language);
	    $this->product->setHide($values->hide);
	    $this->product->setAuthor($author);
	    
	    $this->product->setPrice($values->price);
	    $this->product->setVat($values->vat);
	    $this->product->setDescription($values->description);

	    // delete old categories
	    $this->product->setCategories(new \Doctrine\Common\Collections\ArrayCollection());

	    // set categories
	    foreach ($values->categories as $c) {
		$category = $this->categoryRepository->find($c);
		$this->product->addCategory($category);
	    }

	    // delete old photos and save new ones
	    if ($this->product->getId()) {
		$qb = $this->em->createQueryBuilder();
		$qb->delete('WebCMS\AntiqueModule\Doctrine\Photo', 'l')
		    ->where('l.product = ?1')
		    ->setParameter(1, $this->product)
		    ->getQuery()
		    ->execute();
	    } else {
		$this->product->setDefaultPicture('');
	    }

	    if (array_key_exists('files', $_POST)) {
		$counter = 0;
		if (array_key_exists('fileDefault', $_POST))
		    $default = intval($_POST['fileDefault'][0]) - 1;
		else
		    $default = -1;

		foreach ($_POST['files'] as $path) {

		    $photo = new \WebCMS\AntiqueModule\Doctrine\Photo;
		    $photo->setTitle($_POST['fileNames'][$counter]);

		    if ($default === $counter) {
			$photo->setDefault(TRUE);
			$this->product->setDefaultPicture($path);
		    } else
			$photo->setDefault(FALSE);

		    $photo->setPath($path);
		    $photo->setProduct($this->product);

		    $this->em->persist($photo);

		    $counter++;
		}
	    }

	    if (!$this->product->getId()){
		$this->em->persist($this->product);
	    }
	    
	    $this->em->flush();

	    $this->flashMessage('Product has been added.', 'success');
	    
	    //$this->handleGenerateXml();
	    
	    if (!$this->isAjax()){
		$this->redirect('Products:default', array(
		    'idPage' => $this->actualPage->getId()
		));
	    }
	}

	public function actionDefault($idPage) {
	    
	}

	protected function createComponentProductsGrid($name) {

	    $grid = $this->createGrid($this, $name, '\WebCMS\AntiqueModule\Doctrine\Product', NULL, array(
		'language = ' . $this->state->language->getId(),
		)
	    );
	    $grid->setRememberState(true);
	    
	    $auth = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Author')->findBy(array(), array('name' => 'ASC'));
	    
	    $authors = array(null => 'Pick autor');
	    foreach($auth as $author){
		$authors[$author->getId()] = $author->getName();
	    }
	    
	    $grid->addColumnText('photo', 'Picture')->setCustomRender(function($item){
		return "<img style='height: 40px;' src='". $this->context->httpRequest->url->basePath . \WebCMS\Helpers\SystemHelper::thumbnail($item->getMainPhoto()->getPath(), 'system')."' />";
	    });
	    $grid->addColumnNumber('id', 'ID')->setSortable()->setCustomRender(function($item){
		return $item->getId();
	    })->setFilterNumber();
	    $grid->addColumnText('title', 'Name')->setSortable()->setFilterText();
	    $grid->addColumnNumber('price', 'Price')->setCustomRender(function($item) {
		return \WebCMS\PriceFormatter::format($item->getPrice());
	    })->setSortable()->setFilterNumber();
	    $grid->addColumnText('author', 'Author')->setCustomRender(function($item){
		return $item->getAuthor() ? $item->getAuthor()->getName() : 'unknown';
	    })->setFilterSelect($authors);
	    $grid->addColumnText('categories', 'Categories')->setCustomRender(function($item){
		$category = '';
		foreach($item->getCategories() as $c){
		    $category .= $c->getTitle() . ', ';
		}
		return mb_substr($category, 0, -2);
	    });
	    
	    $grid->addActionHref("updateProduct", 'Edit', 'updateProduct', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-primary ajax'));
	    $grid->addActionHref("deleteProduct", 'Delete', 'deleteProduct', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this item?'));

	    return $grid;
	}

	public function actionDeleteProduct($idPage, $id) {
	    $this->product = $this->repository->find($id);
	    $this->em->remove($this->product);
	    $this->em->flush();

	    $this->flashMessage('Product has been removed.', 'success');

	    if (!$this->isAjax())
		$this->redirect('Products:default', array('idPage' => $idPage));
	}

	public function actionUpdateProduct($idPage, $id) {
	    if ($id)
		$this->product = $this->repository->find($id);
	    else
		$this->product = new \WebCMS\AntiqueModule\Doctrine\Product();

	    if ($this->product->getId()) {
		$this->photos = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Photo')->findBy(array(
		    'product' => $this->product
		));
	    } else {
		$this->photos = array();
	    }
	}

	public function renderUpdateProduct($idPage, $panel) {
	    $this->reloadContent();

	    if (!$panel) {
		$panel = 'basic';
	    }

	    $this->template->photos = $this->photos;
	    $this->template->product = $this->product;
	    $this->template->idPage = $idPage;
	    $this->template->panel = $panel;
	}
    }