
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
              action="<?php echo site_url('admin/permissoes/upload_ajax'); ?>">
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
                <label for="acaoInput">Ação <span class="text-error">*</span></label>
                <?php echo form_input('acao', $permissao->acao, 'id="acaoInput" placeholder="Ação"'); ?>
              </div>

              <div class="control-group">
                <label for="nivelInput">Nível <span class="text-error">*</span></label>
                <?php echo form_input('nivel', $permissao->nivel, 'id="nivelInput" placeholder="Nível"'); ?>
              </div>

              <div class="control-group">
                <label class="checkbox">
                  <?php echo form_checkbox('permitir', 1, $permissao->permitir, 'id="permitirInput" class=""'); ?> Permitir                </label>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url('admin/permissoes'); ?>" class="btn">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
