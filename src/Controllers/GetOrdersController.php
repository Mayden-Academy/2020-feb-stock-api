<?php

namespace App\Controllers;

use App\Abstracts\Controller;
use App\Interfaces\OrderModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetOrdersController extends Controller
{
    private $orderModel;

    /**
     * GetOrdersController constructor.
     * @param $orderModel
     */
    public function __construct(OrderModelInterface $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $completed = $queryParams['completed'] === '1' ? 1 : 0;
        $responseData = ['success' => false,
            'message' => 'Something went wrong, please try again later',
            'data' => []];

        try {
            $orders = $this->orderModel->getAllOrders($completed);
        } catch (\Throwable $e){
            return $this->respondWithJson($response, $responseData, 500);
        }

        if ($orders === false) {
            return $this->respondWithJson($response, $responseData, 500);
        }

        $responseData = ['success' => true,
            'message' => empty($orders) ? 'No orders are currently in the DB' : 'All orders returned',
            'data' => ['orders' => $orders]];

        return $this->respondWithJson($response, $responseData);
    }
}
