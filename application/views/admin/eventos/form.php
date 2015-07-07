
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
              action="<?php echo site_url("admin/espacos-culturais/{$espaco_cultural}/eventos/upload_ajax"); ?>">
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

            <?php if (checar_permissao('espacos_culturais.eventos.publicar_registros', NULL)): ?>
              <div class="control-group">
                <label for="statusInput">Status <span class="text-error">*</span></label>
                <?php echo form_dropdown('status', array('' => '-- Selecione --') + $statuses, $evento->status, 'id="statusInput"'); ?>
              </div>
            <?php endif ?>

              <div class="control-group">
                <label for="tituloInput">Título <span class="text-error">*</span></label>
                <?php echo form_input('titulo', $evento->titulo, 'id="tituloInput" placeholder="Título" maxlength="30"'); ?>
              </div>

              <div class="control-group">
                <label for="slugInput">Slug <span class="text-error">*</span></label>
                <?php echo form_input('slug', $evento->slug, 'id="slugInput" placeholder="Slug"'); ?>
              </div>

              <div class="control-group">
                <label for="dataInput">Data <span class="text-error">*</span> (dia/mês/ano)</label>

                <div class="date-picker input-append date">
                  <?php echo form_input('data', $evento->data, 'id="dataInput" placeholder="Data" readonly="readonly"'); ?>

                  <span class="add-on">
                      <i class="icon-calendar"></i>
                  </span>
                </div>
              </div>

              <div class="control-group">
                <label for="horarioInput">Horário <span class="text-error">*</span></label>
                <?php echo form_input('horario', $evento->horario, 'id="horarioInput" placeholder="Horário"'); ?>
              </div>

              <div class="control-group">
                <label for="descricaoInput">Descrição</label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('descricao', html_entity_decode($evento->descricao, NULL, 'UTF-8'), 'id="descricaoInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="informacoes_valoresInput">Informações de valores</label>
                <?php echo form_textarea('informacoes_valores', html_entity_decode($evento->informacoes_valores, NULL, 'UTF-8'), 'id="informacoes_valoresInput"'); ?>
              </div>

              <div class="control-group">
                <label for="informacoes_datasInput">Informações de datas</label>
                <?php echo form_textarea('informacoes_datas', html_entity_decode($evento->informacoes_datas, NULL, 'UTF-8'), 'id="informacoes_datasInput"'); ?>
              </div>

              <div class="control-group">
                <label for="informacoes_horariosInput">Informações de horários</label>
                <?php echo form_textarea('informacoes_horarios', html_entity_decode($evento->informacoes_horarios, NULL, 'UTF-8'), 'id="informacoes_horariosInput"'); ?>
              </div>

              <div class="control-group">
                <label for="imagem_coverInput">Imagem de cover</label>
                <input id="imagem_coverInput" type="file" name="imagem_cover">
              </div>
              <div class="control-group miniatura-arquivo">
                <?php
                    if ($evento->imagem_cover):
                ?>
                <input type="hidden" name="imagem_cover_file_id" value="<?php echo $evento->imagem_cover->id; ?>">

                <div class="img-conteiner">
                  <img src="<?php echo site_url("/publico/thumb/{$evento->imagem_cover->id}/160/120"); ?>" class="img-polaroid">
                  <button type="button" class="btn btn-small btn-danger excluir"><i class="icon-trash"></i></button>
                </div>
                <?php
                    endif;
                ?>
              </div>

              <div class="agentes-evento">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Agente Cultural</th>
                      <th class="column-actions">Opções</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr style="display: none;">
                      <td>
                        <input type="hidden" name="id_agente[]" value="">
                        <input type="hidden" name="nome_agente[]" value="">
                        <span class="nome"></span>
                      </td>

                      <td class="column-actions">
                        <div class="btn-group">
                          <button class="btn btn-danger delete" type="button" onclick="">Remover</button>
                        </div>
                      </td>
                    </tr>

                    <?php
                      foreach ($evento->agentes as $agente):
                    ?>
                    <tr>
                      <td>
                        <input type="hidden" name="id_agente[]" value="<?php echo @$agente->id; ?>">
                        <input type="hidden" name="nome_agente[]" value="<?php echo @$agente->nome_responsavel; ?>">
                        <span class="nome"><?php echo @$agente->nome_responsavel; ?></span>
                      </td>

                      <td class="column-actions">
                        <div class="btn-group">
                          <button class="btn btn-danger delete" type="button" onclick="">Remover</button>
                        </div>
                      </td>
                    </tr>
                    <?php
                      endforeach;
                    ?>
                  </tbody>
                </table>

                <div class="form-inline adicionar">
                  <input type="hidden" value="">

                  <input type="text" id="typeahead-agente" class="typeahead" data-provide="typeahead" placeholder="Novo Agente..." autocomplete="off" value="">

                  <button type="button" onclick="" class="btn">Adicionar</button>
                </div>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url("admin/espacos-culturais/{$espaco_cultural}/eventos"); ?>" class="btn">Cancelar</a>
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
                    foreach ($evento->fotos as $foto):
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
                <a href="<?php echo site_url('admin/eventos'); ?>" class="btn">Cancelar</a>
              </div>
            </div>

          </div>
        </div>
      <?php echo form_close(); ?>
