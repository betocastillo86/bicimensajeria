<?php
/**
 * Contiene la información base de todos los modelos que se devuelven
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/12/2015
 * Time: 12:13 PM
 */
class BaseModel
{
    public $success;

    public $errorMessage;

    public $errorCode;

    function __construct()
    {
        $this->success = false;
    }

    /***
     * Inicializa los errores dependiendo lo que se envie
     * @param $s true: exitosa
     * @param $e mensjae de error, puede ser nulo
     * @param $c codigo de error
     */
    function setError($s, $e, $c)
    {
        $this->success = $s;
        $this->errorMessage = $e;
        $this->errorCode = $c;
    }

}