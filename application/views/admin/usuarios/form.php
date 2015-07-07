    <?php if (validation_errors()): ?>
      <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

        <?php echo validation_errors(); ?>
      </div>
    <?php endif; ?>

      <?php echo form_open('', 'role="form"'); ?>

        <label class="checkbox">
          <?php echo form_checkbox('ativo', 1, $usuario->ativo, 'id="ativoInput" class=""'); ?> Ativo
        </label>

        <label for="nomeInput">Nome</label>
        <?php echo form_input('nome', $usuario->nome, 'id="nomeInput" placeholder="Nome"'); ?>

        <?php if($usuario->username): ?>
        <label for="sobrenomeInput">Sobrenome</label>
        <?php echo form_input('sobrenome', $usuario->sobrenome, 'id="sobrenomeInput" placeholder="Sobrenome"'); ?>

        <label for="usernameInput">Nome de usuário</label>
        <?php echo form_input('username', $usuario->username, 'id="usernameInput" placeholder="Nome de usuário"'); ?>
        <?php endif; ?>

        <label for="emailInput">Email</label>
        <?php echo form_input('email', $usuario->email, 'id="emailInput" placeholder="Email"'); ?>

        <label for="senhaInput">Senha</label>
        <?php echo form_password('senha', '', 'id="senhaInput"'); ?>

        <label for="confirmeSenhaInput">Confirme a senha</label>
        <?php echo form_password('confirmasenha', '', 'id="confirmeSenhaInput"'); ?>

        <label for="nivelInput">Nível</label>
        <?php echo form_input('nivel', $usuario->nivel, 'id="nivelInput" placeholder="Nível"'); ?>

        <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="Salvar"/>
            <a href="<?php echo site_url('admin/usuarios'); ?>" class="btn">Cancelar</a>
        </div>

      <?php echo form_close(); ?>
