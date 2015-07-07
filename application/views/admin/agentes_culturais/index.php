        <?php echo form_open(site_url('admin/agentes-culturais/delete_many')); ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os registros selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                <th>Nome responsável</th>
              <?php if (checar_permissao('agentes_culturais.*', NULL)): ?>
                <th class="column-actions">Ações</th>
              <?php endif ?>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($agentes_culturais as $agente_cultural):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="agentes_culturais[]" class="checkbox-user" value="<?php echo $agente_cultural->id; ?>"></td>

                <td><?php echo $agente_cultural->nome_responsavel; ?></td>

              <?php if (checar_permissao('agentes_culturais.*', NULL)): ?>
                <td class="column-actions">
                  <div class="btn-group">
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/agentes-culturais/sort/{$agente_cultural->id}/up"); ?>';"><i class="icon-chevron-up"></i></button>
                    <button class="btn down" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/agentes-culturais/sort/{$agente_cultural->id}/down"); ?>';"><i class="icon-chevron-down"></i></button>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/agentes-culturais/edit/{$agente_cultural->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/agentes-culturais/delete/{$agente_cultural->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
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
