<?php

function renderWysiwyg() {

?>

<div id="modal" class="modal" style="display:none;">

    <div class="modal-content">

        <div class="editor-header"><h2 id="editorTitle">Éditeur</h2></div>

        <div class="toolbar">

                <button class="tool-btn" onclick="execCmd('bold')" title="Gras"><i class="fas fa-bold"></i></button>

                <button class="tool-btn" onclick="execCmd('italic')" title="Italique"><i class="fas fa-italic"></i></button>

                <button class="tool-btn" onclick="execCmd('underline')" title="Souligné"><i class="fas fa-underline"></i></button>

                <div class="toolbar-divider"></div>

                <button class="tool-btn" onclick="execCmd('justifyLeft')"><i class="fas fa-align-left"></i></button>

                <button class="tool-btn" onclick="execCmd('justifyCenter')"><i class="fas fa-align-center"></i></button>

                <div class="toolbar-divider"></div>

                <button class="tool-btn" onclick="execCmd('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>

                <input type="color" onchange="execCmd('foreColor', this.value)" title="Couleur">



                <div class="toolbar-divider"></div>



                <div class="image-tool-group">

                    <input type="file" id="imageInput" style="display:none" accept="image/*" onchange="uploadImage(this)">

                    <button class="btn-upload" onclick="document.getElementById('imageInput').click()">

                        <i class="fas fa-image"></i> Insérer Photo

                    </button>

                    <select id="imgSizeSelector" class="size-select">

                        <option value="16px">Icône (16x16)</option>

                        <option value="100%">Colonne (Largeur totale)</option>

                    </select>

                </div>

            </div>

        <div id="editor" contenteditable="true" class="editable-area"></div>

        <div class="editor-footer">

            <button type="button" onclick="closeEditor()">Annuler</button>

            <button type="button" class="btn-blue" onclick="saveData()">Enregistrer</button>

        </div>

    </div>

</div>

<?php

}