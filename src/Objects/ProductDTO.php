<?php

namespace G28\B2bkingext\Objects;

class ProductDTO
{
    private string $ID;
    private string $title;
    private array $variations;

    public function __construct( $id, $title)
    {
        $this->ID           = $id;
        $this->title        = $title;
        $this->variations   = [];
    }

    public function cast( $object ): ProductDTO
    {
        foreach($object as $property => $value) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     */
    public function setID(string $ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getVariations(): array
    {
        return $this->variations;
    }

    /**
     * @param VariationDTO $variation
     */
    public function addVariation(VariationDTO $variation): void
    {
        $this->variations[] = $variation;
    }

    public function toRowArray(): array
    {
        return [
            $this->ID,
            $this->title
        ];
    }
}