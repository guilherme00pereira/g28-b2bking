<?php

?>

<div id="b2bking_importer_ext">
    <div>
        <h1>Importador Tier Price para B2BKing</h1>
    </div>
    <div class="g28b2bking-row">
        <h2>Exportar template</h2>
        <div class="g28b2bking-row-content">
            <button id="btnExportCsv" type="button" class="button button-primary">Exportar</button>
            <span id="loadingExport" style="display: none; padding-left: 15px;">
                <img src="<?php echo esc_url( get_admin_url() . 'images/spinner.gif' ); ?>"  alt="loading"/>
            </span>
        </div>
        <div id="messageExport"></div>
    </div>
    <div class="g28b2bking-row">
        <h2>Importar</h2>
        <div class="g28b2bking-row-content">
            <form class="fileUpload" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" id="file" accept="image/*" />
                </div>
            </form>
            <button id="btnImportCsv" type="button" class="button b2bking-btn-success">Importar</button>
            <span id="loadingImport" style="display: none; padding-left: 15px;">
                <img src="<?php echo esc_url( get_admin_url() . 'images/spinner.gif' ); ?>"  alt="loading"/>
            </span>
        </div>
        <div id="messageImport"></div>
    </div>
</div>