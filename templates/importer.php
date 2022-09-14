<?php

$cat_args = array(
    'orderby'    => 'name',
    'order'      => 'asc',
    'hide_empty' => false,
);
$product_categories = get_terms( 'product_cat', $cat_args );

?>

<div id="b2bking_importer_ext">
    <div>
        <h1>Importador Tier Price para B2BKing</h1>
    </div>
    <div class="g28b2bking-row">
        <h2>Exportar template</h2>
        <div class="g28b2bking-row-content">
            <div class="export-form">
                <form method="post" id="download_form" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="exportProductsCsv">
                    <div class="form_control">
                        <label for="productCategories">Categorias: </label>
                        <select id="productCategories" name="productCategories">
                            <?php foreach ($product_categories as $category) : ?>
                                <option value="<?php echo $category->term_id ?>"><?php echo $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form_control">
                        <input type="submit" name="download_csv" class="button-primary" value="Download CSV" />
                    </div>
                </form>
            </div>
            <div class="text">
                <p>
                    O arquivo contém todos os produtos e suas variações, e está no formato CSV, contendo as seguintes colunas, 
                    <b class="warning">separadas por ponto e vírgula ";"</b>:<br />
                    <b>ID</b> - <b>Título</b> - <b>ID Produto Pai</b> - <b>Preços</b>
                </p>
                <p>
                    As colunas de preços devem ser preenchidas seguindo o padrão:<br />
                    <span class="text-faixa">quantidade</span> | 
                    <span class="text-preco">preço</span> | 
                    <span class="text-faixa">quantidade</span> | 
                    <span class="text-preco">preço</span> | 
                    <span class="text-faixa">quantidade</span> | 
                    <span class="text-preco">preço</span>
                </p>
            </div>
        </div>
        <div id="messageExport"></div>
    </div>
    <div class="g28b2bking-row">
        <h2>Importar</h2>
        <div class="g28b2bking-row-content">
            <div style="margin-right: 50px;">
                <form class="fileUpload" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" id="csvFile" name="file" accept=".csv" />
                    </div>
                </form>
                <div id="btnImportAction">
                    <button id="btnImportCsv" type="button" class="button b2bking-btn-success">Importar</button>
                    <span id="loadingImport" style="display: none; padding-left: 15px;">
                        <img src="<?php echo esc_url( get_admin_url() . 'images/spinner.gif' ); ?>"  alt="loading"/>
                    </span>
                </div>
            </div>
            <div class="text">
                <p>O processo de importação realizará a leitura do arquivo .CSV,
                    identificará em cada linha quais itens são variações, de acordo com a coluna <i><b>ID Produto Pai</b></i>
                    e fará a inserção ou atualização dos preços, caso estejam presentes na coluna <i><b>Preços</b></i></p>
            </div>
        </div>
        <div id="messageImport" class="notice notice-info notice-alt">texto</div>
    </div>
</div>