addContactInformation<?php
$this->element('place-modal');
$this->element('park-selling-entrance-fee-modal');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.directory', ['block' => true]);
?>
<?php if (CAN_8_2) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
            <?php if (CAN_8_2) : ?>
                <button class="btn btn-app" id="rsto-directory-add-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-directory-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-directory-infos-btn" disabled>
                    <i class="fa fa-info-circle"></i> <?= __('Infos') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-directory-delete-btn" data-url="<?= $rsto_directory_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_8_1) : ?>
    <div id="rsto-directory-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Entries') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-directory-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_directory_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                <tr>
                    <th><?= __('Title') ?></th>
                    <th><?= __('Fullname') ?></th>
                    <th><?= __('Description') ?></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_8_2) : ?>
    <div class="modal fade rsto-modal" id="rsto-directory-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New entry") ?></h4>
                </div>
                <form id="rsto-directory-form" name="rsto-directory-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_directory_add_url ?>" data-edit-url="<?= $rsto_directory_edit_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-directory-fullname"><?= __('Fullname') ?></label>
                            <input name="fullname" type="text" class="form-control remote-validation" data-validation-url="<?= $rsto_directory_fullname_validation_url ?>"  data-required="true" id="rsto-directory-fullname" placeholder="<?= __("Enter fullname") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-directory-title"><?= __('Title') ?></label>
                            <select name="title" class="form-control rsto-select2" data-required="true" id="rsto-directory-title" data-url="<?= $rsto_directory_title_select2_url ?>" data-placeholder="<?= __("Choose a title") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-directory-description"><?= __('Description') ?></label>
                            <textarea name="description" rows="7" type="text" class="form-control" id="rsto-directory-description" placeholder="<?= __("Enter description") ?>"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-directory-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rsto-directory-contact-information-list-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("Contact informations list") ?></h4>
                </div>
                <div class="modal-body">
                    <table id="rsto-directory-contact-information-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_directory_contact_information_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                        <thead>
                            <tr>
                                <th><?= __('Type') ?></th>
                                <th><?= __('Label') ?></th>
                                <th><?= __('Info') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-left" id="rsto-directory-contact-information-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                    <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-directory-contact-information-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                    <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-directory-contact-information-delete-btn" data-url="<?= $rsto_directory_contact_information_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade rsto-modal" id="rsto-directory-contact-information-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New entry") ?></h4>
                </div>
                <form id="rsto-directory-contact-information-form" name="rsto-directory-contact-information-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_directory_contact_information_add_url ?>" data-edit-url="<?= $rsto_directory_contact_information_edit_url ?>">
                    <input type="hidden" name="directory" id="rsto-directory-contact-information-directory"/>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-directory-contact-information-type"><?= __('Type') ?></label>
                            <select name="type" class="form-control rsto-select2" data-required="true" id="rsto-directory-contact-information-type" data-url="<?= $rsto_directory_contact_information_type_select2_url ?>" data-placeholder="<?= __("Choose a type") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-directory-contact-information-label"><?= __('Label') ?></label>
                            <input name="label" type="text" class="form-control" id="rsto-directory-contact-information-label" placeholder="<?= __("Enter label, you can left empty") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-directory-contact-information-contact-info"><?= __('Contact information') ?></label>
                            <input name="contact_info" type="text" class="form-control" id="rsto-directory-contact-information-contact-info" placeholder="<?= __("Enter contact information") ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-directory-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
