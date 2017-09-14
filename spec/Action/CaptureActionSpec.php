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

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;
use Prophecy\Argument;
use Sourcefabric\Payum\Mbe4\Action\CaptureAction;
use PhpSpec\ObjectBehavior;
use Sourcefabric\Payum\Mbe4\Request\Api\DoOffsiteCapture;

final class CaptureActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CaptureAction::class);
    }

    function it_executes_request(Capture $request, GatewayInterface $gateway, TokenInterface $token)
    {
        $request->getModel()->willReturn(new ArrayObject());
        $token->getAfterUrl()->shouldBeCalled();
        $request->getToken()->willReturn($token);
        $this->setGateway($gateway);

        $gateway->execute(Argument::type(DoOffsiteCapture::class))->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports(Capture $request): void
    {
        $request->getModel()->willReturn(new \ArrayObject());

        $this->supports($request)->shouldReturn(true);
    }
}
