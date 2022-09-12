<?php

namespace G28\B2bkingext\Objects;

use G28\B2bkingext\Plugin;

class ProductDAO
{
    private array $products;
    private array $rows;

    public function __construct()
    {
        $this->products     = [];
        $this->rows         = [];
    }

    public function getProducts(): array
    {
        $this->getProductsFromDB();
        $this->getVariations();
        ksort( $this->products );
        foreach ( $this->products as $product )
        {
            $this->rows[] = $product->toRowArray();
            foreach ($product->getVariations() as $variation)
            {
                $this->rows[] = $variation->toRowArray();
            }
        }
        return $this->rows;
    }

    private function getProductsFromDB()
    {
        global $wpdb;
        $sqlProducts        = "select ID, post_title as title from " . $wpdb->prefix . "posts 
                                where post_type = 'product' 
                                and ID in (
                                select distinct(post_parent) from " . $wpdb->prefix . "posts 
                                where post_type = 'product_variation'
                                )
                                order by ID";
        $dbProducts         = $wpdb->get_results( $sqlProducts, ARRAY_A );
        foreach ( $dbProducts as $product ) {
            $this->products[$product['ID']] = new ProductDTO( $product['ID'], $product['title'] );
        }
    }

    private function getVariations(): void
    {
        $variations = [];
        $dbVariations = $this->getAllVariations();
        foreach ($dbVariations as $variation) {
            $vdto = new VariationDTO( $variation['ID'], $variation['title'], $variation['parent'] );
            $variations[$variation['ID']] = $vdto;
        }

        $dbVariations = $this->getVariationsWithPriceTiers();
        foreach ($dbVariations as $variation) {
            if( array_key_exists( $variation['ID'], $variations ) ) {
                $vdto = $variations[$variation['ID']];
                $vdto->setPrices($variation['tiers']);
                $variations[$variation['ID']] = $vdto;
            }
        }

        foreach ( $variations as $variation ) {
            $pdto = $this->products[$variation->getParent()];
            $pdto->addVariation( $variation );
            $this->products[$pdto->getID()] = $pdto;
        }
    }

    private function getVariationsWithPriceTiers()
    {
        global $wpdb;
        $sqlVariations = "select p.ID, p.post_parent as parent, m.meta_value as tiers from " . $wpdb->prefix . "posts p
                            join " . $wpdb->prefix . "postmeta m on p.ID = m.post_id
                            where p.post_type = 'product_variation'
                            and meta_key = '" . Plugin::getPriceTierMetaKey() . "'";
        return $wpdb->get_results($sqlVariations, ARRAY_A);

    }

    private function getAllVariations()
    {
        global $wpdb;
        $sqlVariations = "select p.ID, post_title as title, p.post_parent as parent from " . $wpdb->prefix . "posts p 
                            join " . $wpdb->prefix . "postmeta m on p.ID = m.post_id
                            where p.post_type = 'product_variation'";
        $dbVariations = $wpdb->get_results($sqlVariations, ARRAY_A);
        return $dbVariations;
    }

    public static function updateProductPriceTiers( $fileTempName ): string
    {
        $count = 0;
        $items = array_map('str_getcsv', file($fileTempName));
        array_shift( $items );
        foreach ($items as $item) {
            if( !empty( $item[2] ) || !is_null( $item[2] ) )
            {
                if( !empty( $item[4] ) ) {
                    $count++;
                    $id = (int)$item[0];
                    $prices = $item[4];
                    update_post_meta($id, Plugin::getPriceTierMetaKey(), $prices);
                }
            }
        }
        return "Foi realizada a atualização de " . $count . " variações de produto";
    }

}