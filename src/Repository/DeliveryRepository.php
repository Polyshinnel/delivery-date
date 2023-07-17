<?php


namespace App\Repository;


use App\Models\DeliveryTable;

class DeliveryRepository
{
    private $deliveryModel;

    public function __construct(DeliveryTable $deliveryModel)
    {
        $this->deliveryModel = $deliveryModel;
    }

    public function getDeliveryByProvider($providerId) {
        return $this->deliveryModel::where('provider_id', $providerId)->get()->toArray();
    }

    public function getDeliveryRecordByWeek($providerId,$weekDay) {
        $filterArr = [
            'provider_id' => $providerId,
            'week_day' => $weekDay
        ];
        return $this->deliveryModel::where($filterArr)->get()->toArray();
    }

    public function createDeliveryProvider($createArr) {
        $this->deliveryModel::create($createArr);
    }

    public function updateDeliveryProvider($deliveryId, $updateArr) {
        $this->deliveryModel::where('id', $deliveryId)->update($updateArr);
    }

    public function deleteDeliveryProvider($deliveryId, $updateArr) {
        $this->deliveryModel::where('id', $deliveryId)->delete();
    }

    public function getFilteredDate($filter) {
        return $this->deliveryModel::where($filter)->get()->toArray();
    }
}