<?php $this->start('rsto_select_option_group_modal') ?>
<?php if(CAN_3_2) : ?>
<div class="modal fade rsto-modal" id="rsto-select-option-group-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New option group") ?></h4>
            </div>
            <form id="rsto-select-option-group-form" class="rsto-form" name="rsto-select-option-group-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_select_option_group_add_url ?>">
                <input type="hidden" name="select" id="rsto-select-option-group-select"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-select-option-group-name"><?= __('Name') ?></label>
                        <input name="name" type="text" class="form-control remote-validation" data-required="true" id="rsto-select-option-group-name" placeholder="<?= __("Entrez le nom du groupe d'option") ?>" data-validation-url="<?= $rsto_select_option_group_name_validation_url ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Annuler') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-select-option-group-form-submit-btn"><?= __('Enregistrer') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>
