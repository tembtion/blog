<?php
namespace App\Logic;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class Base
{
    protected $result = array();

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function run()
    {
        $this->preExecute();

        $this->execute();

        $this->proExecute();

        return $this->result;
    }

    public function set($keyName, $keyValue)
    {
        $this->$keyName = $keyValue;
    }

    protected function preExecute()
    {
    }

    protected function execute()
    {
    }

    protected function proExecute()
    {
    }
}