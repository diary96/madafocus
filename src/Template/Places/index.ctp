<?php
$this->element('place-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min.js', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min.js', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap.css', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min.js', ['block' => true]);
$this->Html->css('/select2/css/select2.min.css', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.places', ['block' => true]);
?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Zone list') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" id="rsto-zones">
                    <li class="active">
                        <a href="#" data-id="all" class="rsto-zone">
                            <i class="fa fa-arrow-circle-right"></i>
                            All
                        </a>
                    </li>
                    <?php foreach ($rsto_zones as $_rsto_zone) : ?>
                        <li>
                            <a href="#" data-id="<?= $_rsto_zone->id ?>" class="rsto-zone">
                                <i class="fa fa-arrow-circle-right"></i>
                                <?= $_rsto_zone->name ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
            </div>
            <div class="box-body">
                <button class="btn btn-app" id="rsto-add-place-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-edit-place-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-delete-place-btn" data-url="<?= $rsto_places_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
            </div>
        </div>
        <div id="rsto-users-datatable-box" class="box">
            <div class="box-header">
                <h3 class="box-title"><?= __('Places') ?></h3>
            </div>
            <div class="box-body">
                <table id="rsto-places-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_places_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Parent') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>  
<?= $this->fetch('rsto_place_modal') ?>
<?php $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
$(window).on('load', function(){
    RSTOPlaces.init();
});
<?php $this->Html->scriptEnd() ?>
