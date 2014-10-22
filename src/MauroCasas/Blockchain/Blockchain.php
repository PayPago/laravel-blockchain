<?php namespace MauroCasas\Blockchain {

    /**
     * @package Blockchain
     * @version 0.1
     * @author Mauro Casas <casas.mauroluciano@gmail.com>
     */

    use Illuminate\Config\Repository as Config;

    class Blockchain {

        protected $config, $curl, $curlResponse;

        public function __construct($config){
            $this->config = $config;
        }

        /**
         * Get a single block JSON data. In case of being an invalid block, FALSE will be returned.
         * @param string $block
         * @return mixed
         * @since 0.1
         */
        public function block($block){
            $blockchainResponse = $this->callBlockchain('block-index/' . $block, 'GET', array('format' => 'json'));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /** 
         * Get a transaction information.
         * @param string $tx
         * @return mixed
         * @since 0.1
         */
        public function tx($tx){
            $blockchainResponse = $this->callBlockchain('tx-index/' . $tx, 'GET', array('format' => 'json'));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /** 
         * Get a wallet
         * @param string $address
         * @param integer $limit Limit the amount of TXs to be shown
         * @param integer $offset Offset to skip N amount of TXs
         * @return mixed
         * @since 0.1
         */
        public function address($address, $limit = false, $offset = false){
            $settings = array('format' => 'json');

            if($limit && $limit > 0)
                $settings['limit'] = $limit;

            if($offset && $offset > 0)
                $settings['offset'] = $offset;

            $blockchainResponse = $this->callBlockchain('address/' . $address, 'GET', $settings);

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Get data from a set of wallets
         * @param array $addresses
         * @return mixed
         * @since 0.1
         */
        public function multiAddress($addresses = array()){
            $blockchainResponse = $this->callBlockchain('multiaddr', 'GET', array(
                'format' => 'json',
                'active' => (is_array($address) ? implode('|', $address) : $address)
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Get all unspent outputs from an address or a set of addresses
         * @param string|array $address
         * @return mixed
         * @since 0.1
         */
        public function unspentOutputs($address){
            $blockchainResponse = $this->callBlockchain('unspent', 'GET', array(
                'format' => 'json',
                'active' => (is_array($address) ? implode('|', $address) : $address)
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Get all unconfirmed transactions
         * @return mixed
         * @since 0.1
         */
        public function unconfirmedTxs(){
            $blockchainResponse = $this->callBlockchain('unconfirmed-transactions', 'GET', array(
                'format' => 'json'
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Ticker
         * @return mixed
         * @since 0.1
         */
        public function ticker(){
            $blockchainResponse = $this->callBlockchain('ticker', 'GET', array(
                'format' => 'json'
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Converts a currency value into BTC
         * @param int $amount
         * @param string $currency
         * @return mixed
         * @since 0.1
         */
        public function toBTC($amount, $currency = 'USD'){
            $currencies = explode('|', 'USD|ISK|HKD|TWD|CHF|EUR|DKK|CLP|CAD|CNY|THB|AUD|SGD|KRW|JPY|PLN|GBP|SEK|NZD|BRL|RUB');

            $blockchainResponse = $this->callBlockchain('tobtc', 'GET', array(
                'format' => 'json',
                'currency' => in_array($currency, $currencies) ? $currency : 'USD',
                'value' => intval($amount)
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * X & Y values corresponding to the data seen on the requested chart.
         * @param string $type
         * @link https://blockchain.info/api/charts_api
         * @return mixed
         * @since 0.1
         */
        public function chart($type){
            if( ! in_array($type, array(
                'total-bitcoins',
                'market-cap',
                'transaction-fees',
                'n-transactions',
                'n-transactions-excluding-popular',
                'n-unique-addresses',
                'n-transactions-per-block',
                'n-orphaned-blocks',
                'output-volume',
                'estimated-transaction-volume',
                'estimated-transaction-volume-usd',
                'trade-volume',
                'tx-trade-ratio',
                'market-price',
                'cost-per-transaction-percent',
                'cost-per-transaction',
                'hash-rate',
                'difficulty',
                'miners-revenue',
                'avg-confirmation-time',
                'bitcoin-days-destroyed-cumulative',
                'bitcoin-days-destroyed',
                'bitcoin-days-destroyed-min-week',
                'bitcoin-days-destroyed-min-month',
                'bitcoin-days-destroyed-min-year',
                'blocks-size',
                'avg-block-size',
                'my-wallet-transaction-volume',
                'my-wallet-n-users',
                'my-wallet-n-tx'
                )))
                return false;

            $blockchainResponse = $this->callBlockchain('charts/' . $type, 'GET', array(
                'format' => 'json',
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Returns a JSON object containing the statistics seen on the stats page.
         * @param int $amount
         * @param string $currency
         * @link https://blockchain.info/api/charts_api
         * @return mixed
         * @since 0.1
         */
        public function stats(){
            $blockchainResponse = $this->callBlockchain('stats', 'GET', array(
                'format' => 'json',
                ));

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Create a wallet
         * @param string $password
         * @param string $privateKey
         * @param string $label
         * @param string $email
         * @link https://blockchain.info/api/create_wallet
         * @return mixed
         * @since 0.1
         */
        public function createWallet($password, $privateKey = false, $label = false, $email = false){
            $settings = array('password' => $password);

            if($privateKey && $privateKey != '')
                $settings['priv'] = $privateKey;

            if($label && $label != '')
                $settings['label'] = $label;

            if($email && $email != '')
                $settings['email'] = $email;

            $blockchainResponse = $this->callBlockchain('api/v2/create_wallet', 'GET', $settings);

            if( ! $response = json_decode($blockchainResponse))
                return $blockchainResponse;

            return $response;
        }

        /**
         * Query API
         * Some functions are really simple, so with magic we process them
         * @param string $func
         * @param string $params
         * @return mixed
         * @since 0.1
         */
        function __call($func, $params){
            if(in_array($func, array(
                'getdifficulty',
                'getblockcount',
                'latesthash',
                'bcperblock',
                'totalbc',
                'probability',
                'hashestowin',
                'nextretarget',
                'avgtxsize',
                'avgtxvalue',
                'interval',
                'eta',
                'avgtxnumber',
                'newkey',
                'unconfirmedcount',
                '24hrprice',
                'marketcap',
                '24hrtransactioncount',
                '24hrbtcsent',
                'hashrate',
                'rejected',
                'getreceivedbyaddress',
                'getsentbyaddress',
                'addressbalance',
                'addressfirstseen',
                'txtotalbtcoutput',
                'txtotalbtcinput',
                'txfee',
                'txresult',
                'hashtontxid',
                'ntxidtohash',
                'addresstohash',
                'hashtoaddress',
                'hashpubkey',
                'addrpubkey',
                'pubkeyaddr'
                ))){
                $blockchainResponse = $this->callBlockchain('q/' . strtolower($func) . (is_array($params) ? '/' . $params[0] : ''), 'GET', array(
                    'format' => 'json'
                    ));

                if( ! $response = json_decode($blockchainResponse))
                    return $blockchainResponse;

                return $response;                
            }
        }

        //

        /**
         * Generate the Blockchain CURL call
         * @param string $url
         * @param string $type GET|POST
         * @param array $params optional
         * @return mixed
         * @since 0.1
         */
        public function callBlockchain($url, $type = 'GET', $params = array()){
            $curl = curl_init();

            // Todo enable CORS in config.php

            $settings = array();

            if($this->config['cors'] == true)
                $params['cors'] = true;

            if($this->config['api_secret'] != '')
                $params['api_code'] = $this->config['api_secret'];

            if($type == 'GET'){
                $settings[CURLOPT_RETURNTRANSFER] = true;
            }
            elseif($type == 'POST'){
                $settings[CURLOPT_POST] = count($params);
                $settings[CURLOPT_POSTFIELDS] = http_build_query($params);
            }

            $settings[CURLOPT_URL] = 'http://blockchain.info/' . $url . (($type == 'GET' && is_array($params) && count($params) != 0) ? '?' . http_build_query($params): '');

            curl_setopt_array($curl, $settings);

            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        }
        
    }

}