<?php namespace MauroCasas\Blockchain {

    /**
     * @package Blockchain
     * @version 0.1
     * @author Mauro Casas <casas.mauroluciano@gmail.com>
     */

    use Illuminate\Config\Repository as Config;

    class Blockchain {

        protected $config;

        public function __construct($config){
            $this->config = $config;
        }

        /**
         * Get a single block JSON data. In case of being an invalid block, FALSE will be returned.
         * @param string $hash
         * @return mixed
         * @since 0.1
         */
        public function getBlock($hash){
            $response = file_get_contents('https://blockchain.info/rawblock/' . $hash);

            if( ! $response = json_decode($response))
                return false;

            return $response;
        }
        
    }

}