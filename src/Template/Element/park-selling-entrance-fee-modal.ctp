<?php $this->start('rsto_park_selling_entrance_fee_modal') ?>
<?php if (CAN_7_3): ?>
    <?php $this->Html->script('/rsto/js/rsto.parks.prices.new', ['block' => true]) ?>
    <div class="modal fade rsto-modal" id="rsto-park-selling-entrance-fee-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New park selling price") ?></h4>
                </div>
                <form id="rsto-park-selling-entrance-fee-form" name="rsto-park-selling-entrance-fee-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_park_selling_entrance_fee_add_url ?>" data-edit-url="<?= $rsto_park_selling_entrance_fee_edit_url ?>">
                    <input type="hidden" name="park" id="rsto-park-selling-entrance-fee-park"/>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-park-selling-entrance-fee-currency"><?= __('Currency') ?></label>
                            <select name="currency" class="form-control rsto-select2" data-required="true" id="rsto-park-selling-entrance-fee-currency" data-url="<?= $rsto_park_selling_entrance_fee_select2_url ?>" data-placeholder="<?= __('Choose a currency') ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-park-adult-selling-entrance-fee"><?= __('Adult selling entrance fee') ?></label>
                            <input name="adult_entrance_fee" type="text" class="form-control numeric-validation" data-required="true" id="rsto-park-adult-selling-entrance-fee" placeholder="<?= __("Enter adult selling entrance fee") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-park-children-selling-entrance-fee"><?= __('Children selling entrance fee') ?></label>
                            <input name="children_entrance_fee" type="text" class="form-control numeric-validation" data-required="true" id="rsto-park-children-selling-entrance-fee" placeholder="<?= __("Enter children selling entrance fee") ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-park-selling-entrance-fee-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<?php $this->end() ?>