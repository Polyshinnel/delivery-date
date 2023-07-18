<?php


namespace App\Pages;

use App\Controllers\DeliveryController;
use App\Controllers\DeliveryExceptionController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class DeliveryPage
{
    private $twig;
    private $deliveryController;
    private $deliveryExceptionController;

    public function __construct(Twig $twig, DeliveryController $deliveryController, DeliveryExceptionController $deliveryExceptionController)
    {
        $this->twig = $twig;
        $this->deliveryController = $deliveryController;
        $this->deliveryExceptionController = $deliveryExceptionController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $providerId = $args['id'];
        $deliveryList = $this->deliveryController->getDeliveryList($providerId);
        $exceptionList = $this->deliveryExceptionController->getDeliveryExceptionList($providerId);

        $data = $this->twig->fetch('delivery.twig', [
            'title' => 'Страница доставки',
            'delivery_list' => $deliveryList,
            'exception_list' => $exceptionList,
            'provider_id' => $providerId
        ]);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }


    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $json = $params['json'];

        $this->deliveryController->updateDelivery($json);

        $data = json_encode(['msg' => 'data was updated']);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $json = $params['json'];
        $providerId = $params['provider_id'];

        $this->deliveryController->createDeliveryRecord($providerId, $json);

        $data = json_encode(['msg' => 'data was updated']);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function getPrediction(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $json = $params['json'];
        $dataRaw = $this->deliveryController->calculateDelivery($json);
        $data = json_encode($dataRaw, JSON_UNESCAPED_UNICODE);
        return new Response(
            200,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($data)
        );
    }
}