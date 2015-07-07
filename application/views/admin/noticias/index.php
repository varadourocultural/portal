        <?php echo form_open(site_url('admin/noticias/delete_many')); ?>
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
                <th class="column-actions">Ações</th>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($noticias as $noticia):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="noticias[]" class="checkbox-user" value="<?php echo $noticia->id; ?>"></td>

                <td><?php echo $noticia->titulo; ?></td>
                <td><?php echo date('d/m/Y', strtotime($noticia->data)); ?></td>

                <td class="column-actions">
                  <div class="btn-group">
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/noticias/sort/{$noticia->id}/up"); ?>';"><i class="icon-chevron-up"></i></button>
                    <button class="btn down" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/noticias/sort/{$noticia->id}/down"); ?>';"><i class="icon-chevron-down"></i></button>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/noticias/edit/{$noticia->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/noticias/delete/{$noticia->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
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
