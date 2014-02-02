<?php

namespace WebCMS\AntiqueModule\Doctrine;

use Doctrine\orm\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * @orm\Entity
 * @orm\Table(name="antiqueAuthor")
 * @author Tomáš Voslař <tomas.voslar@webcook.cz>
 */
class Author extends \AdminModule\Seo {
    
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
     * @orm\OneToOne(targetEntity="Author", mappedBy="product")
     */
    private $product;
    
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
}    