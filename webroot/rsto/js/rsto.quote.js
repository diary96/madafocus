console.log(window.id);
console.log(window.xCSRFToken);
console.log(window.trip);
console.log(window.tripDet);
function convertDateMdy(inputFormat, sep, sepRes) {
    const val = inputFormat.split(sep);
    return val[1]+ sepRes + val[2] + sepRes + val[0];
}
function convertDateDmyToMdy(inputFormat, sep, sepRes) {
    const val = inputFormat.split(sep);
    return val[1]+ sepRes + val[0] + sepRes + val[2];
}
$(window).on('load', function(){
    const trip = window.trip;
    const tripDet = window.tripDet;
    const token = 'e4f3bbc7681706044d9e0b737d6f399e816deeb3ecf767568f8fa57a89771c5072735d02c6f3d18f87b40b0eedf154f3eb86d3f440d27d23faee0973b3e098b9';
    const id = 'BET-0';
    $('#ref').text(trip.id_trips);
    // $('#ref').text(id);
    $('#start').text(convertDateMdy(trip.start.split('T')[0], '-', '-'));
    // RSTOGetJSON($('#quote').attr('data-url'), {'id': id}, token, function (response) {
    //     if (response) {
    //         console.log('aaa');
    //         console.log(response);
    //     } else {
    //         alert(RSTOMessages.Error);
    //     }
    // });
    let html = '';
    let desc = '';
    let price = '';
    for(var i = 0; i < tripDet.length; i++){
        desc = '';
        price = '';
        html += '<tr><td>' + tripDet[i].day + '</td>'
              + '<td>' + convertDateDmyToMdy(tripDet[i].date.split('T')[0], '/', '/') + '</td>';
        if(i == 0) html += '<td>Arrival ' + tripDet[i].place + '</td>';
        else html += '<td>' + tripDet[i].place + '</td>';

        if(tripDet[i].description.length > 0){
            desc += '<td>' + tripDet[i].description[0];
            price += '<td>' + tripDet[i].prix[0];
        }
        if(tripDet[i].description.length == 1){
            desc += '</td>';
            price += '</td></tr>';
        }
        for (let k = 1; k < tripDet[i].description.length; k++) {
            if(k == tripDet[i].description.length-1){
                desc += '<br>' + tripDet[i].description[k] + '</td>';
                price += '<br>' + tripDet[i].prix[k] + '</td></tr>';
                continue;
            }
            desc += '<br>' + tripDet[i].description[k];
            price += '<br>' + tripDet[i].prix[k];
        }
        html += desc;
        html += price;
        console.log(html);
    }
        
    $('#tquote tbody').append(html);

    $('#total').text(trip.total);
})