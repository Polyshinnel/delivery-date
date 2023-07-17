<?php

use App\Pages\DeliveryExceptionPage;
use App\Pages\DeliveryPage;
use App\Pages\IndexPage;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->group('/',function (RouteCollectorProxy $group) {
        $group->get('',[IndexPage::class,'get']);

        $group->get('settings/delivery/{id}',[DeliveryPage::class,'get']);
        $group->get('settings/delivery/create/{id}',[DeliveryPage::class,'createEvent']);


        $group->post('settings/update-delivery',[DeliveryPage::class,'update']);
        $group->post('settings/create-delivery',[DeliveryPage::class,'create']);


        $group->get('settings/delivery/delivery-exception/{id}',[DeliveryExceptionPage::class,'get']);
        $group->post('settings/delivery-exception/create',[DeliveryExceptionPage::class,'create']);
        $group->post('settings/delivery-exception/update',[DeliveryExceptionPage::class,'update']);
        $group->post('settings/delivery-exception/delete',[DeliveryExceptionPage::class,'delete']);
    });
};