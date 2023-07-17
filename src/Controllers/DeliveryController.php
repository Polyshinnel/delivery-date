<?php


namespace App\Controllers;


use App\Repository\DeliveryExceptionRepository;
use App\Repository\DeliveryRepository;

class DeliveryController
{
    private $deliveryRepository;
    private $deliveryExceptionRepository;
    private $russianWeekDict = [
        '0' => 'Воскресенье',
        '1' => 'Понедельник',
        '2' => 'Вторник',
        '3' => 'Среда',
        '4' => 'Четверг',
        '5' => 'Пятница',
        '6' => 'Суббота'
    ];

    private $offsetRussianDict = [
        'Понедельник' => 'Следующий',
        'Вторник' => 'Следующий',
        'Среда' => 'Следующая',
        'Четверг' => 'Следующий',
        'Пятница' => 'Следующая',
        'Суббота' => 'Следующая',
        'Воскресенье' => 'Следующее',
    ];

    public function __construct(
        DeliveryRepository $deliveryRepository,
        DeliveryExceptionRepository $deliveryExceptionRepository
    )
    {
        $this->deliveryRepository = $deliveryRepository;
        $this->deliveryExceptionRepository = $deliveryExceptionRepository;
    }

    public function getDeliveryList($providerId) {
        $deliveryList = [];

        $rawDeliveryList = $this->deliveryRepository->getDeliveryByProvider($providerId);
        if(!empty($rawDeliveryList)) {
            foreach ($rawDeliveryList as $item) {
                $item['encoded_week_day'] = $this->russianWeekDict[$item['week_day']];
                $encoder = $this->encodeDelivery($item);
                $item['delivery_day'] = $encoder['delivery_day'];
                $item['vip_day'] = $encoder['vip_day'];
                $deliveryList[] = $item;
            }
        }

        return $deliveryList;
    }

    public function createDeliveryRecord($providerId, $json) {
        $jsonArr = json_decode($json, true);
        foreach ($jsonArr as $item) {
            $weekDay = $item['week_day'];
            $deliveryFlag = $item['delivery_flag'];
            $shipmentTimeUntil = $item['shipment_time_until'];
            if(empty($shipmentTimeUntil)) {
                $shipmentTimeUntil = NULL;
            }
            $deliveryDayOffset = $item['delivery_day_offset'];
            if(empty($deliveryDayOffset)) {
                $deliveryDayOffset = NULL;
            }
            $vipFlag = $item['vip_flag'];
            $vipTimeUntil = $item['vip_time_until'];
            if(empty($vipTimeUntil)) {
                $vipTimeUntil = NULL;
            }

            $checkDeliveryRecord = $this->deliveryRepository->getDeliveryRecordByWeek($providerId, $weekDay);
            if(!empty($checkDeliveryRecord)) {
                $deliveryRecord = $checkDeliveryRecord[0];
                $deliveryId = $deliveryRecord['id'];
                $this->updateDeliveryRecord($deliveryId, $item, true);
            } else {
                $createArr = [
                    'provider_id' => $providerId,
                    'week_day' => $weekDay,
                    'delivery_flag' => $deliveryFlag,
                    'shipment_time_until' => $shipmentTimeUntil,
                    'delivery_day_offset' => $deliveryDayOffset,
                    'vip_flag' => $vipFlag,
                    'vip_time_until' => $vipTimeUntil
                ];
                $this->deliveryRepository->createDeliveryProvider($createArr);
            }
        }
    }

    public function updateDelivery($json) {
        $jsonArr = json_decode($json, true);
        foreach ($jsonArr as $item) {
            $deliveryId = $item['id'];
            $this->updateDeliveryRecord($deliveryId, $item, true);
        }
    }

    private function updateDeliveryRecord($deliveryId, $json, $array_flag = false) {
        if(!$array_flag) {
            $json = json_decode($json, true);
        }
        $weekDay = $json['week_day'];
        $deliveryFlag = $json['delivery_flag'];
        $shipmentTimeUntil = $json['shipment_time_until'];
        if(empty($shipmentTimeUntil)) {
            $shipmentTimeUntil = NULL;
        }
        $deliveryDayOffset = $json['delivery_day_offset'];
        if(empty($deliveryDayOffset)) {
            $deliveryDayOffset = NULL;
        }
        $vipFlag = $json['vip_flag'];
        $vipTimeUntil = $json['vip_time_until'];
        if(empty($vipTimeUntil)) {
            $vipTimeUntil = NULL;
        }
        $updateArr = [
            'week_day' => $weekDay,
            'delivery_flag' => $deliveryFlag,
            'shipment_time_until' => $shipmentTimeUntil,
            'delivery_day_offset' => $deliveryDayOffset,
            'vip_flag' => $vipFlag,
            'vip_time_until' => $vipTimeUntil
        ];

        $this->deliveryRepository->updateDeliveryProvider($deliveryId, $updateArr);
    }

    private function encodeDelivery($item) {
        $currentDate = date('d.m.Y');
        $currentTime = date('H:i:s');

        $offsetStr = "+".$item['delivery_day_offset'].' days';

        $mktCurr = strtotime($currentTime);
        $mktShip = strtotime($item['shipment_time_until']);
        $vipTimeMkt = strtotime($item['vip_time_until']);

        $textDelivery = '';
        $textVipDelivery = '';

        $weekDay = date('w');
        $week_start = date('d.m.Y', strtotime('-'.$weekDay.' days'));
        $week_end = date('d.m.Y', strtotime('+'.(6-$weekDay).' days'));
        $startWeekMkt = strtotime($week_start);
        $endWeekMkt = strtotime($week_end);


        if($item['delivery_flag'] != 1) {
            $textDelivery = 'Заказ в данный день не возможен';
            $textVipDelivery = 'Заказ VIP в данный день не возможен';
        } else {
            if(($mktCurr < $mktShip) && ($item['week_day'] == $weekDay)) {
                $dayDelivery = date('d.m.Y', strtotime($offsetStr));

                $dayMkt = strtotime($dayDelivery);

                $dayOfWeekByDate = date('w', strtotime($dayDelivery));
                $dayWeekText = $this->russianWeekDict[$dayOfWeekByDate];

                $textDelivery = 'При заказе сегодня, доставят '.$dayDelivery.'('.$dayWeekText.')';

                if(($item['vip_flag'] == 1) && ($mktCurr < $vipTimeMkt)) {
                    $textVipDelivery = 'Доставят сегодня, при заказе до '.$item['vip_time_until'];
                } else{
                    $textVipDelivery = 'На сегодня уже не доступно';
                }
            } else {
                $offset = 1;
                $offsetStrText = '+'.$offset.' days';
                $deliveryDayOffset = '+'.$item['delivery_day_offset'].' days';
                if($item['week_day'] != $weekDay) {
                    while (true) {
                        $offsetStrNext = '+'.$offset.' days';

                        $nextDay = date('d.m.Y', strtotime($offsetStrNext));

                        $dayOfWeekByDate = date('w', strtotime($nextDay));
                        $offset += 1;

                        if($dayOfWeekByDate == $item['week_day']) {
                            $offsetStrText = $offsetStrNext;
                            break;
                        }
                    }
                }


                $nextDay = date('d.m.Y', strtotime($offsetStrText));
                $deliveryDay = date('d.m.Y', strtotime($nextDay.' '.$deliveryDayOffset));



                if($item['week_day'] == $weekDay) {
                    $provider = $item['provider_id'];
                    $dayWeek = date('w', strtotime($nextDay));
                    $nextRecord = $this->deliveryRepository->getDeliveryRecordByWeek($provider, $dayWeek);
                    if(!$nextRecord[0]['delivery_flag']) {
                        $textDelivery = 'Заказ в данный день не возможен';
                        $textVipDelivery = 'Заказ VIP в данный день не возможен';
                    } else {
                        $deliveryDayOffset = ' +'.$nextRecord[0]['delivery_day_offset'].' days';
                        $deliveryDay = date('d.m.Y', strtotime($nextDay.' '.$deliveryDayOffset));
                    }
                }

                $itemWeekEncoded = $this->russianWeekDict[$item['week_day']];
                $deliveryWeekDay = date('w', strtotime($deliveryDay));
                $deliveryEncodedDay = $this->russianWeekDict[$deliveryWeekDay];

                $textDelivery = 'При заказазе '.$nextDay.'('.$itemWeekEncoded.'), заказ прибудет: '.$deliveryDay.'('.$deliveryEncodedDay.')';
                if($item['week_day'] == $weekDay){
                    $textDelivery = 'При заказазе сегодня, заказ прибудет: '.$deliveryDay.'('.$deliveryEncodedDay.')';
                }
                $textVipDelivery = 'Доступно при заказе до: '.$item['vip_time_until'];
            }
        }

        return [
            'delivery_day' => $textDelivery,
            'vip_day' => $textVipDelivery
        ];
    }
}