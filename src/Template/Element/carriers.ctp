<?php $this->start('rsto_carrier_vehicle_price_list_modal') ?>
<?php if(CAN_9_1) : ?>
<div class="modal fade" id="rsto-carrier-price-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-carrier-price-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_carriers_vehicle_price_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Vehicle type') ?></th>
                            <th><?= __('Full cost price') ?></th>
                            <th><?= __('Half cost price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_9_2) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-carrier-price-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-price-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-price-manage-price-btn"><i class="fa fa-money"></i>&nbsp;<?= __('Selling prices') ?></button>
                <?php if(CAN_9_2) : ?>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-price-delete-btn" data-url="<?= $rsto_carriers_vehicle_price_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-carrier-price-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_vehicle_price_modal') ?>
<?php if(CAN_9_2) : ?>
<div class="modal fade rsto-modal" id="rsto-carrier-price-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-carrier-price-form" class="rsto-form" name="rsto-carrier-price-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_carriers_vehicle_price_add_url ?>" data-edit-url="<?= $rsto_carriers_vehicle_price_edit_url ?>">
                <input type="hidden" name="carrier" id="rsto-carrier-price-carrier"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-carrier-price-vehicle-type"><?= __('Currency') ?></label>
                        <select name="type" class="form-control select2 rsto-select2" data-required="true" id="rsto-carrier-price-vehicle-type" data-url="<?= $rsto_carriers_vehicle_price_vehicle_type_select2_url ?>" data-placeholder="<?= __('Choose a vehicle type') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-full-price"><?= __('Full price') ?></label>
                        <input name="full_cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-carrier-full-price" placeholder="<?= __("Enter full price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-half-price"><?= __('Half price') ?></label>
                        <input name="half_cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-carrier-half-price" placeholder="<?= __("Enter half price") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-price-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_vehicle_selling_price_list_modal') ?>
<?php if(CAN_9_1) : ?>
<div class="modal fade" id="rsto-carrier-selling-price-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-carrier-selling-price-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_carriers_vehicle_selling_price_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Full price') ?></th>
                            <th><?= __('Half price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_9_3) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-carrier-selling-price-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-selling-price-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-selling-price-delete-btn" data-url="<?= $rsto_carriers_vehicle_selling_price_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-carrier-price-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_vehicle_selling_price_modal') ?>
<?php if(CAN_9_2) : ?>
<div class="modal fade rsto-modal" id="rsto-carrier-selling-price-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-carrier-selling-price-form" class="rsto-form" name="rsto-carrier-selling-price-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_carriers_vehicle_selling_price_add_url ?>" data-edit-url="<?= $rsto_carriers_vehicle_selling_price_edit_url ?>">
                <input type="hidden" name="carrier_vehicle_price" id="rsto-carrier-seling-price-carrier-vehicle-price"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-carrier-selling-price-currency"><?= __('Currency') ?></label>
                        <select name="currency" class="form-control select2 rsto-select2" data-required="true" id="rsto-carrier-selling-price-currency" data-url="<?= $rsto_carriers_vehicle_selling_price_currency_select2_url ?>" data-placeholder="<?= __('Choose a currency') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-full-selling-price"><?= __('Full price') ?></label>
                        <input name="full_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-carrier-full-selling-price" placeholder="<?= __("Enter full price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-half-selling-price"><?= __('Half price') ?></label>
                        <input name="half_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-carrier-half-selling-price" placeholder="<?= __("Enter half price") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-selling-price-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_vehicle_list_modal') ?>
<?php if(CAN_9_1) : ?>
<div class="modal fade" id="rsto-carrier-vehicle-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-carrier-vehicle-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_carriers_vehicle_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Brand') ?></th>
                            <th><?= __('Model') ?></th>
                            <th><?= __('seat count') ?></th>
                            <th><?= __('Vehicle registration') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_9_3) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-carrier-vehicle-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-vehicle-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-vehicle-delete-btn" data-url="<?= $rsto_carriers_vehicle_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-carrier-vehicle-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_vehicle_modal') ?>
<?php if(CAN_9_2) : ?>
<div class="modal fade rsto-modal" id="rsto-carrier-vehicle-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-carrier-vehicle-form" class="rsto-form" name="rsto-carrier-vehicle-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_carriers_vehicle_add_url ?>" data-edit-url="<?= $rsto_carriers_vehicle_edit_url ?>">
                <input type="hidden" name="carrier" id="rsto-carrier-vehicle-carrier"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-carrier-vehicle-type"><?= __('Type') ?></label>
                        <select name="type" class="form-control select2 rsto-select2" data-required="true" id="rsto-carrier-vehicle-type" data-url="<?= $rsto_carriers_vehicle_type_select2_url ?>" data-placeholder="<?= __('Choose a type') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-vehicle-brand"><?= __('Brand') ?></label>
                        <select name="brand" class="form-control select2 rsto-select2" data-required="true" id="rsto-carrier-vehicle-brand" data-url="<?= $rsto_carriers_vehicle_brand_select2_url ?>" data-placeholder="<?= __('Choose a brand') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-vehicle-seat-count"><?= __('Seat count') ?></label>
                        <input name="seat_count" type="text" class="form-control numeric-validation" data-required="true" id="rsto-carrier-vehicle-seat-count" placeholder="<?= __("Enter seat count") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-vehicle-model"><?= __('Model') ?></label>
                        <input name="model" type="text" class="form-control" data-required="true" id="rsto-carrier-vehicle-model" placeholder="<?= __("Enter model") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-vehicle-registration"><?= __('Vehicle registration') ?></label>
                        <input name="vehicle_registration" type="text" class="form-control" data-required="true" id="rsto-carrier-vehicle-registration" placeholder="<?= __("Enter vehicle registration") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-vehicle-close-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_driver_list_modal') ?>
<?php if(CAN_9_1) : ?>
<div class="modal fade modal-xl" id="rsto-carrier-driver-list-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-carrier-driver-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_carriers_driver_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Fullname') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Phone number') ?></th>
                            <th><?= __('Email address') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_9_3) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-carrier-driver-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-driver-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-carrier-driver-delete-btn" data-url="<?= $rsto_carriers_driver_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-carrier-driver-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_carrier_driver_modal') ?>
<?php if(CAN_9_2) : ?>
<div class="modal fade rsto-modal" id="rsto-carrier-driver-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-carrier-driver-form" class="rsto-form" name="rsto-carrier-driver-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_carriers_driver_add_url ?>" data-edit-url="<?= $rsto_carriers_driver_edit_url ?>">
                <input type="hidden" name="carrier" id="rsto-carrier-driver-carrier"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-carrier-driver-title"><?= __('Type') ?></label>
                        <select name="title" class="form-control select2 rsto-select2" data-required="true" id="rsto-carrier-driver-title" data-url="<?= $rsto_carriers_driver_title_select2_url ?>" data-placeholder="<?= __('Choose a title') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-driver-fullname"><?= __('Full name') ?></label>
                        <input name="fullname" type="text" class="form-control" data-required="true" id="rsto-carrier-driver-fullname" placeholder="<?= __("Enter the fullname") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-driver-description"><?= __('Description') ?></label>
                        <textarea rows="10" name="description" class="form-control" id="rsto-carrier-driver-description" placeholder="<?= __("Enter description, you can left empty.") ?>"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-driver-phone-number"><?= __('Phone number') ?></label>
                        <input name="phone_number" type="text" class="form-control phone-number-validation" data-required="true" id="rsto-carrier-driver-phone-number" placeholder="<?= __("Enter phone number") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-carrier-driver-email-address"><?= __('Email address') ?></label>
                        <input name="email_address" type="text" class="form-control email-validation" id="rsto-carrier-driver-email-address" placeholder="<?= __("Enter email address, you can left empty.") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-vehicle-close-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>