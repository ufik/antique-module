<?php

namespace WebCMS\AntiqueModule\Doctrine;

use Doctrine\orm\Mapping as orm;

/**
 * Description of Photo
 * @orm\Entity
 * @orm\Table(name="antiquePhoto")
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Photo extends \WebCMS\Entity\Entity {
	/**
	 * @orm\Column
	 */
	private $path;
	
	/**
	 * @orm\Column
	 */
	private $title;
	
	/**
	 * @orm\ManyToOne(targetEntity="Product")
	 * @orm\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $product;
	
	/**
	 * @orm\Column(name="`default`", type="boolean")
	 */
	private $default;
	
	/**
	 * @orm\Column(name="`order`", type="smallint")
	 */
	private $order;
	
	public function getPath() {
		return $this->path;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getProduct() {
		return $this->product;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setProduct($product) {
		$this->product = $product;
	}
	
	public function getDefault() {
		return $this->default;
	}

	public function setDefault($default) {
		$this->default = $default;
	}
	
	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
	}
}
