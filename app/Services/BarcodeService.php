<?php

namespace App\Services;

use App\Models\Member;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    private const WIDTH = 2;
    private const HEIGHT = 60;

    private BarcodeGeneratorSVG $generator;

    public function __construct()
    {
        $this->generator = new BarcodeGeneratorSVG();
    }

    public function generateBarcode(Member $member): string
    {
        return 'GYM' . str_pad((string) $member->id, 6, '0', STR_PAD_LEFT);
    }

    public function renderSvg(string $code): string
    {
        return $this->generator->getBarcode(
            $code,
            BarcodeGeneratorSVG::TYPE_CODE_128,
            self::WIDTH,
            self::HEIGHT
        );
    }
}