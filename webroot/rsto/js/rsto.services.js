//<editor-fold desc="Services" defaultstate="collapsed">
const RSTOService = {
    xCSRFToken: null,
    table: $('#rsto-service-datatable'),
    datatable: null,
    modal: $('#rsto-service-modal'),
    form: $('#rsto-service-form'),
    fields: {
        place: $('#rsto-service-place'),
        type: $('#rsto-service-type'),
        description: $('#rsto-service-description'),
        price: $('#rsto-service-cost-price'),
        fromProvider: $('#rsto-service-from-provider')
    },
    buttons: {
        add: $('#rsto-service-add-btn'),
        edit: $('#rsto-service-edit-btn'),
        prices: $('#rsto-service-prices-btn'),
        providers: $('#rsto-service-providers-btn'),
        delete: $('#rsto-service-delete-btn')
    },
    init: function(){
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'type_name'},
            {'data': 'place_name'},
            {'data': 'description'},
            {'data': 'cost_price'}
        ]);
        
        // When "from provider" checkbox is changed
        _me.fields.fromProvider.change(function(){
            if(this.checked){
                _me.fields.price.val(0).closest('div.form-group').fadeOut(0);
            } else {
                _me.fields.price.val('').closest('div.form-group').fadeIn(0);
            }
            _me.fields.price.trigger('keyup');
        });
        
        // Add service
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            var _editMode = _me.form.attr('data-edit') === 'true';
            _me.modal.modal('hide');
            _me.datatable.ajax.reload();
            alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
        });
        
        // When a service is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            if(data.from_provider === 1){
                _me.buttons.providers.RSTOEnable();
                _me.buttons.prices.RSTODisable();
            } else {
                _me.buttons.prices.RSTOEnable();
                _me.buttons.providers.RSTODisable();
            }
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_service': data.id}, 'data-edit-url');
        });
        
        // When datatable is redrawn
        _me.table.on('draw.dt', function(){
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.prices.RSTODisable();
            _me.buttons.providers.RSTODisable();
        });
        
        // Edit service
        _me.buttons.edit.click(function(){
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            
            // Fill the form
            _me.fields.fromProvider.RSTOOriginalValue(_data.from_provider === 1);
            _me.fields.place.RSTOOriginalValue(_data.id_place, _data.place_name);
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            _me.fields.price.RSTOOriginalValue(_data.cost_price);
            _me.fields.description.RSTOOriginalValue(_data.description);
            
            // Show modal
            _me.form.find('button[type=submit]').RSTODisable();
            _me.modal.modal('show');
        });
        
        // Delete service
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_service': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
//<editor-fold desc="Selling price" defaultstate="collapsed">
const RSTOServiceSellingPrice = {
    xCSRFToken: null,
    table: $('#rsto-service-price-datatable'),
    datatable: null,
    listModal: $('#rsto-service-price-list-modal'),
    modal: $('#rsto-service-selling-price-modal'),
    form: $('#rsto-service-selling-price-form'),
    fields: {
        service: $('#rsto-service-selling-price-service'),
        currency: $('#rsto-service-selling-price-currency'),
        price: $('#rsto-service-selling-price-price')
    },
    buttons: {
        add: $('#rsto-service-price-add-btn'),
        edit: $('#rsto-service-price-edit-btn'),
        delete: $('#rsto-service-price-delete-btn')
    },
    init: function(){
        var _service = RSTOService;
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // When a service is selected
        _service.table.on('selectionChanged.rsto', function(e, data){
            // Update datatable url
            _me.table.RSTODataURLQuery({'id_service': data.id});
            
            // Change form hidden input value
            _me.fields.service.val(data.id);
            
            // Change currency select2 URL
            _me.fields.currency.RSTODataURLQuery({'id_service': data.id});
        });
        
        // Init datatable
        _service.buttons.prices.click(function(){
            var _selectedService = _service.table.RSTODatatableSelectedData();
            if(_selectedService.from_provider){
                if(_me.datatable === null){
                    _me.datatable= _me.table.RSTODatatable([
                        {'data': 'currency_name'},
                        {'data': 'price'}
                    ]);
                } else {
                    _me.datatable.ajax.url(_me.table.attr('data-url')).load();
                }

                // show modal
                _me.listModal.RSTOModalTitle("{0} at {1} - Price list".format(_selectedService.type_name, _selectedService.place_name));
                _me.listModal.modal('show');
            }   
        });
        
        // Add price
        _me.buttons.add.click(function(){
            _me.modal.RSTOModalTitle("New price");
            _me.modal.modal('show');
        });
        
        // When form is submitted
        _me.form.on('submitted.rsto', function(e, response){
            var _editMode = _me.form.attr('data-edit') === 'true';
            _me.modal.modal('hide');
            _me.datatable.ajax.reload();
            alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
        });
        
        // When a price is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_service_selling_price': data.id}, 'data-edit-url');
        });
        
        // When datatable is redrawn
        _me.table.on('draw.dt', function(){
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        // Edit price
        _me.buttons.edit.click(function(){
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            
            // Fill form
            _me.fields.currency.RSTOOriginalValue(_data.id_currency, _data.currency_name);
            _me.fields.price.RSTOOriginalValue(_data.price);
            
            // Show modal
            _me.modal.RSTOModalTitle("Editing price");
            _me.modal.modal('show');
        });
        
        // Delete service
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_service_selling_price': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
// <editor-fold desc="Service providers" defaultstate="collapsed">
const RSTOServiceProvider = {
    xCSRFToken: null,
    table: $('#rsto-service-provider-datatable'),
    datatable: null,
    listModal: $('#rsto-service-provider-list-modal'),
    modal: $('#rsto-service-provider-modal'),
    form: $('#rsto-service-provider-form'),
    fields: {
        service: $('#rsto-service-provider-service'),
        title: $('#rsto-service-provider-title'),
        fullname: $('#rsto-service-provider-fullname'),
        description: $('#rsto-service-provider-description'),
        phoneNumber: $('#rsto-service-provider-booking-phone-number'),
        emailAddress: $('#rsto-service-provider-booking-email-address'),
        costPrice: $('#rsto-service-provider-cost-price'),
        mustBook: $('#rsto-service-provider-must-book')
    },
    buttons: {
        add: $('#rsto-service-provider-add-btn'),
        edit: $('#rsto-service-provider-edit-btn'),
        delete: $('#rsto-service-provider-delete-btn'),
        prices: $('#rsto-service-provider-price-btn'),
        choice: $('#rsto-service-provider-choice-btn')
    },
    init: function(){
        var _me = this;
        var _service = RSTOService;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // When a service is selected
        _service.table.on('selectionChanged.rsto', function(e, data){
            _me.table.RSTODataURLQuery({'id_service': data.id});
            _me.listModal.RSTOModalTitle("List of {0} providers".format(data.type_name));
            _me.fields.service.val(data.id);
        });
        
        // Show list modal
        _service.buttons.providers.click(function(){
            // Init datatable
            if(_me.datatable === null){
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'title_name'},
                    {'data': 'fullname'},
                    {'data': 'description'},
                    {'data': 'booking_phone_number'},
                    {'data': 'booking_email_address'},
                    {'data': 'cost_price'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            _me.listModal.modal('show');
        });
        
        // Adding provider
        _me.buttons.add.click(function(){
            _me.modal.RSTOModalTitle("New provider");
            _me.modal.modal('show');
        });
        
        // After form submission
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success === true){
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // When a provider is selected
        _me.table.on('selectionChanged.rsto', function(e, data){
            _me.form.RSTODataURLQuery({'id_service_provider': data.id}, 'data-edit-url');
            
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            _me.buttons.prices.RSTOEnable();
        });
        
        // When datatable is redrawn
        _me.table.on('draw.dt', function(){
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.prices.RSTODisable();
        });
        
        // Edit service provider
        _me.buttons.edit.click(function(){
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            
            // Fill the form
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.description.RSTOOriginalValue(_data.description);
            _me.fields.phoneNumber.RSTOOriginalValue(_data.booking_phone_number);
            _me.fields.emailAddress.RSTOOriginalValue(_data.booking_email_address);
            _me.fields.mustBook.RSTOOriginalValue(_data.must_book === 1);
            _me.fields.costPrice.RSTOOriginalValue(_data.cost_price);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete service provider
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_service_provider': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
        
        // Choose an existing provider
        _me.buttons.choice.click(function(){
            _me.modal.modal('hide');
            RSTOServiceProviderChoice.modal.modal('show');
        });
    }
};
const RSTOServiceProviderChoice = {
    xCSRFToken: null,
    modal: $('#rsto-service-provider-choice-modal'),
    form: $('#rsto-service-provider-choice-form'),
    fields: {
        provider: $('#rsto-service-provider-choice-provider'),
        costPrice: $('#rsto-service-provider-choice-cost-price'),
        mustBook: $('#rsto-service-provider-choice-must-book')
    },
    buttons: {
        add: $('#rsto-service-provider-choice-add-btn')
    },
    init: function(){
        var _me = this;
        var _provider = RSTOServiceProvider;
        
        // Add new provider
        _me.buttons.add.click(function(){
            _me.modal.modal('hide');
            _provider.modal.modal('show');
        });
    }
};
// </editor-fold>
// <editor-fold desc="Prices" defaultstate="collapsed">
RSTOServiceCostPrice = {
    xCRSFToken: null,
    table: $('#rsto-service-cost-price-datatable'),
    datatable: null,
    listModal: $('#rsto-service-cost-price-list-modal'),
    modal: $('#rsto-service-cost-price-modal'),
    buttons: {
        //add: $('#'),
        //edit: $('#'),
        //delete: $('#')
    },
    init: function(){
        var _me = this;
        var _provider = RSTOServiceProvider;
        var _service = RSTOService;
        
        // When a provider is selected
        _provider.table.on('selectionChanged', function(e, data){
            _me.table.RSTODataURLQuery({'id_service_provider': data.id});
        });
        
        // Show price list
        _provider.buttons.prices.click(function(){
            _me.listModal.RSTOModalTitle("Cost prices - " + _service.table.RSTODatatableSelectedData().type_name);
            
            // Init datatble
            if(_me.datatable === null){
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'from'},
                    {'data': 'to'},
                    {'data': 'adult'},
                    {'data': 'children'}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            _me.listModal.modal('show');
        });
    }
};


// </editor-fold>
$(window).on('load', function(){
    RSTOService.init();
    RSTOServiceSellingPrice.init();
    RSTOServiceProvider.init();
    RSTOServiceProviderChoice.init();
    RSTOServiceCostPrice.init();
});