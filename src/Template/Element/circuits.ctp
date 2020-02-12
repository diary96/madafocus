modal-xl<?php $this->start('rsto_circuit_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("New circuit") ?></h4>
            </div>
            <form id="rsto-service-form" name="rsto-circuit-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-circuit-tour-operateur"><?= __('Tour Operator') ?></label>
                        <select name="type" class="form-control" data-required="true" id="rsto-circuit-tour-operator" data-url="" data-placeholder="<?= __("Choose a tour operator") ?>">
                            <option>Coco Travel</option>
                            <option selected>Matoke Tour</option>
                            <option>Over Sea Travel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-start"><?= __('Start') ?></label>
                        <input name="start" type="text" class="form-control date-validation" data-required="true" id="rsto-circuit-departure" placeholder="<?= __("Pick the start date") ?>" value="11/10/2020">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-length"><?= __('Stay length') ?></label>
                        <input name="length" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-length" placeholder="<?= __("length") ?>" value="10">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-pax"><?= __('Adult count') ?></label>
                        <input name="adult-count" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-departure" placeholder="<?= __("Pax") ?>" value="15">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-pax"><?= __('Children count') ?></label>
                        <input name="children-count" type="text" class="form-control numeric-validation" data-required="true" id="rsto-circuit-departure" placeholder="<?= __("Pax") ?>" value="15">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-driving-mode"><?= __('Driving mode') ?></label>
                        <select name="driving-mode" class="form-control" data-required="true" id="rsto-circuit-tour-operator" data-url="" data-placeholder="<?= __("Choose the driving mode") ?>">
                            <option>Self</option>
                            <option>Driver</option>
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
                    <h4 class="modal-title">MAK-100 itinerary</h4>
                </div>
                <div class="modal-body">
                    <table id="rsto-circuit-days-datatable" width="100%" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th><?= __('Day') ?></th>
                                <th><?= __('Date') ?></th>
                                <th><?= __('Place') ?></th>
                                <th><?= __('Hotel') ?></th>
                                <th><?= __('Specify') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>12/12/2020</td>
                                <td>Antsirabe</td>
                                <td>Zandina Hotel</td>
                                <td>&laquo;Undefined&raquo;</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>12/13/2020</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>12/14/2020</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>12/15/2020</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>12/16/2020</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                                <td>&laquo;Undefined&raquo;</td>
                            </tr>
                        </tbody>
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
                <h4 class="modal-title"><?= __("Day 1") ?></h4>
            </div>
            <form id="rsto-service-form" name="rsto-circuit-day-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body" style="background-color: #f0f0f0">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Place</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="hotel" class="form-control rsto-select" data-required="true" id="rsto-circuit-day-hotel" data-url="/rsmandimby/circuits/places" data-placeholder="Choose a place"></select>
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
                              <select class="form-control" data-required="true" id="rsto-circuit-day-driver" data-url="" data-placeholder="<?= __("Choose a carrier") ?>">
                                  <option>RSMandimby Transport</option>
                                  <option selected>Coopérative Diary</option>
                                  <option>Sandratra Transport</option>
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
                              <select class="form-control" data-required="true" id="rsto-circuit-day-driver" data-url="" data-placeholder="<?= __("Choose a driver") ?>">
                                  <option>14 568 WWT</option>
                                  <option selected>1556 TBL</option>
                                  <option>0103 TAV</option>
                              </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary pull-right"><i class="fa fa-copy"></i> <?= __('Always use these informations') ?></button>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Accomodation</h3>
                        </div>
                        <div class="box-body">
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
                        <div class="box-footer">
                            <button type="button" class="btn btn-default"><i class="fa fa-edit"></i> <?= __('Edit') ?></button>
                            <button type="button" class="btn btn-default"><i class="fa fa-trash"></i> <?= __('Delete') ?></button>
                            <button type="button" class="btn btn-primary pull-right" id="rsto-circuit-day-room-list-add-btn"><i class="fa fa-plus"></i> <?= __('Add') ?></button>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Specify</h3>
                        </div>
                        <div class="box-body">
                            <table id="rsto-circuit-day-specify-datatable" width="100%" class="table table-bordered table-hover table-responsive rsto-datatable" data-x-csrf-token="<?= $x_csrf_token ?>" data-url="/rsmandimby/circuits/specify">
                                <thead>
                                    <tr>
                                        <th><?= __('Name') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-default"><i class="fa fa-edit"></i> <?= __('Edit') ?></button>
                            <button type="button" class="btn btn-default"><i class="fa fa-trash"></i> <?= __('Delete') ?></button>
                            <button type="button" class="btn btn-primary pull-right" id="rsto-circuit-day-specify-add-btn"><i class="fa fa-plus"></i> <?= __('Add') ?></button>
                        </div>
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

<?php $this->start('rsto_circuit_day_room_list_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-day-room-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Add a room") ?></h4>
            </div>
            <form id="rsto-service-form" name="rsto-circuit-day-room-list-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-type-plan"><?= __('Room type') ?></label>
                        <select name="room_type" class="form-control rsto-select" data-required="true" id="rsto-circuit-day-room-list-room-type-plan" data-url="/rsmandimby/circuits/room_type_select2" data-placeholder="<?= __("Choose a room type") ?>"></select>
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-count"><?= __('Count') ?></label>
                        <input type="text" class="form-control" placeholder="Room count">
                    </div>
                    <div class="form-group">
                        <label for="rsto-circuit-day-room-list-room-pax"><?= __('Pax') ?></label>
                        <input type="text" class="form-control" placeholder="Room pax">
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

<?php $this->start('rsto_circuit_day_specify_modal') ?>
<div class="modal fade rsto-modal" id="rsto-circuit-day-specify-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= __("Add a specify") ?></h4>
            </div>
            <form id="rsto-service-form" class="rsto-form" role="form" data-x-csrf-token="<?= $x_csrf_token ?>" data-action-url="" data-edit-url="">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?= __('Service') ?></label>
                        <select name="service" class="form-control rsto-select" data-required="true" data-url="/rsmandimby/circuits/room_type_select2" data-placeholder="<?= __("Choose a service") ?>"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                        <button type="submit" class="btn btn-primary disabled" id="rsto-service-form-submit-btn"><?= __('Save') ?>&nbsp;</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>
