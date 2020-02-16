<?php
$this->element('place-modal');
$this->element('park-selling-entrance-fee-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.parks', ['block' => true]);
?>
<?php if (CAN_7_2 || CAN_7_3) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
            <?php if (CAN_7_2) : ?>
                <button class="btn btn-app" id="rsto-park-add-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-hotel-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
            <?php endif ?>
            <?php if (CAN_7_3) : ?>
                <button class="btn btn-app disabled" id="rsto-park-selling-entrance-fees-btn" disabled>
                    <i class="fa fa-money"></i> <?= __('Prices') ?>
                </button>
            <?php endif ?>
            <?php if (CAN_7_2) : ?>
                <button class="btn btn-app disabled" id="rsto-park-guidance-btn" disabled>
                    <i class="fa fa-user-circle"></i> <?= __('Guidances') ?>
                </button>
            <?php endif ?>
            <?php if (CAN_7_2) : ?>
                <button class="btn btn-app disabled" id="rsto-park-delete-btn" data-url="<?= $rsto_park_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_7_1) : ?>
    <div id="rsto-park-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Parks') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-park-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_park_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <th><?= __('Place') ?></th>
                        <th><?= __('Adult entrance fee') ?></th>
                        <th><?= __('Children entrance fee') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade rsto-modal" id="rsto-park-selling-entrance-fee-list-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title"><?= __("New Park") ?></h4>
                </div>
                <div class="modal-body">
                    <table id="rsto-park-selling-entrance-fee-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_park_selling_entrance_fee_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                        <thead>
                            <tr>
                                <th><?= __('Currency') ?></th>
                                <th><?= __('Adult entrance fee') ?></th>
                                <th><?= __('Children entrance fee') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-left" id="rsto-park-selling-entrance-fee-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                    <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-park-selling-entrance-fee-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                    <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-park-selling-entrance-fee-delete-btn" data-url="<?= $rsto_park_selling_entrance_fee_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_7_2) : ?>
    <div class="modal fade rsto-modal" id="rsto-park-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New Park") ?></h4>
                </div>
                <form id="rsto-park-form" name="rsto-park-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_park_add_url ?>" data-edit-url="<?= $rsto_park_edit_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-park-name"><?= __('Name') ?></label>
                            <input name="name" type="text" class="form-control remote-validation" data-required="true" id="rsto-park-name" placeholder="<?= __("Enter park name") ?>" data-validation-url="<?= $rsto_park_name_validation_url ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-park-place"><?= __('Place') ?></label>
                            <?php if (CAN_4_2) : ?>
                                <div class="row">
                                    <div class="col-xs-10">
                                    <?php endif; ?>
                                    <select name="place" class="form-control rsto-select2" data-required="true" id="rsto-park-place" data-url="<?= $rsto_park_place_select2_url ?>" data-placeholder="<?= __('Choose a place') ?>"></select>
                                    <?php if (CAN_4_2) : ?>
                                    </div>
                                    <div class="col-xs-2" style="padding-left: 0">
                                        <button class="btn btn-default btn-block" id="rsto-park-add-new-place"><i class="fa fa-plus"></i>&nbsp;New</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rsto-park-adult-costing-entrance-fee"><?= __('Adult entrance fee') ?></label>
                            <input name="adult_costing_entrance_fee" type="text" class="form-control numeric-validation" data-required="true" id="rsto-park-adult-costing-entrance-fee" placeholder="<?= __("Enter adult costing entrance fee") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-park-children-costing-entrance-fee"><?= __('Adult entrance fee') ?></label>
                            <input name="children_costing_entrance_fee" type="text" class="form-control numeric-validation" data-required="true" id="rsto-park-children-costing-entrance-fee" placeholder="<?= __("Enter children costing entrance fee") ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-park-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (CAN_4_2) : ?>
    <?= $this->fetch('rsto_place_modal'); ?>
<?php endif; ?>
<?php if (CAN_7_3) : ?>
    <?= $this->fetch('rsto_park_selling_entrance_fee_modal'); ?>
<?php endif; ?>
