var RSTODirectory = {
    xCSRFToken: null,
    table: $('#rsto-directory-datatable'),
    datatable: null,
    modal: $('#rsto-directory-modal'),
    form: $('#rsto-directory-form'),
    fields: {
        fullname: $('#rsto-directory-fullname'),
        title: $('#rsto-directory-title'),
        description: $('#rsto-directory-description')
    },
    buttons: {
        add: $('#rsto-directory-add-btn'),
        edit: $('#rsto-directory-edit-btn'),
        infos: $('#rsto-directory-infos-btn'),
        delete: $('#rsto-directory-delete-btn')
    },
    init: function () {
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'title_name'},
            {'data': 'fullname'},
            {'data': 'description'}
        ]);

        // Add directory
        _me.buttons.add.click(function () {
            _me.modal.modal('show');
        });

        // Set focus on fullname
        _me.modal.on('shown.bs.modal', function () {
            _me.fields.fullname.focus();
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

        // On datatable selection changed
        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            _me.buttons.infos.RSTOEnable();

            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_directory': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.infos.RSTODisable();
        });

        // Edit directory
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();

            // Fill the form
            _me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.description.RSTOOriginalValue(_data.description);

            // Show modal
            _me.modal.modal('show');
        });

        // Delete directory
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_directory': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
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
    }
};

const RSTODirectoryContactInformation = {
    xCSRFToken: null,
    table: $('#rsto-directory-contact-information-datatable'),
    datatable: null,
    listModal: $('#rsto-directory-contact-information-list-modal'),
    modal: $('#rsto-directory-contact-information-modal'),
    form: $('#rsto-directory-contact-information-form'),
    fields: {
        directory: $('#rsto-directory-contact-information-directory'),
        type: $('#rsto-directory-contact-information-type'),
        label: $('#rsto-directory-contact-information-label'),
        contactInfo: $('#rsto-directory-contact-information-contact-info')
    },
    buttons: {
        add: $('#rsto-directory-contact-information-add-btn'),
        edit: $('#rsto-directory-contact-information-edit-btn'),
        delete: $('#rsto-directory-contact-information-delete-btn')
    },
    init: function () {
        var _me = this;
        var _directory = RSTODirectory;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // When a new directory is selected
        _directory.table.on('selectionChanged.rsto', function(e, data){
            _me.fields.directory.val(data.id);
        });
        
        // Init 
        _directory.buttons.infos.click(function () {
            _me.table.RSTODataURLQuery({'id_directory': _directory.table.RSTODatatableSelectedData().id});
            if (_me.datatable === null) {
                // Init datatable
                _me.datatable = _me.table.RSTODatatable([
                    {'data': 'type_name'},
                    {'data': 'label'},
                    {'data': 'contact_info'}
                ]);
            } else {
                // Change datatable url
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            
            // Show modal
            _me.listModal.modal('show');
        });
        
        // Add contact info
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        // When form is submitted
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
        
        // Datatable selection
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // change data-edit-url
            _me.form.RSTODataURLQuery({'id_directory_contact_information': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        // Edit a contact info
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            // Switch edit mode on
            _me.form.attr('data-edit', 'true');
            
            // Fill the form
            _me.fields.type.RSTOOriginalValue(_data.id_type, _data.type_name);
            _me.fields.label.RSTOOriginalValue(_data.label);
            _me.fields.contactInfo.RSTOOriginalValue(_data.contact_info);
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete contact information
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_directory_contact_information': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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

$(window).on('load', function () {
    RSTODirectory.init();
    RSTODirectoryContactInformation.init();
});