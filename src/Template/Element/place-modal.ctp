<?php $this->start('rsto_place_modal') ?>
<?php if(CAN_4_2) : ?>
<div class="modal fade rsto-modal" id="rsto-place-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New place") ?></h4>
            </div>
            <form id="rsto-place-form" class="rsto-form" name="rsto-place-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_places_add_url ?>" <?php if(isset($rsto_places_edit_url)) : ?>data-edit-url="<?= $rsto_places_edit_url ?>"<?php endif; ?> >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-place-name"><?= __('Name') ?></label>
                        <input name="name" type="text" class="form-control remote-validation" data-required="true" id="rsto-place-name" placeholder="<?= __("Enter the place name") ?>" data-validation-url="<?= $rsto_place_name_validation_url ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-place-parent"><?= __('Zone') ?></label>
                        <select name="parent" class="form-control rsto-select2" id="rsto-place-parent" data-required="true" data-url="<?= $rsto_place_parent_select2_url ?>" data-placeholder="<?= __('Choose a zone') ?>"></select>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-place-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>