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

class DeliveryExceptionPage
{
    private $twig;
    private $deliveryExceptionController;

    public function __construct(Twig $twig, DeliveryExceptionController $deliveryExceptionController)
    {
        $this->twig = $twig;
        $this->deliveryExceptionController = $deliveryExceptionController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $providerId = $args['id'];

        $exceptionList = $this->deliveryExceptionController->getDeliveryExceptionList($providerId);

        $data = $this->twig->fetch('exception-delivery.twig', [
            'title' => 'Страница доставки',
            'exception_list' => $exceptionList
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
        $this->deliveryExceptionController->updateDeliveryDayException($json);

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

        $this->deliveryExceptionController->createDeliveryException($providerId, $json);

        $data = json_encode(['msg' => 'data was updated']);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getParsedBody();
        $id = $params['id'];
        $this->deliveryExceptionController->deleteDeliveryDayException($id);
        $data = json_encode(['msg' => 'data was updated']);
        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}