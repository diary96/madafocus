<?php $this->start('rsto_hotel_transfer_selling_price_list_modal') ?>
<div class="modal fade" id="rsto-hotel-transfer-selling-price-list-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table id="rsto-transfer-selling-price-datatable" width="100%" class="table table-bordered table-hover table-responsive" data-url="<?= $rsto_hotel_transfer_selling_price_datatable_url ?>" data-x-csrf-token="<?= $x_csrf_token ?>">
                    <thead>
                        <tr>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Going cost price') ?></th>
                            <th><?= __('Coming cost price') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <?php if(CAN_5_3) : ?>
                <button type="button" class="btn btn-primary pull-left" id="rsto-hotel-transfer-selling-price-add-btn"><i class="fa fa-plus"></i>&nbsp;<?= __('Add') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-hotel-transfer-selling-price-edit-btn"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></button>
                <button type="button" class="btn btn-default pull-left disabled" disabled id="rsto-hotel-transfer-selling-price-delete-btn" data-url="<?= $rsto_hotel_transfer_selling_price_delete_url ?>"><i class="fa fa-trash"></i>&nbsp;<?= __('Delete') ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="rsto-hotel-transfer-selling-price-list-close-btn"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>



