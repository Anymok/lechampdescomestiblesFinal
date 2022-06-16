<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PlantSearch {

    /**
     * @var int|null
     * @Assert\Range(min=1, max=20)
     */
    private $maxPrice;

    
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $color;

     /**
     * @var string|null
     */
    private $type;

       /**
    * @var string|null
    */
    public function getType() : ?string
    {
        return $this->type;
    }

    /**
    * @param string|null $type
    * @var PlantSearch
    */
    public function setType(string $type): PlantSearch
    {
        $this->type = $type;
        return $this;
    }


    /**
    * @var string|null
    */
    public function getColor() : ?string
    {
        return $this->color;
    }

    /**
    * @param string|null $color
    * @var PlantSearch
    */
    public function setColor(string $color): PlantSearch
    {
        $this->color = $color;
        return $this;
    }


    /**
     * @var string|null
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @var PlantSearch
     */
    public function setTitle(string $title): PlantSearch
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @var int|null
     */
    public function getMaxPrice() : ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @var PlantSearch
     */
    public function setMaxPrice(int $maxPrice): PlantSearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

}