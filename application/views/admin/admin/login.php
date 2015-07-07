      <form class="form-signin span3" method="post" action="<?php echo site_url('/admin/login'); ?>">
        <h2 class="form-signin-heading"></h2>

        <div class="control-group">
          <input type="text" name="username" value="<?php echo (! empty($username) ? $username : ''); ?>" class="input-block-level" placeholder="Email">
        </div>

        <div class="control-group">
          <input type="password" name="password" class="input-block-level" placeholder="Senha">
        </div>

        <div class="control-group clearfix">
          <button class="btn btn-large btn-primary hidden-phone pull-right" type="submit">Login</button>
          <button class="btn btn-medium btn-primary visible-phone pull-right" type="submit">Login</button>
        </div>
      </form>
