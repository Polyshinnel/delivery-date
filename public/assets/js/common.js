function getProviderId() {
    let url = window.location.pathname
    let urlArr = url.split('/')
    return urlArr.pop()
}

$('#add-delivery').click(function () {
    $('.delivery-wrapper').append('<div class="delivery-line">\n' +
        '            <p class="monosize">\n' +
        '                <select name="" id="" class="delivery-day">\n' +
        '                    <option value="1">Понедельник</option>\n' +
        '                    <option value="2">Вторник</option>\n' +
        '                    <option value="3">Среда</option>\n' +
        '                    <option value="4">Четверг</option>\n' +
        '                    <option value="5">Пятница</option>\n' +
        '                    <option value="6">Суббота</option>\n' +
        '                    <option value="0">Воскресенье</option>\n' +
        '                </select>\n' +
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
        '\n' +
        '        </div>')
});



$('#create-events').click(function () {
    let createArr = [];
    $('.delivery-line').each(function () {
        let deliveryDay = $(this).find('.delivery-day').val();
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
        let vipTime = $(this).find('.vip-time').val();
        let obj = {
            'week_day': deliveryDay,
            'delivery_flag': deliveryFlag,
            'shipment_time_until': deliveryTimeUntil,
            'delivery_day_offset': deliveryDayOffset,
            'vip_flag': vipFlag,
            'vip_time_until': vipTime
        }
        createArr.push(obj)
    });

    let providerId = getProviderId()
    let json = JSON.stringify(createArr)


    $.ajax({
        url: '/settings/create-delivery',
        method: 'post',
        dataType: 'html',
        data: {
            provider_id: providerId,
            json: json
        },
        success: function(data){
            let url = '/settings/delivery/'+providerId
            window.location.replace(url)
        }
    });
});


$('#refresh-delivery').click(function () {
    let updateArr = [];
    $('.delivery-line').each(function () {
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

    $.ajax({
        url: '/settings/update-delivery',
        method: 'post',
        dataType: 'html',
        data: {
            json: json
        },
        success: function(data){
            window.location.reload()
        }
    });
})

$('#add-delivery-exception').click(function () {
    $('.delivery-wrapper').append('<div class="delivery-line delivery-exception-new">\n' +
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



$('#create-exception').click(function () {
    let selector = $('.delivery-exception-new')
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

        let providerId = getProviderId()
        let json = JSON.stringify(createArr)

        $.ajax({
            url: '/settings/delivery-exception/create',
            method: 'post',
            dataType: 'html',
            data: {
                provider_id: providerId,
                json: json
            },
            success: function(data){
                window.location.reload()
            }
        });
    } else {
        alert('Нечего добавлять!')
    }
})

$('#refresh-delivery-exception').click(function () {
    let selector = $('.delivery-exception-exist')
    if(selector.length) {
        let updateArr = []
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

            let obj = {
                'id': id,
                'exception_name': name,
                'exception_day': exceptionDate,
                'delivery_flag': deliveryFlag,
                'shipment_time_until': deliveryTimeUntil,
                'delivery_day_offset': deliveryDayOffset,
                'vip_flag': vipFlag,
                'vip_time_until': vipTime
            }
            updateArr.push(obj)
        })

        let json = JSON.stringify(updateArr)

        $.ajax({
            url: '/settings/delivery-exception/update',
            method: 'post',
            dataType: 'html',
            data: {
                json: json
            },
            success: function(data){
                window.location.reload()
            }
        });

    } else {
        alert('Нечего обновлять!')
    }
})

$('.delete-exception').click(function () {
    let id = $(this).attr('data-id');
    $.ajax({
        url: '/settings/delivery-exception/delete',
        method: 'post',
        dataType: 'html',
        data: {
            id: id
        },
        success: function(data){
            window.location.reload()
        }
    });
})