<?php

/*
 * This file is part of the mbe4 Payum package.
 *
 * (c) Sourcefabric z.ú. and contributors.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sourcefabric\Payum\Mbe4\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Sourcefabric\Payum\Mbe4\Api;

class ConvertPaymentAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        $details = $payment->getDetails();

        $currency = $payment->getCurrencyCode();
        $this->validateCurrency($currency);

        $details[Api::FIELD_DESCRIPTION] = $payment->getDescription();
        $details[Api::FIELD_CLIENT_TRANSACTION_ID] = md5(uniqid((string) mt_rand(), true));
        $details[Api::FIELD_AMOUNT] = (int) $payment->getTotalAmount();
        $details[Api::FIELD_CURRENCY] = $currency;

        $request->setResult($details);
    }

    /**
     * @param string $currency
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException
     */
    private function validateCurrency(string $currency): void
    {
        if (!in_array(strtoupper($currency), Api::getSupportedCurrencies(), true)) {
            throw new InvalidArgumentException(sprintf('Currency %s is not supported!', $currency));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
        ;
    }
}
