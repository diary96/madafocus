
<?php
$this->Html->script('/datatables.net/js/jquery.dataTables.min.js', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min.js', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap.css', ['block' => true]);
// Cette ligne doit être la dernière
$this->Html->script('/rsto/js/rsto.usergroups', ['block' => true]);
?>
<?php if (CAN_1_1): ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Que voulez vous faire ?') ?></h3>
        </div>
        <div class="box-body">
            <button class="btn btn-app" id="rsto-add-usergroup-btn" >
                <i class="fa fa-plus"></i> <?= __('Créer') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-edit-usergroup-btn" disabled>
                <i class="fa fa-edit"></i> <?= __('Modifier') ?>
            </button>
            <button class="btn btn-app disabled" id="rsto-delete-usergroup-btn" data-url="<?= $rsto_delete_user_group_url ?>" disabled>
                <i class="fa fa-trash"></i> <?= __('Supprimer') ?>
            </button>
        </div>
    </div>
    <div id="rsto-usergroup-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Liste des groupes') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-usergroup-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-x-csrf-token="<?= $x_csrf_token ?>" data-url="<?= $rsto_groups_datatable_url ?>">
                <thead>
                    <tr>
                        <th><?= __('Nom du groupe'); ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php if (CAN_1_2): ?>
    <div class="modal fade rsto-modal" id="rsto-usergroup-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New group") ?></h4>
                </div>
                <form id="rsto-usergroup-form" name="rsto-usergroup-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_add_user_group_url ?>" data-edit-url="<?= $rsto_edit_user_group_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-usergroup-name"><?= __('Group name') ?></label>
                            <input id="rsto-usergroup-name" name="name" data-validation-url="<?= $rsto_name_validation_url ?>" type="text" class="form-control remote-validation" data-required="true" placeholder="<?= __("Enter group name") ?>">
                            <span class="help-block"><?= __("Group name must be unique.") ?></span>
                        </div>
                        <h3><?= __('Privileges') ?></h3>
                        <div class="row">
                            <div class="col-md-6">
                        <?php 
                            $_group_id = 0;
                            $_preview_group_id = 0;
                            $_new_group = false;
                            $_group_index = 0;
                        ?>
                        <?php foreach ($rsto_privileges['privileges'] as $_id => $_privilege) : $_ids = explode('.', $_id) ?>
                            <?php if ($_group_id != intval($_ids[0])) : ?>
                                <?php
                                    $_group_id = intval($_ids[0]);
                                    $_preview_group_id = $_preview_group_id === 0 ? $_group_id : $_preview_group_id;
                                    $_new_group = ($_preview_group_id <> $_group_id);
                                    $_group_label = $rsto_privileges['labels'][$_ids[0]];
                                ?>
                            <?php if($_new_group) : $_group_index++ ?>
                            </div>
                            <?php if($_group_index % 2 === 0) : ?>
                        </div>
                        <div class="row">
                            <?php endif ?>
                            <div class="col-md-6">
                            <?php endif ?>
                            <h4><?= $_group_label ?></h4>
                            <?php endif; ?>
                                <div class="checkbox rsto-privilege">
                                    <label>
                                        <input type="checkbox" name="privileges[<?= $_id ?>]"/> <?= $_privilege ?>
                                    </label>
                                </div>
                        <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Annuler') ?></button>
                        <button disabled id="rsto-usergroup-form-submit" type="submit" class="btn btn-primary disabled" id="rsto-add-user-save-btn"><?= __('Enregistrer') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
