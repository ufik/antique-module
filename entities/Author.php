<?php

namespace WebCMS\AntiqueModule\Doctrine;

use Doctrine\orm\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * @orm\Entity
 * @orm\Table(name="antiqueAuthor")
 * @author Tomáš Voslař <tomas.voslar@webcook.cz>
 */
class Author extends \WebCMS\Entity\Seo {
    
    /**
     * @orm\Column
     */
    private $name;
    
    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $description;
    
    /**
     * @gedmo\Slug(fields={"name"})
     * @orm\Column(length=64)
     */
    private $slug;
    
    /**
     * @orm\OneToMany(targetEntity="Product", mappedBy="author")
     */
    private $products;
    
    public function getName() {
	return $this->name;
    }

    public function getDescription() {
	return $this->description;
    }

    public function getSlug() {
	return $this->slug;
    }

    public function setName($name) {
	$this->name = $name;
    }

    public function setDescription($description) {
	$this->description = $description;
    }

    public function setSlug($slug) {
	$this->slug = $slug;
    }
    
    public function getProducts() {
	return $this->products;
    }

    public function setProducts($products) {
	$this->products = $products;
    }
    
    public function hasProducts(){
	return count($this->products) > 0;
    }
}    