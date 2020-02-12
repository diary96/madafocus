<?php $this->start('rsto_hotel_room_selling_price_modal') ?>
<?php if(CAN_5_3) : ?>
<div class="modal fade rsto-modal" id="rsto-hotel-room-selling-price-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Selling prices") ?></h4>
            </div>
            <form id="rsto-hotel-room-selling-price-form" class="rsto-form" name="rsto-hotel-room-selling-price-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_hotel_room_selling_add_url ?>" data-edit-url="<?= $rsto_hotel_room_selling_edit_url ?>">
                <input type="hidden" name="hotel_room" id="rsto-hotel-room-selling-price-hotel-room"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hotel-room-selling-price-currency"><?= __('Currency') ?></label>
                        <select name="currency" class="form-control select2 rsto-select2" data-required="true" id="rsto-hotel-room-selling-price-currency" data-url="<?= $rsto_hotel_room_selling_price_currency_select2_url ?>" data-placeholder="<?= __('Choose a currency') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-bo-selling-price"><?= __('Bed only') ?></label>
                        <input name="bo" class="form-control numeric-validation" data-required="true" type="text" id="rsto-hotel-room-bo-selling-price" placeholder="<?= __("Enter bed only selling price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-du-selling-price"><?= __('Day use') ?></label>
                        <input name="du" class="form-control numeric-validation" data-required="true" type="text" id="rsto-hotel-room-du-selling-price" placeholder="<?= __("Enter day use selling price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-hb-selling-price"><?= __('Half board') ?></label>
                        <input name="hb" class="form-control numeric-validation" type="text" id="rsto-hotel-room-hb-selling-price" placeholder="<?= __("Enter half board selling price (You can left empty)") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-bb-selling-price"><?= __('Bed and breakfast') ?></label>
                        <input name="bb" class="form-control numeric-validation" type="text" id="rsto-hotel-room-bb-selling-price" placeholder="<?= __("Enter bed and breakfast selling price (You can left empty)") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-fb-selling-price"><?= __('Full board') ?></label>
                        <input name="fb" class="form-control numeric-validation" type="text" id="rsto-hotel-room-fb-selling-price" placeholder="<?= __("Enter full board selling price (You can left empty)") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-room-selling-price-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>
