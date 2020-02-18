modal-xl<?php $this->start('rsto_circuit_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New circuit") ?></h4>
            </div>
            <form id="rsto-circuit-form" name="rsto-circuit-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="<?= $rsto_circuits_add_url ?>" data-edit-url="<?= $rsto_circuits_edit_url ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-circuit-tour-operateur"><?= __('Tour Operator') ?></label>
                        <select name="tour_operator" class="form-control" data-required="false" id="rsto-circuit-tour-operator" data-url="<?= $rsto_circuits_select2_data_url ?>" data-placeholder="<?= __("Choose a tour operator") ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-tour-operateur"><?= __('Currency') ?></label>
                        <select name="currency" class="form-control" data-required="false" id="rsto-circuit-currency" data-url="<?= $rsto_circuit_currency_url ?>" data-placeholder="<?= __("Choose a currency") ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-num-vol"><?= __('Num. vol') ?></label>
                        <input name="num_vol" type="text" class="form-control" data-required="true" id="rsto-circuit-num-vol" placeholder="<?= __("Num. vol") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-start"><?= __('Start') ?></label>
                        <input name="start" type="date" class="form-control date-validation" data-required="true" id="rsto-circuit-start" placeholder="<?= __("Pick the start date") ?>" value="<?= date("Y-m-d")?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-time"><?= __('Arriving time') ?></label>
                        <input name="arriving_time" type="time" class="form-control date-validation" data-required="true" id="rsto-circuit-time" placeholder="<?= __("time") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-length"><?= __('Stay length') ?></label>
                        <input name="duration" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-length" placeholder="<?= __("length") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-pax"><?= __('Adult count') ?></label>
                        <input name="adults" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-adult" placeholder="<?= __("Pax") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-pax"><?= __('Children count') ?></label>
                        <input name="childrens" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-child" placeholder="<?= __("Pax") ?>">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-driving-mode"><?= __('Driving mode') ?></label>
                        <select name="self_drive" class="form-control" data-required="false" id="rsto-circuit-drive" data-url="" data-placeholder="<?= __("Choose the driving mode") ?>">
                            <option value="1">Self</option>
                            <option value="0">Driver</option>
                        </select>
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
<?php $this->end() ?>

<?php $this->start('rsto_circuit_days_modal') ?>
<?php if (CAN_10_1) : ?>
    <div class="modal fade" id="rsto-circuit-days-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <table id="rsto-circuit-days-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_circuit_daily_datable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                        <thead>
                            <tr>
                                <th><?= __('Day') ?></th>
                                <th><?= __('Date') ?></th>
                                <th><?= __('Place') ?></th>
                                <th><?= __('Hotel') ?></th>
                                <th><?= __('Specify') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <?php if (CAN_10_2) : ?>
                        <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-circuit-day-configure-btn"><i class="fa fa-gears"></i>&nbsp;<?= __('Configure') ?></button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-carrier-price-close-btn"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<?php $this->end() ?>

<?php $this->start('rsto_circuit_day_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-day-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="rsto-trip-det-form" name="rsto-circuit-day-form" class="rsto-form" role="form" data-ignore-validation="true" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="<?= $rsto_circuit_trip_det_edit_url ?>" data-specify-url="<?= $rsto_circuit_edit_specify?>" data-next-url="<?=$rsto_daily_next_place_url?>" data-room-url="<?= $rsto_circuit_edit_room_hotel?>">
                <div class="modal-body" style="background-color: #f0f0f0">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Place</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="id_places" class="form-control rsto-select2" id="rsto-circuit-place" data-url="<?= $rsto_circuits_places_select ?>" data-placeholder="Choose a place"></select>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Driving information</h3>
                        </div>
                          <div class="box-body">
                            <div class="form-group">
                              <label for="rsto-circuit-day-driver"><?= __('Carrier') ?></label>
                              <select name="carrier" class="form-control rsto-select2"  id="rsto-circuit-day-driver" data-url="<?= $rsto_circuits_carrier_select ?>" data-placeholder="<?= __("Choose a carrier") ?>">

                              </select>
                            </div>
                            <!--div class="form-group">
                              <label for="rsto-circuit-day-driver"><-?= __('Driver') ?></label>
                              <select class="form-control" data-required="true" id="rsto-circuit-day-driver" data-url="" data-placeholder="<?= __("Choose a driver") ?>">
                                  <option>Rakoto Bizard</option>
                                  <option selected>Rasendra Sarotra</option>
                                  <option>Ralay Dolpika</option>
                              </select>
                            </div-->
                            <div class="form-group">
                              <label for="rsto-circuit-day-driver"><?= __('Vehicle') ?></label>
                              <select name="type_vehicle" class="form-control rsto-select2"  id="rsto_circuits_vehicle_select" data-url="<?= $rsto_circuits_vehicle_type_select ?>" data-placeholder="<?= __("Choose a car") ?>">
                              </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary pull-right" id="rsto-circuit-always-drive-form-submit-btn" data-url="<?= $rsto_circuit_always_use_url ?>" ><i class="fa fa-copy"></i> <?= __('Always use these informations') ?></button>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Accomodation</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="rsto-circuit-day-hotel"><?= __('Hotel') ?></label>
                                <select name="hotel" class="form-control rsto-select2"  id="rsto-circuit-day-hotel" data-url="<?= $rsto_circuits_hotel_select ?>" data-placeholder="Choose an hotel"></select>
                            </div>
                            <div class="form-group">
                                <label for="rsto-circuit-meal-plan"><?= __('Meal plan') ?></label>
                                <select name="id_select_option" class="form-control rsto-select2" id="rsto-circuit-meal-plan" data-url="<?= $rsto_circuits_meal_select ?>" data-placeholder="<?= __("Choose a meal plan") ?>">
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= __('Room list') ?></label>
                                <table id="rsto-circuit-day-hotel-rooms-datatatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_circuit_room_hotel_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                                    <thead>
                                        <tr>
                                            <th><?= __('Room') ?></th>
                                            <th><?= __('Count') ?></th>
                                            <th><?= __('Pax') ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="rsto-circuit-edit-room" type="button" class="btn btn-default"><i class="fa fa-edit"></i> <?= __('Edit') ?></button>
                            <button id="rsto-circuit-delete-room" type="button" class="btn btn-default"><i class="fa fa-trash"></i> <?= __('Delete') ?></button>
                            <button type="button" class="btn btn-primary pull-right" id="rsto-circuit-day-room-list-add-btn"><i class="fa fa-plus"></i> <?= __('Add') ?></button>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Specify</h3>
                        </div>
                        <div class="box-body">
                            <table id="rsto-circuit-day-specify-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_circuit_list_specify_by_trip ?>" data-x-csrf-token="<?= $x_csrf_token ?>" >
                                <thead>
                                    <tr>
                                        <th><?= __('Name') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button type="button" id="rsto-circuit-specify-edit-btn" class="btn btn-default"><i class="fa fa-edit"></i> <?= __('Edit') ?></button>
                            <button type="button" id="rsto-circuit-specify-delete-btn" class="btn btn-default"><i class="fa fa-trash"></i> <?= __('Delete') ?></button>
                            <button type="button" class="btn btn-primary pull-right" id="rsto-circuit-day-specify-add-btn"><i class="fa fa-plus"></i> <?= __('Add') ?></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" class="btn btn-primary" id="rsto-service-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>


<?php $this->start('rsto_circuit_day_specify_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-day-specify-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Add a specify") ?></h4>
            </div>
            <form id="rsto-circuit-service-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-get-url="<?= $rsto_circuit_list_specify_by_trip?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?= __('Service') ?></label>
                        <select name="service" id="rsto-circuit-list-circuit" class="form-control rsto-select" data-required="false" data-url="<?=$rsto_circuit_list_service_by_place?>" data-placeholder="<?= __("Choose a service") ?>"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="button" class="btn btn-primary" id="rsto-circuit-service-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>
<?php $this->start('rsto_circuit_day_room_list_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-day-room-add-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Add a room") ?></h4>
            </div>
            <form id="rsto-circuit-day-add-room-form" name="rsto-circuit-day-room-list-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="rsto-circuit-day-room-id">
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-type-plan"><?= __('Room type') ?></label>
                        <select name="id" class="form-control rsto-select" data-required="true" id="rsto-circuit-day-room-list-room-type-plan" data-url="<?= $rsto_circuit_room_add_url ?>" data-placeholder="<?= __("Choose a room type") ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-count"><?= __('Count') ?></label>
                        <input type="text" class="form-control" id="rsto-circuit-day-room-count-plan" placeholder="Room count">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-pax"><?= __('Pax') ?></label>
                        <input type="text" class="form-control" id="rsto-circuit-day-pax-plan" placeholder="Room pax">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="button" class="btn btn-primary" id="rsto-circuit-day-room-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>
