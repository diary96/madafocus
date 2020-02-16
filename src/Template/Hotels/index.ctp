<?php
$this->element('hotel-transfer-list-modal');
$this->element('hotel-transfer-modal');
$this->element('hotel-transfer-selling-price-list-modal');
$this->element('hotel-transfer-selling-price-modal');
$this->element('place-modal');
$this->element('hotel-room-list-modal');
$this->element('hotel-room-modal');
$this->element('hotel-room-selling-price-list-modal');
$this->element('hotel-room-selling-price-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.places', ['block' => true]);
$this->Html->script('/rsto/js/rsto.hotels', ['block' => true]);
//$this->Html->script('/rsto/js/rsto.hotelrooms.add', ['block' => true]);

?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
    </div>
    <div class="box-body">
        <?php if(CAN_5_2) : ?>
        <button class="btn btn-app" id="rsto-add-hotel-btn">
            <i class="fa fa-plus"></i> <?= __('New') ?>
        </button>
        <?php endif; ?>
        <button class="btn btn-app disabled" id="rsto-manage-hotel-rooms-btn" disabled>
            <i class="fa fa-bed"></i> <?= __('Rooms') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-manage-hotel-transfers-btn" disabled>
            <i class="fa fa-taxi"></i> <?= __('Transfers') ?>
        </button>
        <?php if(CAN_5_2) : ?>
        <button class="btn btn-app disabled" id="rsto-edit-hotel-btn" disabled>
            <i class="fa fa-edit"></i> <?= __('Edit') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-delete-hotel-btn" data-url="<?= $rsto_hotel_delete_url ?>" disabled>
            <i class="fa fa-trash"></i> <?= __('Delete') ?>
        </button>
        <?php endif; ?>
    </div>
</div>
<div id="rsto-hotel-datatable-box" class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('Places') ?></h3>
    </div>
    <div class="box-body">
        <table id="rsto-hotels-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_hotel_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
            <thead>
                <tr>
                    <th><?= __('Name') ?></th>
                    <th><?= __('Place') ?></th>
                    <th><?= __('Phone number') ?></th>
                    <th><?= __('Email address') ?></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade rsto-modal" id="rsto-hotel-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New hotel") ?></h4>
            </div>
            <form id="rsto-hotel-form" name="rsto-hotel-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_hotel_add_url ?>" data-edit-url="<?= $rsto_hotel_edit_url ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hotel-name"><?= __('Name') ?></label>
                        <input name="name" type="text" class="form-control remote-validation" data-required="true" id="rsto-hotel-name" placeholder="<?= __("Enter the hotel name") ?>" data-validation-url="<?= $rsto_hotel_name_validation_url ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-place"><?= __('Place') ?></label>
                        <div class="row">
                            <div class="col-xs-10">
                                <select name="place" class="form-control rsto-select2" data-required="true" id="rsto-hotel-place" data-url="<?= $rsto_hotel_place_select2_url ?>" data-placeholder="<?= __('Choose a place') ?>"></select>
                            </div>
                            <div class="col-xs-2" style="padding-left: 0">
                                <button class="btn btn-default btn-block" id="rsto-hotel-add-new-place"><i class="fa fa-plus"></i>&nbsp;New</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-phone-number"><?= __('Phone number') ?></label>
                        <input name="reservation_phone_number" class="form-control phone-number-validation" data-required="true" type="text" id="rsto-hotel-reservation-phone-number" placeholder="<?= __("Enter the hotel reservation phone number") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-reservation-email-address"><?= __('Email address') ?></label>
                        <input name="reservation_email_address" class="form-control email-validation" data-required="true" type="text" id="rsto-hotel-reservation-email-address" placeholder="<?= __("Enter the hotel reservation email address") ?>">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="rsto-hotel-have-restaurant">
                                <input type="checkbox" name="have_restaurant" id="rsto-hotel-have-restaurant"/>
                                This hotel have a restaurant
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="rsto-hotel-do-tranfer">
                                <input type="checkbox" name="do_transfer" id="rsto-hotel-do-tranfer"/>
                                This hotel do transfers
                            </label>
                        </div>
                        <span class="help-block"><?= _('You can manage transfers later.') ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->fetch('rsto_transfer_list_modal') ?>
<?= $this->fetch('rsto_hotel_transfer_modal') ?>
<?= $this->fetch('rsto_hotel_transfer_selling_price_list_modal') ?>
<?= $this->fetch('rsto_hotel_transfer_selling_price_modal') ?>
<?= $this->fetch('rsto_hotel_room_list_modal') ?>
<?= $this->fetch('rsto_hotel_room_selling_price_list_modal') ?>
<?= $this->fetch('rsto_place_modal') ?>
<?= $this->fetch('rsto_hotel_room_modal') ?>
<?= $this->fetch('rsto_hotel_room_selling_price_modal') ?>
