<?php

namespace G28\B2bkingext\Objects;

class VariationDTO
{
    private string $ID;
    private string $title;
    private string $parent;
    private array $prices;

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
     * @param string $title
     */
    public function appendTitle(string $title): void
    {
        $this->title .= " | " . $title;
    }

    /**
     * @return array
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param string $prices
     */
    public function setPrices(string $prices): void
    {
        $p =  str_replace(":", ";", $prices);
        $this->prices = explode(";", $p);
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
        $this->prices       = [];
    }

    public function toRowArray(): array
    {
        $columns = [
            $this->ID,
            $this->title,
            $this->parent
        ];
        return array_merge( $columns, $this->prices );
    }
}