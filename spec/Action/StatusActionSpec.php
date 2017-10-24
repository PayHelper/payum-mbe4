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

namespace spec\PayHelper\Payum\Mbe4\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;
use PayHelper\Payum\Mbe4\Action\StatusAction;
use PhpSpec\ObjectBehavior;

final class StatusActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(StatusAction::class);
    }

    function it_should_implement_interface(): void
    {
        $this->shouldImplement(ActionInterface::class);
    }

    function it_should_mark_as_new(GetStatusInterface $request): void
    {
        $request->getModel()->willReturn(new ArrayObject([]));
        $request->markNew()->shouldBeCalled();

        $this->execute($request);
    }

    function it_should_mark_as_captured(GetStatusInterface $request): void
    {
        $request->getModel()->willReturn(new ArrayObject(['responsecode' => 0]));
        $request->markCaptured()->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports(GetStatusInterface $request): void
    {
        $request->getModel()->willReturn(new \ArrayObject());

        $this->supports($request)->shouldReturn(true);
    }
}
