        <?php
            if (! empty($atributo_ascendente)):
        ?>
        <?php echo form_open(site_url("admin/atributos-descendentes/delete_many")); ?>
        <?php
            else:
        ?>
        <?php echo form_open(site_url('admin/atributos/delete_many')); ?>
        <?php
            endif;
        ?>
          <div id="removeAll" class="well well-small hide">
            <p>Deseja remover todos os registros selecionados?</p>
            <button type="submit" class="btn btn-danger">Remover todos</button>
            <button type="button" id="hide-container" class="btn">Cancelar</a>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="checkbox-container"><input type="checkbox" id="checkall"></th>
                <th>Nome</th>
                <th class="column-actions">Ações</th>
              </tr>
            </thead>

            <tbody>
              <?php
                  foreach ($atributos as $atributo):
              ?>
              <tr>
                <td class="checkbox-container"><input type="checkbox" name="atributos[]" class="checkbox-user" value="<?php echo $atributo->id; ?>"></td>

                <td><?php echo $atributo->nome; ?></td>

                <td class="column-actions">
                  <div class="btn-group">
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/atributos/sort/{$atributo->id}/up"); ?>';"><i class="icon-chevron-up"></i></button>
                    <button class="btn down" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/atributos/sort/{$atributo->id}/down"); ?>';"><i class="icon-chevron-down"></i></button>
                    <?php
                        if (! empty($atributo_ascendente)):
                    ?>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/atributos-descendentes/edit/{$atributo->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/atributos-descendentes/delete/{$atributo->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
                    <?php
                        else:
                    ?>
                    <button type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/atributos/edit/{$atributo->id}"); ?>';"  class="btn btn-primary"><span class="hidden-phone hidden-tablet">Editar</span><i class="hidden-desktop icon-white icon-pencil"></i></button>
                    <button type="button" onclick="javascript:if(window.confirm('Deseja remover este registro?')) window.location.href ='<?php echo site_url("admin/atributos/delete/{$atributo->id}"); ?>'; else return false;" class="btn btn-danger"><span class="hidden-phone hidden-tablet">Remover</span><i class="hidden-desktop icon-white icon-trash"></i></button>
                    <?php
                        endif;
                    ?>
                    <button class="btn up" type="button" onclick="javascript:window.location.href ='<?php echo site_url("admin/atributos-descendentes/{$atributo->id}"); ?>';">Descendentes</button>
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
