<?php

namespace FrontendModule\AntiqueModule;

/**
 * Description of CategoriesPresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class CategoriesPresenter extends BasePresenter{
	private $repository;
	
	private $productRepository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
	
		$this->repository = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Category');
		$this->productRepository = $this->em->getRepository('WebCMS\AntiqueModule\Doctrine\Product');
	}

	protected function beforeRender() {
		
		$parameters = $this->getParameter('parameters');
		
		$ppp = $this->settings->get('Products per page', 'antiqueModule', 'text')->getValue();
		$cp = $this->getParameter('p');
		
		$product = NULL;
		$products = array();
		// if there are no parameters, show all categories
		if(count($parameters) === 0){
			
			$category = $this->repository->findBy(array(
				'language' => $this->language,
				'title' => 'Main'
			));
			
			if(count($category) > 0){ 
				$title = $category[0]->getTitle();
				$category = $category[0];
				$categories = $this->getStructure($this, $category, $this->repository, TRUE, 'nav navbar-nav', FALSE, FALSE, $this->actualPage, '', 'Antique');
			}else{
				$category = NULL;
				$title = '';
				$categories = '';
			}
		// otherwise try to find category or product by parameters and show it
		}else{
			
			$lastParam = $parameters[count($parameters) - 1];
			
			// check whether is this product
			$product = $this->productRepository->findBy(array(
				'slug' => $lastParam
			));
			
			if(count($product) > 0){
				unset($parameters[count($parameters) - 1]);
				$product = $product[0];
			}
			
			if(count($parameters) > 0){
			    // define category
			    $lastParam = $parameters[count($parameters) - 1];
			}
			
			$category = $this->repository->findBy(array(
				'slug' => $lastParam
			));

			$category = $category[0];

			$title = $category->getTitle();

			foreach($parameters as $p){
				$item = $this->repository->findBy(array(
					'slug' => $p
				));
				$this->addBreadcrumbsItem($item[0]);
			}
			
			// and finally add product to breadcrumbs
			if($product && count($parameters) > 0){
				// set product url
				$product->setLink(
					$this->link(':Frontend:Antique:Categories:default', array(
						'id' => $category->getId(),
						'path' => $this->actualPage->getPath() . '/' . $category->getPath() . '/' . $product->getSlug(),
						'abbr' => $this->abbr
					))
				);
				
				// seo settings
				$this->actualPage->setMetaTitle($product->getMetaTitle());
				$this->actualPage->setMetaDescription($product->getMetaDescription());
				$this->actualPage->setMetaKeywords($product->getMetaKeywords());
				
				$this->addBreadcrumbsItem($category, $product);
			}else{
				$product = null;
				// category
				// seo settings
				$this->actualPage->setMetaTitle($category->getMetaTitle());
				$this->actualPage->setMetaDescription($category->getMetaDescription());
				$this->actualPage->setMetaKeywords($category->getMetaKeywords());
			}
			
			// check for products
			$qb = $this->em->createQueryBuilder();
			
			$query = $qb->select('p')
			    ->from('WebCMS\AntiqueModule\Doctrine\Product', 'p')
			    ->leftjoin('p.categories', 'c')
			    ->andWhere('c.id = :categoryId')
			    ->setParameter('categoryId', $category->getId())
			    ->setMaxResults($ppp)
			    ->setFirstResult($ppp * $cp)
			    ->getQuery();
			
			$products = $query->getResult();
			
			$paginator = new \Nette\Utils\Paginator;
			$paginator->setItemCount(count($category->getProducts()));
			$paginator->setItemsPerPage($ppp);
			$paginator->setPage($cp);
			
			$categories = $this->getStructure($this, $category, $this->repository, TRUE, 'nav navbar-nav', FALSE, FALSE, $this->actualPage, '', 'Antique');
			
			$this->template->paginator = $paginator;			
		}
		
		// it is here, because of breadcrumbs
		parent::beforeRender();
		
		if(!$product){
			$this->actualPage->setClass(
				$this->settings->get('Category body class', 'antiqueModule', 'text')->getValue()
			);
		}else{
			$this->actualPage->setClass(
				$this->settings->get('Product detail body class', 'antiqueModule', 'text')->getValue()
			);
		}
		
		$this->template->product = $product;
		$this->template->category = $category;
		$this->template->page = $this->actualPage;
		$this->template->products = $products;
		$this->template->title = $title;
		$this->template->categories = $categories;
	}
	
	public function actionDefault($id){
		
	}
	
	public function renderDefault($id){
		
		
		$this->template->id = $id;
	}
	
	private function addBreadcrumbsItem($item, $product = NULL){
		
		if($product){
			$title = $product->getTitle();
			$path = '/' . $product->getSlug();
		}
		else{
			$title = $item->getTitle();
			$path = '';
		}
		
		$this->addToBreadcrumbs($this->actualPage->getId(), 
				'Antique',
				'Categories',
				$title,
				$this->actualPage->getPath() . '/' . $item->getPath() . $path
			);
	}
	
	public function listBox($context, $fromPage){
		
		$repository = $context->em->getRepository('\WebCMS\AntiqueModule\Doctrine\Category');
		$category = $repository->findOneBy(array(
				'language' => $context->language,
				'title' => 'Main'
			));
		
		return $this->getStructure($context, $category, $repository, FALSE, 'submenu clr', TRUE, FALSE, $fromPage, '', 'Antique');
	}
}