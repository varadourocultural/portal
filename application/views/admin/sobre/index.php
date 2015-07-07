
      <?php
          if (validation_errors()):
      ?>
      <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

        <?php echo validation_errors(); ?>
      </div>
      <?php
          endif;
      ?>

      <div id="frm-adicionar-arquivo" class="modal hide fade">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Novo arquivo</h3>
        </div>

        <div class="modal-body">
          <form method="post" enctype="multipart/form-data"
              action="<?php echo site_url('admin/sobre/upload_ajax'); ?>">
            <div class="alert alert-error hide">
              <button type="button" class="close" data-dismiss="alert">&times;</button>

              <span></span>
            </div>

            <label for="arquivoInput">Arquivo</label>
            <input id="arquivoInput" type="file" name="arquivo">
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn cancelar">Cancelar</button>
          <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
      </div>

      <?php echo form_open('', 'role="form" enctype="multipart/form-data"'); ?>
        <div class="tabbable">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-principal" data-toggle="tab">Principal</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="tab-principal">
              <div class="control-group">
                <label for="o_que_somosInput">O que somos? <span class="text-error">*</span></label>
                <?php echo form_textarea('o_que_somos', html_entity_decode($sobre->o_que_somos, NULL, 'UTF-8'), 'id="o_que_somosInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="acoes_coluna_1Input">Ações e Realizações Coluna 1</label>
                <small>Para separar a coluna em títulos use a tag: &lth3&gtTítulo&lt/h3&gt.</small>
                <?php echo form_textarea('acoes_coluna_1', $sobre->acoes_coluna_1, 'id="acoes_coluna_1Input" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="acoes_coluna_2Input">Ações e Realizações Coluna 2</label>
                <small>Para separar a coluna em títulos use a tag: &lth3&gtTítulo&lt/h3&gt.</small>
                <?php echo form_textarea('acoes_coluna_2', $sobre->acoes_coluna_2, 'id="acoes_coluna_2Input" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="acoes_coluna_3Input">Ações e Realizações Coluna 3</label>
                <small>Para separar a coluna em títulos use a tag: &lth3&gtTítulo&lt/h3&gt.</small>
                <?php echo form_textarea('acoes_coluna_3', $sobre->acoes_coluna_3, 'id="acoes_coluna_3Input" class="wysiwyg-basic"'); ?>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url("admin/sobre"); ?>" class="btn">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
