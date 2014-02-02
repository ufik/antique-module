<?php

    namespace WebCMS\AntiqueModule;

    /**
     * Antique module.
     *
     * @author Tomáš Voslař <tomas.voslar at webcook.cz>
     */
    class Antique extends \WebCMS\Module {

	protected $name = 'Antique';
	protected $author = 'Tomáš Voslař';
	protected $presenters = array(
	    array(
		'name' => 'Antique',
		'frontend' => TRUE,
		'parameters' => FALSE
	    ),
	    array(
		'name' => 'Categories',
		'frontend' => TRUE,
		'parameters' => TRUE
	    ),
	    array(
		'name' => 'Products',
		'frontend' => FALSE
	    ),
	    array(
		'name' => 'Authors',
		'frontend' => TRUE,
		'parameters' => TRUE
	    ),
	    array(
		'name' => 'Settings',
		'frontend' => FALSE
	));
	protected $params = array(
	);

	public function __construct() {
	    $this->addBox('Category list', 'Categories', 'listBox', 'Antique');
	}

    }
    