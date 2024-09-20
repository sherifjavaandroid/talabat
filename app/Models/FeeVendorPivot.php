<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FeeVendorPivot extends Pivot
{
    protected $casts = [
        'fee_id' => 'int',
        'vendor_id' => 'int',
    ];
}

