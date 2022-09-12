<?php

?>

<div id="b2bking_importer_ext">
    <div>
        <h1>Importador Tier Price para B2BKing</h1>
    </div>
    <div class="g28b2bking-row">
        <h2>Exportar template</h2>
        <div class="g28b2bking-row-content">
            <div style="margin-right: 50px;">
                <form method="post" id="download_form" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="exportProductsCsv">
                    <input type="submit" name="download_csv" class="button-primary" value="Download CSV" />
                </form>
            </div>
            <div class="text">
                <p>
                    O arquivo contém todos os produtos e suas variações, e está no formato CSV, contendo as seguintes colunas, 
                    <b class="warning">separadas por vírgula ","</b>:<br />
                    <b>ID</b> - <b>Título</b> - <b>ID Produto Pai</b> - <b>Preços</b>
                </p>
                <p>
                    A coluna de preços deve ser preenchida de acordo com o seguinte padrão:<br />
                    <span class="text-faixa">início da faixa:</span>
                    <span class="text-preco">preço</span>, <b class="warning">sempre separados por ponto e vírgula ";"</b>
                    <br />
                    Exemplo: <i>
                            <span class="text-faixa">20:</span>
                            <span class="text-preco">85,68;</span>
                            <span class="text-faixa">40:</span>
                            <span class="text-preco">78,54;</span>
                            <span class="text-faixa">60:</span>
                            <span class="text-preco">73,19;</span>
                            <span class="text-faixa">80:</span>
                            <span class="text-preco">69,62;</span>
                        </i>
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