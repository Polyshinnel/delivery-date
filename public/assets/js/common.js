function getProviderId() {
    let url = window.location.pathname
    let urlArr = url.split('/')
    return urlArr.pop()
}


function updateDelivery() {
    let updateArr = [];
    $('.delivery-line-base').each(function () {
        let deliverySelector = $(this).find('.delivery-week-day')
        let id = deliverySelector.attr('data-id');
        let weekDay = deliverySelector.attr('data-week')
        let deliveryFlag = 0;
        if($(this).find('.delivery-flag input').is(':checked')) {
            deliveryFlag = 1;
        }
        let shipmentTimeUntil = $(this).find('.delivery-time').val();
        let deliveryDayOffset = $(this).find('.delivery-day-offset').val();
        let vipFlag = 0;
        if($(this).find('.delivery-vip input').is(':checked')) {
            vipFlag = 1;
        }
        let vipTime = $(this).find('.delivery-vip-time').val();

        let obj = {
            'id': id,
            'week_day': weekDay,
            'delivery_flag': deliveryFlag,
            'shipment_time_until': shipmentTimeUntil,
            'delivery_day_offset': deliveryDayOffset,
            'vip_flag': vipFlag,
            'vip_time_until': vipTime
        }

        updateArr.push(obj)
    })

    let json = JSON.stringify(updateArr)

    return json;
}

function getNewException() {
    let selector = $('.delivery-exception-new')
    let providerId = getProviderId()
    if(selector.length) {
        let createArr = [];
        selector.each(function () {
            let name = $(this).find('.delivery-name').val()
            let exceptionDate = $(this).find('.delivery-date').val()
            let deliveryFlag = 0;
            if($(this).find('.delivery-flag').is(':checked')) {
                deliveryFlag = 1;
            }
            let deliveryTimeUntil = $(this).find('.delivery-time-until').val()
            let deliveryDayOffset = $(this).find('.delivery-day-offset').val()
            let vipFlag = 0;
            if($(this).find('.vip-flag').is(':checked')) {
                vipFlag = 1;
            }
            let vipTime = $(this).find('.vip-time').val()
            let obj = {
                'provider_id': providerId,
                'exception_name': name,
                'exception_day': exceptionDate,
                'delivery_flag': deliveryFlag,
                'shipment_time_until': deliveryTimeUntil,
                'delivery_day_offset': deliveryDayOffset,
                'vip_flag': vipFlag,
                'vip_time_until': vipTime
            }
            createArr.push(obj)
        })

        let json = JSON.stringify(createArr)
        return json;

    }

    return '';
}

function getExistExceptions() {
    let selector = $('.delivery-exception-exist')
    let updateArr = []
    if(selector.length) {
        selector.each(function () {
            let id = $(this).find('.delivery-name').attr('data-id')
            let name = $(this).find('.delivery-name').val()
            let exceptionDate = $(this).find('.delivery-date').val()
            let deliveryFlag = 0;
            if($(this).find('.delivery-flag').is(':checked')) {
                deliveryFlag = 1;
            }
            let deliveryTimeUntil = $(this).find('.delivery-time-until').val()
            let deliveryDayOffset = $(this).find('.delivery-day-offset').val()
            let vipFlag = 0;
            if($(this).find('.vip-flag').is(':checked')) {
                vipFlag = 1;
            }
            let vipTime = $(this).find('.vip-time').val()
            let deleteFlag = $(this).find('.delete-exception').attr('data-flag');
    
            let obj = {
                'id': id,
                'exception_name': name,
                'exception_day': exceptionDate,
                'delivery_flag': deliveryFlag,
                'shipment_time_until': deliveryTimeUntil,
                'delivery_day_offset': deliveryDayOffset,
                'vip_flag': vipFlag,
                'vip_time_until': vipTime,
                'delete_flag': deleteFlag
            }
            updateArr.push(obj)
        })
    
        let json = JSON.stringify(updateArr)
        return json
    } 
    
    return ''
}

$('#add-delivery').click(function () {
    $('.delivery-exception-block').append('<div class="delivery-line delivery-exception-new">\n' +
        '            <p class="monosize">\n' +
        '                <input type="text" name="" id="" class="delivery-name">\n' +
        '            </p>\n' +
        '            <p class="monosize">\n' +
        '                <input type="date" name="" id="" class="delivery-date">\n' +
        '            </p>\n' +
        '            <div class="checkbox-wrapper monosize">\n' +
        '                <input type="checkbox" name="" id="" class="delivery-flag">\n' +
        '            </div>\n' +
        '            <div class="monosize">\n' +
        '                <input type="time" class="monosize delivery-time-until" name="" id="" value="">\n' +
        '            </div>\n' +
        '\n' +
        '            <div class="monosize">\n' +
        '                <input type="text" class="monosize delivery-day-offset" name="" id="" value="">\n' +
        '            </div>\n' +
        '\n' +
        '            <div class="checkbox-wrapper monosize">\n' +
        '                <input type="checkbox" name="" id="" class="vip-flag">\n' +
        '            </div>\n' +
        '\n' +
        '            <div class="monosize">\n' +
        '                <input type="time" class="monosize vip-time" name="" id="" value="">\n' +
        '            </div>\n' +
        '        </div>')
});

$(document).ready(function () {
    $('.delivery-line-base input').on('input', function() {

        let weekDay = $(this).parent().parent().find('.delivery-week-day').attr('data-id')
        let deliveryCheckbox = $(this).parent().parent().find('.delivery-flag input')
        let deliveryTimeInput = $(this).parent().parent().find('.delivery-time')
        let deliveryDayOffsetInput = $(this).parent().parent().find('.delivery-day-offset')
        let vipCheckbox = $(this).parent().parent().find('.delivery-vip input')
        let vipDeliveryTimeInput = $(this).parent().parent().find('.delivery-vip-time')
        let deliveryFlag = 0;
        let vipFlag = 0;
        let deliveryPrediction = $(this).parent().parent().find('.delivery-prediction')
        let vipPrediction = $(this).parent().parent().find('.vip-prediction')


        $(this).parent().parent().css('background','#9CFAB6')
        if(!deliveryCheckbox.is(':checked')){
            deliveryTimeInput.prop("disabled",true)
            deliveryDayOffsetInput.prop("disabled",true)
            vipCheckbox.prop("disabled",true)
            vipDeliveryTimeInput.prop("disabled",true)
        } else {
            deliveryFlag = 1;
            deliveryTimeInput.prop("disabled",false)
            deliveryDayOffsetInput.prop("disabled",false)
            vipCheckbox.prop("disabled",false)
            vipDeliveryTimeInput.prop("disabled",false)
        }

        if(vipCheckbox.is(':checked')) {
            vipFlag = 1;
        }

        console.log(deliveryFlag)


        let jsonObj = {
            'week_day': weekDay,
            'delivery_flag': deliveryFlag,
            'delivery_day_offset': deliveryDayOffsetInput.val(),
            'shipment_time_until': deliveryTimeInput.val(),
            'vip_flag': vipFlag,
            'vip_time_until': vipDeliveryTimeInput.val()
        }

        let json = JSON.stringify(jsonObj)

        $.ajax({
            url: '/settings/prediction-delivery',
            method: 'post',
            dataType: 'json',
            data: {
                json: json
            },
            success: function(data){
                deliveryPrediction.html(data.delivery_day)
                vipPrediction.html(data.vip_day)
            }
        });
    });

    $('.delivery-line-exception input').on('input', function(){
        let deliveryCheckbox = $(this).parent().parent().find('.delivery-flag');
        let deliveryTimeUntilInput = $(this).parent().parent().find('.delivery-time-until');
        let deliveryDayOffsetInput = $(this).parent().parent().find('.delivery-day-offset');
        let vipFlag = $(this).parent().parent().find('.vip-flag');
        let vipTime = $(this).parent().parent().find('.vip-time');

        let cssColor = $(this).parent().parent().css('background-color');

        if(cssColor != 'rgb(246, 171, 156)') {
            $(this).parent().parent().css('background','#9CFAB6')
        }

        if(!deliveryCheckbox.is(':checked')) {
            deliveryTimeUntilInput.prop("disabled",true)
            deliveryDayOffsetInput.prop("disabled",true)
            vipFlag.prop("disabled",true)
            vipTime.prop("disabled",true)
        } else {
            deliveryTimeUntilInput.prop("disabled",false)
            deliveryDayOffsetInput.prop("disabled",false)
            vipFlag.prop("disabled",false)
            vipTime.prop("disabled",false)
        }
    })
})

$('.delete-exception').click(function () {
    let deleteFlag = $(this).attr('data-flag');
    if(deleteFlag == '0') {
        deleteFlag = 1;
    } else {
        deleteFlag = 0;
    }
    $(this).attr('data-flag', deleteFlag)

    if(deleteFlag == 1) {
        $(this).parent().css('background','#F6AB9C')
    } else {
        $(this).parent().css('background','#FFFFFF')
    }

    console.log(deleteFlag)
})

$('#save-changes').click(function(){
    let existJsonExceptions = getExistExceptions();
    let newJsonExceptions = getNewException();
    let updateDeliveryJson = updateDelivery();
    let providerId = getProviderId()

    $.ajax({
        url: '/settings/processing-delivery',
        method: 'post',
        dataType: 'json',
        data: {
            delivery_json: updateDeliveryJson,
            exception_json: existJsonExceptions,
            new_exception_json: newJsonExceptions,
            provider_id: providerId,
        },
        success: function(data){
            window.location.reload()
        }
    });
})