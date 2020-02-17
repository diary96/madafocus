<?php $this->start('rsto_select_option_modal') ?>
<?php if(CAN_3_2) : ?>
<div class="modal fade rsto-modal" id="rsto-select-option-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New option") ?></h4>
            </div>
            <form id="rsto-select-option-form" class="rsto-form" name="rsto-select-option-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_select_add_url ?>" data-edit-url="<?= $rsto_select_edit_url ?>" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-select-option-group"><?= __('Group') ?></label>
                        <div class="row">
                            <div class="col-xs-10">
                                <select name="group" class="form-control rsto-select2" data-required="true" id="rsto-select-option-group" data-url="<?= $rsto_select_group_select2_url ?>" data-placeholder="<?= __('Choose a group') ?>"></select>    
                            </div>
                            <div class="col-xs-2" style="padding-left: 0">
                                <button class="btn btn-block btn-default" id="rsto-select-option-group-add-btn"><i class="fa fa-plus"></i>&nbsp;New</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rsto-select-option"><?= __('Option') ?></label>
                        <input name="option" type="text" class="form-control remote-validation" data-required="true" id="rsto-select-option" placeholder="<?= __("Enter option") ?>" data-validation-url="<?= $rsto_select_option_validation_url ?>">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="rsto-select-default">
                                <input type="checkbox" name="default" id="rsto-select-default"/>
                                <?= __('Par défaut') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
                    <button type="submit" class="btn btn-primary disabled" id="rsto-select-form-submit-btn"><?= __('Save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $this->end() ?>
