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

namespace Sourcefabric\Payum\Mbe4\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\GetHttpRequest;
use Sourcefabric\Payum\Mbe4\Api;
use Sourcefabric\Payum\Mbe4\Request\Api\DoOffsiteCapture;

class DoOffsiteCaptureAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request
     * @throws \Payum\Core\Reply\HttpRedirect
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $httpRequest = new GetHttpRequest();
        $this->gateway->execute($httpRequest);

        // we are redirected back from mbe4 so update model
        if (isset($httpRequest->query[Api::FIELD_RESPONSE_CODE])) {
            $model->replace($httpRequest->query);

            return;
        }

        throw new HttpRedirect($this->api->getOffsiteUrl((array) $model));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof DoOffsiteCapture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
