var RSTOCircuits = {
    xCSRFToken: null,
    table: $('#rsto-circuit-datatable'),
    datatable: null,
    modal: $('#rsto-circuit-modal'),
    form: $('#rsto-circuit-form'),
    fields: {
<<<<<<< HEAD
        fullname: $('#rsto-circuit-fullname'),
        title: $('#rsto-circuit-title'),
        description: $('#rsto-circuit-description')
=======
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    },
    buttons: {
        add: $('#rsto-circuit-add-btn'),
        edit: $('#rsto-circuit-edit-btn'),
        infos: $('#rsto-circuit-infos-btn'),
<<<<<<< HEAD
        delete: $('#rsto-circuit-delete-btn')
=======
        delete: $('#rsto-circuit-delete-btn'),
        configure: $('#rsto-circuit-configure-btn')
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
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
<<<<<<< HEAD
        _me.modal.on('shown.bs.modal', function () {
            _me.fields.fullname.focus();
        });
=======
        /*_me.modal.on('shown.bs.modal', function () {
            _me.fields.fullname.focus();
        });*/
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128

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
<<<<<<< HEAD
=======
            _me.buttons.configure.RSTOEnable();
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128

            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id_circuit': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.infos.RSTODisable();
<<<<<<< HEAD
=======
            _me.buttons.configure.RSTODisable();
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
        });

        // Edit circuit
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();

            // Fill the form
<<<<<<< HEAD
            _me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.description.RSTOOriginalValue(_data.description);
=======
            /*_me.fields.fullname.RSTOOriginalValue(_data.fullname);
            _me.fields.title.RSTOOriginalValue(_data.id_title, _data.title_name);
            _me.fields.description.RSTOOriginalValue(_data.description);*/
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128

            // Show modal
            _me.modal.modal('show');
        });

<<<<<<< HEAD
=======

>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
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

<<<<<<< HEAD
$(window).on('load', function(){
    RSTOCircuits.init();
=======
var RSTOTripChild = {
    xCSRFToken: null,
    table: $('#rsto-circuit-days-datatable'),
    datatable: null,
    listModal: $('#rsto-circuit-days-modal'),
    configureModal: $('#rsto-circuit-day-modal'),
    fields: {
        hotel: $('#rsto-circuit-day-hotel'),
        carrier: $('#rsto-circuit-day-driver'),
        vehicle: $('#rsto-circuit-day-driver'),
        place : $('#rsto-circuit-place')

    },
    buttons: {
        configure: $('#rsto-circuit-day-configure-btn')
    },
    init: function () {
        var circuits = RSTOCircuits;
        var _me = RSTOTripChild;

       _me.xCSRFToken = circuits.xCSRFToken;

        _me.fields.place.change(function(){
            _me.fields.hotel.RSTODataURLQuery({place: _me.fields.place.val()});

        });
       /*_me.place.onchange(function () {
            // console.log(_me.place.value);
       })*/

        // configure dependencies

        // Show daily trip
        circuits.buttons.configure.click( function () {
            var _selectedTrip = circuits.table.RSTODatatableSelectedData();
            // Update trip daily datatable url, the trip child will be filtered by trips
            _me.table.RSTODataURLQuery( {id_trips: _selectedTrip.id});
            if (_me.datatable === null) {
                _me.datatable = _me.table.RSTODatatable ([
                    {"data": "day"},
                    {"data": "date"},
                    {"data": "place"},
                    {"data": "hotel"},
                    {"data": "carrier"}
                ]);
            } else {
                _me.datatable.ajax.url(_me.table.attr('data-url')).load();
            }
            // Update modal title
            _me.listModal.RSTOModalTitle("ITENERARY LISTE - {0}".format(_selectedTrip.id));

            _me.listModal.modal('show');
        });
        // On selecte row in the table
        _me.table.on('selectionChanged.rsto', function(e, data) {
            // action
            // display the configure button
            _me.buttons.configure.RSTOEnable();
        });
        _me.buttons.configure.click( function() {
            _me.configureModal.modal('show');
        });
    }
};

var RSTOTripEdit = {
    xCSRFToken: null,
    form:$('#rsto-service-form'),
    fields: {
        hotel: $('#rsto-circuit-day-hotel'),
        carrier: $('#rsto-circuit-day-driver'),
        vehicle: $('#rsto-circuit-day-driver'),
        place : $('#rsto-circuit-place')

    },
    init: function () {

    }

}
$(window).on('load', function(){
    RSTOCircuits.init();
    RSTOTripChild.init();
    /*
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    var _dt = $('#rsto-circuit-datatable');
    var _m = $('#rsto-circuit-modal');
    var _lm = $('#rsto-circuit-days-modal');
    var _edit = $('#rsto-circuit-edit-btn');
    var _configure = $('#rsto-circuit-configure-btn');
    var _add = $('#rsto-circuits-add-btn');
<<<<<<< HEAD
    // var _departure = $('#rsto-circuit-departure');
    var _circuitDayDt = $('#rsto-circuit-days-datatable');
    var _configureCircuitDay = $('#rsto-circuit-day-configure-btn');
    var _circuitDayMd = $('#rsto-circuit-day-modal');
    
=======
    var _departure = $('#rsto-circuit-departure');
    var _circuitDayDt = $('#rsto-circuit-days-datatable');
    var _configureCircuitDay = $('#rsto-circuit-day-configure-btn');
    var _circuitDayMd = $('#rsto-circuit-day-modal');

>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    // init datatable
    _dt.DataTable();
    _dt.find('tr').each(function(){
        $(this).RSTOSelectableDatatableRow(_dt, {});
    });
<<<<<<< HEAD
    
=======

>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    // when a row is selected
    _dt.on('selectionChanged.rsto', function(){
        _edit.RSTOEnable();
        _configure.RSTOEnable();
    });
<<<<<<< HEAD
    
=======

>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    // init modals
    _add.click(function(){
        _m.modal('show');
    });
<<<<<<< HEAD
    
    // init departure datepicker
    // _departure.datepicker();
    
    // configure circuit
    _configure.click(function(){
        _lm.modal('show');
    });
    
=======

    // init departure datepicker
    _departure.datepicker();

    // configure circuit
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
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
<<<<<<< HEAD
    
=======

>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
    $('#rsto-circuit-day-specify-datatable').RSTODatatable([
        {data: 'type_name'}
    ]);
    $('#rsto-circuit-day-hotel-rooms-datatatable').DataTable();
<<<<<<< HEAD
    
    $('#rsto-circuit-day-room-list-add-btn').click(function(){
       $('#rsto-circuit-day-room-list-modal').modal('show');
    });
    
    $('#rsto-circuit-day-specify-add-btn').click(function(){
        $('#rsto-circuit-day-specify-modal').modal('show');
    });
})
=======

    $('#rsto-circuit-day-room-list-add-btn').click(function(){
       $('#rsto-circuit-day-room-list-modal').modal('show');
    });

    $('#rsto-circuit-day-specify-add-btn').click(function(){
        $('#rsto-circuit-day-specify-modal').modal('show');
    });

     */
})
>>>>>>> 8fa09321f97ca2aafd75c3b363e57a5925738128
