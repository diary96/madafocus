<?php
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min.js', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min.js', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap.css', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min.js', ['block' => true]);
$this->Html->css('/select2/css/select2.min.css', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.users', ['block' => true]);
?>
<?php if (CAN_2_2) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
            <button class="btn btn-app" id="rsto-add-user-btn">
                <i class="fa fa-user-plus"></i> <?= __('Add') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-edit-user-btn"  disabled>
                <i class="fa fa-edit"></i> <?= __('Edit') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-tasks-user-btn" disabled>
                <i class="fa fa-tasks"></i> <?= __('Tasks') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-reset-user-btn" disabled>
                <i class="fa fa-refresh"></i> <?= __('Reset') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-delete-user-btn" data-url="<?= $rsto_users_delete_url ?>" disabled>
                <i class="fa fa-trash"></i> <?= __('Delete') ?>
            </button>
        </div>
    </div>
<?php endif; ?>
<?php if (CAN_2_1) : ?>
    <div id="rsto-users-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Users') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-users-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_users_datatable_data_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                    <tr>
                        <th><?= __('Fullname') ?></th>
                        <th><?= __("Username") ?></th>
                        <th><?= __('Group') ?></th>
                        <th><?= __('Phone number') ?></th>
                        <th><?= __('Email address') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php if (CAN_2_2) : ?>
    <div class="modal fade rsto-modal" id="rsto-add-user-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New user") ?></h4>
                </div>
                <form id="rsto-user-form" class="rsto-form" name="rsto-user-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_users_add_url ?>" data-edit-url="<?= $rsto_users_edit_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-user-firstname"><?= __('Firstname') ?></label>
                            <input name="firstname" type="text" data-required="true" class="form-control" id="rsto-user-firstname" placeholder="<?= __("Enter firstname") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-user-lastname"><?= __('Lastname') ?></label>
                            <input name="lastname" type="text" data-required="true" class="form-control" id="rsto-user-lastname" placeholder="<?= __("Enter lastname") ?>">
                        </div>
                        <div class="form-group">
                            <label for="rsto-user-group"><?= __('Group') ?></label>
                            <select name="group" class="form-control rsto-select2" data-required="true" id="rsto-user-group" data-url="<?= $rsto_users_select2_data_url ?>" data-placeholder="<?= __('Choose a group') ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-user-username"><?= __("Username") ?></label>
                            <input name="username" type="text" class="form-control remote-validation" data-required="true" id="rsto-user-username" placeholder="<?= __("Entrer username") ?>" data-validation-url="<?= $rsto_users_username_validation_url ?>">
                            <span class="help-block"><?= __("Le nom d'utilisateur doit être unique") ?></span>
                        </div>
                        <div class="form-group">
                            <label for="rsto-user-email-address"><?= __('Email address') ?></label>
                            <input name="email_address" type="email" class="form-control email-validation" data-required="true" id="rsto-user-email-address" placeholder="<?= __("Enter email address") ?>">
                            <span class="help-block"><?= __("The default password will be sent at this address. User have to change his password after his first login") ?></span>
                        </div>
                        <div class="form-group" data-ignore-validation="true">
                            <label for="rsto-user-gender"><?= __('Gender') ?></label>
                            <select name="gender" class="form-control rsto-select2" data-required="true" data-placeholder="<?= __('Select gender') ?>" id="rsto-user-gender">
                                <option value="0"><?= __('Homme') ?></option>
                                <option value="1"><?= __('Femme') ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-user-phonenumber"><?= __("Phone number") ?></label>
                            <input name="phone_number" type="text" class="form-control phone-number-validation" data-required="true" id="rsto-user-phonenumber" placeholder="<?= __("Enter phone number") ?>">
                            <span class="help-block"><?= __("This phone number will be used for double factor authentication.") ?></span>
                        </div>

                        <div class="form-group">
                            <label for="rsto-user-timezone"><?= __('Timezone') ?></label>
                            <select name="timezone" class="form-control rsto-select2" data-required="true" id="rsto-user-timezone" data-url="<?= $rsto_timezones_select2_data_url ?>" data-placeholder="<?= __('Choose timezone') ?>"></select>
                            <span class="help-block"><?= __("Enter timezone") ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-add-user-save-btn"><?= __('Save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
