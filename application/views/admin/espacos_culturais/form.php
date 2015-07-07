
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
              action="<?php echo site_url('admin/espacos-culturais/upload_ajax'); ?>">
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

            <?php if (checar_permissao('espacos_culturais.publicar_registros', NULL)): ?>
              <div class="control-group">
                <label for="statusInput">Status <span class="text-error">*</span></label>
                <?php echo form_dropdown('status', array('' => '-- Selecione --') + $statuses, $espaco_cultural->status, 'id="statusInput"'); ?>
            </div>
              <?php endif ?>

              <div class="control-group">
                <label for="nome_espacoInput">Nome do espaço <span class="text-error">*</span></label>
                <?php echo form_input('nome_espaco', $espaco_cultural->nome_espaco, 'id="nome_espacoInput" placeholder="Nome do espaço" maxlength="30"'); ?>
              </div>

              <div class="control-group">
                <label for="slugInput">Slug <span class="text-error">*</span></label>
                <?php echo form_input('slug', $espaco_cultural->slug, 'id="slugInput" placeholder="Slug"'); ?>
              </div>

              <div class="control-group">
                <label for="natureza_juridicaInput">Natureza jurídica <span class="text-error">*</span></label>
                <?php echo form_dropdown('natureza_juridica', array('' => '-- Selecione --') + $naturezas_juridicas, $espaco_cultural->natureza_juridica, 'id="natureza_juridicaInput"'); ?>
              </div>

              <div class="control-group multiple-selectors atributos" data-campo="tipo_espaco">
                <label>Tipo do espaço <span class="text-error">*</span></label>
                <?php echo gerar_seletor_atributos('tipo_espaco', $tipos_espacos, $tipos_espacos_selecionados); ?>
              </div>

              <div class="control-group">
                <label for="nome_responsavel_espacoInput">Nome do responsável <span class="text-error">*</span></label>
                <?php echo form_input('nome_responsavel', $espaco_cultural->nome_responsavel, 'id="nome_responsavel_espacoInput" placeholder="Nome do responsável"'); ?>
              </div>

              <div class="control-group multiple-checkboxes" data-campo="espaco_fisico_virtual">
                <label>Espaço físico/virtual</label>
                <?php echo gerar_seletor_atributos('espaco_fisico_virtual', $espacos_fisicos_virtuais, $espacos_fisicos_virtuais_selecionados); ?>
              </div>

              <div class="control-group multiple-checkboxes" data-campo="ponto_cultura">
                <label>Ponto de cultura</label>
                <?php echo gerar_seletor_atributos('ponto_cultura', $pontos_cultura, $pontos_cultura_selecionados); ?>
              </div>

              <div class="control-group multiple-selectors atributos" data-campo="area_atuacao_primaria">
                <label>Área de atuação primária<span class="text-error">*</span></label>
                <?php echo gerar_seletor_atributos('area_atuacao_primaria', $areas_atuacao_primaria, @array($area_atuacao_primaria_selecionada), 1, 'selecao-simples'); ?>
              </div>

              <div class="control-group multiple-checkboxes" data-campo="area_atuacao_cultural">
                <label>Área de atuação cultural <span class="text-error">*</span></label>
                <?php echo gerar_seletor_atributos('area_atuacao_cultural', $areas_atuacoes_culturais, $areas_atuacoes_culturais_selecionadas); ?>
              </div>

              <div class="control-group">
                <label for="enderecoInput">Endereço <span class="text-error">*</span></label>
                <?php echo form_input('endereco', $espaco_cultural->endereco, 'id="enderecoInput" placeholder="Endereço"'); ?>
              </div>

              <div class="control-group">
                <label for="cepInput">Cep <span class="text-error">*</span></label>
                <?php echo form_input('cep', $espaco_cultural->cep, 'id="cepInput" placeholder="Cep"'); ?>
              </div>

              <div class="control-group">
                <label for="complementoInput">Complemento</label>
                <?php echo form_input('complemento', $espaco_cultural->complemento, 'id="complementoInput" placeholder="Complemento"'); ?>
              </div>

              <div class="control-group">
                <label>Mapa </label>
                <div class="map" id="map"></div>
              </div>

              <div class="control-group">
                <label for="latitude">Latitude </label>
                <?php echo form_input('latitude', @$espaco_cultural->latitude, 'id="latitudeInput" placeholder="Latitude"'); ?>
              </div>

              <div class="control-group">
                <label for="longitude">Longitude </label>
                <?php echo form_input('longitude', @$espaco_cultural->longitude, 'id="longitudeInput" placeholder="Longitude"'); ?>
              </div>

              <div class="control-group">
                <label for="atividades_culturaisInput">Atividades culturais</label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('atividades_culturais', html_entity_decode($espaco_cultural->atividades_culturais, NULL, 'UTF-8'), 'id="atividades_culturaisInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label for="celularInput">Celular</label>
                <?php echo form_input('celular', $espaco_cultural->celular, 'id="celularInput" placeholder="Celular"'); ?>
              </div>

              <div class="control-group">
                <label for="telefone_fixoInput">Telefone fixo</label>
                <?php echo form_input('telefone_fixo', $espaco_cultural->telefone_fixo, 'id="telefone_fixoInput" placeholder="Telefone fixo"'); ?>
              </div>

              <div class="control-group">
                <label for="telefone_comercialInput">Telefone trab./comercial <span class="text-error">*</span></label>
                <?php echo form_input('telefone_comercial', $espaco_cultural->telefone_comercial, 'id="telefone_comercialInput" placeholder="Telefone trab./comercial"'); ?>
              </div>

              <div class="control-group">
                <label for="siteInput">End. Site </label>
                <?php echo form_input('site', $espaco_cultural->site, 'id="siteInput" placeholder="Ex.: http://enderecodosite.com.br"'); ?>
              </div>

              <div class="control-group">
                <label for="emailInput">Email <span class="text-error">*</span></label>
                <?php echo form_input('email', $espaco_cultural->email, 'id="emailInput" placeholder="Email"'); ?>
              </div>

              <div class="control-group">
                <label for="preco_minimoInput">Preço mínimo</label>
                <?php echo form_input('preco_minimo', $espaco_cultural->preco_minimo, 'id="preco_minimoInput" placeholder="Preço mínimo"'); ?>
              </div>

              <div class="control-group">
                <label for="preco_maximoInput">Preço máximo</label>
                <?php echo form_input('preco_maximo', $espaco_cultural->preco_maximo, 'id="preco_maximoInput" placeholder="Preço máximo"'); ?>
              </div>

              <div class="control-group">
                <label for="url_agenda_culturalInput">URL agenda cultural</label>
                <?php echo form_input('url_agenda_cultural', $espaco_cultural->url_agenda_cultural, 'id="url_agenda_culturalInput" placeholder="URL agenda cultural"'); ?>
              </div>

              <div class="control-group">
                <label for="informacoes_precoInput">Informações preço</label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('informacoes_preco', html_entity_decode($espaco_cultural->informacoes_preco, NULL, 'UTF-8'), 'id="informacoes_precoInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group multiple-checkboxes" data-campo="formas_pagamento">
                <label>Formas de pagamento</label>
                <?php echo gerar_seletor_atributos('formas_pagamento', $formas_pagamentos, $formas_pagamentos_selecionadas); ?>
              </div>

              <div class="control-group">
                <label for="twitterInput">Twitter</label>
                <?php echo form_input('twitter', $espaco_cultural->twitter, 'id="twitterInput" placeholder="Twitter"'); ?>
              </div>

              <div class="control-group">
                <label for="facebookInput">Facebook <span class="text-error">*</span></label>
                <?php echo form_input('facebook', $espaco_cultural->facebook, 'id="facebookInput" placeholder="Ex.: http://facebook.com/enderecodapagina'); ?>
              </div>

              <div class="control-group">
                <label for="google_plusInput">Google+</label>
                <?php echo form_input('google_plus', $espaco_cultural->google_plus, 'id="google_plusInput" placeholder="Google+"'); ?>
              </div>

              <div class="control-group">
                <label for="youtubeInput">You Tube</label>
                <?php echo form_input('youtube', $espaco_cultural->youtube, 'id="youtubeInput" placeholder="You Tube"'); ?>
              </div>

              <div class="control-group">
                <label for="outras_redes_sociaisInput">Outras redes sociais</label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('outras_redes_sociais', html_entity_decode($espaco_cultural->outras_redes_sociais, NULL, 'UTF-8'), 'id="outras_redes_sociaisInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="control-group">
                <label>Horário de funcionamento</label>

                <table>
                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 0); ?>
                      Segunda
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[0]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[0]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_0', '1', (bool) intval(@$espaco_cultural->horarios_dias[0]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 1); ?>
                      Terça
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[1]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[1]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_1', '1', (bool) intval(@$espaco_cultural->horarios_dias[1]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 2); ?>
                      Quarta
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[2]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[2]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_2', '1', (bool) intval(@$espaco_cultural->horarios_dias[2]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 3); ?>
                      Quinta
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[3]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[3]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_3', '1', (bool) intval(@$espaco_cultural->horarios_dias[3]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 4); ?>
                      Sexta
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[4]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[4]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_4', '1', (bool) intval(@$espaco_cultural->horarios_dias[4]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 5); ?>
                      Sábado
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[5]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[5]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_checkbox('horarios_fechado_5', '1', (bool) intval(@$espaco_cultural->horarios_dias[5]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <?php echo form_hidden('horarios_dia_semana[]', 6); ?>
                      Domingo
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_abertura[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[6]->horario_abertura); ?>
                    </td>

                    <td>
                      <?php echo form_dropdown('horarios_horario_fechamento[]', array('' => '') + $horarios, @$espaco_cultural->horarios_dias[6]->horario_fechamento); ?>
                    </td>

                    <td>
                      <label class="checkbox">
                        <?php echo form_hidden('horarios_fechado_6', 0); ?>
                        <?php echo form_checkbox('horarios_fechado_6', '1', (bool) intval(@$espaco_cultural->horarios_dias[6]->fechado)); ?>
                        Fechado
                      </label>
                    </td>
                  </tr>
                </table>
              </div>

              <div class="control-group">
                <label for="fechado_almocoInput">Fechado no horário de almoço(12:00 - 14:00)
                <?php echo form_checkbox('fechado_almoco', '1', (bool) intval(@$espaco_cultural->fechado_almoco)); ?>
                </label>
              </div>

              <div class="control-group">
                <label for="informacoes_adicionaisInput">Informações adicionais</label>
                <small>
                  Antes de colar o conteúdo aqui de aplicativos de rica formatação textual como Word (e similares) , cole primeiro em um aplicativo sem formatação, como o Bloco de Notas (e similares).
                </small>
                <?php echo form_textarea('informacoes_adicionais', html_entity_decode($espaco_cultural->informacoes_adicionais, NULL, 'UTF-8'), 'id="informacoes_adicionaisInput" class="wysiwyg-basic"'); ?>
              </div>

              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Salvar"/>
                <a href="<?php echo site_url('admin/espacos-culturais'); ?>" class="btn">Cancelar</a>
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
                    foreach ($espaco_cultural->fotos as $foto):
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
                <a href="<?php echo site_url('admin/espacos-culturais'); ?>" class="btn">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
