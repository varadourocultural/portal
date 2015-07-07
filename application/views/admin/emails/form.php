    <?php if (validation_errors()): ?>
      <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

        <?php echo validation_errors(); ?>
      </div>
    <?php endif; ?>

      <?php echo form_open('', 'role="form" class="well well-small"'); ?>

        <label class="checkbox">
          <?php echo form_checkbox('ativo', 1, $email->ativo, 'id="ativoInput" class=""'); ?> Ativo
        </label>

        <label for="nomeInput">Nome</label>
        <?php echo form_input('nome', $email->nome, 'id="nomeInput" placeholder="Nome"'); ?>

        <label for="emailInput">Email</label>
        <?php echo form_input('email', $email->email, 'id="emailInput" placeholder="Email"'); ?>

        <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="Salvar"/>
            <a href="<?php echo site_url("admin/emails"); ?>" class="btn">Cancelar</a>
        </div>

      <?php echo form_close(); ?>
