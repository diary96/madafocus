<?php $this->start('rsto_hotel_room_modal') ?>
<?php if(CAN_5_2) : ?>
<div class="modal fade rsto-modal" id="rsto-hotel-room-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New room") ?></h4>
            </div>
            <form id="rsto-hotel-room-form" class="rsto-form" name="rsto-hotel-room-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_hotel_room_add_url ?>" data-edit-url="<?= $rsto_hotel_room_edit_url ?>">
                <input type="hidden" name="hotel" id="rsto-hotel-room-hotel"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-hotel-room-type"><?= __('Type') ?></label>
                        <select name="type" class="form-control select2 rsto-select2" id="rsto-hotel-room-type" data-required="true" data-url="<?= $rsto_hotel_room_type_select2_url ?>" data-placeholder="<?= __('Choose a type') ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-capacity"><?= __('Capacity') ?></label>
                        <input name="capacity" class="form-control integer-validation" type="text" id="rsto-hotel-room-capacity" data-required="true" placeholder="<?= __("Enter room capacity") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-du"><?= __('Day use') ?></label>
                        <input name="du_cost_price" class="form-control numeric-validation" type="text" id="rsto-hotel-room-du" data-required="true" placeholder="<?= __("Enter day use cost price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-bo"><?= __('Bed only') ?></label>
                        <input name="bo_cost_price" class="form-control numeric-validation" type="text" id="rsto-hotel-room-bo" data-required="true" placeholder="<?= __("Enter bed only cost price") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-bb"><?= __('Bed and breakfast') ?></label>
                        <input name="bb_cost_price" class="form-control numeric-validation" type="text" id="rsto-hotel-room-bb" data-can-be-empty="true" placeholder="<?= __("Enter bed and breakfast cost price (You can left empty)") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-hb"><?= __('Half board') ?></label>
                        <input name="hb_cost_price" class="form-control numeric-validation" type="text" id="rsto-hotel-room-hb" data-can-be-empty="true" placeholder="<?= __("Enter half board cost price (You can left empty)") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-hotel-room-fb"><?= __('Full board') ?></label>
                        <input name="fb_cost_price" class="form-control numeric-validation" type="text" id="rsto-hotel-room-fb" data-can-be-empty="true" placeholder="<?= __("Enter full board cost price (You can left empty)") ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-hotel-room-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>