<?php

namespace App\Observers;

use App\Models\Member;
use App\Services\BarcodeService;

class MemberObserver
{
    public function created(Member $member): void
    {
        $member->updateQuietly([
            'barcode' => app(BarcodeService::class)
                ->generateBarcode($member),
        ]);
    }
}