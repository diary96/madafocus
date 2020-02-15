<!DOCTYPE html>
<?php
// Inclusion des header et sidebar
$this->element('header');
$this->element('sidebar');
?>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= $this->fetch('meta') ?>
        <title><?= h($this->fetch('title')) ?></title>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!-- début css communs -->
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('/bootstrap/css/bootstrap.min.css') ?>
        <?= $this->Html->css('/font-awesome/css/font-awesome.min.css') ?>
        <?= $this->Html->css('/Ionicons/css/ionicons.min.css') ?>
        <!-- fin css communs -->
        <!-- début css spécifiques -->
        <?= $this->fetch('css') ?>
        <!-- fin css spécifiques -->
        <!-- début css communs : ces deux styles doivent être appelés en dernier -->
        <?= $this->Html->css('/rsto/css/AdminLTE.css') ?>
        <?= $this->Html->css('/rsto/css/skins/skin-green.min') ?>
        <!-- fin css spécifiques -->
    </head>
    <body class="skin-green sidebar-mini wysihtml5-supported">
        <?= $this->fetch('rsto_header') ?>
        <?= $this->fetch('rsto_sidebar') ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?= $rsto_page_infos['title'] ?>
                    <small><?= $rsto_page_infos['subtitle'] ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-home"></i></a></li>
                    <li class="active">Page actuelle</li>
                </ol>
            </section>
            <section class="content">
                <?= $this->fetch('content') ?>
            </section>
        </div>
        <!-- début scripts communs -->
        <?= $this->Html->script('/jquery/jquery.min') ?>
        <?= $this->Html->script('/bootstrap/js/bootstrap.min') ?>
        <?= $this->Html->script('/rsto/js/adminlte.min') ?>
        <?= $this->Html->script('/rsto/js/rsto') ?>
        <!-- fin scripts communs -->
        <!-- début scripts spécifiques -->
        <?= $this->fetch('script') ?>
        <!-- fin scripts spécifiques -->
    </body>
</html>
