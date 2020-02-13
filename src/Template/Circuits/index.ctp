<?php
$this->element('circuits');
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// deapicker
$this->Html->script('/bootstrap-datepicker/js/bootstrap-datepicker.js', ['block' => true]);
$this->Html->css('/bootstrap-datepicker/css/bootstrap-datepicker3.min', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.circuits', ['block' => true]);
?>
<?php if (CAN_11_2) : ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
        </div>
        <div class="box-body">
                <button class="btn btn-app" id="rsto-circuit-add-btn">
                    <i class="fa fa-plus"></i> <?= __('New') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-circuit-edit-btn" disabled>
                    <i class="fa fa-edit"></i> <?= __('Edit') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-circuit-configure-btn" disabled>
                    <i class="fa fa-calendar"></i> <?= __('Configure') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-circuit-validation-btn" disabled>
                    <i class="fa fa-download"></i> <?= __('Quote') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-circuit-validation-btn" disabled>
                    <i class="fa fa-check-circle"></i> <?= __('Validate') ?>
                </button>
                <button class="btn btn-app disabled" id="rsto-service-delete-btn" disabled>
                    <i class="fa fa-trash"></i> <?= __('Delete') ?>
                </button>
        </div>
    </div>
<?php endif ?>
<?php if (CAN_11_1) : ?>
    <div id="rsto-circuit-datatable-box" class="box">
        <div class="box-header">
            <h3 class="box-title"><?= __('Services') ?></h3>
        </div>
        <div class="box-body">
            <table id="rsto-circuit-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_circuits_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                <thead>
                    <tr>
                        <th><?= __('Code') ?></th>
                        <th><?= __('Start') ?></th>
                        <th><?= __('Length') ?></th>
                        <th><?= __('Pax') ?></th>
                        <th><?= __('Drive') ?></th>
                        <th><?= __('Status') ?></th>
                    </tr>
                </thead>
                <!-- <tbody>
                    <tr>
                        <td>MAK-100</td>
                        <td>12/3/2020</td>
                        <td>10</td>
                        <td>2 adults, 2 children </td>
                        <td>Self</td>
                        <td>Finished</td>
                    </tr>
                    <tr>
                        <td>MAK-101</td>
                        <td>02/17/2020</td>
                        <td>9</td>
                        <td>2 adults, 2 children </td>
                        <td>Self</td>
                        <td>Validated</td>
                    </tr>
                    <tr>
                        <td>BET-51</td>
                        <td>07/03/2020</td>
                        <td>21</td>
                        <td>3 adults, 0 children </td>
                        <td>driver</td>
                        <td>Waiting for validation</td>
                    </tr>
                    <tr>
                        <td>TEE-27</td>
                        <td>07/03/2020</td>
                        <td>18</td>
                        <td>2 adults, 0 children </td>
                        <td>driver</td>
                        <td>Pendind setup</td>
                    </tr>
                </tbody> -->
            </table>
        </div>
    </div>
<?php endif ?>

<?= $this->fetch('rsto_circuit_modal') ?>
<?= $this->fetch('rsto_circuit_days_modal') ?>
<?= $this->fetch('rsto_circuit_day_modal') ?>
<?= $this->fetch('rsto_circuit_day_room_list_modal') ?>
<?= $this->fetch('rsto_circuit_day_specify_modal') ?>
