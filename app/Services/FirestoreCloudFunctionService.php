<?php

namespace App\Services;

use App\Traits\FirebaseAuthTrait;
use Illuminate\Support\Facades\Http;

class FirestoreCloudFunctionService
{

    use FirebaseAuthTrait;
    public $authToken;

    //endpoints
    // :: demo-populate
    // :: geomod-drivers
    // :: drivers-nearby?lat=5.5612297&lng=-0.2288345&range=7&limit=1

    public function getEndpoint($endpoint)
    {
        $projectId = setting('projectId', "");
        $resourceLocation = setting('resourceLocation', "us-central1");
        $baseUrl = "https://" . $resourceLocation . "-" . $projectId . ".cloudfunctions.net/" . $endpoint;
        return $baseUrl;
    }

    public function refreshAuth($force = false)
    {
        $this->authToken = setting("serverFBAuthToken", "");
        $tokenExpiry = ((int) setting("serverFBAuthTokenExpiry", 0)) ?? 0;
        //
        if ($force || $tokenExpiry < now()->milliseconds) {
            $uId = "web_server";
            $firebaseAuth = $this->getFirebaseAuth();
            $customToken = $firebaseAuth->createCustomToken($uId);
            $signInResult = $firebaseAuth->signInWithCustomToken($customToken);
            $this->authToken = $signInResult->idToken();
            //generate new tokens 5mintues to its expiry
            $tokenExpiry = (now()->milliseconds + ($signInResult->ttl() ?? 0)) - 300000;
            //
            setting([
                "serverFBAuthToken" => $this->authToken,
                "serverFBAuthTokenExpiry" => $tokenExpiry,
            ])->save();
        }
    }


    //
    public function nearbyDriver($lat, $lng, $range, $limit = 10, $vehicleTypeId = null)
    {

        //
        $this->refreshAuth();
        //
        $baseUrl = $this->getEndpoint("drivers-nearby");
        $payload = [
            "lat" => $lat,
            "lng" => $lng,
            "range" => $range,
            "limit" => $limit,
            "vehicle_type_id" => $vehicleTypeId,
        ];
        //
        $response = Http::withToken($this->authToken)->get(
            $baseUrl,
            $payload
        );

        if ($response->ok()) {
            $drivers = $response->json()['drivers'];
            return $drivers;
        } else {
            $errorCode = $response->json()[0]["error"]["code"];
            if ($errorCode == 401 || $errorCode == 403) {
                $this->refreshAuth(true);
            }

            try {


                if ($response->json()[0]["error"]["status"] == "FAILED_PRECONDITION") {
                    logger("Please follow this link to create the required condition on your firebase", [$response->json()[0]["error"]["message"]]);

                    $error = "Please follow this link to create the required condition on your firebase \n" . $response->json()[0]["error"]["message"] . "";
                    throw new \Exception($error, 1);
                } else {
                    logger("Error with drivers search", [$response->body()]);
                }
            } catch (\Exception $ex) {
                logger("Error with drivers search", [$ex->getMessage() ?? $response->body()]);
                throw new \Exception($ex->getMessage() ?? $response->body(), 1);
            }
            // throw new \Exception($response->body(), 1);
        }
    }
}
