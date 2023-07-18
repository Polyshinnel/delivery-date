<?php


namespace App\Controllers;


use App\Repository\DeliveryExceptionRepository;

class DeliveryExceptionController
{
    private $deliveryExceptionRepository;

    public function __construct(DeliveryExceptionRepository $deliveryExceptionRepository)
    {
        $this->deliveryExceptionRepository = $deliveryExceptionRepository;
    }

    public function getDeliveryExceptionList($providerId) {
        return $this->deliveryExceptionRepository->getDeliveryExceptionProvider($providerId);
    }

    public function createDeliveryException($providerId, $json) {
        $jsonArr = json_decode($json, true);
        foreach ($jsonArr as $item) {
            $exceptionName = $item['exception_name'];
            $exceptionDay = $item['exception_day'];
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

            $filter = [
                'provider_id' => $providerId,
                'exception_day' => $exceptionDay
            ];

            $res = $this->deliveryExceptionRepository->getFilteredDeliveryException($filter);
            if(!empty($res)) {
                $exceptionId = $res[0]['id'];
                $updateArr = [
                    'exception_name' => $exceptionName,
                    'delivery_flag' => $deliveryFlag,
                    'shipment_time_until' => $shipmentTimeUntil,
                    'delivery_day_offset' => $deliveryDayOffset,
                    'vip_flag' => $vipFlag,
                    'vip_time_until' => $vipTimeUntil
                ];
                $this->deliveryExceptionRepository->updateDeliverException($exceptionId, $updateArr);
            } else {
                $createArr = [
                    'provider_id' => $providerId,
                    'exception_name' => $exceptionName,
                    'exception_day' => $exceptionDay,
                    'delivery_flag' => $deliveryFlag,
                    'shipment_time_until' => $shipmentTimeUntil,
                    'delivery_day_offset' => $deliveryDayOffset,
                    'vip_flag' => $vipFlag,
                    'vip_time_until' => $vipTimeUntil
                ];
                $this->deliveryExceptionRepository->createDeliverException($createArr);
            }
        }
    }

    public function updateDeliveryDayException($json) {
        $jsonArr = json_decode($json, true);
        foreach ($jsonArr as $item) {
            $exceptionId = $item['id'];
            $exceptionName = $item['exception_name'];
            $exceptionDay = $item['exception_day'];
            $deliveryFlag = $item['delivery_flag'];
            $shipmentTimeUntil = $item['shipment_time_until'];

            if(!$item['delete_flag']) {
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

                $updateArr = [
                    'exception_name' => $exceptionName,
                    'exception_day' => $exceptionDay,
                    'delivery_flag' => $deliveryFlag,
                    'shipment_time_until' => $shipmentTimeUntil,
                    'delivery_day_offset' => $deliveryDayOffset,
                    'vip_flag' => $vipFlag,
                    'vip_time_until' => $vipTimeUntil
                ];

                $this->deliveryExceptionRepository->updateDeliverException($exceptionId,$updateArr);
            } else {
                $this->deliveryExceptionRepository->deleteDeliveryException($exceptionId);
            }


        }
    }

    public function deleteDeliveryDayException($id) {
        $this->deliveryExceptionRepository->deleteDeliveryException($id);
    }
}