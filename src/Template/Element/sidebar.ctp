<?php
/**
 * Ce block contient le formulaire de recherche
 */
$_sidebar_groups_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['groups'];
$_sidebar_users_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['users'];
$_sidebar_selects_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['selects'];
$_sidebar_places_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['places'];
$_sidebar_hotels_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['hotels'];
$_sidebar_hubs_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['hubs'];
$_sidebar_parks_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['parks'];
$_sidebar_directory_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['directory'];
$_sidebar_carriers_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['carriers'];
$_sidebar_services_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['services'];
$_sidebar_circuits_is_active = $rsto_sidebar_urls['current'] === $rsto_sidebar_urls['circuits'];
$this->start('rsto_sidebar_user_panel')
?>
<div class="user-panel">
    <div class="pull-left image">
        <?= 
            $this->Html->image('user-160x160.jpg', [
                'alt' => 'User image',
                'class' => 'img-circle'
            ]);
        ?>
    </div>
    <div class="pull-left info">
        <p><?= $rsto_logged_user_fullname ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> <?= __('En ligne') ?></a>
    </div>
</div>
<!-- search form -->
<form action="#" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
</form>
<?php $this->end(); ?>

<?php
/**
 * Ce block contient le menu principal
 */
$this->start('rsto_sidebar_main_menu');
?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header"><?= __('MENU') ?></li>
    <li><a href="#"><i class="fa fa-dashboard"></i> <span><?= __('Dashboard') ?></span></a></li>
    <?php $_sidebar_directories_is_open = $_sidebar_places_is_active || $_sidebar_hotels_is_active || $_sidebar_hubs_is_active || $_sidebar_parks_is_active || $_sidebar_directory_is_active || $_sidebar_carriers_is_active || $_sidebar_services_is_active; ?>
    <?php if(CAN_5_1 || CAN_5_2 || CAN_5_3 || CAN_4_1 || CAN_4_2 || CAN_6_1 || CAN_6_2 || CAN_7_1 || CAN_7_2 || CAN_8_1 || CAN_8_2 || CAN_9_1 || CAN_9_2 || CAN_9_3 || CAN_10_1 || CAN_10_2 || CAN_10_3) :  ?>
    <li class="treeview <?php if($_sidebar_directories_is_open): ?>menu-open<?php endif; ?>">
        <a href="#">
            <i class="fa fa-database"></i>
            <span><?= __('Database') ?></span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu" <?php if($_sidebar_directories_is_open): ?>style="display:block"<?php endif; ?>>
            <?php if(CAN_8_1 || CAN_8_2): ?>
            <li <?php if ($_sidebar_directory_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['directory'] ?>"><i class="fa fa-book"></i> <?= __('Directory') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_5_1 || CAN_5_2): ?>
            <li <?php if ($_sidebar_hotels_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['hotels'] ?>"><i class="fa fa-hotel"></i> <?= __('Hotels') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_4_1 || CAN_4_2): ?>
            <li <?php if ($_sidebar_places_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['places'] ?>"><i class="fa fa-map-marker"></i> <?= __('Places') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_6_1 || CAN_6_2): ?>
            <li <?php if ($_sidebar_hubs_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['hubs'] ?>"><i class="fa fa-plane"></i> <?= __('Hubs') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_7_1 || CAN_7_2): ?>
            <li <?php if ($_sidebar_parks_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['parks'] ?>"><i class="fa fa-tree"></i> <?= __('Parks and guidances') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_9_1 || CAN_9_2 || CAN_9_3) : ?>
            <li <?php if ($_sidebar_carriers_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['carriers'] ?>"><i class="fa fa-car"></i> <?= __('Carriers') ?></a></li>
            <?php endif ?>
            <?php if(CAN_10_1 || CAN_10_2 || CAN_10_3) : ?>
            <li <?php if ($_sidebar_services_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['services'] ?>"><i class="fa fa-chevron-circle-right"></i> <?= __('Services') ?></a></li>
            <?php endif ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php if (CAN_11_1 || CAN_11_2) : ?>
    <li <?php if ($_sidebar_circuits_is_active) :?>class="active"<?php endif; ?>>
        <a href="<?= $rsto_sidebar_urls['circuits'] ?>">
            <i class="fa fa-circle-o-notch"></i> <span><?= __('Circuits') ?></span>
        </a>
    </li>
    <?php endif; ?>
    <li>
        <a href="#">
            <i class="fa fa-calendar-plus-o"></i> <span><?= __('Booking') ?></span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="fa fa-money"></i> <span><?= __('Finance') ?></span>
        </a>
    </li>
    <?php $_sidebar_system_is_open = $_sidebar_groups_is_active || $_sidebar_users_is_active || $_sidebar_selects_is_active; ?>
    <?php if(CAN_1_1 || CAN_1_2 || CAN_2_1 || CAN_2_2 || CAN_3_1 || CAN_3_2) : ?>
    <li class="treeview  <?php if($_sidebar_system_is_open): ?>menu-open<?php endif; ?>">
        <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>System</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu" <?php if($_sidebar_system_is_open): ?>style="display:block"<?php endif; ?>>
            <?php if(CAN_3_1 || CAN_3_2): ?>
            <li <?php if ($_sidebar_selects_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['selects'] ?>"><i class="fa fa-list"></i> <?= __('Taxonomy') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_2_1 || CAN_2_2): ?>
            <li <?php if ($_sidebar_users_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['users'] ?>"><i class="fa fa-users"></i> <?= __('Users') ?></a></li>
            <?php endif; ?>
            <?php if(CAN_1_1 || CAN_1_2): ?>
            <li <?php if ($_sidebar_groups_is_active) :?>class="active"<?php endif; ?>><a href="<?= $rsto_sidebar_urls['groups'] ?>"><i class="fa fa-users"></i> <?= __("User groups") ?></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
</ul>
<?php $this->end(); ?>

<?php 
/**
 * Ce block constitue le sidebar gauche
 */
$this->start('rsto_sidebar');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?= $this->fetch('rsto_sidebar_user_panel') ?>
        <?= $this->fetch('rsto_sidebar_main_menu') ?>
    </section>
</aside>
<?php $this->end(); ?>
