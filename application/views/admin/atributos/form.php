
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
              action="<?php echo site_url('admin/atributos/upload_ajax'); ?>">
            <div class="alert alert-error hide">
              <button type="button" class="close" data-dismiss="alert">&times;</button>

              <span></span>
            </div>

            <label for="arquivoInput">Arquivo <span class="text-error">*</span></label>
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
                <label for="tipoInput">Tipo de seleção <span class="text-error">*</span></label>
                <?php echo form_dropdown('tipo', array('' => '-- Selecione --') + $tipos_atributos, $atributo->tipo, 'id="tipoInput"'); ?>
              </div>

              <div class="control-group">
                <label for="nomeInput">Nome <span class="text-error">*</span></label>
                <?php echo form_input('nome', $atributo->nome, 'id="nomeInput" placeholder="Nome"'); ?>
              </div>

            <?php
                if ($area_atuacao_cultural):
            ?>

              <div class="control-group">
                <label for="siglaInput">Sigla</label>
                <?php echo form_input('sigla', $atributo->sigla, 'id="siglaInput" placeholder="Sigla" maxlength="3"'); ?>
              </div>

            <?php
                endif;
            ?>

              <div class="control-group">
                <label for="iconeInput">Ícone</label>
                <input id="iconeInput" type="file" name="icone">
              </div>

              <div class="control-group miniatura-arquivo">
                <?php
                    if ($atributo->icone):
                ?>
                <input type="hidden" name="icone_file_id" value="<?php echo $atributo->icone->id; ?>">

                <div class="img-conteiner">
                  <img src="<?php echo site_url("/publico/thumb/{$atributo->icone->id}/160/120"); ?>" class="img-polaroid">
                  <button type="button" class="btn btn-small btn-danger excluir"><i class="icon-trash"></i></button>
                </div>
                <?php
                    endif;
                ?>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <?php
                    if (! empty($atributo_ascendente)):
                ?>
                <a href="<?php echo site_url("admin/atributos-descendentes/{$atributo_ascendente}"); ?>" class="btn">Cancelar</a>
                <?php
                    else:
                ?>
                <a href="<?php echo site_url('admin/atributos'); ?>" class="btn">Cancelar</a>
                <?php
                    endif;
                ?>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
