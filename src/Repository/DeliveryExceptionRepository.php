<?php


namespace App\Repository;


use App\Models\DeliveryException;

class DeliveryExceptionRepository
{
    private $deliveryExceptionModel;

    public function __construct(DeliveryException $deliveryException)
    {
        $this->deliveryExceptionModel = $deliveryException;
    }

    public function getDeliveryExceptionProvider($providerId) {
        return $this->deliveryExceptionModel::where('provider_id', $providerId)->get()->toArray();
    }

    public function getFilteredDeliveryException($filterArr) {
        return $this->deliveryExceptionModel::where($filterArr)->get()->toArray();
    }

    public function createDeliverException($createArr) {
        $this->deliveryExceptionModel::create($createArr);
    }

    public function updateDeliverException($exceptionId, $updateArr) {
        $this->deliveryExceptionModel->where('id', $exceptionId)->update($updateArr);
    }

    public function deleteDeliveryException($exceptionId) {
        $this->deliveryExceptionModel->where('id', $exceptionId)->delete();
    }
}