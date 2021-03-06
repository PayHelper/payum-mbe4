# Payum mbe4 Extension [![Build Status](https://travis-ci.org/PayHelper/payum-mbe4.svg)](https://travis-ci.org/PayHelper/payum-mbe4) [![StyleCI](https://styleci.io/repos/103504312/shield?branch=master)](https://styleci.io/repos/103504312)

The Payum extension. It provides [mbe4](http://www.mbe4.de/) payment integration.

Getting Started
===============

Requirements
----------------

This library requires PHP 7.1 or higher.

Installing the extension
------------------------

Install this extension as a Composer dependency by requiring it in a `composer.json` file:

```bash
composer require payhelper/payum-mbe4
```

Register the `mbe4` Payum factory using `PayumBuilder`:

```php
use Payum\Core\GatewayFactoryInterface;
use PayHelper\Payum\Mbe4\Mbe4GatewayFactory;

$payumBuilder->addGatewayFactory('mbe4', function(array $config, GatewayFactoryInterface $gatewayFactory) {
    return new Mbe4GatewayFactory($config, $gatewayFactory);
});

$payumBuilder->addGateway('mbe4', [
    'factory' => 'mbe4',
    'username' => 'username', // change this
    'password' => 'password', // change this
    'clientId' => 4321, // change this
    'serviceId' => 1234, // change this
    'contentclass' => 1, // change this, see mbe4 documentation below
]);
``` 

Supported methods
-----------------

This extension supports only single payments, no subscriptions.

- [Single offsite payment](Resources/doc/single_payment.md)

See `mbe4` [documentation](Resources/doc/mbe4_documentation.pdf).

Symfony integration
-------------------

1. PayumBundle installation

In order to use that extension with the Symfony, you will need to install [PayumBundle](https://github.com/Payum/PayumBundle) first and configure it according to its documentation.

```bash
composer require payum/payum-bundle ^2.0
```

2. Register `mbe4` Gateway Factory as a service

```yaml
# app/config/services.yml

services:
    app.payum.mbe4.factory:
        class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
        arguments: [PayHelper\Payum\Mbe4\Mbe4GatewayFactory]
        tags:
            - { name: payum.gateway_factory_builder, factory: mbe4 }
```

3. Configure the gateway

```yaml
# app/config/config.yml

payum:
    gateways:
        mbe4:
            factory: mbe4
            username: username # change this
            password: password # change this
            clientId: 4321 # change this
            serviceId: 1234 # change this
            contentclass: 1 # change this
```

4. Gateway usage

Retrieve it from the `payum` service:

```php
$gateway = $this->get('payum')->getGeteway('mbe4');
```

License
-------
This library is licensed under the [GNU GPLv3](LICENSE) license.
