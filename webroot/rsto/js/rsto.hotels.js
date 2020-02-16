//<editor-fold desc="Hotel" defaultstate="collapsed">
const RSTOHotel = {
    xCSRFToken: null,
    table: $('#rsto-hotels-datatable'),
    datatable: null,
    modal: $('#rsto-hotel-modal'),
    form: $('#rsto-hotel-form'),
    fields: {
        name: $('#rsto-hotel-name'),
        place: $('#rsto-hotel-place'),
        reservationEmailAddress: $('#rsto-hotel-reservation-email-address'),
        reservationPhoneNumber: $('#rsto-hotel-reservation-phone-number'),
        haveRestaurant: $('#rsto-hotel-have-restaurant'),
        doTransfer: $('#rsto-hotel-do-tranfer')
    },
    buttons: {
        add: $('#rsto-add-hotel-btn'),
        addPlace: $('#rsto-hotel-add-new-place'),
        edit: $('#rsto-edit-hotel-btn'),
        rooms: $('#rsto-manage-hotel-rooms-btn'),
        transfers: $('#rsto-manage-hotel-transfers-btn'),
        delete: $('#rsto-delete-hotel-btn')
    },
    init: function () {
        var _me = RSTOHotel;
        _me.xCSRFToken = _me.table.attr('data-x-csrf-token');
        // Init Datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "name"},
            {"data": "place_name"},
            {"data": "reservation_email_address"},
            {"data": "reservation_phone_number"}
        ]);

        // Open hotel modal in order to add new hotel
        _me.buttons.add.click(function () {
            _me.modal.modal('show');
        });

        // Add hotel
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success) {
                _me.modal.modal('hide');
                alert(_me.form.attr('edit-mode') === true ? RSTOMessages.Updated : RSTOMessages.Added);
                _me.datatable.ajax.reload();
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // When an hotel is selected
        _me.table.on('selectionChanged.rsto', function (e, data) {
            _me.buttons.rooms.RSTOEnable();
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            if (data.do_transfer) {
                _me.buttons.transfers.RSTOEnable();
            } else {
                _me.buttons.transfers.RSTODisable();
            }


            // change form edit url
            _me.form.RSTODataURLQuery({'id_hotel': data.id}, 'data-edit-url');
        });

        // When datatable is redrawn
        _me.table.on('draw.dt', function () {
            _me.buttons.rooms.RSTODisable();
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.transfers.RSTODisable();
        });

        // When hotel's place is change, hotel's name must be revalidated
        _me.fields.place.on('change.select2', function (e, reset) {
            if (!(reset || false)) {
                _me.fields.name.RSTODataURLQuery({'place': _me.fields.place.val()}, 'data-validation-url').trigger('keyup');
            }
        });

        // Editing hotel
        _me.buttons.edit.click(function () {
            // Enter edit mode
            _me.form.attr('data-edit', 'true');

            // Fill the form
            var _data = _me.table.RSTODatatableSelectedData();
            _me.fields.name.RSTOOriginalValue(_data.name);
            _me.fields.place.RSTOOriginalValue(_data.id_place, _data.place_name);
            _me.fields.reservationEmailAddress.RSTOOriginalValue(_data.reservation_email_address);
            _me.fields.reservationPhoneNumber.RSTOOriginalValue(_data.reservation_phone_number);
            _me.fields.haveRestaurant.prop('checked', _data.have_restaurant === 1);
            _me.fields.doTransfer.prop('checked', _data.do_transfer === 1);

            // Show modal
            _me.modal.modal('show');
            _me.form.find('button[type=submit]').RSTODisable();
        });

        // Deleting hotel
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response === true) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hotel': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response === true) {
                            alert(RSTOMessages.Deleted);
                            _me.datatable.ajax.reload();
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });
        });
        
        // Add new place
        if(RSTOPlaces){
            RSTOPlaces.init('validation');
            _me.buttons.addPlace.click(function(){
                RSTOPlaces.modal.modal('show');
            });
            RSTOPlaces.form.on('submitted.rsto', function(e, response){
                _me.fields.place.append(new Option(response.row.name, response.row.id_place, true, true)).trigger('change.select2');
                RSTOPlaces.modal.modal('hide');
            });
        }   
    }
};
//</editor-fold>
//<editor-fold desc="Transfers" defaultstate="collapsed">
const RSTOHotelTransfer = {
    xCSRFToken: null,
    table: $('#rsto-transfer-datatable'),
    datatable: null,
    listModal: $('#rsto-hotel-transfer-list-modal'),
    modal: $('#rsto-hotel-transfer-modal'),
    buttons: {
        add: $('#rsto-hotel-transfer-add-btn'),
        edit: $('#rsto-hotel-transfer-edit-btn'),
        delete: $('#rsto-hotel-transfer-delete-btn'),
        prices: $('#rsto-hotel-transfer-manage-price-btn')
    },
    form: $('#rsto-hotel-transfer-form'),
    fields: {
        hotel: $('#rsto-hotel-transfer-hotel'),
        hub: $('#rsto-hotel-transfer-hub'),
        going: $('#rsto-hotel-transfer-going-cost-price'),
        coming: $('#rsto-hotel-transfer-coming-cost-price')
    },
    init: function () {
        var _me = this;
        var _hotel = RSTOHotel;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // When an hotel is selected
        _hotel.table.on('selectionChanged.rsto', function (e, data) {
            // Update hub select2 url
            _me.fields.hub.RSTODataURLQuery({id_hotel: data.id});
        });

        _hotel.buttons.transfers.click(function () {
            var _selectedHotel = _hotel.table.RSTODatatableSelectedData();
            // Change modal title
            _me.listModal.RSTOModalTitle("Transfer list - ".format(_selectedHotel.name));
            // Init datatable
            _me.table.RSTODataURLQuery({'id_hotel': _selectedHotel.id});
            if (_me.datatable === null) {
                _me.datatable = _me.table.RSTODatatable([
                    {data: 'hub_name'},
                    {data: 'going_cost_price'},
                    {data: 'coming_cost_price'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            // Show modal
            _me.listModal.modal('show');
        });

        // Add transfer
        _me.buttons.add.click(function () {
            var _selectedHotel = _hotel.table.RSTODatatableSelectedData();
            // Update modal title
            _me.modal.RSTOModalTitle("New transfer - {0}".format(_selectedHotel.name));
            _me.fields.hotel.val(_selectedHotel.id);
            // Show modal
            _me.modal.modal('show');
        });

        // After form submission
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // When a transfer is selected
        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            _me.buttons.prices.RSTOEnable();

            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_hotel_transfer': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.prices.RSTODisable();
        });

        // Edit transfer
        _me.buttons.edit.click(function () {
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');

            // Update modal title
            _me.modal.RSTOModalTitle("Editing transfer : {0} to {1}".format(_hotel.table.RSTODatatableSelectedData().name, _data.hub_name));

            // Fill the form
            _me.fields.hub.RSTOOriginalValue(_data.id_hub, _data.hub_name);
            _me.fields.going.RSTOOriginalValue(_data.going_cost_price);
            _me.fields.coming.RSTOOriginalValue(_data.coming_cost_price);

            // Show modal
            _me.modal.modal('show');
        });

        // Delete transfer
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hotel_transfer': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response) {
                            _me.datatable.ajax.reload();
                            alert(RSTOMessages.Deleted);
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });
        });

        // Manage selling prices

    }
};
//</editor-fold>
// <editor-fold desc="Transfers selling prices" defaultstate="collapsed">
const RSTOTransferSellingPrice = {
    xCSRFToken: null,
    listModal: $('#rsto-hotel-transfer-selling-price-list-modal'),
    modal: $('#rsto-hotel-transfer-selling-price-modal'),
    table: $('#rsto-transfer-selling-price-datatable'),
    datatable: null,
    form: $('#rsto-hotel-transfer-selling-price-form'),
    fields: {
        transfer: $('#rsto-hotel-transfer-selling-price-hotel-transfer'),
        currency: $('#rsto-hotel-transfer-selling-price-currency'),
        going: $('#rsto-hotel-transfer-going-price'),
        coming: $('#rsto-hotel-transfer-coming-price')
    },
    buttons: {
        add: $('#rsto-hotel-transfer-selling-price-add-btn'),
        edit: $('#rsto-hotel-transfer-selling-price-edit-btn'),
        delete: $('#rsto-hotel-transfer-selling-price-delete-btn')
    },
    init: function () {
        var _transfer = RSTOHotelTransfer;
        var _hotel = RSTOHotel;
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        _transfer.buttons.prices.click(function () {
            // Init datatable
            var _selectedTransfer = _transfer.table.RSTODatatableSelectedData();
            _me.table.RSTODataURLQuery({id_hotel_transfer: _selectedTransfer.id});
            if (_me.datatable === null) {
                _me.datatable = _me.table.RSTODatatable([
                    {data: 'currency_name'},
                    {data: 'going_price'},
                    {data: 'coming_price'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }

            // Update modal title
            _me.listModal.RSTOModalTitle("Transfer price list : {0} to {1}".format(_hotel.table.RSTODatatableSelectedData().name, _transfer.table.RSTODatatableSelectedData().hub_name));

            // Show modal
            _me.listModal.modal('show');
        });

        _transfer.table.on('selectionChanged.rsto', function (e, data) {
            // Update currency select2 data-url
            _me.fields.currency.RSTODataURLQuery({'id_hotel_transfer': data.id});

            // Update hotel_transfer input value
            _me.fields.transfer.val(data.id);
        });

        // Add price
        _me.buttons.add.click(function () {
            // Update modal title
            _me.modal.RSTOModalTitle("New transfer selling price : {0} to {1}".format(_hotel.table.RSTODatatableSelectedData().name, _transfer.table.RSTODatatableSelectedData().hub_name));

            _me.modal.modal('show');
        });

        // After form submission
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success) {
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // When a selling price is selected
        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Disable button
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();

            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_hotel_transfer_selling_price': data.id}, 'data-edit-url');
        });

        // When datatable is redrawn
        _me.table.on('draw.dt', function () {
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });

        // Edit selling price
        _me.buttons.edit.click(function () {
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');

            // Update modal title
            _me.modal.RSTOModalTitle("Edit transfer selling price : {0} to {1}".format(_hotel.table.RSTODatatableSelectedData().name, _transfer.table.RSTODatatableSelectedData().hub_name));

            // Fill the form
            _me.fields.transfer.val(_transfer.table.RSTODatatableSelectedData().id);
            _me.fields.currency.RSTOOriginalValue(_data.id_currency, _data.currency_name);
            _me.fields.going.RSTOOriginalValue(_data.going_price);
            _me.fields.coming.RSTOOriginalValue(_data.coming_price);

            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete selling price
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hotel_transfer_selling_price': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
                        if(response){
                            _me.datatable.ajax.reload();
                            alert(RSTOMessages.Deleted);
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });
        });
    }
};
// </editor-fold>
//<editor-fold desc="Rooms" defaultstate="collapsed">
const RSTOHotelRoom = {
    xCSRFToken: null,
    table: $('#rsto-hotel-rooms-datatable'),
    datatable: null,
    listModal: $('#rsto-hotel-room-list-modal'),
    modal: $('#rsto-hotel-room-modal'),
    form: $('#rsto-hotel-room-form'),
    fields: {
        hotel: $('#rsto-hotel-room-hotel'),
        type: $('#rsto-hotel-room-type'),
        capacity: $('#rsto-hotel-room-capacity'),
        bo: $('#rsto-hotel-room-bo'),
        du: $('#rsto-hotel-room-du'),
        bb: $('#rsto-hotel-room-bb'),
        hb: $('#rsto-hotel-room-hb'),
        fb: $('#rsto-hotel-room-fb')
    },
    buttons: {
        add: $('#rsto-hotel-room-add-btn'),
        edit: $('#rsto-hotel-room-edit-btn'),
        prices: $('#rsto-hotel-room-manage-price-btn'),
        delete: $('#rsto-hotel-room-delete-btn')
    },
    init: function () {
        var _me = RSTOHotelRoom;
        var hotel = RSTOHotel;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Configure dependencies
        hotel.datatable.on('selectionChanged.rsto', function (e, data) {
            // Update id_hotel on room form
            _me.fields.hotel.val(data.id);

            // Update room type's select2 URL
            // Room wich are on in the room datatable should be exculded from the select2
            // We add the id_hotel to the select2 query so server can filter the list
            _me.fields.type.RSTODataURLQuery({'id_hotel': data.id});
        });

        // Show room list
        hotel.buttons.rooms.click(function () {
            var _selectedHotel = hotel.table.RSTODatatableSelectedData();
            // Update room's datatable url so the room list will be filtered by hotel
            _me.table.RSTODataURLQuery({'id_hotel': _selectedHotel.id});
            if (_me.datatable === null) {
                _me.datatable = _me.table.RSTODatatable([
                    {"data": "type_name"},
                    {"data": "capacity"},
                    {"data": "bo_cost_price"},
                    {"data": "du_cost_price"},
                    {"data": "bb_cost_price"},
                    {"data": "hb_cost_price"},
                    {"data": "fb_cost_price"}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            // Update modal title
            _me.listModal.RSTOModalTitle("Room list - {0}".format(_selectedHotel.name));
            
            _me.listModal.modal('show');
        });

        // Adding room
        _me.buttons.add.click(function () {
            // Update modal title
            _me.modal.RSTOModalTitle("New room - " + hotel.table.RSTODatatableSelectedData().name);
            
            _me.modal.modal('show');
        });

        // When form is submitted
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                _me.modal.modal('hide');
                alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
                _me.datatable.ajax.reload();
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // When a room is selected in datatable
        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Update room edit url
            _me.form.RSTODataURLQuery({'id_hotel_room': data.id}, 'data-edit-url');

            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.prices.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
        });

        // When room datatable is redrawn
        _me.table.on('draw.dt', function () {
            _me.buttons.edit.RSTODisable();
            _me.buttons.prices.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });

        // Editing room
        _me.buttons.edit.click(function () {
            var _data = _me.table.RSTODatatableSelectedData();
            // Fill the form
            _me.form.attr('data-edit', 'true');
            _me.fields.hotel.val(_data.id_hotel);
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            _me.fields.capacity.RSTOOriginalValue(_data.capacity);
            _me.fields.du.RSTOOriginalValue(_data.du_cost_price);
            _me.fields.bo.RSTOOriginalValue(_data.bo_cost_price);
            _me.fields.bb.RSTOOriginalValue(_data.bb_cost_price === '-' ? '' : _data.bb_cost_price);
            _me.fields.hb.RSTOOriginalValue(_data.hb_cost_price === '-' ? '' : _data.bb_cost_price);
            _me.fields.fb.RSTOOriginalValue(_data.fb_cost_price === '-' ? '' : _data.bb_cost_price);
            
            // Update modal title
            _me.modal.RSTOModalTitle("Edit room - " + hotel.table.RSTODatatableSelectedData().name);
            
            // Show modal
            _me.modal.modal('show');
        });

        // Deleting room
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response === true) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hotel_room': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response === true) {
                            alert(RSTOMessages.Deleted);
                            _me.datatable.ajax.reload();
                        } else {
                            alert(RSTOMessages.Deleted);
                        }
                    });
                }
            });
        });
    }
};
//</editor-fold>
//<editor-fold desc="Room selling prices" defaultstate="collapsed">
const RSTOHotelRoomSellingPrice = {
    xCSRFToken: null,
    listModal: $('#rsto-hotel-room-selling-price-list-modal'),
    modal: $('#rsto-hotel-room-selling-price-modal'),
    table: $('#rsto-hotel-room-selling-price-datatable'),
    datatable: null,
    form: $('#rsto-hotel-room-selling-price-form'),
    fields: {
        hotelRoom: $('#rsto-hotel-room-selling-price-hotel-room'),
        currency: $('#rsto-hotel-room-selling-price-currency'),
        bo: $('#rsto-hotel-room-bo-selling-price'),
        du: $('#rsto-hotel-room-du-selling-price'),
        bb: $('#rsto-hotel-room-bb-selling-price'),
        hb: $('#rsto-hotel-room-hb-selling-price'),
        fb: $('#rsto-hotel-room-fb-selling-price')
    },
    buttons: {
        add: $('#rsto-hotel-room-selling-price-add-btn'),
        edit: $('#rsto-hotel-room-selling-price-edit-btn'),
        delete: $('#rsto-hotel-room-selling-price-delete-btn')
    },
    init: function () {
        var room = RSTOHotelRoom;
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Configure dependencies
        room.table.on('selectionChanged.rsto', function (e, data) {
            // Change prices datatable url
            _me.table.RSTODataURLQuery({'id_hotel_room': data.id});

            // Update the hotel_room input on the form
            _me.fields.hotelRoom.val(data.id);

            // Update currency select2 data-url so server can filter currencies
            _me.fields.currency.RSTODataURLQuery({'id_hotel_room': data.id});
        });

        // Show price list
        room.buttons.prices.click(function () {
            // Init datatables
            if (_me.datatable === null) {
                _me.datatable = _me.table.RSTODatatable([
                    {"data": "currency_name"},
                    {"data": "bo"},
                    {"data": "du"},
                    {"data": "bb"},
                    {"data": "hb"},
                    {"data": "fb"}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            // Update modal title
            _me.listModal.RSTOModalTitle("Price list - " + room.table.RSTODatatableSelectedData().type_name);
            
            _me.listModal.modal('show');
        });

        // Add price
        _me.buttons.add.click(function () {
            // Update modal title
            _me.listModal.RSTOModalTitle("New selling price - " + room.table.RSTODatatableSelectedData().type_name);
            
            _me.modal.modal('show');
        });

        // After form submission
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                _me.modal.modal('hide');
                alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
                _me.datatable.ajax.reload();
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // Enabling buttons
        _me.table.on('selectionChanged.rsto', function (e, data) {
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();

            // Update form data-edit-url
            _me.form.RSTODataURLQuery({'id_hotel_room_selling_price': data.id}, 'data-edit-url');
        });

        // Disabling buttons
        _me.table.on('draw.dt', function () {
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });

        // Editing price
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            // Fill the form
            var _data = _me.table.RSTODatatableSelectedData();
            _me.fields.currency.RSTOOriginalValue(_data.id_currency, _data.currency_name);
            _me.fields.bo.RSTOOriginalValue(_data.bo);
            _me.fields.du.RSTOOriginalValue(_data.du);
            _me.fields.bb.RSTOOriginalValue(_data.bb === '-' ? '' : _data.bb);
            _me.fields.hb.RSTOOriginalValue(_data.hb === '-' ? '' : _data.hb);
            _me.fields.fb.RSTOOriginalValue(_data.fb === '-' ? '' : _data.fb);
            
            // Update modal title
            _me.listModal.RSTOModalTitle("Edit selling price - " + room.table.RSTODatatableSelectedData().type_name);
            
            // Show modal
            _me.modal.modal('show');
        });

        // Deleting price
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response === true) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hotel_room_selling_price': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response === true) {
                            alert(RSTOMessages.Deleted);
                            _me.datatable.ajax.reload();
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });
        });
    }
};
//</editor-fold>

$(window).on('load', function () {
    RSTOHotel.init();
    RSTOHotelTransfer.init();
    RSTOTransferSellingPrice.init();
    RSTOHotelRoom.init();
    RSTOHotelRoomSellingPrice.init();
});