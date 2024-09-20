<?php

namespace App\Traits;



trait OrderNotificationStatusMessageTrait
{

    //
    private function getVendorOrderNotificationMessage($status, $order)
    {

        $message = "";
        switch ($status) {
            case 'pending':
                $message = setting(
                    'order.notification.message.manager.preparing',
                    __("Order #") . $order->code . __(" has just been placed with you")
                );
                break;
            case 'preparing':
                $message = setting('order.notification.message.manager.preparing', __("Order is now being prepared"));
                break;
            case 'ready':
                $message = setting('order.notification.message.manager.ready', __("Order is now ready for delivery/pickup"));
                break;
            case 'enroute':
                $message = setting(
                    'order.notification.message.manager.enroute',
                    __("Order #") . $order->code . __(" has been assigned to a delivery boy")
                );
                break;
            case 'completed':
                $message = setting('order.notification.message.manager.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'delivered':
                $message = setting('order.notification.message.manager.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'successful':
                $message = setting('order.notification.message.manager.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'cancelled':
                $message = setting('oorder.notification.message.manager.cancelled', __("Order #") . $order->code . " " . __("cancelled"));
                break;
            case 'failed':
                $message = setting('order.notification.message.manager.failed', __("Order failed"));
                break;
            default:
                $message = __("Order #") . $order->code . __(" has been ") . __($order->status) . "";
                break;
        }
        return $message;
    }


    private function getCustomerOrderNotificationMessage($status, $order)
    {
        $message = "";
        switch ($status) {
            case 'pending':
                $message = setting('order.notification.message.pending', __("Your order is pending"));
                break;
            case 'preparing':
                $message = setting('order.notification.message.preparing', __("Your order is now being prepared"));
                break;
            case 'ready':
                $message = setting('order.notification.message.ready', __("Your order is now ready for delivery/pickup"));
                break;
            case 'enroute':
                $message = setting(
                    'order.notification.message.enroute',
                    __("Order #") . $order->code . __(" has been assigned to a delivery boy")
                );
                break;
            case 'completed':
                $message = setting('order.notification.message.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'delivered':
                $message = setting('order.notification.message.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'successful':
                $message = setting('order.notification.message.completed',  __("Order #") . $order->code . __(" has been delivered"));
                break;
            case 'cancelled':
                $message = setting('order.notification.message.cancelled', __("Order #") . $order->code . " " . __("cancelled"));
                break;
            case 'failed':
                $message = setting('order.notification.message.failed', __("Order failed"));
                break;
            default:
                $message = __("Order #") . $order->code . __(" has been ") . __($order->status) . "";
                break;
        }
        return $message;
    }
}
