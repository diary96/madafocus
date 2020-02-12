var RSTOCircuits = {
    xCSRFToken: null,
    table: $('#rsto-circuit-datatable'),
    datatable: null,
    modal: $('#rsto-circuit-modal'),
    form: $('#rsto-circuit-form'),
    fields: {
        fullname: $('#rsto-circuit-fullname'),
        title: $('#rsto-circuit-title'),
        description: $('#rsto-circuit-description')
    },
    buttons: {
        add: $('#rsto-circuit-add-btn'),
        edit: $('#rsto-circuit-edit-btn'),
        infos: $('#rsto-circuit-infos-btn'),
        delete: $('#rsto-circuit-delete-btn')
    },
    init: function () {
        var _me = this;
        _me.xCSRFToken = _me.table.attr('data-x-csrf-token');

        // Init datatable
        _me.datatable = _me.table.RSTODatatable([
            {'data': 'id'},
            {'data': 'START'},
            {'data': 'DURATION'},
            {'data': 'pax'},
            {'data': 'drive_lib'},
            {'data': 'status'}
        ]);

        // Add circuit
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
            _me.form.RSTODataURLQuery({'id_circuit': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.infos.RSTODisable();
        });

        // Edit circuit
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

        // Delete circuit
        _me.buttons.delete.click(function () {
            confirm(RSTOMessages.ConfirmDelete, function (response) {
                if (response) {
                    RSTOGetJSON(_me.buttons.delete.attr('data-url'), {'id_circuit': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
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

$(window).on('load', function(){
    RSTOCircuits.init();
    var _dt = $('#rsto-circuit-datatable');
    var _m = $('#rsto-circuit-modal');
    var _lm = $('#rsto-circuit-days-modal');
    var _edit = $('#rsto-circuit-edit-btn');
    var _configure = $('#rsto-circuit-configure-btn');
    var _add = $('#rsto-circuits-add-btn');
    var _departure = $('#rsto-circuit-departure');
    var _circuitDayDt = $('#rsto-circuit-days-datatable');
    var _configureCircuitDay = $('#rsto-circuit-day-configure-btn');
    var _circuitDayMd = $('#rsto-circuit-day-modal');
    
    // init datatable
    _dt.DataTable();
    _dt.find('tr').each(function(){
        $(this).RSTOSelectableDatatableRow(_dt, {});
    });
    
    // when a row is selected
    _dt.on('selectionChanged.rsto', function(){
        _edit.RSTOEnable();
        _configure.RSTOEnable();
    });
    
    // init modals
    _add.click(function(){
        _m.modal('show');
    });
    
    // init departure datepicker
    _departure.datepicker();
    
    // configure circuit
    _configure.click(function(){
        _lm.modal('show');
    });
    
    // configure circuit days datatable
    _circuitDayDt.DataTable();
    _circuitDayDt.find('tr').each(function(){
        $(this).RSTOSelectableDatatableRow(_circuitDayDt, {});
    });
    _circuitDayDt.on('selectionChanged.rsto', function(){
        _configureCircuitDay.RSTOEnable();
    });
    _configureCircuitDay.click(function(){
        _circuitDayMd.modal('show');
    });
    
    $('#rsto-circuit-day-specify-datatable').RSTODatatable([
        {data: 'type_name'}
    ]);
    $('#rsto-circuit-day-hotel-rooms-datatatable').DataTable();
    
    $('#rsto-circuit-day-room-list-add-btn').click(function(){
       $('#rsto-circuit-day-room-list-modal').modal('show');
    });
    
    $('#rsto-circuit-day-specify-add-btn').click(function(){
        $('#rsto-circuit-day-specify-modal').modal('show');
    });
})