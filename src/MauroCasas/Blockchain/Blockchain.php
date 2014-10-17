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
            $this->curl = curl_init();
        }

        /**
         * Get a single block JSON data. In case of being an invalid block, FALSE will be returned.
         * @param string $block
         * @return mixed
         * @since 0.1
         */
        public function block($block){
            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/block-index/' . $block . '?format=json'
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

            return $response;
        }

        /** 
         * Get a transaction information.
         * @param string $tx
         * @return mixed
         * @since 0.1
         */
        public function tx($tx){
            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/tx-index/' . $tx . '?format=json'
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

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
        public function address($address, $limit = 0, $offset = 0){
            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/address/' . $address . '?format=json' . ( $limit != 0 ? '&limit=' . $limit : '' ) . ( $offset != 0 ? '&offset=' . $offset : '' )
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

            return $response;
        }

        /**
         * Get data from a set of wallets
         * @param array $addresses
         * @return mixed
         * @since 0.1
         */
        public function multi_address($addresses = array()){
            if( ! is_array($addresses))
                return false;


            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/multiaddr?active=' . implode('|', $addresses)
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

            return $response;
        }

        /**
         * Get all unspent outputs from an address or a set of addresses
         * @param string|array $address
         * @return mixed
         * @since 0.1
         */
        public function unspent_outputs($address){
            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/unspent?active=' . (is_array($address) ? implode('|', $address) : $address)
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

            return $response;            
        }

        /**
         * Get all unconfirmed transactions
         * @return mixed
         * @since 0.1
         */
        public function unconfirmed_txs(){
            $this->curlSet(array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => 'http://blockchain.info/unconfirmed-transactions?format=json'
                ));

            $this->curlExec();

            if( ! $response = json_decode($this->curlResponse))
                return $this->curlResponse;

            return $response;            
        }

        /**
         * Set CURL params through an array
         * @param array $settings
         * @since 0.1
         */
        protected function curlSet($settings){
            curl_setopt_array($this->curl, $settings);
        }

        /**
         * Execute the CURL request
         * @return mixed
         * @since 0.1
         */
        protected function curlExec(){
            $this->curlResponse = curl_exec($this->curl);
            curl_close($this->curl);

            return $this->curlResponse;
        }
        
    }

}