<?php

/**
 * This example combines Result and Maybe to cover all potential cases during a read operation.
 * Different things could go wrong here (e.g. db connection could not be successful).
 * This is why we wrap OrderService::findOrderById in a result(), in order to catch any exception.
 * The returned value is a Result<Maybe<Order>> object, which we can easily unwrap into an Order object using extract_some_or_fail().
 * In case we couldn't find any Order by that orderId (i.e. the Maybe is a None) the function would throw an exception.
 */

use Careship\Functional\Maybe\Maybe;
use Careship\Functional\Result\Result;
use function Careship\Functional\extract_some_or_fail;
use function Careship\Functional\Result\result;

interface Address {}

interface Order {
    function getCustomerAddress(): Address;
}

interface OrderRepository {
    /** @return Maybe<Order> */
    public function load(string $orderId): Maybe;
}

final class OrderService {
    /** @var OrderRepository */
    private $orderRepository;

    /**
     * @return Result<Maybe<Order>>
     */
    public function findOrderById(string $orderId): Result
    {
        return result(function() use ($orderId) {
            return $this->orderRepository->load($orderId);
        });
    }
}

$orderService = new OrderService();

$order = extract_some_or_fail(
    $orderService->findOrderById('some_order_id'),
    'Cannot find order'
);

// At this point we are 100% sure that $order is of type Order
$order->getCustomerAddress();