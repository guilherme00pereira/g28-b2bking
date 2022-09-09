<?php

namespace G28\B2bkingext;

class Controller {

    public function __construct()
	{
		add_action('admin_menu', array($this, 'addMenuPage' ));
		add_action( 'admin_enqueue_scripts', [ $this, 'registerStylesAndScripts'] );
        add_action( 'wp_ajax_ajaxImportTierPricesCsv', [ $this, 'ajaxImportTierPricesCsv' ] );
        add_action( 'wp_ajax_ajaxExportProductsCsv', [ $this, 'ajaxExportProductsCsv' ] );
	}

    public function addMenuPage()
	{
		add_submenu_page(
            'b2bking',
			'Importador',
			'Importador',
			'manage_options',
			'g28-b2bkingext',
			array( $this, 'renderMenuPage' )
        );
	}

    public function renderMenuPage()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
		wp_enqueue_style(Plugin::getAssetsPrefix() . 'importer_style');
		wp_enqueue_script( Plugin::getAssetsPrefix() . 'importer_script' );
		ob_start();
        include sprintf( "%simporter.php", Plugin::getTemplateDir() );
        $html = ob_get_clean();
        echo $html;
    }

    public function registerStylesAndScripts()
	{
		wp_register_style( Plugin::getAssetsPrefix() . 'importer_style', Plugin::getAssetsUrl() . 'css/importer.css' );
		wp_register_script(
            Plugin::getAssetsPrefix() . 'importer_script',
            Plugin::getAssetsUrl() . 'js/importer.js',
            array( 'jquery' ),
            null,
            true
        );
		wp_localize_script( Plugin::getAssetsPrefix() . 'importer_script', 'ajaxobj', [
			'ajax_url'        	=> admin_url( 'admin-ajax.php' ),
			'g28b2bking_nonce'  => wp_create_nonce( 'g28b2bking_nonce' ),
			'action_export'     => 'ajaxExportProductsCsv',
            'action_import'     => 'ajaxImportTierPricesCsv',
		]);
	}

    public function ajaxExportProductsCsv()
    {
        /* select p.ID, p.post_parent, m.meta_value from wp_posts p 
            join wp_postmeta m on p.ID = m.post_id
            where p.post_type = 'product_variation'
            and meta_key like 'attribute%'
            order by ID */
        $items = [];
        global $wpdb;
        $sqlProducts        = "select ID, post_title from wp_posts where post_type = 'product' order by ID";
        $products           = $wpdb->get_results( $sqlProducts, ARRAY_A );
		foreach ( $products as $product ) {
			$items[] = $product['ID'];
		}
        $sqlVariations        = "select p.ID, p.post_parent, m.meta_value from wp_posts p 
                                join wp_postmeta m on p.ID = m.post_id
                                where p.post_type = 'product_variation'
                                and meta_key like 'attribute%'
                                order by ID";
        $variations           = $wpdb->get_results( $sqlVariations, ARRAY_A );
        foreach( $variations as $variation) {
            
        }
    }

    public function ajaxImportTierPricesCsv()
    {
        sleep(2);
        echo json_encode(['success' => true, 'message' => 'Banners atualizados com sucesso!']);
        wp_die();
    }

}