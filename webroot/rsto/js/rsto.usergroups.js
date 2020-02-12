const RSTOUserGroups = {
    xCSRFToken: null,
    table: $('#rsto-usergroup-datatable'),
    datatable: null,
    modal: $('#rsto-usergroup-modal'),
    form: $('#rsto-usergroup-form'),
    fields: {
        name: $('#rsto-usergroup-name'),
        privileges: $('.rsto-privilege>label>input')
    },
    buttons: {
        add: $('#rsto-add-usergroup-btn'),
        edit: $('#rsto-edit-usergroup-btn'),
        delete: $('#rsto-delete-usergroup-btn')
    },
    init: function () {
        var _me = this;
        
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');

        // Init form
        _me.form.RSTOForm(function () {
            var _isValid = false;
            _me.fields.privileges.each(function () {
                _isValid |= this.checked;
            });
            return _isValid;
        });

        // Revalidate form when a checkbox is checked
        _me.fields.privileges.change(function () {
            _me.form.RSTOValidate();
        });

        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "name"}
        ]);

        _me.table.on('selectionChanged.rsto', function (e, data) {
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();

            // Update edit-url
            _me.form.RSTODataURLQuery({'id_group': data.id}, 'data-edit-url');
        });

        // Add group
        _me.buttons.add.click(function () {
            _me.modal.modal('show');
        });

        // Put focus on group's name
        _me.modal.on('shown.bs.modal', function () {
            _me.fields.name.focus();
        });

        // After submission
        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });

        // Edit group
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            
            // Fill form
            var _data = _me.table.RSTODatatableSelectedData();
            var _privileges = (_data.privileges || "").split(';');
            for (var _i = 0; _i < _privileges.length; _i++) {
                _me.form.find("input[name='privileges[{0}]']".format(_privileges[_i])).RSTOOriginalValue(true);
            }
            _me.fields.name.RSTOOriginalValue(_data.name);
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete group
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_group': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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
    RSTOUserGroups.init();
});