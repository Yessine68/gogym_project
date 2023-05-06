<?php

namespace App\Services;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode( $user,$name,$number)
    {

        // set qrcode
        $result = $this->builder
            ->data("Your name : ".$user.' '. " Your event name: ".$name.' '."your identification : ".' '.$number)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
            ->build()
        ;


        //Save img png
        $result->saveToFile('QRcode/'.'client'.$user.'nomEvent'.$name.'.png');

        return $result->getDataUri();
    }
}