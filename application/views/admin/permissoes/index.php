        <?php echo form_open(site_url('admin/permissoes/delete_many')); ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os registros selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                <th>Ação</th>
                <th>Nível</th>
                <th>Permitir</th>
                <th class="column-actions">Ações</th>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($permissoes as $permissao):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="permissoes[]" class="checkbox-user" value="<?php echo $permissao->id; ?>"></td>

                <td><?php echo $permissao->acao; ?></td>
                <td><?php echo $permissao->nivel; ?></td>
                <td><?php echo (intval($permissao->permitir) ? 'Sim' : 'Não'); ?></td>
                
                <td class="column-actions">
                  <div class="btn-group">
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/permissoes/sort/{$permissao->id}/up"); ?>';"><i class="icon-chevron-up"></i></button>
                    <button class="btn down" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/permissoes/sort/{$permissao->id}/down"); ?>';"><i class="icon-chevron-down"></i></button>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/permissoes/edit/{$permissao->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/permissoes/delete/{$permissao->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
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
