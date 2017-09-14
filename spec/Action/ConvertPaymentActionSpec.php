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

namespace spec\Sourcefabric\Payum\Mbe4\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Sourcefabric\Payum\Mbe4\Action\ConvertPaymentAction;
use PhpSpec\ObjectBehavior;

final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_should_implement_interface(): void
    {
        $this->shouldImplement(ActionInterface::class);
        $this->shouldImplement(GatewayAwareInterface::class);
    }

    function it_supports(Convert $request, PaymentInterface $payment): void
    {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $this->supports($request)->shouldReturn(true);
    }
}
