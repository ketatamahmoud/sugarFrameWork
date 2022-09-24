<?php

namespace App\Model;

use App\Core\AbstractModel;

class User extends AbstractModel
{

protected string $e_mail;
protected string $password;
protected static $primary ='e_mail';
protected static $object = 'user';

    /**
     * @return string 
     */
    public function getE_mail() : string 
    {
        return $this->e_mail;
    }


    /**
     * @param string  $e_mail
     */
    public function setE_mail(string  $e_mail): self
    {
        $this->e_mail = $e_mail;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getPassword() : string 
    {
        return $this->password;
    }


    /**
     * @param string  $password
     */
    public function setPassword(string  $password): self
    {
        $this->password = $password;
        return $this ;
    }




}