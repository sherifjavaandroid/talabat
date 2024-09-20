<?php

namespace App\Traits;

use App\Models\Order;

trait DigitalOrderTrait
{

    public function handleDigitalOrder(Order $model)
    {
        
        //if the update is updated
        if (in_array($model->payment_status, ['successful', 'success']) && !in_array($model->status, ['delivered', 'completed'])) {
            //check if the product is digital
            $allDigital = false;
            $orderProducts = $model->products();
            foreach ($orderProducts as $orderProduct) {
                if (!$orderProduct->product->digital) {
                    $allDigital = false;
                    break;
                }else{
                    $allDigital = true;
                }
            }

            if ($allDigital) {
                $model->setStatus("delivered");
            }
        }
    }
}
