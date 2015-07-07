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
    <link href="<?php echo base_url('css/admin/style.css" rel="stylesheet'); ?>">

  <!--[if lt IE 9]>
    <script src="<?php echo base_url('js/admin/html5shiv.js'); ?>"></script>
  <![endif]-->

  </head>

  <body>

    <header>

      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container-fluid">
            <a class="brand btn btn-small" href="<?php echo site_url('admin'); ?>">Admin</a>
          </div>
        </div>
      </div>

    </header>

    <div class="container-fluid">

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
          <button type="button" class="close" data-dismiss="alert">&times;</button>

          <?php echo $this->session->flashdata('error'); ?>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>

          <?php echo $this->session->flashdata('success'); ?>
        </div>
      <?php endif; ?>

      <?php
          echo $content_for_layout;
      ?>

    </div>

    <script src="<?php echo base_url('js/admin/jquery-1.10.2.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/admin/main.js'); ?>"></script>

  </body>
</html>
