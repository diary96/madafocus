<?php
$this->element('carriers');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.carriers', ['block' => true]);
?>
<?php if (CAN_9_2 || CAN_9_3) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
                <button class="btn btn-app" id="rsto-carrier-add-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-carrier-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-carrier-prices-btn" disabled>
                    <i class="fa fa-money"></i> <?= __('Prices') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-carrier-vehicle-btn" disabled>
                    <i class="fa fa-car"></i> <?= __('Vehicles') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-carrier-drivers-btn" disabled>
                    <i class="fa fa-drivers-license"></i> <?= __('Drivers') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-carrier-delete-btn" data-url="<?= $rsto_carriers_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_9_1) : ?>
    <div id="rsto-carrier-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Carriers') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-carrier-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_carriers_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                    <tr>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Name') ?></th>
                        <th><?= __('Phone number') ?></th>
                        <th><?= __('Email address') ?></th>
                        <th><?= __('Description') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_9_2) : ?>
    <div class="modal fade rsto-modal" id="rsto-carrier-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New carrier") ?></h4>
                </div>
                <form id="rsto-carrier-form" name="rsto-carrier-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_carriers_add_url ?>" data-edit-url="<?= $rsto_carriers_edit_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-carrier-title"><?= __('Title') ?></label>
                            <select name="title" class="form-control rsto-select2" data-required="true" id="rsto-directory-title" data-url="<?= $rsto_carriers_title_select2 ?>" data-placeholder="<?= __("Choose a title") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-carrier-fullname"><?= __('Fullname') ?></label>
                            <input name="fullname" type="text" class="form-control remote-validation" data-required="true" id="rsto-directory-fullname" placeholder="<?= __("Enter fullname") ?>" data-validation-url="<?= $rsto_carriers_validate_fullname_url ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-carrier-description"><?= __('Description') ?></label>
                            <textarea name="description" rows="7" type="text" class="form-control" data-required="true" id="rsto-directory-description" placeholder="<?= __("Enter textarea") ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rsto-carrier-booking-phone-number"><?= __('Booking phone number') ?></label>
                            <input name="booking_phone_number" type="text" class="form-control phone-number-validation" data-required="true" id="rsto-carrier-booking-phone-number" placeholder="<?= __("Enter booking phone number") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-carrier-booking-email-address"><?= __('Booking email address') ?></label>
                            <input name="booking_mail_address" type="text" class="form-control email-validation" id="rsto-carrier-booking-email-address" placeholder="<?= __("Enter booking email address, you can left empty") ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<?= $this->fetch('rsto_carrier_vehicle_price_list_modal') ?>
<?= $this->fetch('rsto_carrier_vehicle_price_modal') ?>
<?= $this->fetch('rsto_carrier_vehicle_selling_price_list_modal') ?>
<?= $this->fetch('rsto_carrier_vehicle_selling_price_modal') ?>
<?= $this->fetch('rsto_carrier_vehicle_list_modal') ?>
<?= $this->fetch('rsto_carrier_vehicle_modal') ?>
<?= $this->fetch('rsto_carrier_driver_list_modal') ?>
<?= $this->fetch('rsto_carrier_driver_modal') ?>