<?php
namespace App\Services\PagSeguro;

class Credentials
{

    //a uri será sessions, approvals etc...
    public static function getCredentials($uri)
    {
        //dados obtidos no arquivo config
        $email = config('pagseguro.email');
        $token = config('pagseguro.token');
        $env = config('pagseguro.env');

        //se estivermos em ambiente de sandbox iremos montar uma url
        $urlBase = $env == 'sandbox' ? 'https://ws.sandbox.pagseguro.uol.com.br' . $uri
            : 'https://ws.pagseguro.uol.com.br' . $uri;

        //montamos a url
        return "$urlBase?email={$email}&token={$token}";

    }

}
