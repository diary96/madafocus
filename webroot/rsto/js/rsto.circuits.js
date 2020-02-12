const RSTOCircuit = {
    xCSRFToken: null,
    table: $('#rsto-circuit-datatable'),
    modal:$('#rsto-circuit-modal'),
    datatable: null,
    init: function () {
        var _me = RSTOCircuit;
        _me.xCSRFToken = _me.table.attr('data-x-csrf-token');
        // Init Datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "id"},
            {"data": "start"},
            {"data": "duration"},
            {"data": "pax"},
            {"data": "drive_lib"},
            {"data": "status"}
        ]);
    }
};

$(window).on('load', function(){
    RSTOCircuit.init();

    var _dt = $('#rsto-circuits-datatable');
    var _m = $('#rsto-circuit-modal');
    var _lm = $('#rsto-circuit-days-modal');
    var _edit = $('#rsto-circuit-edit-btn');
    var _configure = $('#rsto-circuit-configure-btn');
    var _add = $('#rsto-circuits-add-btn');
    var _departure = $('#rsto-circuit-departure');
    var _configure = $('#rsto-circuit-configure-btn');
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
