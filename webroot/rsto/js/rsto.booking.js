const RSTObooking = {
    xCSRFToken: null,
    table: $('#rsto-booking-datatable'),
    datatable: null,
    buttons: {
        confirmBtn: $('#rsto-booking-confirm-btn'),
        cancelBtn: $('#rsto-booking-cancel-btn'),
        callBtn: $('#rsto-booking-call-btn'),
        viewBtn: $('#rsto-booking-view-btn')
    },
    init: function () {
        var _me = RSTObooking;
        _me.xCSRFToken = _me.table.attr('data-x-csrf-token');
        // Init Datatable
        _me.datatable = _me.table.RSTODatatable([
            {"data": "numero_ticket"},
            {"data": "id_service"},
            {"data": "id_trips"},
            {"data": "total"},
            {"data": "date_start"},
            {"data": "date_ending"},
            {"data": "duration"},
            {"data": "total_person"},
            {"data": "adult"},
            {"data": "children"},
            {"data": "id_status"},
            {"data": "cost"}
        ]);

        _me.buttons.viewBtn.click(function(){
            $('#rsto-book-1-modal').modal('show');
        });

        _me.buttons.confirmBtn.click(function(){
            confirm('Do you really want to confirm ?', function(response){
                if(response === false) return;
                _dt.find("tr.selected").remove();
            });
        });

        _me.buttons.cancelBtn.click(function(){
            confirm('Do you really want to cancel ?', function(response){
                if(response === false) return;
                switch(_dt.find('tr.selected').data('type')){
                    case 'hotel':
                        $('#rsto-book-2-modal').modal('show');
                        break;
                    case 'drive':
                        $('#rsto-book-3-modal').modal('show');
                        break;
                    default:
                        break;
                };
                _dt.find('tr.selected').remove();
                $('.btn-app').not('#rsto-booking-filter-btn').RSTODisable();
            });
        });

        $('#rsto-booking-filter-btn').click(function(){
            $('#rsto-book-filter-modal').modal('show');
        });

        // When a ticket is selected
        _me.table.on('selectionChanged.rsto', function (e, data) {
            _me.buttons.viewBtn.RSTOEnable();
            _me.buttons.confirmBtn.RSTOEnable();
            _me.buttons.cancelBtn.RSTOEnable();
            if(data.type === 'mobile'){
                _me.buttons.callBtn.RSTOEnable();
            } else {
                _me.buttons.callBtn.RSTODisable();
            }
        });
    }
};

$(window).on('load', function(){
    RSTObooking.init();
});
