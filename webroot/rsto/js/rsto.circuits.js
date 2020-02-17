

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
        numVol: $('#rsto-circuit-num-vol'),
        arrTime: $('#rsto-circuit-time'),
        currency: $('#rsto-circuit-currency'),
        drive: $('#rsto-circuit-drive')
    },
    buttons: {
        add: $('#rsto-circuit-add-btn'),
        edit: $('#rsto-circuit-edit-btn'),
        infos: $('#rsto-circuit-infos-btn'),
        delete: $('#rsto-circuit-delete-btn'),
        validation: $('#rsto-circuit-validation-btn'),
        configure: $('#rsto-circuit-configure-btn'),
        quote: $('#rsto-circuit-quote-btn'),
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
            _me.buttons.quote.RSTOEnable();


            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id': data.id}, 'data-edit-url');
        });

        _me.table.on('draw.dt', function () {
            // Disable buttons
            _me.buttons.edit.RSTODisable();
            _me.buttons.delete.RSTODisable();
            _me.buttons.validation.RSTODisable();
            _me.buttons.infos.RSTODisable();
            _me.buttons.configure.RSTODisable();
            _me.buttons.quote.RSTODisable();
        });

        // Edit circuit
        _me.buttons.edit.click(function () {
            _me.form.attr('data-edit', 'true');
            var _data = _me.table.RSTODatatableSelectedData();
            // Fill the form+
            _me.fields.start.RSTOOriginalValue(convertDateymd(_data.START));
            _me.fields.duration.RSTOOriginalValue(_data.DURATION);
            _me.fields.adultCount.RSTOOriginalValue(_data.ADULTS);
            _me.fields.childCount.RSTOOriginalValue(_data.childrens);
            _me.fields.numVol.RSTOOriginalValue(_data.num_vol);
            _me.fields.arrTime.RSTOOriginalValue(getTime(_data.arriving_time));

            //valeur select is_drive
            var indice;
            if(_data.self_drive)
                indice = 1;
            else
                indice = 0;
            _me.fields.drive.RSTOOriginalValue(indice,_data.drive_lib);
            //----------------------------
            _me.fields.tour.RSTOOriginalValue(parseInt(_data.id_tour_operator),_data.lib_tour_operator);
            _me.fields.currency.RSTOOriginalValue(parseInt(_data.id_currency), _data.currency_lib);

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

        // creating Quote
        _me.buttons.quote.click(function(){
            console.log(_me.table.RSTODatatableSelectedData().id);
            RSTOGetJSON(_me.buttons.quote.attr('data-url'), {'id': _me.table.RSTODatatableSelectedData().id}, _me.xCSRFToken, function (response) {
                if (response) {
                    console.log(response);
                    var dialog = window.open('./quote/', '_blank', '',false);
                    dialog.id = _me.table.RSTODatatableSelectedData().id;
                    dialog.xCSRFToken = _me.xCSRFToken;
                    dialog.trip = response.row.trip;
                    dialog.tripDet = response.row.tripDet;
                    dialog.opener = window;
                } else {
                    alert(RSTOMessages.Error);
                }
            });
        });
    }
};

var RSTOTripChild = {
    xCSRFToken: null,
    table: $('#rsto-circuit-days-datatable'),
    tableRoom: $('#rsto-circuit-day-hotel-rooms-datatatable'),
    datatable: null,
    datatableRoom: [],
    listModal: $('#rsto-circuit-days-modal'),
    listModal: $('#rsto-circuit-days-modal'),
    configureModal: $('#rsto-circuit-day-modal'),
    form: $('#rsto-trip-det-form'),
    formAddRoom: $('#rsto-circuit-day-add-room-form'),
    modalAddNewRoom: $('#rsto-circuit-day-room-add-list-modal'),
    rowSelectedRoom: null,
    fields: {
        id_hotel: $('#rsto-circuit-day-hotel'),
        carrier: $('#rsto-circuit-day-driver'),
        id_carrier_vehicle: $('#rsto_circuits_vehicle_select'),
        id_places : $('#rsto-circuit-place'),
        id_meal: $('#rsto-circuit-meal-plan'),

        id_selected: $('#rsto-circuit-day-room-id'),
        id_room: $('#rsto-circuit-day-room-list-room-type-plan'),
        id_count_room: $('#rsto-circuit-day-room-count-plan'),
        id_pax_room: $('#rsto-circuit-day-pax-plan'),
        meal_plan: $('#rsto-circuit-meal-plan')
    },
    buttons: {
        configure: $('#rsto-circuit-day-configure-btn'),
        addRoom: $('#rsto-circuit-day-room-list-add-btn'),
        editRoom: $('#rsto-circuit-edit-room'),
        addSpecify: $('#rsto-service-form-submit-btn'),
        deleteRoon: $('#rsto-circuit-delect-room'),
        saveAddRoom: $('#rsto-circuit-day-room-form-submit-btn'),
        always_drive: $('#rsto-circuit-always-drive-form-submit-btn'),
    },
    dataRoomTemp: null,
    init: function () {
        var circuits = RSTOCircuits;
        var _me = RSTOTripChild;

       _me.xCSRFToken = circuits.xCSRFToken;
        _me.buttons.addRoom.click( function () {
            _me.formAddRoom.RSTOReset();
            _me.fields.id_room.RSTODataURLQuery({hotel:_me.fields.id_hotel.val()});
            _me.modalAddNewRoom.modal('show');
        });
        _me.buttons.editRoom.click( function() {
            _me.formAddRoom.RSTOReset();
            var selectedData = _me.tableRoom.RSTODatatableSelectedData();
            console.log(selectedData);
            _me.fields.id_selected.val(selectedData.id);
            _me.fields.id_room.RSTODataURLQuery({hotel:_me.fields.id_hotel.val()});
            _me.fields.id_room.RSTOOriginalValue(selectedData.id, selectedData.type_name);
            _me.fields.id_count_room.RSTOOriginalValue(selectedData.count);
            _me.fields.id_pax_room.RSTOOriginalValue(selectedData.pax)
            _me.modalAddNewRoom.modal('show');
        });
        _me.buttons.always_drive.click( function() {
            if(_me.fields.carrier.val()!= null && _me.fields.id_carrier_vehicle.val() != null){
                var url = _me.buttons.always_drive.attr('data-url') + "?id=" + circuits.table.RSTODatatableSelectedData().id;
                $.ajax({
                    url : url,
                    type : 'POST',
                    data : 'carrier=' + _me.fields.carrier.val() + '&vehicle=' + _me.fields.id_carrier_vehicle.val(),
                    headers: {
                        'x-csrf-token': _me.xCSRFToken
                    },
                    dataType : 'json',
                    success: function(outpout){
                        _me.datatable.ajax.reload();
                    }

                });
            }

        });

       // On change place
        _me.fields.id_places.change(function(){
            // load hotel by id_place
            _me.fields.id_hotel.RSTODataURLQuery({place: _me.fields.id_places.val()});
            _me.fields.id_meal.RSTODataURLQuery({hotel: _me.fields.id_places.val()});
/*
            console.log(_me.fields.id_hotel);
            _me.fields.id_hotel[0].value = null;
            _me.fields.id_hotel[0].innerText = null;
            _me.fields.id_hotel[0].outerText = null;
            console.log(_me.fields.id_hotel);

 */


        });
        // On change hotel
        _me.fields.id_hotel.change( function () {
            console.log(_me.fields.id_hotel.val());
            // load Meal plan by hotel
           // _me.fields.id_meal.RSTODataURLQuery({hotel: _me.fields.id_hotel.val()});
        });
        // On change carrier
        _me.fields.carrier.change( function() {
            // load Vehicule by id carrier
            // _me.fields.id_carrier_vehicle.RSTODataURLQuery({carrier: _me.fields.carrier.val()});

        });

        // configure dependencies

        // Show daily trip
        circuits.buttons.configure.click( function () {
            _me.form.attr('data-edit', 'true');
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
        var transform = function(dataObject, columns) {
            var response = []
            for(var data of dataObject) {
                var row = [];
                for(var column of columns){
                    row.push(data[column]);
                }
                response.push(row);
            }
            return response;


        }
        var loadData = function() {
            _me.tableRoom.DataTable().clear().destroy();
            var dataSet = _me.dataRoomTemp;
            _me.tableRoom.DataTable({
                data: dataSet,
                columns: [
                    { "data": "type_name" },
                    { "data": "count" },
                    { "data": "pax" }
                ],
                createdRow: function (row, data) {
                    $(row).RSTOSelectableDatatableRow(_me.tableRoom, data);
                }
            });
            _me.fields.id_selected.val('');
        }
        _me.buttons.configure.click( function() {
            // initForm();
            _me.form.RSTOReset();
            _me.form.attr('data-edit', 'true');
            var dataSelected = _me.table.RSTODatatableSelectedData();
            console.log(dataSelected);
            // populate all field
            if (dataSelected.id_hotel ) _me.fields.id_hotel.RSTOOriginalValue(parseInt(dataSelected.id_hotel), dataSelected.hotel);
            if(dataSelected.id_places)   _me.fields.id_places.RSTOOriginalValue(parseInt(dataSelected.id_places), dataSelected.place);
            if(dataSelected.carrier) _me.fields.carrier.RSTOOriginalValue(parseInt(dataSelected.id_carrier), dataSelected.carrier);
            if(dataSelected.type_vehicule) _me.fields.id_carrier_vehicle.RSTOOriginalValue(parseInt(dataSelected.type_vehicule), dataSelected.type_vehicle_libelle);
            if(dataSelected.id_select_option) _me.fields.meal_plan.RSTOOriginalValue(parseInt(dataSelected.id_select_option), dataSelected.meal_plan);
            // populate ALL select 2 form
            _me.fields.id_hotel.RSTODataURLQuery({place: _me.fields.id_places.val()});
            _me.fields.id_meal.RSTODataURLQuery({hotel:_me.fields.id_hotel.val()});
            // populate datable of room by id trip dep
           //  var url = _me.tableRoom.RSTODataURLReturnQuery({id_trip: dataSelected.id});
           //  console.log(_me.tableRoom);
            var url = _me.tableRoom[0].baseURI + '/room_hotel?id_trip=' + dataSelected.id;
            $.ajax({ url: url,
                type: 'get',
                dataType: 'json',
                headers: {
                    'x-csrf-token': _me.xCSRFToken
                },
                success: function(output) {
                    if (output){
                        console.log(output);
                        _me.dataRoomTemp = output;
                        loadData();
                    }
                }
            });



            _me.configureModal.RSTOModalTitle("Day - {0}".format(dataSelected.day));
            _me.configureModal.modal('show');
            // Update data-edit-url
            _me.form.RSTODataURLQuery({'id': dataSelected.id}, 'data-edit-url');
        });
        // Ajout dans un object temporaire pour le traitement necessaire
        var findOnTemp = function(id_room){
            console.log(id_room);
            for(var temp of _me.dataRoomTemp) {
                console.log(temp.id);
                if(temp.id == id_room) {
                    return temp
                }
            }
            return null;
        }
        _me.buttons.saveAddRoom.click( function () {
            var _selectedTrip = _me.table.RSTODatatableSelectedData();

            const data = findOnTemp(_me.fields.id_room.val());
            console.log(_me.fields.id_selected.val());
            if(_me.fields.id_selected.val()!=="") {
                var index = 0;
                const length = _me.dataRoomTemp.length;
                for (var i=0;i<length;i++) {
                    const row = _me.dataRoomTemp[i];
                    console.log(row.id);
                    if (row.id == _me.fields.id_selected.val()) {
                        row.id = _me.fields.id_room.val();
                        row.type_name =_me.fields.id_room[0].innerText;
                        row.count = parseInt(_me.fields.id_count_room.val());
                        row.pax = parseInt(_me.fields.id_pax_room.val());

                        loadData();
                        _me.modalAddNewRoom.modal('hide');
                        _me.formAddRoom.RSTOReset();
                        return;
                    }
                }
            } else{
                console.log(data);
                if (data) {
                    data.id = _me.fields.id_room.val();
                    data.type_name = _me.fields.id_room[0].innerText;
                    data.count += parseInt(_me.fields.id_count_room.val());
                    data.pax += parseInt(_me.fields.id_pax_room.val());
                    loadData();
                    _me.modalAddNewRoom.modal('hide');
                    _me.formAddRoom.RSTOReset();
                    return;
                }
                // recuperation des valeurs dans fields
                var id_room = _me.fields.id_room.val();
                var type_libelle = _me.fields.id_room[0].innerText;
                var count =parseInt( _me.fields.id_count_room.val());
                var pax = parseInt(_me.fields.id_pax_room.val());
                // Mettre les données dans un objet de la meme structure que dataset
                var objectTemp = {
                    id_trip: _selectedTrip.id,
                    count: count,
                    pax: pax,
                    id: id_room,
                    type_name: type_libelle,
                    id_hotel: _selectedTrip.id_hotel
                }
                // Ajout de l'objet dans dataset
                _me.dataRoomTemp.push(objectTemp);
                // reloader le datatable tempo
                _me.modalAddNewRoom.modal('hide');
                loadData();
                // reinit le form controle
                _me.formAddRoom.RSTOReset();
            }

        });
        // supprimer une ligne du datable tempo sans affecter la base de donnée
        _me.buttons.deleteRoon.click( function() {
            var selectedElement = _me.tableRoom.RSTODatatableSelectedData();
            if(_me.dataRoomTemp){
                _me.dataRoomTemp.splice(_me.dataRoomTemp.indexOf(selectedElement),1);

                loadData();
            }
        });

        _me.form.on('submitted.rsto', function (e, response) {
            if (response.success === true) {
                var _editMode = _me.form.attr('data-edit') === 'true';
                _me.configureModal.modal('hide');
                _me.datatable.ajax.reload();
                alert(_editMode ? RSTOMessages.Updated : RSTOMessages.Added);
            } else {
                alert(RSTOMessages.Error);
            }
        });
        _me.form.on('submit', function(e) {
            var _selectedTrip = _me.table.RSTODatatableSelectedData();
            const url = _me.form.attr('data-room-url');
            console.log(_me.dataRoomTemp);
            $.ajax({
                url: url + '?' + $.param({ id_trip: _selectedTrip.id }),
                type: 'post',
                dataType: 'json',
                data: JSON.stringify(_me.dataRoomTemp),
                headers: {
                    'x-csrf-token': _me.xCSRFToken,
                    'Content-Type': 'application/json'
                },
                success: function(output) {
                    if (output){
                        console.log(output);
                        _me.dataRoomTemp = output;
                        loadData();
                    }
                }
            });
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
function convertDateymd(inputFormat) {
    const val = inputFormat.split('/');
    return val[2]+"-" + val[1] +"-" + val[0];
}
function getTime(time) {
    return time.split('T')[1].split('+')[0];
}
$(window).on('load', function(){
    RSTOCircuits.init();
    RSTOTripChild.init();

})
