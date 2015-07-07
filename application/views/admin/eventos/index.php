        <?php echo form_open(site_url("admin/espacos-culturais/{$espaco_cultural}/eventos/delete_many")); ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os registros selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                <th>Título</th>
                <th>Data</th>
              <?php if (checar_permissao('espacos_culturais.eventos.*', NULL)): ?>
                <th class="column-actions">Ações</th>
              <?php endif ?>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($eventos as $evento):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="eventos[]" class="checkbox-user" value="<?php echo $evento->id; ?>"></td>

                <td><?php echo $evento->titulo; ?></td>
                <td><?php echo $evento->informacoes_datas; ?></td>

              <?php if (checar_permissao('espacos_culturais.eventos.*', NULL)): ?>
                <td class="column-actions">
                  <div class="btn-group">
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/espacos-culturais/{$espaco_cultural}/eventos/edit/{$evento->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/espacos-culturais/{$espaco_cultural}/eventos/delete/{$evento->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
                  </div>
                </td>
              <?php endif ?>
              </tr>
              <?php
                  endforeach;
              ?>
            </tbody>
          </table>
        <?php echo form_close(); ?>

        <div class="pagination pagination-centered">
          <?php echo $pagination; ?>
        </div>
