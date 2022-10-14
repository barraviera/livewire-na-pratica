<?php

//usamos o .env para colocar as informações de email e token a serem preenchidas referentes ao pagseguro
return [
    'email' => env('PAGSEGURO_EMAIL'),
    'token' => env('PAGSEGURO_TOKEN'),
    'env' => env('PAGSEGURO_ENV'),
];
