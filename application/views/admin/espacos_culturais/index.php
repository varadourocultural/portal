        <?php echo form_open(site_url('admin/espacos_culturais/delete_many')); ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os registros selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                <th>Nome do espaço</th>
              <?php if (checar_permissao('espacos_culturais.*', NULL)): ?>
                <th class="column-actions">Ações</th>
              <?php endif ?>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($espacos_culturais as $espaco_cultural):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="espacos_culturais[]" class="checkbox-user" value="<?php echo $espaco_cultural->id; ?>"></td>

                <td><?php echo $espaco_cultural->nome_espaco; ?></td>

                <td class="column-actions">
                  <div class="btn-group">
                <?php if (checar_permissao('espacos_culturais.*', NULL)): ?>
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/espacos-culturais/sort/{$espaco_cultural->id}/up"); ?>';"><i class="icon-chevron-up"></i></button>
                    <button class="btn down" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/espacos-culturais/sort/{$espaco_cultural->id}/down"); ?>';"><i class="icon-chevron-down"></i></button>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/espacos-culturais/edit/{$espaco_cultural->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/espacos-culturais/delete/{$espaco_cultural->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
                <?php else: ?>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/espacos-culturais/{$espaco_cultural->id}/eventos"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Eventos</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                <?php endif; ?>
                  </div>
                </td>
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
