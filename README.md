Blockchain for Laravel
======================

A fully-featured API to make use of Blockchain.info API. 

## Installation
Just add the package to your **composer.json**

> "maurocasas/blockchain": "*"

And then run in your Laravel root directory

> composer update

Open up **app/config/app.php** and under $providers array()
register the package provider

> 'MauroCasas\Blockchain\BlockchainServiceProvider'

This will also register the facade for you to use, for example

> Blockchain::getdifficulty()

If you're going to create wallets, and send funds using this package,
you need to register an API key from Blockchain.info or you'll
get rejected.

You can request an [API Key at Blockchain.info](https://blockchain.info/api/api_create_code) (Usually takes a day or two)

## Usage

### Create Wallets

[Create Wallets API Documentation](https://blockchain.info/api/create_wallet)

To create wallets you'll need an API key, yes or yes.

> Blockchain::createWallet('MAIN PASSWORD', 'PRIVATE KEY', 'LABEL', 'EMAIL ADDRESS')

Only **Main Password** is mandatory, you can ommit the rest if you want.

### Query API

[Query API Documentation](https://blockchain.info/q)

You can use any function in the documentation with the exact same name, so for example I could do

> Blockchain::probability()

If you want to use any function which requires a parameter, just send it in the function, for example:

> Blockchain::getsentbyaddress('PUBLIC KEY')

### Exchange Rates

[Charts & Statistics API Documentation](https://blockchain.info/api/charts_api)

#### Charts

> Blockchain::chart('total-bitcoins')

You can look at all the available charts over [here](https://blockchain.info/charts)
The URL slug for the chart is needed here.

> https://blockchain.info/charts/total-bitcoins

Just use the **total-bitcoins** part. All charts are available.

#### Statistics

Returns all Blockchain statistics

> Blockchain::stats()

### Exchange Rates API

[Exchange Rates API Documentation](https://blockchain.info/api/exchange_rates_api)

#### Ticker

> Blockchain::ticker()

#### Convert currencies to BTC

> Blockchain::toBTC(500, 'JPY')

**Currencies allowed**

* USD (by default)
* ISK
* HKD
* TWD
* CHF
* EUR
* DKK
* CLP
* CAD
* CNY
* THB
* AUD
* SGD
* KRW
* JPY
* PLN
* GBP
* SEK
* NZD
* BRL
* RUB