<?php

namespace G28\B2bkingext;

use G28\B2bkingext\Objects\ProductDAO;

class Controller {

    public function __construct()
	{
		add_action('admin_menu', array($this, 'addMenuPage' ));
		add_action( 'admin_enqueue_scripts', [ $this, 'registerStylesAndScripts'] );
        add_action( 'wp_ajax_ajaxImportTierPricesCsv', [ $this, 'ajaxImportTierPricesCsv' ] );
        add_action( 'wp_ajax_ajaxImportStatus', [ $this, 'ajaxImportStatus' ] );
        add_action( 'admin_post_exportProductsCsv', [ $this, 'exportProductsCsv' ] );
        add_filter( 'woocommerce_registration_redirect', [ $this, 'loginRedirect'], 10, 1);
        add_filter( 'woocommerce_login_redirect', [ $this, 'loginRedirect'], 10, 2);
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
		wp_register_style( Plugin::getAssetsPrefix() . 'importer_style', Plugin::getAssetsUrl() . 'css/importer.css', null, date("YmdHis") );
		wp_register_script(
            Plugin::getAssetsPrefix() . 'importer_script',
            Plugin::getAssetsUrl() . 'js/importer.js',
            array( 'jquery' ),
            date("YmdHis"),
            true
        );
		wp_localize_script( Plugin::getAssetsPrefix() . 'importer_script', 'ajaxobj', [
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'security'              => wp_create_nonce( 'g28b2bking_nonce' ),
            'action_import'         => 'ajaxImportTierPricesCsv',
            'action_importStatus'   => 'ajaxExportProductsCsv',
        ]);
	}

    public function exportProductsCsv()
    {
        $category   = $_POST['productCategories'];
        $pdao       = new ProductDAO();
        $products   = $pdao->getProducts($category);
        $fileName   = "export_products.csv";
        ob_clean();
        header( 'Pragma: public' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Cache-Control: private', false );
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=' . $fileName );
        $fp         = fopen( 'php://output', 'w' );
        fputcsv( $fp, [ "ID", "name", "parent", "price_tiers" ], ";" );
        foreach ( $products AS $product ) {
            fputcsv( $fp, $product, ";" );
        }
        fclose( $fp );
        ob_flush();
        exit;
    }

    public function ajaxImportTierPricesCsv()
    {
        $return = ProductDAO::updateProductPriceTiers( $_FILES["file"]["tmp_name"] );
        echo $return;
        wp_die();
    }

    public function ajaxImportStatus()
    {
        echo json_encode(['complete' => true, 'message' => 'Importação finalizada!']);
        wp_die();
    }

    public function loginRedirect( $redirection_url )
    {
        $redirection_url = wp_get_referer() ? wp_get_referer() : wc_get_page_permalink( 'welcome' );
        return $redirection_url;
    }

}