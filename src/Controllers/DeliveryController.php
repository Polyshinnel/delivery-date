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
                $encoder = $this->encodeWeekDelivery($item);
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

    private function encodeWeekDelivery($item) {
        $weekDay = $item['week_day'];
        $deliveryFlag = $item['delivery_flag'];

        if(!$deliveryFlag) {
            return [
                'delivery_day' => 'Доставка в этот день не доступна',
                'vip_day' => 'Vip доставка в этот день не доступна'
            ];
        }

        $vipFlag = $item['vip_flag'];
        $vipTime = $item['vip_time_until'];
        if(!$vipFlag){
            $vipDay = 'Vip доставка в этот день не доступна';
        } else {
            $vipDay = 'Доступна при заказе до: '.$vipTime;
        }

        $deliveryDayOffset = $item['delivery_day_offset'];
        $shipmentTimeUntil = $item['shipment_time_until'];

        $nextWeekDay = (($weekDay + $deliveryDayOffset) % 7);
        $encodedDay = $this->russianWeekDict[$nextWeekDay];
        $nextWeekPointer = '';

        if((($weekDay + $deliveryDayOffset) / 7) > 1) {
            $nextWeekPointer = $this->offsetRussianDict[$encodedDay].' ';
        }



        $deliveryDay = 'Привезут в '.$nextWeekPointer.$encodedDay.' при заказе до: '.$shipmentTimeUntil;
        return [
            'delivery_day' => $deliveryDay,
            'vip_day' => $vipDay
        ];
    }

    public function calculateDelivery($json) {
        $jsonArr = json_decode($json, true);
        return $this->encodeWeekDelivery($jsonArr);
    }
}