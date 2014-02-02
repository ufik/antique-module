<?php

namespace WebCMS\AntiqueModule\Doctrine;

use Doctrine\orm\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * Description of Product
 * @orm\Entity
 * @orm\Table(name="antiqueProduct")
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Product extends \AdminModule\Seo {

    /**
     * @orm\Column(length=64)
     */
    private $title;

    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @orm\Column(nullable=true)
     */
    private $material;

    /**
     * @gedmo\Slug(fields={"title"})
     * @orm\Column(length=64)
     */
    private $slug;

    /**
    * @orm\ManyToOne(targetEntity="Author")
    * @orm\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE")
    */
    private $author;

    /**
     * @orm\OneToMany(targetEntity="Photo", mappedBy="product")
     */
    private $photos;

    /**
     * @orm\ManyToMany(targetEntity="Category", inversedBy="products", cascade={"persist"})
     * @orm\JoinTable(name="antiqueProductCategory")
     * @orm\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $categories;

    /**
     * @orm\ManyToOne(targetEntity="\AdminModule\Language")
     * @orm\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $language;

    /**
     * @orm\Column(type="decimal", precision=12, scale=4, nullable=true)
     */
    private $price;

    /**
     * @orm\Column(type="integer", nullable=true)
     */
    private $vat;

    /**
     * @orm\Column(type="boolean", nullable=true)
     */
    private $hide;

    /**
     * @orm\Column(type="datetime")
     */
    private $created;
    
    /**
     * @orm\Column(type="datetime")
     */
    private $sold;
    
    /**
     * @orm\Column
     */
    private $state;
    
    private $priceWithVat;
    private $link;

    /**
     * @orm\Column(nullable=true)
     */
    private $defaultPicture;

    public function __construct() {
	$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
	$this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addCategory($category) {
	$this->categories->add($category);
    }

    public function getTitle() {
	return $this->title;
    }

    public function getDescription() {
	return $this->description;
    }

    public function getSlug() {
	return $this->slug;
    }

    public function getPhotos() {
	return $this->photos;
    }

    public function getCategories() {
	return $this->categories;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function setDescription($description) {
	$this->description = $description;
    }

    public function setSlug($slug) {
	$this->slug = $slug;
    }

    public function setPhotos($photos) {
	$this->photos = $photos;
    }

    public function setCategories($categories) {
	$this->categories = $categories;
    }

    public function getLanguage() {
	return $this->language;
    }

    public function setLanguage($language) {
	$this->language = $language;
    }

    public function getPrice() {
	return $this->price;
    }

    public function getVat() {
	return $this->vat;
    }

    public function setPrice($price) {
	$this->price = $price;
    }

    public function setVat($vat) {
	$this->vat = $vat;
    }

    public function getPriceWithVat() {
	return $this->price * (($this->vat / 100) + 1);
    }

    public function setPriceWithVat($priceWithVat) {
	$this->priceWithVat = $priceWithVat;
    }

    public function getLink() {
	return $this->link;
    }

    public function setLink($link) {
	$this->link = $link;
    }

    public function getDefaultPicture() {
	return $this->defaultPicture;
    }

    public function setDefaultPicture($defaultPicture) {
	$this->defaultPicture = $defaultPicture;
    }

    public function getMainPhoto() {
	foreach ($this->getPhotos() as $photo) {
	    if ($photo->getDefault())
		return $photo;
	}

	return new Photo();
    }

    public function getHide() {
	return $this->hide;
    }

    public function setHide($hide) {
	$this->hide = $hide;
    }

    public function getMaterial() {
	return $this->material;
    }

    public function getAuthor() {
	return $this->author;
    }

    public function setMaterial($material) {
	$this->material = $material;
    }

    public function setAuthor($author) {
	$this->author = $author;
    }
    
    public function getCreated() {
	return $this->created;
    }

    public function getSold() {
	return $this->sold;
    }

    public function getState() {
	return $this->state;
    }

    public function setCreated($created) {
	$this->created = $created;
    }

    public function setSold($sold) {
	$this->sold = $sold;
    }

    public function setState($state) {
	$this->state = $state;
    }
}