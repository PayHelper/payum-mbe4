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

namespace spec\Sourcefabric\Payum\Mbe4\Request\Api;

use Payum\Core\Request\Generic;
use Sourcefabric\Payum\Mbe4\Request\Api\DoOffsiteCapture;
use PhpSpec\ObjectBehavior;

final class DoOffsiteCaptureSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DoOffsiteCapture::class);
    }

    function it_should_extends_generic_request(): void
    {
        $this->shouldHaveType(Generic::class);
    }
}
