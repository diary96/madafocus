<?php $this->start('rsto_service_selling_price_list_modal') ?>
<?php if(CAN_10_1) : ?>
<div class="modal fade" id="rsto-service-price-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-service-price-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_service_selling_price_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_10_2) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-service-price-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-price-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-price-delete-btn" data-url="<?= $rsto_service_selling_price_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-carrier-price-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_service_selling_price_modal') ?>
<?php if (CAN_10_3) : ?>
<div class="modal fade rsto-modal" id="rsto-service-selling-price-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New service") ?></h4>
            </div>
            <form id="rsto-service-selling-price-form" name="rsto-service-selling-price-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_service_selling_price_add_url ?>" data-edit-url="<?= $rsto_service_selling_price_edit_url ?>">
                <input type="hidden" name="service" id="rsto-service-selling-price-service">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-service-selling-price-currency"><?= __('Currency') ?></label>
                        <select name="currency" class="form-control rsto-select2" data-required="true" id="rsto-service-selling-price-currency" data-url="<?= $rsto_service_selling_price_currency_select2_url ?>" data-placeholder="<?= __("Choose a currency") ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-service-selling-price-price"><?= __('Price') ?></label>
                        <input name="price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-selling-price-price" placeholder="<?= __("Enter a price") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-service-selling-price-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_service_provider_list_modal') ?>
<?php if(CAN_10_1) : ?>
<div class="modal fade modal-xl" id="rsto-service-provider-list-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-service-provider-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_service_provider_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Fullname') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Phone number') ?></th>
                            <th><?= __('Email address') ?></th>
                            <th><?= __('Cost price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_10_2) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-service-provider-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-provider-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-provider-price-btn"><i class="fa fa-money"></i>&nbsp;<?= __('Manage prices') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-provider-delete-btn" data-url="<?= $rsto_service_provider_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-service-provider-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_service_provider_modal') ?>
<?php if (CAN_10_3) : ?>
    <div class="modal fade rsto-modal" id="rsto-service-provider-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New provider") ?></h4>
                </div>
                <form id="rsto-service-provider-form" name="rsto-service-provider-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_service_provider_add_url ?>" data-edit-url="<?= $rsto_service_provider_edit_url ?>">
                    <input type="hidden" name="service" id="rsto-service-provider-service">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-service-provider-title"><?= __('Title') ?></label>
                            <select name="title" class="form-control rsto-select2" data-required="true" id="rsto-service-provider-title" data-url="<?= $rsto_service_title_select2_url ?>" data-placeholder="<?= __("Choose a title") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-fullname"><?= __('Fullname') ?></label>
                            <input name="fullname" type="text" class="form-control" data-required="true" id="rsto-service-provider-fullname" placeholder="<?= __("Enter fullname") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-description"><?= __('Description') ?></label>
                            <textarea name="description" rows="7" type="text" class="form-control" id="rsto-service-provider-description" placeholder="<?= __("Enter a description") ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-booking-phone-number"><?= __('Booking phone number') ?></label>
                            <input name="booking_phone_number" type="text" class="form-control phone-number-validation" id="rsto-service-provider-booking-phone-number" placeholder="<?= __("Enter booking phone number") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-booking-email-address"><?= __('Booking email address') ?></label>
                            <input name="booking_mail_address" type="text" class="form-control email-validation" id="rsto-service-provider-booking-email-address" placeholder="<?= __("Enter booking email address, you can leave empty") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-cost-price"><?= __('Cost price') ?></label>
                            <input name="cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-provider-cost-price" placeholder="<?= __("Enter cost price") ?>">
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="rsto-service-provider-must-book">
                                    <input type="checkbox" name="must_book" id="rsto-service-provider-must-book"/>
                                    Must book
                                </label>
                            </div>
                            <span class="help-block"><?= _('Means that a booking must be sent to the provider.') ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" id="rsto-service-provider-choice-btn"><?= __('Choose an existing') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_service_provider_choice_modal') ?>
    <div class="modal fade rsto-modal" id="rsto-service-provider-choice-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("Choose an existing provider") ?></h4>
                </div>
                <form id="rsto-service-provider-form" name="rsto-service-provider-choice-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                    <input type="hidden" name="service" id="rsto-service-provider-choice-service">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-service-provider-choice-provider"><?= __('Provider') ?></label>
                            <select name="provider" class="form-control rsto-select2" data-required="true" id="rsto-service-provider-choice-provider" data-url="" data-placeholder="<?= __("Choose a provider") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-choice-cost-price"><?= __('Cost price') ?></label>
                            <input name="cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-provider-choice-cost-price" placeholder="<?= __("Enter cost price") ?>">
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="rsto-service-provider-choice-must-book">
                                    <input type="checkbox" name="must_book" id="rsto-service-provider-choice-must-book"/>
                                    Must book
                                </label>
                            </div>
                            <span class="help-block"><?= _('Means that a booking must be sent to the provider.') ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" id="rsto-service-provider-choice-add-btn"><?= __('Add a new provider') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-carrier-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('rsto_service_price_list_modal') ?>
<?php if(CAN_10_3) : ?>
<div class="modal fade" id="rsto-service-cost-price-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-service-cost-price-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_service_cost_price_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('From') ?></th>
                            <th><?= __('To') ?></th>
                            <th><?= __('Adult cost price') ?></th>
                            <th><?= __('Children cost price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_10_2) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-service-cost-price-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-cost-price-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-cost-price-price-btn"><i class="fa fa-money"></i>&nbsp;<?= __('Manage prices') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-service-cost-price-delete-btn" data-url=""><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-service-cost-price-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_service_cost_price_modal') ?>
<?php if (CAN_10_3) : ?>
    <div class="modal fade rsto-modal" id="rsto-service-cost-price-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New cost price") ?></h4>
                </div>
                <form id="rsto-service-cost-price-form" name="rsto-service-cost-price-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                    <input type="hidden" name="service" id="rsto-service-provider-service">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-service-cost-price-from"><?= __('From') ?></label>
                            <input name="from" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-cost-price-from" placeholder="<?= __("Enter the minimum number of person") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-cost-price-to"><?= __('To') ?></label>
                            <input name="to" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-cost-price-to" placeholder="<?= __("Enter the maximum number of person") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-cost-price-adult"><?= __('Adult') ?></label>
                            <input name="adult" type="text" class="form-control numeric-validation" id="rsto-service-cost-price-adult" placeholder="<?= __("Enter adult cost price") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-cost-price"><?= __('Children') ?></label>
                            <input name="children" type="text" class="form-control numeric-validation" id="rsto-service-cost-price-children" placeholder="<?= __("Enter children cost price, you can leave empty") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-provider-cost-price"><?= __('Cost price') ?></label>
                            <input name="cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-provider-cost-price" placeholder="<?= __("Enter cost price") ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-service-cost-price-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<?php $this->end() ?>
