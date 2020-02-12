var RSTOHub = {
    xCSRFToken : null,
    table: $('#rsto-hub-datatable'),
    datatable: null,
    modal: $('#rsto-hub-modal'),
    form: $('#rsto-hub-form'),
    fields: {
        name: $('#rsto-hub-name'),
        type: $('#rsto-hub-type'),
        place: $('#rsto-hub-place')
    },
    buttons: {
        add: $('#rsto-hub-add-btn'),
        edit: $('#rsto-hotel-edit-btn'),
        delete: $('#rsto-hub-delete-btn')
    },
    init: function(){
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'name'},
            {'data': 'type_name'},
            {'data': 'place_name'}
        ]);
        
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable button
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_hub': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        // Add new hub
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        _me.modal.on('shown.bs.modal', function(){
            _me.fields.name.focus();
        });
        
        // Name should be unique on each place
        _me.fields.place.on('change.select2', function(){
            _me.fields.name.RSTODataURLQuery({'id_place': _me.fields.place.val()}, 'data-validation-url').trigger('keyup');
        });
        
        // After form submission
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success === true){
                alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // Edit hub
        _me.buttons.edit.click(function(){
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            
            // Fill the form
            _me.fields.name.RSTOOriginalValue(_data.name);
            _me.fields.place.RSTOOriginalValue(_data.id_place, _data.place_name);
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Deleting hub
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_hub' : _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(reponse){
                        alert(RSTOMessages.Deleted);
                        _me.datatable.ajax.reload();
                    });
                } else {
                    alert(RSTOMessages.Error);
                }
            });
        });
    }
};

$(window).on('load', function(){
    RSTOHub.init();
    if(RSTOPlace){
        RSTOPlace.init('#rsto-hub-add-new-place', RSTOHub);
    }
});