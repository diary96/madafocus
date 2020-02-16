const RSTOPlaces = {
    xCSRFToken: null,
    table: $('#rsto-places-datatable'),
    datatable: null,
    modal: $('#rsto-place-modal'),
    form: $('#rsto-place-form'),
    sidebar: $('#rsto-zones'),
    fields: {
        parent: $('#rsto-place-parent'),
        name: $('#rsto-place-name')
    },
    buttons: {
        add: $('#rsto-add-place-btn'),
        edit: $('#rsto-edit-place-btn'),
        delete: $('#rsto-delete-place-btn')
    },
    /**
     * Initialiae the object
     * @argument {string} option : all | datable | form | sidebar | validation
     * @returns {void}
     */
    init: function (option) {
        var _option = option || 'all';
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // init sidebar 
        if(_option === 'all' || _option === 'sidebar'){
            _me.sidebar.find('a.rsto-zone').click(function(){
                var _zoneId = $(this).attr('data-id');
                // Change datatable url
                _me.table.RSTODataURLQuery({'id_parent': _zoneId});
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
                // Select active li
                _me.sidebar.find('li.active').removeClass('active');
                $(this).closest('li').addClass('active');
            });
        }
            
        
        // init datatable
        if(_option === 'all' || _option === 'datatable'){
            _me.datatable = _me.table.RSTODatatable([
                {"data": "name"},
                {"data": "parent_name"}
            ]);

            // Enable buttons
            _me.table.on('selectionChanged.rsto', function (e, data) {
                _me.buttons.edit.RSTOEnable();
                _me.buttons.delete.RSTOEnable();

                // Change form's data-edit-url
                _me.form.RSTODataURLQuery({'id_place': data.id}, 'data-edit-url');

                // Add exclusion
                _me.fields.parent.RSTODataURLQuery({'exclude': data.id});
            });

            // Disable buttons
            _me.table.on('draw.dt', function () {
                _me.buttons.edit.RSTODisable();
                _me.buttons.delete.RSTODisable();
                //_me.fields.parent.RSTODataURLQuery();
            });
        }   

        // init validations
        if(_option === 'all' || _option === 'validation' || _option === 'form'){
            // Revalidate name when parent is changed
            _me.fields.parent.on('change.select2', function(){
                // A parent cannot have two places with the same name, 
                // Send de parent's id to the server so he can check if he has a child place with the same name
                var _query = {'id_parent' : _me.fields.parent.val()};
                _me.fields.name.RSTODataURLQuery(_query, 'data-validation-url');
            });

            // Name must be revalidated when zone is changed
            _me.fields.parent.change(function(){
                _me.fields.name.trigger('keyup');
            });
        }
        
        // init form
        if(_option === 'all' || _option === 'form'){
            // Set focus on place name
            _me.modal.on('shown.bs.modal', function(){
                _me.fields.name.focus();
            });

            // Close modal after submission
            _me.form.on('submitted.rsto', function (e, response) {
                if (response.success) {
                    _me.modal.modal('hide');
                    _me.datatable.ajax.reload();
                    alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
                } else {
                    alert(RSTOMessages.Error);
                }
            });

            _me.buttons.add.click(function () {
                // Remove exclusion
                _me.fields.parent.RSTORemoveDataURLQuery();

                _me.modal.modal('show');
            });

            // Edit place
            _me.buttons.edit.on('click', function(){
                var _data = _me.table.RSTODatatableSelectedData();
                _me.form.attr('data-edit', 'true');
                if(_data.parent_name !== '-'){
                    _me.fields.parent.RSTOOriginalValue(_data.id_parent, _data.parent_name);
                }
                _me.fields.name.RSTOOriginalValue(_data.name);
                _me.modal.modal('show');
            });

            // Delete place
            _me.buttons.delete.on('click', function(){
                confirm(RSTOMessages.ConfirmDelete, function(response){
                    if(response === true){
                        RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_place': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
    }
};