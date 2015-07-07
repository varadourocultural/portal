        <?php echo form_open(site_url('admin/usuarios/delete_many')); ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os usuários selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
              <thead>
                  <tr>
                      <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                      <th>Nome</th>
                      <th class="hidden-phone">Email</th>
                      <th class="column-actions">Ações</th>
                  </tr>
              </thead>

              <tbody>
                  <?php
                  foreach ($usuarios as $usuario):
                  ?>
                  <tr>
                      <td class="checkbox-container"><input type="checkbox" name="usuarios[]" class="checkbox-user" value="<?php echo $usuario->id; ?>"></td>

                      <td>
                          <?php echo $usuario->nome; ?>
                      </td>

                      <td  class="hidden-phone">
                          <?php echo $usuario->email; ?>
                      </td>

                      <td class="column-actions">
                        <div class="btn-group">
                          <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/usuarios/edit/{$usuario->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                          <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/usuarios/delete/{$usuario->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
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
