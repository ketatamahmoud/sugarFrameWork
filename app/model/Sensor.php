<?php

namespace App\Model;

use App\Core\AbstractModel;

class Sensor extends AbstractModel
{

protected string $id;
protected string $sensor_date;
protected string $temperature_mesure;
protected string $sole_mesure;
protected string $air_mesure;
protected string $arrosage = 0;
protected static $primary ='id';
protected static $object = 'sensor';

    /**
     * @return string 
     */
    public function getId() : string 
    {
        return $this->id;
    }


    /**
     * @param string  $id
     */
    public function setId(string  $id): self
    {
        $this->id = $id;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getSensor_date() : string 
    {
        return $this->sensor_date;
    }


    /**
     * @param string  $sensor_date
     */
    public function setSensor_date(string  $sensor_date): self
    {
        $this->sensor_date = $sensor_date;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getTemperature_mesure() : string 
    {
        return $this->temperature_mesure;
    }


    /**
     * @param string  $temperature_mesure
     */
    public function setTemperature_mesure(string  $temperature_mesure): self
    {
        $this->temperature_mesure = $temperature_mesure;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getSole_mesure() : string 
    {
        return $this->sole_mesure;
    }


    /**
     * @param string  $sole_mesure
     */
    public function setSole_mesure(string  $sole_mesure): self
    {
        $this->sole_mesure = $sole_mesure;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getAir_mesure() : string 
    {
        return $this->air_mesure;
    }


    /**
     * @param string  $air_mesure
     */
    public function setAir_mesure(string  $air_mesure): self
    {
        $this->air_mesure = $air_mesure;
        return $this ;
    }


    /**
     * @return string 
     */
    public function getArrosage() : string 
    {
        return $this->arrosage;
    }


    /**
     * @param string  $arrosage
     */
    public function setArrosage(string  $arrosage): self
    {
        $this->arrosage = $arrosage;
        return $this ;
    }




}