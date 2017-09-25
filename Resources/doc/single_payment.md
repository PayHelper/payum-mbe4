# Single Payment

* [Capture](#capture)
* [Symfony integration](#symfony-integration)

## Capture

**Note** that Authorization is done via Capture request.
In other words, payment is authorized and captured immediately.

```php
use Payum\Core\Request\Capture;
use Sourcefabric\Payum\Mbe4\Api;

$payment = [];
$payment[Api::FIELD_DESCRIPTION] = 'description';
$payment[Api::FIELD_CLIENT_TRANSACTION_ID] = md5(uniqid((string) mt_rand(), true));
$payment[Api::FIELD_AMOUNT] = 100;
$payment[Api::FIELD_CURRENCY] = 'EUR';

$payum
    ->getGateway('mbe4')
    ->execute(new Capture($payment));
```

# Symfony integration:

```php
<?php

//src/Acme/PaymentBundle/Controller
namespace AcmeDemoBundle\Controller;

use Payum\Core\Security\SensitiveValue;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    public function prepareAction(Request $request)
    {
        $gatewayName = 'mbe4';

        $storage = $this->get('payum')->getStorage('Acme\PaymentBundle\Entity\PaymentDetails');

        /** @var \Acme\PaymentBundle\Entity\PaymentDetails $details */
        $details = $storage->create();

        $storage->update($details);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $details,
            'acme_payment_done' // the route to redirect after capture;
        );

        return $this->redirect($captureToken->getTargetUrl());
    }
}

```