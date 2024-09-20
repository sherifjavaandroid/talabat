<?php

namespace App\Http\Livewire;

use App\Models\Earning;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Traits\FirebaseDBTrait;
use App\Traits\FirebaseAuthTrait;
use App\Traits\DBTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DataLivewire extends BaseLivewireComponent
{

    use FirebaseDBTrait, FirebaseAuthTrait;
    use DBTrait;
    public $model;
    public $actionCalled;

    public function render()
    {
        return view('livewire.settings.data-clear', [
            'totalCurrentJobs' => $this->getTotalJobs(),
            'totalFailedJobs' => $this->getTotalFailedJobs(),
        ]);
    }

    public function getTotalJobs()
    {
        return DB::table('jobs')->count();
    }
    public function getTotalFailedJobs()
    {
        return DB::table('failed_jobs')->count();
    }

    //
    public function confirmAction($model, $method)
    {
        $this->model = $model;
        $this->actionCalled  = $method;
        $this->showCreateModal();
    }

    public function clearOrders()
    {
        //
        try {

            $this->isDemo();

            DB::statement("SET foreign_key_checks=0");
            //order payments
            DB::table('payments')->truncate();
            DB::table('statuses')->truncate();
            //clear order products
            DB::table('order_products')->truncate();
            //order stops
            DB::table('order_stops')->truncate();
            //order taxi
            DB::table('taxi_orders')->truncate();
            //remittances
            DB::table('remittances')->truncate();
            //commissions
            DB::table('commissions')->truncate();
            //coupons usage
            DB::table('coupon_user')->truncate();
            //orders
            DB::table('orders')->truncate();
            DB::table('auto_assignments')->truncate();

            //
            $vendorEarningIds = Earning::whereNull("user_id")->pluck("id")->toArray();
            Payout::whereIn("earning_id", $vendorEarningIds)->delete();
            DB::table('earnings')->whereNull("user_id")->truncate();
            if (Schema::hasTable('earneds')) {
                DB::table('earneds')->truncate();
            }

            //clear photos of orders
            $this->clearMedia(["App\Models\Order", "App\Models\OrderStop"]);
            $this->clearTableRecords("order_id");
            DB::statement("SET foreign_key_checks=1");


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearWalletTransactions()
    {
        try {

            $this->isDemo();

            DB::statement("SET foreign_key_checks=0");
            //order payments
            DB::table('wallet_transactions')->truncate();
            DB::table('wallets')->update(['balance' => 0]);
            DB::statement("SET foreign_key_checks=1");
            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearUserWalletBalance()
    {
        try {

            $this->isDemo();

            DB::statement("SET foreign_key_checks=0");
            //order payments
            DB::table('wallets')->update(['balance' => 0]);
            DB::statement("SET foreign_key_checks=1");
            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearProducts()
    {
        //
        try {

            $this->isDemo();

            DB::statement("SET foreign_key_checks=0");
            DB::table('category_product')->truncate();
            DB::table('coupon_product')->truncate();
            DB::table('menu_product')->truncate();
            DB::table('option_product')->truncate();
            DB::table('product_subcategory')->truncate();
            DB::table('favourites')->truncate();
            DB::table('products')->truncate();
            //clear media
            $this->clearMedia(["App\Models\Product"]);
            DB::statement("SET foreign_key_checks=1");


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearServices()
    {
        //
        try {

            $this->isDemo();

            DB::statement("SET foreign_key_checks=0");
            DB::table('service_option_groups')->truncate();
            DB::table('service_options')->truncate();
            DB::table('option_service')->truncate();
            DB::table('order_services')->truncate();
            DB::table('services')->truncate();
            //clear media
            $this->clearMedia(["App\Models\Service"]);
            DB::statement("SET foreign_key_checks=1");


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearVendors()
    {
        //
        //
        try {

            $this->isDemo();


            //unassugn the managers
            User::whereNotNull('vendor_id')
                ->update(['vendor_id' => null]);

            DB::statement("SET foreign_key_checks=0");
            DB::table('category_vendor')->truncate();
            DB::table('country_vendor')->truncate();
            DB::table('city_vendor')->truncate();
            DB::table('day_vendor')->truncate();
            DB::table('payment_method_vendor')->truncate();
            DB::table('state_vendor')->truncate();
            DB::table('package_type_pricings')->truncate();
            DB::table('option_product')->delete();
            DB::table('options')->truncate();
            DB::table('option_groups')->truncate();
            //
            $vendorEarningIds = Earning::whereNull("user_id")->pluck("id")->toArray();
            Payout::whereIn("earning_id", $vendorEarningIds)->delete();
            DB::table('earnings')->whereNull("user_id")->delete();
            //
            DB::table('vendors')->truncate();
            //clear media
            $this->clearMedia(["App\Models\Vendor", "App\Models\PackageType", "App\Models\Option", "App\Models\OptionGroup"]);
            DB::statement("SET foreign_key_checks=1");


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearUsers()
    {
        //
        try {

            $this->isDemo();


            //unassugn the managers
            DB::statement("SET foreign_key_checks=0");
            //
            User::where('creator_id', "!=", Auth::id())
                ->update(['creator_id' => null]);
            //vendors
            Vendor::where('creator_id', "!=", Auth::id())
                ->update(['creator_id' => null]);
            //
            DB::table('delivery_addresses')->truncate();
            DB::table('wallet_transactions')->truncate();
            DB::table('wallets')->truncate();
            DB::table('users')->where('id', "!=", Auth::id())->delete();
            $this->clearMedia(["App\Models\User"]);
            DB::statement("SET foreign_key_checks=1");
            //get last user id
            $lastUserId = User::latest()->first()->id;
            //set auto increment to next id
            $lastUserId = $lastUserId + 1;
            DB::statement("ALTER TABLE users AUTO_INCREMENT = $lastUserId");



            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearDrivers()
    {
        //
        try {

            $this->isDemo();
            DB::statement("SET foreign_key_checks=0");
            //first clear firebase empty drivers
            $vehicles = Vehicle::get();
            foreach ($vehicles as $vehicle) {
                $driverProfile = User::find($vehicle->driver_id);
                if (empty($driverProfile)) {
                    $vehicle->delete();
                    //firebase clear
                    try {
                        $firestoreClient = $this->getFirebaseStoreClient();
                        $documentPath = "drivers/{$vehicle->driver_id}";
                        $firestoreClient->deleteDocument($documentPath);
                    } catch (\Exception $ex) {
                        logger("Error deleting firebase firestore document", [$ex]);
                    }
                }
            }
            //
            $this->clearMedia(["App\Models\Vehicle"]);
            DB::statement("SET foreign_key_checks=1");


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }


    public function clearOldMedia()
    {
        //
        try {

            $this->isDemo();
            $keepMediaType = ["App\Models\Product", "App\Models\Service", "App\Models\Payment"];
            $mediaSet = Media::whereNotIn('model_type', $keepMediaType)->get();
            foreach ($mediaSet as $media) {
                $keep = Media::where('model_type', $media->model_type)
                    ->where("model_id", $media->model_id)
                    ->where("collection_name", $media->collection_name)
                    ->latest()
                    ->take(1)
                    ->pluck('id');

                $mediaCollection = Media::where('model_type', $media->model_type)
                    ->where("model_id", $media->model_id)
                    ->where("collection_name", $media->collection_name)
                    ->whereNotIn('id', $keep)->get();

                //delete the files from storage
                foreach ($mediaCollection as $mediaItem) {
                    $filePath = $mediaItem->getPath();
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $mediaItem->delete();
                }
            }


            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {

            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }


    public function clearPushNotifications()
    {
        try {

            $this->isDemo();
            DB::table('push_notifications')->truncate();
            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearJobs()
    {
        try {

            $this->isDemo();
            DB::table('jobs')->truncate();
            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }

    public function clearFailedJobs()
    {
        try {

            $this->isDemo();
            DB::table('failed_jobs')->truncate();
            $this->showSuccessAlert($this->model . " " . __('clear successfully!'));
            $this->dismissModal();
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? $this->model . " " . __('clearing failed!'));
        }
    }









    //clear media
    public function clearMedia(array $models)
    {
        //clear media
        DB::table('media')->whereIn("model_type", $models)->delete();
    }
}
