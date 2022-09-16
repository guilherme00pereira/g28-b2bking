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

    public function getProducts( $category = null): array
    {
        $this->getProductsFromDB( $category );
        if( count( $this->products ) > 0 ) {
            $this->getVariations();
            ksort($this->products);
            foreach ($this->products as $product) {
                $this->rows[] = $product->toRowArray();
                foreach ($product->getVariations() as $variation) {
                    $this->rows[] = $variation->toRowArray();
                }
            }
        } else {
            $this->rows[] = ["Nenhum produto encontrado para a categoria informada", null, null, null];
        }
        return $this->rows;
    }

    private function getProductsFromDB( $category)
    {
        global $wpdb;
        $sqlProducts        = "select ID, post_title as title from " . $wpdb->prefix . "posts where post_type = 'product'
                and ID in ( select distinct(post_parent) from " . $wpdb->prefix . "posts where post_type = 'product_variation' ) ";
        if( !is_null( $category ) ) {
            $sqlProducts    .= " and ID in (select s.object_id from " . $wpdb->prefix . "term_relationships s
                                inner join " . $wpdb->prefix . "term_taxonomy x on s.term_taxonomy_id = x.term_taxonomy_id
                                where x.term_id = '" . $category . "' or x.parent = '" . $category . "') ";
        }
        $sqlProducts        .= "order by ID";
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
            if( array_key_exists( $variation['ID'], $variations ) ) {
                $vdto = $variations[$variation['ID']];
                $vdto->appendTitle($variation['attribute']);
                $variations[$variation['ID']] = $vdto;
            } else {
                $vdto = new VariationDTO( $variation['ID'], $variation['title'], $variation['parent'] );
                $vdto->appendTitle($variation['attribute']);
                $variations[$variation['ID']] = $vdto;
            }
        }

        $dbVariations = $this->getVariationsWithPriceTiers();
        foreach ($dbVariations as $variation) {
            if( array_key_exists( $variation['ID'], $variations ) ) {
                $vdto = $variations[$variation['ID']];
                $vdto->setPrices( $variation['tiers'] );
                $variations[$variation['ID']] = $vdto;
            }
        }

        foreach ( $variations as $variation ) {
            $pdto = $this->products[$variation->getParent()];
            if( !is_null( $pdto ) ) {
                $pdto->addVariation($variation);
                $this->products[$pdto->getID()] = $pdto;
            }
        }
    }

    private function getAllVariations()
    {
        global $wpdb;
        $sqlVariations = "select distinct(p.ID), post_title as title, p.post_parent as parent, meta_value as attribute from " . $wpdb->prefix . "posts p 
                            join " . $wpdb->prefix . "postmeta m on p.ID = m.post_id
                            where p.post_type = 'product_variation' and meta_key like'attribute%' order by p.ID";
        $dbVariations = $wpdb->get_results($sqlVariations, ARRAY_A);
        return $dbVariations;
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

    public function updateProductPriceTiers( $fileTempName ): string
    {
        $count = 0;
        $rows = file($fileTempName);
        array_shift( $rows );
        array_shift( $rows );
        foreach ($rows as $row) {
            if( substr($row, -1) === ";" ) 
            {
                $row = substr( $row, 0, -1 );
            }
            $row = str_getcsv($row, ';');
            if( $this->checkRowValidity( $row ) )
            {
                $count++;
                update_post_meta((int)$row[0], '_regular_price', $row[4]);
                update_post_meta((int)$row[2], 'minimum_allowed_quantity', $row[3]);
                $this->setPricesTiers( $row );
            }
        }
        return "Foi realizada a atualização de " . $count . " variações de produto";
    }

    private function setPricesTiers( $row )
    {
        $priceString = "";
        $totalColumns = count($row);
        for($i = 3; $i < $totalColumns; $i++) 
        {
            if( !empty( $row[$i] ) ) {
                $priceString .= $row[$i] . ($i%2 == 0 ? ";" : ":");
            }
        }
        update_post_meta((int)$row[0], Plugin::getPriceTierMetaKey(), $priceString);
    }

    private function checkRowValidity( $row ): bool
    {
        if(count($row) < 4) return false;
        return !( empty( $row[2] ) && empty( $row[3] ) && empty( $row[4] ));
    }

    public function getCategories()
    {
        $cat_args = array(
            'orderby'    => 'name',
            'order'      => 'asc',
            'parent'    => 0,
            'hide_empty' => false,
        );
        $categories = get_terms( 'product_cat', $cat_args );
        return $categories;
    }

}