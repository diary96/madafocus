<?php $this->start('rsto_header') ?>
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">RSTO</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Madafocus</b> RSTO</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only"><?= __('Réduire le menu') ?></span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <!--span class="label label-success">0</span-->
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= h(__('Vous avez {0} message(s)', 0)) ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><a style="text-align: center"><?= __("Vous n'avez aucun message") ?></a></li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#"><?= __('Voir tous les messages') ?></a></li>
                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <!--span class="label label-warning">0</span-->
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= __('Vous avez {0} notification(s)', 0) ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><a style="text-align:  center"><?= __('(Aucun)') ?></a></li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#"><?= __('Voir toutes les notifications') ?></a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <!--span class="label label-danger">9</span-->
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= __('Vous avez {0} tâches(s)', 0) ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><a style="text-align:  center"><?= __('(Aucun)') ?></a></li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#"><?= __('Voir toutes les tâches') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?=
                        $this->Html->image('user-160x160.jpg', [
                            'alt' => 'User image',
                            'class' => 'user-image'
                        ]);
                        ?>
                        <span class="hidden-xs"><?= $rsto_logged_user_fullname ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?=
                            $this->Html->image('user-160x160.jpg', [
                                'alt' => 'User image',
                                'class' => 'img-circle'
                            ]);
                            ?>
                            <p>
                                <?= $rsto_logged_user_fullname ?>
                                <small><?= $rsto_logged_user_groupname ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat"><?= __('Mon compte') ?></a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= $rsto_logout_url ?>" class="btn btn-default btn-flat"><?= __('Déconnexion') ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<?php $this->end() ?>