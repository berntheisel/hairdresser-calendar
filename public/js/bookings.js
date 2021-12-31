let serviceCounter = 0;

function checkServiceCounter() {
    serviceCounter = $('#serviceTable >tbody >tr').length;

    if (serviceCounter === 0) {
        $('#createBookingButton').prop('disabled', true);
        $('#serviceTable').hide();
    } else {
        $('#createBookingButton').prop('disabled', false);
        $('#serviceTable').show();
    }
}

function addServiceRow(id) {

    let url = 'ajax-add-service-row';

    if (id !== undefined) {
        url = '../' + url;
    }

    serviceCounter++;

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            'serviceCounter': serviceCounter
        },
        success: function (response) {
            $('#servicesContainer').append(response);
            checkServiceCounter();
        },
        error: function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
}

function deleteServiceRow(id) {
    serviceCounter--;
    $('#serviceRow_' + id).remove();
    checkServiceCounter();
}

function changeService(element) {
    let serviceId = element.value;
    let serviceRowId = element.dataset.serviceCounter;
    let serviceDuration = serviceCatalog[serviceId];

    $('#serviceRowDurationInMinutes_' + serviceRowId).val(serviceDuration);
}

function loadServiceRows(booking_id) {
    checkServiceCounter();
    $.ajax({
        url: 'ajax-load-service-rows',
        type: 'POST',
        data: {
            'booking_id': booking_id
        },
        success: function (response) {
            $('#servicesContainer').html(response);
            checkServiceCounter();
        },
        error: function (request, error) {
            alert("Request: " + JSON.stringify(request));
        }
    });
}

