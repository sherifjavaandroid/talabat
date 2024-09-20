<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\UserToken;
use App\Services\ModelTranslationService;
use App\Services\TranslationFixService;
use App\Services\FirestoreRestService;
use App\Services\GeoBoundaryService;
use App\Traits\FirebaseAuthTrait;
use GeoSot\EnvEditor\Facades\EnvEditor;
//
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Upgrades\Upgrade26;

class TroubleShootLivewire extends BaseLivewireComponent
{

    use FirebaseAuthTrait;

    public $autoAssignmentChecks = [
        "cron_job" => null,
        "online_regular_drivers" => null,
        "online_taxi_drivers" => null,
        "ready_orders" => null,
        "pending_taxi_orders" => null,
        "firebase_drivers" => null,
    ];

    public function render()
    {
        return view('livewire.troubleshoot');
    }


    public function fixImage()
    {

        try {
            //set the domain
            $url = url('');
            $envUrl = env('APP_URL');
            //
            if ($url != $envUrl) {
                if (EnvEditor::keyExists("APP_URL")) {
                    EnvEditor::editKey("APP_URL", $url);
                } else {
                    EnvEditor::addKey("APP_URL", $url);
                }
            }

            //artisan storage link
            \Artisan::call('storage:link', []);
            $this->showSuccessAlert(__("Fix Image(Not Loading)") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixCache()
    {

        try {
            //artisan calls
            \Artisan::call('view:clear', []);
            \Artisan::call('config:clear', []);
            \Artisan::call('cache:clear', []);
            $this->showSuccessAlert(__("Clear Cache") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }


    public function fixNotification()
    {
        try {
            if (!Schema::hasColumn('push_notifications', 'product_id') || !Schema::hasColumn('push_notifications', 'service_id')) {
                $upgradeClass = new Upgrade26();
                $upgradeClass->run();
            }
            $this->showSuccessAlert(__("Fix Notification Error") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixAutoassignment()
    {
        $this->showCreateModal();
        // check if cron job is set
        $lastCronCall = setting('cronJobLastRunRaw', null);
        if ($lastCronCall == null) {
            $this->autoAssignmentChecks["cron_job"] = false;
        } else {
            $lastCronJobTimeFormatted = \Carbon\Carbon::parse($lastCronCall);
            $nowTime = \Carbon\Carbon::now();
            $mintuesDiff = $nowTime->diffInMinutes($lastCronJobTimeFormatted);
            if ($mintuesDiff > 5) {
                $this->autoAssignmentChecks["cron_job"] = false;
            } else {
                $this->autoAssignmentChecks["cron_job"] = true;
            }
        }

        //regular drivers online
        //TODO: check if driver is online
        $regularDriversOnline = User::role('driver')
            ->where(function ($query) {
                $query->whereHas('driver_type', function ($query) {
                    $query->where('is_taxi', 0);
                })->orWhereDoesntHave('vehicle');
            })
            // ->whereDoesntHave('vehicle')
            ->where('is_online', 1)->count();
        $this->autoAssignmentChecks["online_regular_drivers"] = $regularDriversOnline > 0;
        //taxi drivers online
        //TODO: check if driver is online
        $taxiDriversOnline = User::role('driver')
            ->where(function ($query) {
                $query->whereHas('driver_type', function ($query) {
                    $query->where('is_taxi', 1);
                })->orWhereHas('vehicle');
            })
            // ->whereHas('vehicle')
            ->where('is_online', 1)->count();
        $this->autoAssignmentChecks["online_taxi_drivers"] = $taxiDriversOnline > 0;

        //ready orders
        $readyOrders = Order::whereDoesntHave('taxi_order')->currentstatus('ready')->count();
        $this->autoAssignmentChecks["ready_orders"] = $readyOrders > 0;

        //taxi orders
        $taxiOrders = Order::whereHas('taxi_order')->currentstatus('pending')->count();
        $this->autoAssignmentChecks["pending_taxi_orders"] = $taxiOrders > 0;


        //drivers check
        // fetch drivers data from firestore
        try {
            $drivers = $this->getDrivers();
            $firestoreClient = $this->getFirebaseStoreClient();
            foreach ($drivers as $driverId) {
                //
                $driver = User::find($driverId);
                //delete driver node if driver doesn't exists on users databa
                if (empty($driver)) {
                    //
                    try {
                        $firestoreClient->deleteDocument("drivers/" . $driverId . "");
                    } catch (\Exception $error) {
                        logger("Driver delete error", [$error->getMessage() ?? '']);
                    }
                }
            }
            $this->autoAssignmentChecks["firebase_drivers"] = true;
        } catch (\Exception $error) {
            $this->autoAssignmentChecks["firebase_drivers"] = false;
            logger("drivers error", [$error->getMessage() ?? '']);
        }
    }


    public function fixReferralCodes()
    {

        try {
            $users = User::whereNull('code')->get();
            foreach ($users as $user) {
                $user->code = \Str::random(3) . "" . $user->id . "" . \Str::random(2);
                $user->save();
            }
            $this->showSuccessAlert(__("Referral code fixed") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixUserPermission()
    {

        try {
            Artisan::call('permission:cache-reset');
            Artisan::call('db:seed --class=PermissionsTableSeeder --force');
            Artisan::call('permission:cache-reset');
            $this->showSuccessAlert(__("User permissions fixed") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixMissingUserRoles()
    {

        try {

            $users = User::doesntHave('roles')->get();
            foreach ($users as $user) {
                $user->assignRole("client");
            }
            $this->showSuccessAlert((count($users) ?? 0) . " " . __("User missing role fixed") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixModelTranslations()
    {

        try {
            $modelTranslationService = new ModelTranslationService();
            $modelTranslationService->fixTranslations();
            $this->showSuccessAlert(__("Model Translations") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }
    public function fixProductTranslations()
    {

        try {
            $this->isDemo();
            $modelTranslationService = new ModelTranslationService();
            $modelTranslationService->fixProductTranslations();
            $this->showSuccessAlert(__("Product Translations") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"));
        }
    }

    public function fixTranslationFallback()
    {
        try {
            $translationFixService = new TranslationFixService();
            $translationFixService->generateFallbackTranslation();
            $this->showSuccessAlert(__("Translation Fallback generated") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Translation Fallback Failed"));
        }
    }


    public function fixArabicTranslationModels()
    {
        try {
            $translationFixService = new TranslationFixService();
            $translationFixService->fixArabicCharacters();
            $this->showSuccessAlert(__("Translation arabic values fixed") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Translation arabic values fix Failed"));
        }
    }


    public function getDrivers()
    {

        $firestoreClient = $this->getFirebaseStoreClient();
        $drivers = [];

        //
        $loadMore = true;
        $nextPageToken = "";
        // while ($loadMore) {
        //
        $driverDocuments = $firestoreClient->listDocuments('drivers', [
            "pageSize" => User::role('driver')->count(),
            'pageToken' => $nextPageToken
        ]);
        //
        if (array_key_exists('nextPageToken', $driverDocuments)) {
            $nextPageToken = $driverDocuments["nextPageToken"];
        }
        //
        if (!empty($driverDocuments['documents'])) {
            //
            foreach ($driverDocuments['documents'] as $key => $driverDocument) {

                //
                if ($driverDocument->has('id')) {
                    $drivers[] = $driverDocument->get('id');
                }
            }
            //
            $loadMore = true;
        } else {
            $loadMore = false;
        }
        // }

        return $drivers;
    }



    //
    public function fixFirestoreIndexesLink()
    {
        try {
            //creating the firestore index on the fly
            $drivers = (new FirestoreRestService())->whereWithinGeohash(5.122, 3.000, 10, 0);
            $drivers = (new FirestoreRestService())->whereWithinGeohash(5.122, 3.000, 10, 0, 1);
            $this->showSuccessAlert(__("Firestore indexes already created") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }



    //
    public $totalDriverModified = 0;
    public function fixFirebaseDriverRecords()
    {
        try {
            $this->isDemo();
            $this->totalDriverModified = 0;
            //first get drivers from firebase firestore
            $firestoreClient = $this->getFirebaseStoreClient();
            $this->firebaseDriverRecordsFix($firestoreClient);
            $msg = __("Driver Firebase Resolved") . " " . __("Successfully");
            $msg .= " " . $this->totalDriverModified . " " . __("Driver records modified");
            $this->showSuccessAlert($msg);
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }


    public function firebaseDriverRecordsFix($firestoreClient, $token = null)
    {
        try {
            //first get drivers from firebase firestore
            $driverDocumentsRaw = $firestoreClient->listDocuments('drivers', [
                "pageSize" => 50,
                'pageToken' => $token
            ]);

            $driverDocuments = $driverDocumentsRaw["documents"] ?? [];
            foreach ($driverDocuments as $driverDocument) {
                $driverDocData = $driverDocument->toArray();
                $docPath = $driverDocument->getRelativeName();
                //remove the first / from the path, if it starts with /
                if (substr($docPath, 0, 1) == "/") {
                    $docPath = substr($docPath, 1);
                }

                //$driverId get from the path of the document
                $driverId = explode("/", $docPath)[1] ?? null;
                //check if there is a driver record with the driver id
                $driver = User::find($driverId);
                if (empty($driver)) {
                    //delete the driver record from firestore
                    $firestoreClient->deleteDocument($docPath);
                    $this->totalDriverModified++;
                    continue;
                }

                //check if array have vehicle type id
                if (!array_key_exists("vehicle_type_id", $driverDocData)) {
                    $newDriverData = [
                        "vehicle_type_id" => null
                    ];
                    try {
                        //update the vehicle type id to null
                        $firestoreClient->setDocument(
                            $docPath,
                            $newDriverData,
                            true,
                            [],
                            ["merge" => true]
                        );
                        $this->totalDriverModified++;
                    } catch (\Exception $ex) {
                        logger("Issue with setDocument driver id: " . $driverId, [$ex->getMessage() ?? '']);
                    }
                    continue;
                }

                $vehicleTypeId = $driverDocData["vehicle_type_id"] ?? null;
                if ($vehicleTypeId == 0 || $vehicleTypeId === 0) {
                    try {
                        //update the vehicle type id to null
                        $firestoreClient->updateDocument($docPath, [
                            "vehicle_type_id" => null
                        ]);
                        $this->totalDriverModified++;
                    } catch (\Exception $ex) {
                        logger("Issue with updateDocument driver id: " . $driverId, [$ex->getMessage() ?? '']);
                    }
                    continue;
                }
            }

            //call itself again if there is a next page token
            if (array_key_exists('nextPageToken', $driverDocumentsRaw)) {
                $this->firebaseDriverRecordsFix($firestoreClient, $driverDocumentsRaw["nextPageToken"]);
            }
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }



    public function fixWebBrowserFCMTokens()
    {

        try {
            $this->isDemo();

            $userTokens = UserToken::get();
            //loop through and get user model, then check if the user has sanctum token
            foreach ($userTokens as $userToken) {
                $user = User::find($userToken->user_id);
                if (empty($user)) {
                    $userToken->delete();
                    continue;
                }
                if (empty($user->tokens) || !$userToken->is_mobile) {
                    $userToken->delete();
                    continue;
                } else {
                    //
                    $userToken->is_mobile = 1;
                    $userToken->save();
                }
            }



            $userTokens = UserToken::where('is_mobile', 0)->get();
            foreach ($userTokens as $userToken) {
                $userToken->delete();
            }


            //also clear duplicate tokens and leave the latest
            $userTokens = UserToken::get();
            $userTokens->groupBy('token')->each(function ($group) {
                $group->slice(1)->each(function ($item) {
                    $item->delete();
                });
            });


            $this->showSuccessAlert(__("Firestore Web Tokens cleared") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Firestore Web Tokens clearing") . " " . __("Failed"));
        }
    }




    //
    public function fixCountiresBoundaries()
    {

        try {
            $this->isDemo();
            GeoBoundaryService::syncCountriesBoundaries();
            $this->showSuccessAlert(__("Countries Boundaries Synced") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }

    public function fixStatesBoundaries()
    {

        try {
            $this->isDemo();
            GeoBoundaryService::syncStatesBoundaries();
            $this->showSuccessAlert(__("States Boundaries Synced") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }

    public function fixCitiesBoundaries()
    {

        try {
            $this->isDemo();
            GeoBoundaryService::syncCitiesBoundaries();
            $this->showSuccessAlert(__("Cities Boundaries Synced") . " " . __("Successfully"));
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Failed"), $time = 30000);
        }
    }
}