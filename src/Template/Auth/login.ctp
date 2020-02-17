<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Bienvenue sur RSTO</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?= $this->Html->meta('icon') ?>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $this->Html->css('/bootstrap/css/bootstrap.min.css') ?>
        <?= $this->Html->css('/font-awesome/css/font-awesome.min.css') ?>
        <?= $this->Html->css('/Ionicons/css/ionicons.min.css') ?>
        <?= $this->Html->css('/rsto/css/AdminLTE.min.css') ?>
        <?= $this->Html->css('/iCheck/square/blue.css') ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <p><b>Madafocus</b> RSTO</p>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg"><?= __("Veuillez vous authentifier") ?></p>
                <?= $this->flash->render() ?>
                <form action="<?= $authentication_url ?>" method="post">
                    <input type="hidden" name="_csrfToken" value="<?= $x_csrf_token ?>"/>
                    <div class="form-group has-feedback">
                        <input name="username" type="text" class="form-control" placeholder="<?= __("Nom d'utilisateur") ?>">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="secret" type="password" class="form-control" placeholder="<?= __("Mot de passe") ?>">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox"><?= __("Se souvenir de moi") ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-primary btn-block btn-flat"><?= __("S'authentifier") ?></button>
                        </div>
                    </div>
                </form>

                <a href="#"><?= __("Mot de passe oublié") ?></a><br>

            </div>
        </div>
        <?= $this->Html->script('/jquery/jquery.min.js') ?>
        <?= $this->Html->script('/bootstrap/js/bootstrap.min.js') ?>
        <?= $this->Html->script('/iCheck/icheck.min.js') ?>
        <?= $this->Html->script('/rsto/js/rsto.js') ?>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });
        </script>
    </body>
</html>

