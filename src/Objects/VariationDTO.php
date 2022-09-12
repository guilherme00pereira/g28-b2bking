<?php

namespace G28\B2bkingext\Objects;

class VariationDTO
{
    private string $ID;
    private string $title;
    private string $parent;
    private string $prices;

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
     * @return string
     */
    public function getPrices(): string
    {
        return $this->prices;
    }

    /**
     * @param string $prices
     */
    public function setPrices(string $prices): void
    {
        $this->prices = $prices;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     */
    public function setParent(string $parent): void
    {
        $this->parent = $parent;
    }

    public function __construct( $id, $title, $parent )
    {
        $this->ID           = $id;
        $this->title        = $title;
        $this->parent       = $parent;
        $this->prices       = "";
    }

    public function toRowArray(): array
    {
        return [
            $this->ID,
            $this->title,
            $this->parent,
            $this->prices
        ];
    }
}