<?php

/**
 * In this example we use Either to let the client know whether we successfully verified a Customer or not.
 * Preventing double verification of a Customer is an expected use case, hence we can't technically model it as a Failure.
 * Either is therefore a convenient way to model a yes/no information inside the result of a successful operation.
 */

use Careship\Functional\Either\Either;
use Careship\Functional\Either\Reason;
use Careship\Functional\Maybe\Maybe;
use Careship\Functional\Result\ExceptionStack;
use Careship\Functional\Result\Result;
use function Careship\Functional\Either\handle_either;
use function Careship\Functional\Either\no;
use function Careship\Functional\Either\yes;
use function Careship\Functional\extract_some_or_fail;
use function Careship\Functional\Result\handle_result;
use function Careship\Functional\Result\result;

final class Customer {
    /** @var bool */
    private $verified;

    function verifyEmail(): Either {
        if ($this->verified) {
            return no('Already verified');
        }

        $this->verified = true;

        return yes();
    }
}

interface CustomerRepository {
    /** @return Maybe<Customer> */
    public function load(string $customerId): Maybe;

    public function save(Customer $customer): void;
}

final class CustomerService {
    /** @var CustomerRepository */
    private $customerRepository;

    /**
     * @return Result<null>
     */
    public function verifyEmail(string $customerId): Result
    {
        return result(function() use ($customerId) {
            $customer = extract_some_or_fail(
                $this->customerRepository->load($customerId),
                'Cannot verify customer email'
            );

            $wasCustomerVerified = $customer->verifyEmail();

            $wasCustomerVerified->yes(function() use ($customer) {
                $this->customerRepository->save($customer);
            });

            return $wasCustomerVerified;
        });
    }
}

$customerService = new CustomerService();

return handle_result(
    $customerService->verifyEmail('some_customer_id'),
    function (Either $wasCustomerVerified) {
        return handle_either(
            $wasCustomerVerified,
            function() {
                return 'Customer successfully verified';
            },
            function (Reason $reason) {
                return $reason->toString();
            }
        );
    },
    function (ExceptionStack $exceptionStack) {
        $exceptionStack->toString();
    }
);
