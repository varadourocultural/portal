
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
              action="<?php echo site_url('admin/noticias/upload_ajax'); ?>">
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
            <li><a href="#tab-multi-file-fotos" data-toggle="tab">Fotos</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="tab-principal">

              <div class="control-group">
                <label for="tituloInput">Título <span class="text-error">*</span></label>
                <?php echo form_input('titulo', $noticia->titulo, 'id="tituloInput" placeholder="Título" maxlength="30"'); ?>
              </div>

              <div class="control-group">
                <label for="conteudoInput">Conteúdo <span class="text-error">*</span></label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('conteudo', html_entity_decode($noticia->conteudo, NULL, 'UTF-8'), 'id="conteudoInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="autorInput">Autor </label>
                <?php echo form_input('autor', $noticia->autor, 'id="autorInput" placeholder="Autor"'); ?>
              </div>

              <div class="control-group">
                <label for="slugInput">Slug <span class="text-error">*</span></label>
                <?php echo form_input('slug', $noticia->slug, 'id="slugInput" placeholder="Slug"'); ?>
              </div>

              <div class="control-group">
                <label for="dataInput">Data <span class="text-error">*</span> (dia/mês/ano)</label>

                <div class="date-picker input-append date">
                  <?php echo form_input('data', $noticia->data, 'id="dataInput" placeholder="Data" readonly="readonly"'); ?>

                  <span class="add-on">
                      <i class="icon-calendar"></i>
                  </span>
                </div>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url('admin/noticias'); ?>" class="btn">Cancelar</a>
              </div>
            </div>

            <div class="tab-pane" id="tab-multi-file-fotos">
              <button type="button" class="btn btn-success adicionar" onclick="addFileMultiFile('foto_file_id')">Adicionar</button>

              <ul class="thumbnails">
                <li class="span2" style="display: none;">
                  <div class="thumbnail clearfix">
                    <img src="" alt="">
                    <input type="hidden" name="foto_file_id[]">

                    <div class="actions">
                      <div class="pull-right">
                        <button type="button" class="btn btn-small btn-danger remover"><i class="icon-white icon-trash"></i></button>
                      </div>
                    </div>
                  </div>
                </li>
                <?php
                    foreach ($noticia->fotos as $foto):
                ?>
                <li class="span2">
                  <div class="thumbnail clearfix">
                    <img src="<?php echo site_url("/publico/thumb/{$foto->foto_file_id}/160/120"); ?>" alt="">
                    <?php echo form_hidden('foto_file_id[]', $foto->foto_file_id); ?>

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
                <a href="<?php echo site_url('admin/noticias'); ?>" class="btn">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
