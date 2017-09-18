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

namespace spec\Sourcefabric\Payum\Mbe4;

use Payum\Core\GatewayFactory;
use Payum\Core\GatewayFactoryInterface;
use Sourcefabric\Payum\Mbe4\Mbe4GatewayFactory;
use PhpSpec\ObjectBehavior;

final class Mbe4GatewayFactorySpec extends ObjectBehavior
{
    function let(GatewayFactoryInterface $gatewayFactory): void
    {
        $this->beConstructedWith([], $gatewayFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Mbe4GatewayFactory::class);
    }

    function it_should_extend_base_gateway(): void
    {
        $this->shouldHaveType(GatewayFactory::class);
    }
}
