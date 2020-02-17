var RSTOCircuits = {
    xCSRFToken: null,
    table: $('#rsto-circuit-datatable'),
    datatable: null,
    modal: $('#rsto-circuit-modal'),
    form: $('#rsto-circuit-form'),
    fields: {
        start: $('#rsto-circuit-start'),
        duration: $('#rsto-circuit-length'),
        adultCount: $('#rsto-circuit-adult'),
        childCount: $('#rsto-circuit-child'),
        tour: $('#rsto-circuit-tour-operator'),
        drive: $('#rsto-circuit-drive')
    },
    buttons: {
        add: $('#rsto-circuit-add-btn'),
        edit: $('#rsto-circuit-edit-btn'),
        infos: $('#rsto-circuit-infos-btn'),
        delete: $('#rsto-circuit-delete-btn'),
        validation: $('#rsto-circuit-validation-btn'),
        configure: $('#rsto-circuit-configure-btn')
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
        /*_me.modal.on('shown.bs.modal', function () {
            _me.fields.fullname.focus();
        });*/

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
            var _data = _me.table.RSTODatatableSelectedData();
            if(_data.ID_STATUS == 1){
                _me.buttons.validation.RSTOEnable();
            }
            else{
                _me.buttons.validation.RSTODisable();
            }

            // Enable buttons
            _me.buttons.edit.RSTOEnable();
            _me.buttons.delete.RSTOEnable();

            _me.buttons.infos.RSTOEnable();
            _me.buttons.configure.RSTOEnable();


            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            //_me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            //_me.buttons.validation.RSTOEnable();
            _me.buttons.infos.RSTODisable();
            _me.buttons.configure.RSTODisable();
        });

        // Edit circuit
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            var d = new Date(_data.START);
            //d = _data.START;
            // Fill the form+
            console.log(convertDateymd(_data.START));
            //console.log(convertDate(d));
            _me.fields.start.RSTOOriginalValue(convertDateymd(_data.START));
            _me.fields.duration.RSTOOriginalValue(_data.DURATION);
            _me.fields.adultCount.RSTOOriginalValue(_data.ADULTS);
            _me.fields.childCount.RSTOOriginalValue(_data.childrens);

            //valeur select is_drive
            var indice;
            if(_data.self_drive)
                indice = 1;
            else
                indice = 0;
            _me.fields.drive.RSTOOriginalValue(indice,_data.drive_lib);
            //----------------------------
            _me.fields.tour.RSTOOriginalValue(parseInt(_data.id_tour_operator),_data.lib_tour_operator);


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

        // validate circuit
        _me.buttons.validation.click(function () {
            confirm(RSTOMessages.Validation, function (response) {
                if (response) {
                    RSTOGetJSON(_me.buttons.validation.attr('data-url'), {'id': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                        if (response) {
                            _me.datatable.ajax.reload();
                            alert(RSTOMessages.Validated);
                        } else {
                            alert(RSTOMessages.Error);
                        }
                    });
                }
            });

        });
    }
};

var RSTOTripChild = {
    xCSRFToken: null,
    table: $('#rsto-circuit-days-datatable'),
    datatable: null,
    listModal: $('#rsto-circuit-days-modal'),
    configureModal: $('#rsto-circuit-day-modal'),
    form: $('#rsto-service-form'),
    fields: {
        id_hotel: $('#rsto-circuit-day-hotel'),
        carrier: $('#rsto-circuit-day-driver'),
        type_vehicule: $('#rsto-circuit-day-type-vehicule'),
        id_places : $('#rsto-circuit-place')
    },
    buttons: {
        configure: $('#rsto-circuit-day-configure-btn')
    },
    init: function () {
        var circuits = RSTOCircuits;
        var _me = RSTOTripChild;

       _me.xCSRFToken = circuits.xCSRFToken;

        _me.fields.id_places.change(function(){
            _me.fields.id_hotel.RSTODataURLQuery({place: _me.fields.id_places.val()});

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
            _me.form.attr('data-edit', 'true');
            var dataSelected = _me.table.RSTODatatableSelectedData();
            console.log(dataSelected);
            _me.fields.id_hotel.RSTOOriginalValue(dataSelected.id_hotel);
            _me.fields.id_places.RSTOOriginalValue(dataSelected.place);
            _me.fields.carrier.RSTOOriginalValue(dataSelected.id_carrier);

            _me.configureModal.RSTOModalTitle("Day - {0}".format(dataSelected.day));
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
function convertDate(inputFormat) {
    function pad(s) { return (s < 10) ? '0' + s : s; }
    const d = new Date(inputFormat);
    return [d.getFullYear(), pad(d.getDate()),pad(d.getMonth()+1)].join('-')
    //return [d.getFullYear(), pad(d.getMonth()+1),pad(d.getDate())].join('-')
}
function convertDateymd(inputFormat) {
    var val = inputFormat.split('/');
    var result = val[2]+"-" + val[1] +"-" + val[0];
    return result;
}
$(window).on('load', function(){
    RSTOCircuits.init();
    RSTOTripChild.init();

})
