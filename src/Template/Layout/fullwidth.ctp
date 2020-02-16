<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= h($this->fetch('title')) ?></title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('/bootstrap/css/bootstrap.min') ?>
        <?= $this->Html->css('/font-awesome/css/font-awesome.min') ?>
        <?= $this->Html->css('/Ionicons/css/ionicons.min') ?>
        <?= $this->fetch('css') ?>

        <?= $this->Html->css('/rsto/css/AdminLTE') ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body>
        <div class="wrapper">
            <?= $this->fetch('content') ?>
        </div>
        <!-- ./wrapper -->
        <?= $this->Html->script('/jquery/jquery.min') ?>
        <?= $this->Html->script('/bootstrap/js/bootstrap.min') ?>
        <?= $this->Html->script('/rsto/js/adminlte.min') ?>
        <?= $this->Html->script('/rsto/js/rsto') ?>
        <!-- fin scripts communs -->
        <!-- début scripts spécifiques -->
        <?= $this->fetch('script') ?>
    </body>
</html>
