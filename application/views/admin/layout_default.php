<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <meta name="viewport" content="width=960">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?php echo base_url('css/admin/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/admin/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/admin/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/admin/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('css/admin/style.css" rel="stylesheet'); ?>">

  <!--[if lt IE 9]>
    <script src="<?php echo base_url('js/admin/html5shiv.js'); ?>"></script>
  <![endif]-->

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRbjb8EyQsaXJsi45qsBqfPBWCLx33SoU&amp;sensor=true"></script>

    <?php $this->load->view('admin/_partials/jsvars'); ?>
  </head>

  <body>

    <header>
      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container-fluid">

            <a class="brand btn btn-small" href="<?php echo site_url('admin'); ?>">Admin</a>

            <ul class="nav">
              <li class="<?php if ($this->uri->segment(2) == 'espacos-culturais'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/espacos-culturais'); ?>">Espaços Culturais</a>
              </li>

              <li class="<?php if ($this->uri->segment(2) == 'agentes-culturais'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/agentes-culturais'); ?>">Agentes Culturais</a>
              </li>

            <?php if(checar_permissao('atributos.*', NULL)): ?>
              <li class="<?php if (($this->uri->segment(2) == 'atributos') || ($this->uri->segment(2) == 'atributos-descendentes')): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/atributos'); ?>">Atributos</a>
              </li>
            <?php endif; ?>


            <?php if(checar_permissao('noticias.listar_registros*', NULL)): ?>
              <li class="<?php if ($this->uri->segment(2) == 'noticias'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/noticias'); ?>">Notícias</a>
              </li>
            <?php endif; ?>


            <?php if(checar_permissao('usuarios.*', NULL)): ?>
              <li class="<?php if ($this->uri->segment(2) == 'usuarios'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/usuarios'); ?>">Usuários</a>
              </li>

              <li class="<?php if ($this->uri->segment(2) == 'sobre'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/sobre'); ?>">Sobre</a>
              </li>

              <li class="<?php if ($this->uri->segment(2) == 'projetos'): ?>active<?php endif; ?>">
                <a href="<?php echo site_url('admin/projetos'); ?>">Projetos</a>
              </li>
            <?php endif; ?>
            </ul>

          <?php if(checar_permissao('usuarios.*', NULL)): ?>
            <div class="pull-right">
              <ul class="nav">
                <li>
                  <a href="<?php echo site_url('/admin/logout'); ?>">Sair</a>
                </li>
              </ul>
            </div>
          <?php endif;?>

          </div>
        </div>
      </div>

    </header>

    <div class="container-fluid">

      <ul class="breadcrumb">
      <?php
          if (count($breadcrumbs) > 5):
              $breadcrumbs = array_slice($breadcrumbs, -5, 5);
      ?>
        <li class="active">... <span class="divider">/</span></li>
      <?php
          endif;
      ?>

        <?php
            for ($i = 0; $i < count($breadcrumbs) - 1; $i++):
        ?>
        <li><a href="<?php echo $breadcrumbs[$i][1]; ?>"><?php echo $breadcrumbs[$i][0]; ?></a> <span class="divider">/</span></li>
        <?php
            endfor;
        ?>

        <li class="active"><?php echo $breadcrumbs[count($breadcrumbs) - 1][0]; ?></li>

        <?php
            foreach ($navbar as $opt):
        ?>
        <?php echo $opt; ?>
        <?php
            endforeach;
        ?>
      </ul>

      <?php
          if ($this->session->flashdata('error')):
      ?>
        <div class="alert alert-error">
          <button type="button" class="close" data-dismiss="alert">&times;</button>

          <?php echo $this->session->flashdata('error'); ?>
        </div>
      <?php
          endif;
      ?>

      <?php
          if ($this->session->flashdata('success')):
      ?>
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>

          <?php echo $this->session->flashdata('success'); ?>
        </div>
      <?php
          endif;
      ?>

      <?php
          echo $content_for_layout;
      ?>

    </div>

    <script src="<?php echo base_url('js/admin/jquery-1.10.2.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/bootstrap-datepicker.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/bootstrap-datepicker.pt-BR.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/jquery.iframe-transport.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/ckeditor/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/ckeditor/adapters/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/jquery.slugit.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/main.js'); ?>"></script>

  </body>
</html>
