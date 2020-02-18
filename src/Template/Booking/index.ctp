<?php
// datatable
$this->Html->script('/datatables.net/js/jquery.dataTables.min', ['block' => true]);
$this->Html->script('/datatables.net-bs/js/dataTables.bootstrap.min', ['block' => true]);
$this->Html->css('/datatables.net-bs/css/dataTables.bootstrap', ['block' => true]);
// select2
$this->Html->script('/select2/js/select2.full.min', ['block' => true]);
$this->Html->css('/select2/css/select2.min', ['block' => true]);
// Cette ligne doit être appelée en dernier
$this->Html->script('/rsto/js/rsto.booking', ['block' => true]);
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('What do you want to do ?') ?></h3>
    </div>
    <div class="box-body">
        <button class="btn btn-app" id="rsto-booking-filter-btn">
            <i class="fa fa-filter"></i> <?= __('Filter') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-booking-call-btn" disabled>
            <i class="fa fa-phone-square"></i> <?= __('Called') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-booking-view-btn" disabled>
            <i class="fa fa-eye"></i> <?= __('Details') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-booking-confirm-btn" data-url="<?= $rsto_booking_validate_url ?>"  disabled>
            <i class="fa fa-edit"></i> <?= __('Confirm') ?>
        </button>
        <button class="btn btn-app disabled" id="rsto-booking-cancel-btn" disabled>
            <i class="fa fa-close"></i> <?= __('Cancel') ?>
        </button>
    </div>
</div>
<?php if (CAN_12_1) : ?>
<div id="rsto-booking-datatable-box" class="box">
    <div class="box-header">
        <h3 class="box-title"><?= __('Services') ?></h3>
    </div>
    <div class="box-body">
        <table id="rsto-booking-datatable" class="table table-bordered table-hover table-responsive rsto-datatable" width="100%" data-url="<?= $rsto_booking_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
            <thead>
                <tr>
                    <th><?= __('Numero Ticket') ?></th>
                    <th><?= __('Provider') ?></th>
                    <th><?= __('Service') ?></th>
                    <th><?= __('Total') ?></th>
                    <th><?= __('Date start') ?></th>
                    <th><?= __('Duration / Count') ?></th>
                    <th><?= __('Total Person') ?></th>
                    <th><?= __('Adult') ?></th>
                    <th><?= __('Children') ?></th>
                    <th><?= __('Etat') ?></th>
                    <th><?= __('Cost') ?></th>
                    <th><?= __('Email') ?></th>
                    <th><?= __('Phone') ?></th>
                    <th><?= __('Method') ?></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php endif ?>
<div class="modal fade rsto-modal" id="rsto-book-1-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New circuit") ?></h4>
            </div>
            <div class="modal-body">
                <h2>RSMandimby</h2>
                <p>
                    <b>Date: </b>02/14/20<br>
                    <b>Service: </b>4x4 rent<br>
                    <b>Duration: </b>2<br>
                    <b>Count: </b>1<br>
                    <b>Booking method : </b> Mail
                </p>
                <h2>Contact</h2>
                <p>
                    <b>Email : </b>xxxxx@xxx.xx<br>
                    <b>Phone : </b>XXX XX XXX XX
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade rsto-modal" id="rsto-book-2-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Hotel replacement") ?></h4>
            </div>
            <form class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-circuit-day-hotel"><?= __('Hotel') ?></label>
                        <select name="hotel" class="form-control rsto-select" data-required="true" id="rsto-circuit-day-hotel" data-url="/rsmandimby/circuits/hotels" data-placeholder="Choose an hotel"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-day-meal-plan"><?= __('Meal plan') ?></label>
                        <select name="meal_plan" class="form-control" data-required="true" id="rsto-circuit-day-meal-plan" data-url="" data-placeholder="<?= __("Choose a meal plan") ?>">
                            <option>BO</option>
                            <option>BB</option>
                            <option>HB</option>
                            <option>FB</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?= __('Room list') ?></label>
                        <table id="rsto-circuit-day-hotel-rooms-datatatable" width="100%" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th><?= __('Room') ?></th>
                                    <th><?= __('Count') ?></th>
                                    <th><?= __('Pax') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Double</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>Suite</td>
                                    <td>1</td>
                                    <td>3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" id="rsto-circuit-day-room-list-add-btn"><i class="fa fa-plus"></i> <?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left"><i class="fa fa-edit"></i> <?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left"><i class="fa fa-trash"></i> <?= __('Delete') ?></button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('Finish') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade rsto-modal" id="rsto-book-3-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Vehicle replacement") ?></h4>
            </div>
            <form class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                  <div class="form-group">
                    <label for="rsto-circuit-day-driver"><?= __('Carrier') ?></label>
                    <select class="form-control" data-required="true" id="rsto-circuit-day-driver" data-url="" data-placeholder="<?= __("Choose a carrier") ?>">
                        <option>RSMandimby Transport</option>
                        <option selected>Coopérative Diary</option>
                        <option>Sandratra Transport</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="rsto-circuit-day-driver"><?= __('Vehicle type') ?></label>
                    <select class="form-control" data-required="true" id="rsto-circuit-day-driver" data-url="" data-placeholder="<?= __("Choose a vehicle type") ?>">
                        <option>4x4</option>
                        <option selected>Berline</option>
                        <option>SUV</option>
                    </select>
                  </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('Finish') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade rsto-modal" id="rsto-book-filter-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Filter booking") ?></h4>
            </div>
            <form class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                  <div class="form-group">
                    <label for=""><?= __('Circuit') ?></label>
                    <select class="form-control " data-required="true" data-url="" data-placeholder="<?= __("Choose a circuit") ?>">
                        <option>MAK 1245</option>
                        <option>TOK 4444</option>
                        <option>ABC 3478</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for=""><?= __('Provider') ?></label>
                    <select class="form-control" data-required="true" data-url="" data-placeholder="<?= __("Choose a vehicle type") ?>">
                        <option>Rakoto Bizard</option>
                        <option>Rasendra Sarotra</option>
                        <option>Ralay Dolpika</option>
                    </select>
                  </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('Filter') ?></button>
            </div>
        </div>
    </div>
</div>
