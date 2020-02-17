<?php $this->start('rsto_hotel_transfer_selling_price_modal') ?>
<?php if(CAN_5_3) : ?>
<div class="modal fade rsto-modal" id="rsto-hotel-transfer-selling-price-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-hotel-transfer-selling-price-form" class="rsto-form" name="rsto-hotel-transfer-selling-price-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_hotel_transfer_selling_price_add_url ?>" data-edit-url="<?= $rsto_hotel_transfer_selling_price_edit_url ?>">
                <input type="hidden" name="hotel_transfer" id="rsto-hotel-transfer-selling-price-hotel-transfer"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-selling-price-currency"><?= __('Currency') ?></label>
                        <select name="currency" class="form-control select2 rsto-select2" data-required="true" id="rsto-hotel-transfer-selling-price-currency" data-url="<?= $rsto_hotel_transfer_selling_price_currency_select2 ?>" data-placeholder="<?= __('Choose a currency') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-going-price"><?= __('Going price') ?></label>
                        <input name="going_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-hotel-transfer-going-price" placeholder="<?= __("Enter going price") ?>">
                        <span class="help-block"><?= _('Going means the transfer starts from the hotel.') ?></span>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-coming-price"><?= __('Coming price') ?></label>
                        <input name="coming_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-hotel-transfer-coming-price" placeholder="<?= __("Enter coming price") ?>">
                        <span class="help-block"><?= _('Coming means the transfer starts from the hub.') ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-transfer-selling-price-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>
