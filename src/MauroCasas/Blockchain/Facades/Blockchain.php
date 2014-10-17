<?php namespace MauroCasas\Blockchain\Facades {

    use Illuminate\Support\Facades\Facade;

    /**
     * @package Blockchain
     * @version 0.1
     * @author Mauro Casas <casas.mauroluciano@gmail.com>
     */

    class Blockchain extends Facade {
        
        protected static function getFacadeAccessor(){
            return 'blockchain';
        }

    }

}