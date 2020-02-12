const RSTOPark = {
    xCSRFToken: null,
    table: $('#rsto-park-datatable'),
    datatable: null,
    modal: $('#rsto-park-modal'),
    form: $('#rsto-park-form'),
    fields: {
        name: $('#rsto-park-name'),
        place: $('#rsto-park-place'),
        adult: $('#rsto-park-adult-costing-entrance-fee'),
        children: $('#rsto-park-children-costing-entrance-fee')
    },
    buttons: {
        add: $('#rsto-park-add-btn'),
        edit: $('#rsto-hotel-edit-btn'),
        delete: $('#rsto-park-delete-btn'),
        prices: $('#rsto-park-selling-entrance-fees-btn')
    },
    entranceFee : {
        table: $('#rsto-park-selling-entrance-fee-datatable'),
        datatable: null,
        listModal: $('#rsto-park-selling-entrance-fee-list-modal'),
        buttons: {
            add: $('#rsto-park-selling-entrance-fee-add-btn'),
            edit: $('#rsto-park-selling-entrance-fee-edit-btn'),
            delete: $('#rsto-park-selling-entrance-fee-delete-btn')
        }
    },
    init: function () {
        var _me = this;

        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'name'},
            {'data': 'place_name'},
            {'data': 'adult_costing_entrance_fee'},
            {'data': 'children_costing_entrance_fee'}
        ]);

        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            _me.buttons.prices.RSTOEnable();

            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_park': data.id}, 'data-edit-url');

            // Selling prices datatable
            _me.entranceFee.table.RSTODataURLQuery({'id_park': data.id});
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.prices.RSTODisable();
        });

        // When modal is shown
        _me.modal.on('shown.bs.modal', function () {
            _me.fields.name.focus();
        });

        // Add park
        _me.buttons.add.click(function () {
            _me.modal.modal('show');
        });

        // After form submission
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                var _editMode = _me.form.attr('data-edit');
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // When place is changed, name must be revalidated
        _me.fields.place.on('change.select2', function () {
            if ($(this).val() !== null) {
                _me.fields.name.RSTODataURLQuery({'id_place': _me.fields.place.val()}, 'data-validation-url').trigger('keyup');
            }
        });

        // Editing park
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();

            // Fill the form
            _me.fields.name.RSTOOriginalValue(_data.name);
            _me.fields.place.RSTOOriginalValue(_data.id_place, _data.place_name);
            _me.fields.adult.RSTOOriginalValue(_data.adult_costing_entrance_fee);
            _me.fields.children.RSTOOriginalValue(_data.children_costing_entrance_fee);

            // Show modal
            _me.modal.modal('show');
        });

        // Delete park
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response === true) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_park': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response === true) {
                            _me.datatable.ajax.reload();
                            alert(RSTOMessages.Deleted);
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });
        });
        
        // SELLING ENTRANCE FEE
        
        // Show list
        _me.buttons.prices.click(function(){
            _me.entranceFee.listModal.modal('show');
            // Init or refresh datatable
            if (_me.entranceFee.datatable === null) {
                _me.entranceFee.datatable = _me.entranceFee.table.RSTODatatable([
                    {'data': 'currency_name'},
                    {'data': 'adult'},
                    {'data': 'children'}
                ]);
            } else {
                _me.entranceFee.datatable.ajax.url(_me.entranceFee.table.attr('data-url')).load();
            }
        });
        
        // Manage entrance fees
        if (RSTOParkSellingEntranceFee) {
            var _price = $.extend(_me.entranceFee, RSTOParkSellingEntranceFee);
            
            _price.init();
            
            // Add selling entrance fee
            _price.buttons.add.click(function () {
                _price.modal.modal('show');
            });
            
            // Configure selling entrance fee form
            _me.table.on('selectionChanged.rsto', function (e, data) {
                _price.fields.park.val(data.id);
                _price.fields.currency.RSTODataURLQuery({'id_park': data.id});
            });
            
            // After selling entrance form submission
            _price.form.on('submitted.rsto', function () {
                _price.datatable.ajax.reload();
            });
            
            // Editing selling entrance fee
            _price.buttons.edit.click(function () {
                _price.edit(_price.table.RSTODatatableSelectedData());
            });
            
            // On selling entrance fee selected
            _price.table.on('selectionChanged.rsto', function (e, data) {
                // Enable buttons
                _price.buttons.edit.RSTOEnable();
                _price.buttons.delete.RSTOEnable();
                
                // Update data-edit-url
                _price.form.RSTODataURLQuery({'id_park_selling_entrance_fee': data.id}, 'data-edit-url');
            });
            
            _price.table.on('draw.dt', function () {
                // Disable buttons
                _price.buttons.edit.RSTODisable();
                _price.buttons.delete.RSTODisable();
            });
            
            // Delete selling entrance fee
            _price.buttons.delete.click(function(){
                confirm(RSTOMessages.ConfirmDelete, function(response){
                    if(response === true){
                        RSTOGetJSON(_price.buttons.delete.attr('data-url'), {'id_park_selling_entrance_fee': _price.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
                            if(response === true){
                                _price.datatable.ajax.reload();
                                alert(RSTOMessages.Deleted);
                            } else {
                                alert(RSTOMessages.Error);
                            }
                        });
                    }
                });
            });
        }
    }
};

$(window).on('load', function () {
    RSTOPark.init();
    if (RSTOPlace) {
        RSTOPlace.init('#rsto-park-add-new-place', RSTOPark);
    }

});