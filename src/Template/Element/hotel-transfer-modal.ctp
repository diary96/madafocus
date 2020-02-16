<?php $this->start('rsto_hotel_transfer_modal') ?>
<div class="modal fade rsto-modal" id="rsto-hotel-transfer-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form class="rsto-form" id="rsto-hotel-transfer-form" data-action-url="<?= $rsto_hotel_transfer_add_url ?>" data-edit-url="<?= $rsto_hotel_transfer_edit_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <input type="hidden" id="rsto-hotel-transfer-hotel" name="hotel"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-hub"><?= __('Hub') ?></label>
                        <select name="hub" class="form-control select2 rsto-select2" id="rsto-hotel-transfer-hub" data-required="true" data-url="<?= $rsto_hotel_transfer_hub_select2_url ?>" data-placeholder="<?= __('Choose a type') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-going-cost-price"><?= __('Going cost price') ?></label>
                        <input name="going_cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-hotel-transfer-going-cost-price" placeholder="<?= __("Enter going cost price") ?>">
                        <span class="help-block"><?= _('Going means the transfer starts from the hotel.') ?></span>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-transfer-coming-cost-price"><?= __('Coming cost price') ?></label>
                        <input name="coming_cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-hotel-transfer-coming-cost-price" placeholder="<?= __("Enter coming cost price") ?>">
                        <span class="help-block"><?= _('Coming means the transfer starts from the hub.') ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-transfer-modal-close-btn"><?= __('Close') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-transfer-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>