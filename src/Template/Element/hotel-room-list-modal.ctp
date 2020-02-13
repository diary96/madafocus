<?php $this->start('rsto_hotel_room_list_modal') ?>
<div class="modal modal-xl fade" id="rsto-hotel-room-list-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-hotel-rooms-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_hotel_room_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Capacity') ?></th>
                            <th><?= __('BO') ?></th>
                            <th><?= __('DU') ?></th>
                            <th><?= __('BB') ?></th>
                            <th><?= __('HB') ?></th>
                            <th><?= __('FB') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_5_2) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-hotel-room-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-hotel-room-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-hotel-room-manage-price-btn"><i class="fa fa-money"></i>&nbsp;<?= __('Selling prices') ?></button>
                <?php if(CAN_5_2) : ?>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-hotel-room-delete-btn" data-url="<?= $rsto_hotel_room_deleted_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-room-list-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>

