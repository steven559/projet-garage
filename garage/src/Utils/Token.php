<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 24/04/2019
 * Time: 09:56
 */

namespace App\Utils;


final class Token {



    private $salt;

    private $token;



    public function __construct($salt="unephraseauhasard")

    {

        $this->salt = $salt;

    }



    private static function getCurrentTime()

    {

        return time();

    }



    private function reSalt()

    {

        $this->salt.=self::getCurrentTime();

    }



    public function generateToken()

    {

        $this->reSalt();

        $this->setToken(sha1(time().$this->salt));

        return $this->getToken();

    }



    public function setToken($token)

    {

        $this->token = $token;

    }



    public function getToken()

    {

        return $this->token;

    }

}