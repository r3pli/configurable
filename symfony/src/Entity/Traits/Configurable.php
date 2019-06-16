<?php

namespace App\Entity\Traits;

use App\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait Configurable
{
    /**
     * @ORM\ManyToMany(targetEntity="Property")
     * @ORM\JoinTable(name="product_properties",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="property_id", referencedColumnName="id", unique=true)}
     *      )
     * @var ArrayCollection
     */
    private $properties;

    /**
     * @return ArrayCollection
     */
    public function getProperties() :ArrayCollection
    {
        return $this->properties;
    }

    /**
     * @param ArrayCollection $properties
     * @return $this
     */
    public function setProperties(ArrayCollection $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param Property $property
     * @return $this
     */
    public function addProperty(Property $property): self
    {
        if(null === $this->properties) {
            $this->properties = new ArrayCollection();
        }
        $this->properties->add($property);
        return $this;
    }

    /**
     * @param Property $property
     * @return $this
     */
    public function removeProperty(Property $property): self
    {
        return $this;
    }
}