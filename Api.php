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

namespace Sourcefabric\Payum\Mbe4;

class Api
{
    const VERSION = '3.1.2';
    const FIELD_CONTENTCLASS = 'contentclass';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_CLIENT_TRANSACTION_ID = 'clienttransactionid';
    const FIELD_AMOUNT = 'amount';
    const FIELD_RETURNURL = 'returnurl';
    const FIELD_CALLBACKURL = 'callbackurl';
    const FIELD_TIMESTAMP = 'timestamp';
    const FIELD_HASH = 'hash';
    const FIELD_ID = 'id';
    const FIELD_CURRENCY = 'currency';
    const FIELD_RESPONSE_CODE = 'responsecode';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param array $fields
     *
     * @return string
     */
    public function getOffsiteUrl(array $fields): string
    {
        $fields = $this->preparePayment($fields);

        return urldecode($this->getApiEndpoint().'?'.http_build_query($fields));
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public function preparePayment(array $fields): array
    {
        $supportedParams = [
            'password' => null,
            'username' => null,
            'clientid' => null,
            'serviceid' => null,
            self::FIELD_CONTENTCLASS => null,
            self::FIELD_DESCRIPTION => null,
            self::FIELD_ID => null,
            self::FIELD_CLIENT_TRANSACTION_ID => null,
            self::FIELD_AMOUNT => null,
            self::FIELD_RETURNURL => null,
            self::FIELD_CALLBACKURL => null,
            self::FIELD_TIMESTAMP => null,
            self::FIELD_HASH => null,
        ];

        $fields[self::FIELD_CONTENTCLASS] = $this->options['contentclass'];

        $fields = array_filter(array_replace(
            $supportedParams,
            array_intersect_key($fields, $supportedParams)
        ));

        $config['username'] = $this->options['username'];
        $config['clientid'] = $this->options['clientId'];
        $config['serviceid'] = $this->options['serviceId'];
        $fields = $config + $fields;

        $fields[self::FIELD_CALLBACKURL] = $fields[self::FIELD_RETURNURL];
        $fields[self::FIELD_TIMESTAMP] = $this->generateTimestamp();
        $fields[self::FIELD_HASH] = $this->generateHash($fields);

        unset($fields[self::FIELD_RETURNURL]);

        return (array) $fields;
    }

    /**
     * @param array $fields
     *
     * @return string
     */
    private function generateHash(array $fields): string
    {
        return md5(implode('', [
            $this->options['password'],
            $this->options['username'],
            $this->options['clientId'],
            $this->options['serviceId'],
            $fields[self::FIELD_CONTENTCLASS],
            $fields[self::FIELD_DESCRIPTION],
            $fields[self::FIELD_CLIENT_TRANSACTION_ID],
            $fields[self::FIELD_AMOUNT],
            $fields[self::FIELD_RETURNURL],
            $fields[self::FIELD_TIMESTAMP],
        ]));
    }

    /**
     * @return string
     */
    private function generateTimestamp(): string
    {
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('UTC'));

        return $now->format('Y-m-d\Th:i:s.\0\0\0\Z');
    }

    /**
     * @return string
     */
    protected function getApiEndpoint(): string
    {
        return 'https://billing.mbe4.de/widget/singlepayment';
    }

    /**
     * @return array
     */
    public static function getSupportedCurrencies(): array
    {
        return [
            'EUR',
        ];
    }
}
