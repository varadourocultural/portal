
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
              action="<?php echo site_url('admin/projetos/upload_ajax'); ?>">
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
            <li class="active"><a href="#tab-projeto-1" data-toggle="tab">Projeto 1</a></li>
            <li ><a href="#tab-projeto-2" data-toggle="tab">Projeto 2</a></li>
            <li><a href="#tab-multi-file-imagens" data-toggle="tab">Imagens</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="tab-projeto-1">
              <div class="control-group">
                <label for="projeto_1Input">Título</label>
                <?php echo form_input('projeto_1', html_entity_decode($projetos->projeto_1, NULL, 'UTF-8'), 'id="projeto_1Input" '); ?>
              </div>

              <div class="control-group">
                <label for="proj_1_col_1Input">Coluna 1</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_1_col_1',$projetos->proj_1_col_1, 'id="proj_1_col_1Input" class="wysiwyg"'); ?>
              </div>

              <div class="control-group">
                <label for="proj_1_col_2Input">Coluna 2</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_1_col_2',$projetos->proj_1_col_2, 'id="proj_1_col_2Input" class="wysiwyg"'); ?>
              </div>

              <div class="control-group">
                <label for="proj_1_col_3Input">Coluna 3</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_1_col_3',$projetos->proj_1_col_3, 'id="proj_1_col_3Input" class="wysiwyg"'); ?>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url("admin/projetos"); ?>" class="btn">Cancelar</a>
              </div>
            </div>

            <div class="tab-pane" id="tab-projeto-2">
              <div class="control-group">
                <label for="projeto_2Input">Título</label>
                <?php echo form_input('projeto_2', html_entity_decode($projetos->projeto_2, NULL, 'UTF-8'), 'id="projeto_2Input"'); ?>
              </div>

              <div class="control-group">
                <label for="proj_2_col_1Input">Coluna 1</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_2_col_1', $projetos->proj_2_col_1, 'id="proj_2_col_1Input" class="wysiwyg"'); ?>
              </div>

              <div class="control-group">
                <label for="proj_2_col_2Input">Coluna 2</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_2_col_2', $projetos->proj_2_col_2, 'id="proj_2_col_2Input" class="wysiwyg"'); ?>
              </div>

              <div class="control-group">
                <label for="proj_2_col_3Input">Coluna 3</label>
                <small>Para separar a coluna em títulos use a opção: Formatação &gt; Título 3.</small>
                <?php echo form_textarea('proj_2_col_3', $projetos->proj_2_col_3, 'id="proj_2_col_3Input" class="wysiwyg"'); ?>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url("admin/projetos"); ?>" class="btn">Cancelar</a>
              </div>
            </div>

            <div class="tab-pane" id="tab-multi-file-imagens">
              <button type="button" class="btn btn-success adicionar" onclick="addFileMultiFile('imagem_file_id')">Adicionar</button>

              <ul class="thumbnails">
                <li class="span2" style="display: none;">
                  <div class="thumbnail clearfix">
                    <img src="" alt="">
                    <input type="hidden" name="imagem_file_id[]">

                    <div class="actions">
                      <div class="pull-right">
                        <button type="button" class="btn btn-small btn-danger remover"><i class="icon-white icon-trash"></i></button>
                      </div>
                    </div>
                  </div>
                </li>
                <?php
                    foreach ($projetos->imagens as $imagem):
                ?>
                <li class="span2">
                  <div class="thumbnail clearfix">
                    <img src="<?php echo site_url("/publico/thumb/{$imagem->imagem_file_id}/160/120"); ?>" alt="">
                    <?php echo form_hidden('imagem_file_id[]', $imagem->imagem_file_id); ?>

                    <div class="actions">
                      <div class="pull-right">
                        <button type="button" class="btn btn-small btn-danger remover"><i class="icon-white icon-trash"></i></button>
                      </div>
                    </div>
                  </div>
                </li>
                <?php
                    endforeach;
                ?>
              </ul>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url('admin/projetos'); ?>" class="btn">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
