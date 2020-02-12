const RSTOUser = {
    xCSRFToken: null,
    table: $('#rsto-users-datatable'),
    datatable: null,
    modal: $('#rsto-add-user-modal'),
    form: $('#rsto-user-form'),
    fields: {
        group: $('#rsto-user-group'),
        firstname: $('#rsto-user-firstname'),
        lastname: $('#rsto-user-lastname'),
        username: $('#rsto-user-username'),
        phoneNumber: $('#rsto-user-phonenumber'),
        emailAddress: $('#rsto-user-email-address'),
        gender: $('#rsto-user-gender'),
        timezone: $('#rsto-user-timezone')
    },
    buttons: {
        add: $('#rsto-add-user-btn'),
        edit: $('#rsto-edit-user-btn'),
        tasks: $('#rsto-tasks-user-btn'),
        reset: $('#rsto-reset-user-btn'),
        delete: $('#rsto-delete-user-btn')
    },
    init: function () {
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "fullname"},
            {"data": "username"},
            {"data": "group_name"},
            {"data": "phone_number"},
            {"data": "email_address"}
        ]);

        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.tasks.RSTOEnable();
            _me.buttons.reset.RSTOEnable();
            _me.buttons.delete.RSTOEnable();

            // Change data-edit-url
            _me.form.RSTODataURLQuery({'id_user': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.tasks.RSTODisable();
            _me.buttons.reset.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });

        // Add user
        _me.buttons.add.click(function () {
            _me.modal.modal('show');
        });
        
        // Put focus on firstname
         _me.modal.on('shown.bs.modal', function(){
             _me.fields.firstname.focus();
         });

        // After form submit
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.datatable.ajax.reload();
                _me.modal.modal('hide');
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // Edit user
        _me.buttons.edit.click(function () {
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');

            // Fill the form
            _me.fields.emailAddress.RSTOOriginalValue(_data.email_address);
            _me.fields.firstname.RSTOOriginalValue(_data.firstname);
            _me.fields.lastname.RSTOOriginalValue(_data.lastname);
            _me.fields.gender.RSTOOriginalValue(_data.gender);
            _me.fields.group.RSTOOriginalValue(_data.group, _data.group_name);
            _me.fields.timezone.RSTOOriginalValue(_data.timezone, _data.timezone_name);
            _me.fields.phoneNumber.RSTOOriginalValue(_data.phone_number);
            _me.fields.username.RSTOOriginalValue(_data.username);

            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete user
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_user' : _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
    RSTOUser.init();
});