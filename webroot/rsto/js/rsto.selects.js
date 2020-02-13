var RSTOSelectOption = {
    xCSRFToken: null,
    table: $('#rsto-select-option-datatable'),
    datatable: null,
    modal: $('#rsto-select-option-modal'),
    form: $('#rsto-select-option-form'),
    fields: {
        group: $('#rsto-select-option-group'),
        name: $('#rsto-select-option'),
        default: $('#rsto-select-default')
    },
    buttons: {
        add: $('#rsto-select-option-add-btn'),
        edit: $('#rsto-select-option-edit-btn'),
        delete: $('#rsto-select-option-delete-btn')
    },
    selects: $('#rsto-selects>li>a.rsto-select'),
    init: function(){
        var _me = this;
        _me.xCSRFToken = _me.form.attr('data-x-csrf-token');
        
        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "option"},
            {"data": "group_name"}
        ]);
        
        _me.table.on('selectionChanged.rsto', function(e, data){
            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();
            
            // Update form data-edit-url
            _me.form.RSTODataURLQuery({'id_option': data.id}, 'data-edit-url');
        });
        
        _me.table.on('draw.dt', function(){
            // Enable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
        });
        
        _me.selects.click(function(){
            var _a = $(this);
            var _id_select = _a.attr('data-id');
            var _li = _a.closest('li');
            var _ul = _a.closest('ul');
            _ul.find('li.active').removeClass('active');
            _li.addClass('active');
            // Update datatable url
            _me.datatable.ajax.url(RSTOChangeURLQuery(_me.table.attr('data-url'), {'id_select' : _id_select})).load();
            // Update group select2 data-url
            _me.fields.group.RSTODataURLQuery({'id_select': _id_select});
            // Update option data-validation-url
            _me.fields.name.RSTODataURLQuery({'id_select': _id_select}, 'data-validation-url');
        });
        
        // Add option
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        // After form submission
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success === true){
                _me.modal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_me.form.attr('data-edit') === 'true' ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        
        // Edit option
        _me.buttons.edit.click(function(){
            var _data = _me.table.RSTODatatableSelectedData();
            _me.form.attr('data-edit', 'true');
            
            // Fill form
            _me.fields.group.RSTOOriginalValue(_data.id_select_option_group, _data.group_name);
            _me.fields.name.RSTOOriginalValue(_data.option);
            _me.fields.default[0].checked = _data.default === 1;
            
            // Show modal
            _me.modal.modal('show');
        });
        
        // Delete option
        _me.buttons.delete.click(function(){
            confirm(RSTOMessages.ConfirmDelete, function(response){
                if(response === true){
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_option': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function(response){
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

const RSTOSelectOptionGroup = {
    modal: $('#rsto-select-option-group-modal'),
    form: $('#rsto-select-option-group-form'),
    fields : {
        select: $('#rsto-select-option-group-select'),
        name: $('#rsto-select-option-group-name')
    },
    buttons: {
        add: $('#rsto-select-option-group-add-btn')
    },
    init: function(option){
        var _me = this;
        option.selects.click(function(){
            var _id_select = $(this).attr('data-id');
            _me.fields.select.val(_id_select);
            _me.fields.name.RSTODataURLQuery({'id_select' : _id_select}, 'data-validation-url');
        });
        
        _me.buttons.add.click(function(){
            _me.modal.modal('show');
        });
        
        _me.form.on('submitted.rsto', function(e, response){
            if(response.success){
                _me.modal.modal('hide');
                option.fields.group.append(new Option(response.row.name, response.row.id_select_option_group, true, true)).trigger('change');
            } else {
                alert(RSTOMessages.Error);
            }
        });
    }
};

$(window).on('load', function(){
    RSTOSelectOption.init();
    RSTOSelectOptionGroup.init(RSTOSelectOption);
    // Active first select
    RSTOSelectOption.selects.first().click();
});