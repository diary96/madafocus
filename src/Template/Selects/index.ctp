<?php
$this->element('select-option-modal');
$this->element('select-option-group-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min.js', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min.js', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap.css', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min.js', ['block' => true]);
$this->Html->css('/select2/css/select2.min.css', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.selects', ['block' => true]);
?>
<?php if(CAN_3_1) :?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Dropdown list') ?></h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked" id="rsto-selects">
                    <?php foreach ($rstoSelects as $_select) : ?>
                        <li>
                            <a href="#" data-id="<?= $_select->id ?>" class="rsto-select">
                                <i class="fa fa-arrow-circle-right"></i>
                                <?= $_select->name ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9">
        <?php if(CAN_3_2) : ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
            </div>
            <div class="box-body">
                <button class="btn btn-app" id="rsto-select-option-add-btn">
                    <i class="fa fa-plus"></i> <?= __('Add') ?>
                </button>
                <button class="btn btn-app" id="rsto-select-option-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app" id="rsto-select-option-delete-btn" data-url="<?= $rsto_select_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
            </div>
        </div>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= __('Options list') ?></h3>
            </div>
            <div class="box-body">
                <table id="rsto-select-option-datatable" width="100%" class="table table-bordered table-hover table-responsive rsto-datatable" data-url="<?= $rsto_select_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Option') ?></th>
                            <th><?= __('Group') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->fetch('rsto_select_option_modal') ?>
<?= $this->fetch('rsto_select_option_group_modal') ?>