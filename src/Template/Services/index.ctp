<?php
$this->element('services');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.services', ['block' => true]);
?>
<?php if (CAN_10_2 || CAN_10_3) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
                <button class="btn btn-app" id="rsto-service-add-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-prices-btn" disabled>
                    <i class="fa fa-money"></i> <?= __('Prices') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-providers-btn" disabled>
                    <i class="fa fa-user-circle-o"></i> <?= __('Providers') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-dependencies-btn" disabled>
                    <i class="fa fa-chain"></i> <?= __('Reliances') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-delete-btn" data-url="<?= $rsto_service_delete_url ?>" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_10_1) : ?>
    <div id="rsto-service-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Services') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-service-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_service_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                    <tr>
                        <th><?= __('Type') ?></th>
                        <th><?= __('Place') ?></th>
                        <th><?= __('Description') ?></th>
                        <th><?= __('Adult cost price') ?></th>
                        <th><?= __('Children cost price') ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_10_2) : ?>
    <div class="modal fade rsto-modal" id="rsto-service-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><?= __("New service") ?></h4>
                </div>
                <form id="rsto-service-form" name="rsto-service-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_service_add_url ?>" data-edit-url="<?= $rsto_service_edit_url ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rsto-service-place"><?= __('Place') ?></label>
                            <select name="place" class="form-control rsto-select2" data-required="true" id="rsto-service-place" data-url="<?= $rsto_service_place_select2_url ?>" data-placeholder="<?= __("Choose a place") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-type"><?= __('Type') ?></label>
                            <select name="type" class="form-control rsto-select2" data-required="true" id="rsto-service-type" data-url="<?= $rsto_service_type_select2_url ?>" data-placeholder="<?= __("Choose a type") ?>"></select>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-description"><?= __('Name') ?></label>
                            <textarea name="description" class="form-control" rows="10" id="rsto-service-description" placeholder="<?= __("Enter a description, you can leave empty") ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label for="rsto-service-from-provider">
                                    <input type="checkbox" name="from_provider" id="rsto-service-from-provider"/>
                                    Is from provider
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-adult-cost-price"><?= __('Adult cost price') ?></label>
                            <input name="adult_cost_price" type="text" class="form-control numeric-validation" data-required="true" id="rsto-service-adult-cost-price" placeholder="<?= __("Enter service adult cost price") ?>">
                            <span class="help-block"><?= _('A cost price must be given when a service is not from a provider') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="rsto-service-children-cost-price"><?= __('Children cost price') ?></label>
                            <input name="children_cost_price" type="text" class="form-control numeric-validation" id="rsto-service-children-cost-price" placeholder="<?= __("Enter service children cost price") ?>">
                            <span class="help-block"><?= _('If you leave empty, the price will be the same as the adult cost price.') ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-service-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>

<?= $this->fetch('rsto_service_selling_price_list_modal') ?>
<?= $this->fetch('rsto_service_selling_price_modal') ?>
<?= $this->fetch('rsto_service_provider_list_modal') ?>
<?= $this->fetch('rsto_service_provider_modal') ?>
<?= $this->fetch('rsto_service_provider_choice_modal') ?>
<?= $this->fetch('rsto_service_provider_price_list_modal') ?>
<?= $this->fetch('rsto_service_provider_price_modal') ?>