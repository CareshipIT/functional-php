<?php

use Careship\Functional\Either\Reason;
use Careship\Functional\Result\Result;
use function Careship\Functional\Result\handle_result;

interface OrderService {
    function setOrderToShipped(string $orderId): Result;
}

final class ShipOrderController
{
    /** @var OrderService */
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(string $orderId): JsonResponse
    {
        return handle_result(
            $this->orderService->setOrderToShipped($orderId),
            function () {
                return JsonResponse('ok');
            },
            function (Reason $reason) {
                return JsonResponse('error: ' . $reason->toString());
            }
        );
    }
}
