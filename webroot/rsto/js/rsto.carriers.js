//<editor-fold desc="Carriers" defaultstate="collapsed">
const RSTOCarrier = {
    xCSRFToken: null,
    table: $('#rsto-carrier-datatable'),
    datatable: null,
    modal: $('#rsto-carrier-modal'),
    form: $('#rsto-carrier-form'),
    fields: {
        title: $('#rsto-directory-title'),
        fullname: $('#rsto-directory-fullname'),
        description: $('#rsto-directory-description'),
        phoneNumber: $('#rsto-carrier-booking-phone-number'),
        emailAddress: $('#rsto-carrier-booking-email-address'),
    },
    buttons: {
        add: $('#rsto-carrier-add-btn'),
        edit: $('#rsto-carrier-edit-btn'),
        prices: $('#rsto-carrier-prices-btn'),
        vehicles: $('#rsto-carrier-vehicle-btn'),
        drivers: $('#rsto-carrier-drivers-btn'),
        delete: $('#rsto-carrier-delete-btn')
    },
    init: function(){
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'title_name'},
            {'data': 'fullname'},
            {'data': 'booking_phone_number'},
            {'data': 'booking_mail_address'},
            {'data': 'description'}
        ]);
        
        // Add carrier
        _me.buttons.add.click(function(){
            // Update modal title
            _me.modal.RSTOModalTitle('New carrier');
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // When a carrier is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.prices.RSTOEnable();
            _me.buttons.vehicles.RSTOEnable();
            _me.buttons.drivers.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_carrier': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.prices.RSTODisable();
            _me.buttons.vehicles.RSTODisable();
            _me.buttons.drivers.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        // Edit carrier
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');
            
            // Fill the form
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.description.RSTOOriginalValue(_data.description);
            _me.fields.phoneNumber.RSTOOriginalValue(_data.booking_phone_number);
            _me.fields.emailAddress.RSTOOriginalValue(_data.booking_mail_address);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete carrier
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_carrier': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
//</editor-fold>
//<editor-fold desc="Prices" defaultstate="collapsed">
const RSTOCarrierVehiclePrice = {
    xCSRFToken: null,
    table: $('#rsto-carrier-price-datatable'),
    datatable: null,
    listModal: $('#rsto-carrier-price-list-modal'),
    modal: $('#rsto-carrier-price-modal'),
    form: $('#rsto-carrier-price-form'),
    fields: {
        carrier: $('#rsto-carrier-price-carrier'),
        type: $('#rsto-carrier-price-vehicle-type'),
        full: $('#rsto-carrier-full-price'),
        half: $('#rsto-carrier-half-price')
    },
    buttons: {
        add: $('#rsto-carrier-price-add-btn'),
        edit: $('#rsto-carrier-price-edit-btn'),
        prices: $('#rsto-carrier-price-manage-price-btn'),
        delete: $('#rsto-carrier-price-delete-btn')
    },
    init: function(){
        var _me = this;
        var _carrier = RSTOCarrier;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // Show price list
        _carrier.buttons.prices.click(function(){
            var _selectedCarrier = _carrier.table.RSTODatatableSelectedData();
            _me.table.RSTODataURLQuery({'id_carrier': _selectedCarrier.id});
            if(_me.datatable === null){
                // init datatable
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'type_name'},
                    {'data': 'full_cost_price'},
                    {'data': 'half_cost_price'}
                ]);
            } else {
                // Update ajax url
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            // Update modal title
            _me.listModal.RSTOModalTitle("Price list - " + _selectedCarrier.fullname);
            
            _me.listModal.modal('show');
        });
        
        // When a new carrier is selected
        _carrier.table.on('selectionChanged.rsto', function(e, data){
            // Update id_carrier field
            _me.fields.carrier.val(data.id);
            // Update modal title
            _me.modal.RSTOModalTitle("New price for " + data.fullname);
            // Update vehicle type select2 URL
            _me.fields.type.RSTODataURLQuery({'id_carrier': data.id});
        });
        
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.prices.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_carrier_vehicle_price': data.id}, 'data-edit-url');
        });
        
        // Add price
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // Edit price
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');
            
            // Fill the form
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            _me.fields.full.RSTOOriginalValue(_data.full_cost_price);
            _me.fields.half.RSTOOriginalValue(_data.half_cost_price);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete price
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_carrier_vehicle_price': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
//</editor-fold>
//<editor-fold desc="Selling prices" defaultstate="collapsed">
const RSTOCarrierVehicleSellingPrice = {
    xCSRFToken: null,
    table: $('#rsto-carrier-selling-price-datatable'),
    datatable: null,
    listModal: $('#rsto-carrier-selling-price-list-modal'),
    modal: $('#rsto-carrier-selling-price-modal'),
    form: $('#rsto-carrier-selling-price-form'),
    fields: {
        vehicle: $('#rsto-carrier-seling-price-carrier-vehicle-price'),
        currency: $('#rsto-carrier-selling-price-currency'),
        full: $('#rsto-carrier-full-selling-price'),
        half: $('#rsto-carrier-half-selling-price')
    },
    buttons: {
        add: $('#rsto-carrier-selling-price-add-btn'),
        edit: $('#rsto-carrier-selling-price-edit-btn'),
        delete: $('#rsto-carrier-selling-price-delete-btn')
    },
    init: function(){
        var _me = this;
        var _vehicle = RSTOCarrierVehiclePrice;
        var _carrier = RSTOCarrier;
        
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // When a new vehicle is selected
        _vehicle.table.on('selectionChanged.rsto', function(e, data){
            // Update datatable url
            _me.table.RSTODataURLQuery({'id_carrier_vehicle_price': data.id});
            
            // Update selling price list modal title
            _me.listModal.RSTOModalTitle("Selling prices of {0} - {1}".format(data.type_name, _carrier.table.RSTODatatableSelectedData().fullname));
            
            // Update carrier vehicle price hidden field
            _me.fields.vehicle.val(data.id);
            
            // Update currency select2 url
            _me.fields.currency.RSTODataURLQuery({'id_carrier_vehicle_price': data.id});
        });
        
        _vehicle.buttons.prices.click(function(){
            // Init datatable
            if(_me.datatable === null){
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'currency_name'},
                    {'data': 'full_price'},
                    {'data': 'half_price'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            _me.listModal.modal('show');
        });
        
        // Add price
        _me.buttons.add.click(function(){
            // Update modal title
            _me.modal.RSTOModalTitle("New selling price of {0} - {1}".format(_vehicle.table.RSTODatatableSelectedData().type_name, _carrier.table.RSTODatatableSelectedData().fullname));
            
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // When a price is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_carrier_vehicle_selling_price': data.id}, 'data-edit-url');
        });
        
        // Edit price
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.modal.RSTOModalTitle("Editing selling price of {0} - {1}".format(_vehicle.table.RSTODatatableSelectedData().type_name, _carrier.table.RSTODatatableSelectedData().fullname));
            _me.form.attr('data-edit', 'true');
            
            // Fill the form
            _me.fields.currency.RSTOOriginalValue(_data.id_currency, _data.currency_name);
            _me.fields.full.RSTOOriginalValue(_data.full_price);
            _me.fields.half.RSTOOriginalValue(_data.half_price);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete price
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_carrier_vehicle_selling_price': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
//</editor-fold>
//<editor-fold desc="Vehicle" defaultstate="collapsed">
const RSTOCarrierVehicle = {
    xCSRFToken: null,
    table: $('#rsto-carrier-vehicle-datatable'),
    datatable: null,
    listModal: $('#rsto-carrier-vehicle-list-modal'),
    modal: $('#rsto-carrier-vehicle-modal'),
    form: $('#rsto-carrier-vehicle-form'),
    fields: {
        carrier: $('#rsto-carrier-vehicle-carrier'),
        type: $('#rsto-carrier-vehicle-type'),
        brand: $('#rsto-carrier-vehicle-brand'),
        seatCount: $('#rsto-carrier-vehicle-seat-count'),
        model: $('#rsto-carrier-vehicle-model'),
        vehicleRegistration: $('#rsto-carrier-vehicle-registration')
    },
    buttons: {
        add: $('#rsto-carrier-vehicle-add-btn'),
        edit: $('#rsto-carrier-vehicle-edit-btn'),
        delete: $('#rsto-carrier-vehicle-delete-btn')
    },
    init: function(){
        var _me = this;
        var _carrier = RSTOCarrier;
        
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        _carrier.table.on('selectionChanged.rsto', function(e, data){
            // Update datatable url
            _me.table.RSTODataURLQuery({'id_carrier': data.id});
            
            // Update form hidden field
            _me.fields.carrier.val(data.id);
        });
        
        _carrier.buttons.vehicles.click(function(){
            // Update modal title
            _me.listModal.RSTOModalTitle("Vehicle list - " + _carrier.table.RSTODatatableSelectedData().fullname);
            
            // Init datatable
            if(_me.datatable === null){
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'type_name'},
                    {'data': 'brand_name'},
                    {'data': 'model'},
                    {'data': 'seat_count'},
                    {'data': 'vehicle_registration'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            _me.listModal.modal('show');
        });
        
        // Add vehicle
        _me.buttons.add.click(function(){
            _me.modal.RSTOModalTitle("New vehicle for " + _carrier.table.RSTODatatableSelectedData().fullname);
            _me.modal.modal('show');
        });
        
        // When form is sumbitted
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success) {
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // When a vehicle is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_carrier_vehicle': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Enable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        // Edit vehicle
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');
            
            // Fill the form
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            _me.fields.brand.RSTOOriginalValue(_data.id_brand, _data.brand_name);
            _me.fields.model.RSTOOriginalValue(_data.model);
            _me.fields.seatCount.RSTOOriginalValue(_data.seat_count);
            _me.fields.vehicleRegistration.RSTOOriginalValue(_data.vehicle_registration);
            
            // show modal
            _me.modal.modal('show');
        });
        
        // Delete vehicle
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_carrier_vehicle': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
                        if(response === true){
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
//</editor-fold>
//<editor-fold desc="Drivers">
const RSTOCarrierDriver = {
    xCSRFToken: null,
    table: $('#rsto-carrier-driver-datatable'),
    datatable: null,
    listModal: $('#rsto-carrier-driver-list-modal'),
    modal: $('#rsto-carrier-driver-modal'),
    form: $('#rsto-carrier-driver-form'),
    fields: {
        carrier: $('#rsto-carrier-driver-carrier'),
        title: $('#rsto-carrier-driver-title'),
        fullname: $('#rsto-carrier-driver-fullname'),
        description: $('#rsto-carrier-driver-description'),
        phoneNumber: $('#rsto-carrier-driver-phone-number'),
        emailAddress: $('#rsto-carrier-driver-email-address')
    },
    buttons: {
        add: $('#rsto-carrier-driver-add-btn'),
        edit: $('#rsto-carrier-driver-edit-btn'),
        delete: $('#rsto-carrier-driver-delete-btn')
    },
    init: function(){
        var _me = this;
        var _carrier = RSTOCarrier;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // When a carrier is selected
        _carrier.table.on('selectionChanged.rsto', function(e, data){
            // Update datatable data-url
            _me.table.RSTODataURLQuery({'id_carrier': data.id});
            
            // Update form hidden input
            _me.fields.carrier.val(data.id);
        });
        
        // Show list modal
        _carrier.buttons.drivers.click(function(){
            // Update modal title
            _me.listModal.RSTOModalTitle("Driver list - " + _carrier.table.RSTODatatableSelectedData().fullname);
            
            // Init datatable
            if(_me.datatable === null){
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'title_name'},
                    {'data': 'fullname'},
                    {'data': 'description'},
                    {'data': 'phone_number'},
                    {'data': 'email_address'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            // Show modal
            _me.listModal.modal('show');
        });
        
        // Add driver
        _me.buttons.add.click(function(){
            _me.modal.RSTOModalTitle('New driver');
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // When driver is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update dat-edit-url
            _me.form.RSTODataURLQuery({'id_carrier_driver': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        })
        
        // Edit driver
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');
            
            // Update modal title
            _me.modal.RSTOModalTitle("Editing driver - " + _carrier.table.RSTODatatableSelectedData().fullname);
            
            // Fill the form
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.description.RSTOOriginalValue(_data.description);
            _me.fields.phoneNumber.RSTOOriginalValue(_data.phone_number);
            _me.fields.emailAddress.RSTOOriginalValue(_data.email_address);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete driver
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_carrier_driver': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
                        if(response === true || response === 1){
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
//</editor-fold>
$(window).on('load', function(){
    RSTOCarrier.init();
    RSTOCarrierVehiclePrice.init();
    RSTOCarrierVehicleSellingPrice.init();
    RSTOCarrierVehicle.init();
    RSTOCarrierDriver.init();
});