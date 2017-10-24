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

namespace PayHelper\Payum\Mbe4;

use PayHelper\Payum\Mbe4\Action\Api\DoOffsiteCaptureAction;
use PayHelper\Payum\Mbe4\Action\ConvertPaymentAction;
use PayHelper\Payum\Mbe4\Action\CaptureAction;
use PayHelper\Payum\Mbe4\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class Mbe4GatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'mbe4',
            'payum.factory_title' => 'Mbe4',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.api.do_offsite_capture' => new DoOffsiteCaptureAction(),
        ]);

        if (false == $config['payum.api']) {
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'username',
                'password',
                'clientId',
                'serviceId',
                'contentclass',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config);
            };
        }
    }
}
