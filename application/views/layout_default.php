<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">

        <title><?php echo @$title; ?></title>
        <meta name="description" content="<?php echo @$meta_description; ?>">
        <meta name="viewport" content="width=1160">

        <meta property="og:title" content="<?php echo @$title; ?>">
        <meta property="og:description" content="<?php echo @$meta_description; ?>">
        <meta property="og:site_name" content="Varadouro Cultural">
        <meta property="og:image" content="">

        <!--[if (gt IE 8) | (IEMobile)]><!-->
            <link rel="stylesheet" href="<?php echo base_url('/css/main.css'); ?>">
        <!--<![endif]-->

        <!--[if (lt IE 9) & (!IEMobile)]>
            <link rel="stylesheet" href="<?php echo base_url('/css/main-ie.css'); ?>">
        <![endif]-->

        <script src="<?php echo base_url('/js/vendor/modernizr/modernizr.js'); ?>"></script>
        <?php $this->load->view('_partials/jsvars.php'); ?>

    </head>
    <body data-namespace="<?php echo @$layout_namespace; ?>">

        <?php $this->load->view('_partials/_header.php'); ?>

        <main class="<?php echo 'main ' . @$main_class; ?>" role="main">

            <?php
                echo $content_for_layout;
            ?>

        </main>

        <?php $this->load->view('_partials/_footer.php'); ?>

    </body>
</html>
