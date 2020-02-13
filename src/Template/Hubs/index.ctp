<?php
$this->element('place-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.hubs', ['block' => true]);

?>
<?php if(CAN_6_2) : ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
    </div>
    <div class="box-body">
        <button class="btn btn-app" id="rsto-hub-add-btn">
            <i class="fa fa-plus"></i> <?= __('New') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-hotel-edit-btn" disabled>
            <i class="fa fa-edit"></i> <?= __('Edit') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-hub-delete-btn" data-url="<?= $rsto_hub_delete_url ?>" disabled>
            <i class="fa fa-trash"></i> <?= __('Delete') ?>
        </button>
    </div>
</div>
<?php endif; ?>
<?php if(CAN_6_1) : ?>
<div id="rsto-hub-datatable-box" class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('Hubs') ?></h3>
    </div>
    <div class="box-body">
        <table id="rsto-hub-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_hub_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
            <thead>
                <tr>
                    <th><?= __('Name') ?></th>
                    <th><?= __('type') ?></th>
                    <th><?= __('Place') ?></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php endif; ?>
<?php if(CAN_6_2) : ?>
<div class="modal fade rsto-modal" id="rsto-hub-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New hub") ?></h4>
            </div>
            <form id="rsto-hub-form" name="rsto-hub-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_hub_add_url ?>" data-edit-url="<?= $rsto_hub_edit_url ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hub-type"><?= __('Type') ?></label>
                        <select name="type" class="form-control rsto-select2" data-required="true" id="rsto-hub-type" data-url="<?= $rsto_hub_type_select2_url ?>" data-placeholder="<?= __('Choose a type') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hub-place"><?= __('Place') ?></label>
                        <?php if(CAN_4_2) : ?>
                        <div class="row">
                            <div class="col-xs-10">
                        <?php endif; ?>
                                <select name="place" class="form-control rsto-select2" data-required="true" id="rsto-hub-place" data-url="<?= $rsto_hub_place_select2_url ?>" data-placeholder="<?= __('Choose a place') ?>"></select>
                        <?php if(CAN_4_2) : ?>
                            </div>
                            <div class="col-xs-2" style="padding-left: 0">
                                <button class="btn btn-default btn-block" id="rsto-hub-add-new-place"><i class="fa fa-plus"></i>&nbsp;New</button>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hub-name"><?= __('Name') ?></label>
                        <input name="name" type="text" class="form-control remote-validation" data-required="true" id="rsto-hub-name" placeholder="<?= __("Enter the hotel name") ?>" data-validation-url="<?= $rsto_hub_name_validation_url ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if(CAN_4_2) : ?>
<?= $this->fetch('rsto_place_modal'); ?>
<?php endif; ?>
