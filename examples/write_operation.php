<?php

/**
 * This example combines Result and Maybe to cover all potential cases during a write operation.
 * Different things could go wrong here (e.g. order not found by that orderId, db connection not successful).
 * This is why we wrap OrderService::setOrderToShipped in a result(), in order to catch any exception.
 * The returned value is a Result<null> object, which we can easily evaluate using success_or_fail().
 * In case anything went wrong in the service call (i.e. the Result is a Failure) the function would throw an exception.
 */

use Careship\Functional\Maybe\Maybe;
use Careship\Functional\Result\Result;
use function Careship\Functional\extract_some_or_fail;
use function Careship\Functional\Result\result;
use function Careship\Functional\success_or_fail;

interface Order {
    function ship(): void;
}

interface OrderRepository {
    /** @return Maybe<Order> */
    public function load(string $orderId): Maybe;

    public function save(Customer $customer): Maybe;
}

final class OrderService {
    /** @var OrderRepository */
    private $orderRepository;

    /**
     * @return Result<null>
     */
    public function setOrderToShipped(string $orderId): Result
    {
        return result(function() use ($orderId) {
            $order = extract_some_or_fail(
                $this->orderRepository->load($orderId),
                'Cannot set order to shipped'
            );

            $order->ship();

            $this->orderRepository->save($order);
        });
    }
}

$orderService = new OrderService();

success_or_fail(
    $orderService->setOrderToShipped('some_order_id'),
    'Cannot set order to shipped'
);
