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

use Sourcefabric\Payum\Mbe4\Api;
use PhpSpec\ObjectBehavior;

final class ApiSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith([
            'username' => 'user',
            'password' => 'password',
            'clientId' => 1234,
            'serviceId' => 4321,
            'contentclass' => 13,
        ]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Api::class);
    }

    function it_prepares_a_payment()
    {
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('UTC'));

        $hash = $this->generateHash($now);

        $fields = [
            'contentclass' => 13,
            'description' => 'desc',
            'clienttransactionid' => '12345-334',
            'amount' => 500,
            'returnurl' => 'url',
            'timestamp' => $now->format('Y-m-d\Th:i:s.\0\0\0\Z'),
        ];

        $this->preparePayment($fields)->shouldReturn([
            'username' => 'user',
            'clientid' => 1234,
            'serviceid' => 4321,
            'contentclass' => 13,
            'description' => 'desc',
            'clienttransactionid' => '12345-334',
            'amount' => 500,
            'timestamp' => $now->format('Y-m-d\Th:i:s.\0\0\0\Z'),
            'callbackurl' => 'url',
            'hash' => $hash,
        ]);
    }

    function it_gets_offsite_url()
    {
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('UTC'));

        $fields = [
            'contentclass' => 13,
            'description' => 'desc',
            'clienttransactionid' => '12345-334',
            'amount' => 500,
            'returnurl' => 'url',
            'timestamp' => $now->format('Y-m-d\Th:i:s.\0\0\0\Z'),
        ];

        $hash = $this->generateHash($now);

        $this->getOffsiteUrl($fields)->shouldReturn(urldecode(sprintf(
            'https://billing.mbe4.de/widget/singlepayment?username=user&clientid=1234&serviceid=4321&contentclass=13&description=desc&clienttransactionid=12345-334&amount=500&timestamp=%s&callbackurl=url&hash=%s',
            $now->format('Y-m-d\Th:i:s.\0\0\0\Z'),
            $hash
        )));
    }

    private function generateHash(\DateTimeInterface $now): string
    {
        return md5(implode('', [
            'password',
            'user',
            1234,
            4321,
            13,
            'desc',
            '12345-334',
            500,
            'url',
            $now->format('Y-m-d\Th:i:s.\0\0\0\Z'),
        ]));
    }
}
