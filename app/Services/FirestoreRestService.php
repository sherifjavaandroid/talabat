<?php

namespace App\Services;

use App\Traits\FirebaseAuthTrait;
use Illuminate\Support\Facades\Http;

use Beste\Geohash;


class FirestoreRestService
{

    use FirebaseAuthTrait;
    public $authToken;

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
            $payload = [
                "serverFBAuthToken" => $this->authToken,
                "serverFBAuthTokenExpiry" => $tokenExpiry,
            ];
            setting($payload)->save();
        }
    }
    /****************************************************************************************
     * OLD WAYS OF GETTING DRIVERS
    //
    public function whereBetween($earthDistanceToNorth, $earthDistanceToSouth, $rejectedDriversCount)
    {

        //
        $this->refreshAuth();
        //
        $maxDriverOrderNotificationAtOnce = (int) setting('maxDriverOrderNotificationAtOnce', 1) + $rejectedDriversCount;
        $baseUrl = "https://firestore.googleapis.com/v1/projects/" . setting("projectId", "") . "/databases/(default)/documents/:runQuery";

        // logger("Search range", [$earthDistanceToNorth, $earthDistanceToSouth, $maxDriverOrderNotificationAtOnce]);
        //
        $response = Http::withToken($this->authToken)->post(
            $baseUrl,
            [
                'structuredQuery' => [
                    'where' => [
                        'compositeFilter' => [
                            'op' => 'AND',
                            'filters' => [
                                0 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'earth_distance',
                                        ],
                                        'op' => 'LESS_THAN_OR_EQUAL',
                                        'value' => [
                                            'doubleValue' => $earthDistanceToNorth,
                                        ],
                                    ],
                                ],
                                1 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'earth_distance',
                                        ],
                                        'op' => 'GREATER_THAN_OR_EQUAL',
                                        'value' => [
                                            'doubleValue' => $earthDistanceToSouth,
                                        ],
                                    ],
                                ],
                                2 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'online',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                                3 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'free',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'from' => [
                        0 => [
                            'collectionId' => 'drivers',
                        ],
                    ],
                    'limit' => $maxDriverOrderNotificationAtOnce,
                ],
            ]
        );

        //
        $drivers = [];
        if ($response->ok()) {
            $driversRawData = $response->json();
            foreach ($driversRawData as $driver) {
                if (empty($driver["document"])) {
                    continue;
                }
                //else get driver data
                try {

                    $drivers[] = [
                        "id" => $driver["document"]["fields"]["id"]["stringValue"] ?? $driver["document"]["fields"]["id"]["integerValue"],
                        "lat" => $driver["document"]["fields"]["lat"]["doubleValue"],
                        "long" => $driver["document"]["fields"]["long"]["doubleValue"],
                        "earth_distance" => $driver["document"]["fields"]["earth_distance"]["doubleValue"],
                    ];
                } catch (\Exception $ex) {
                    logger("Driver details Error", [
                        "1",
                        $ex->getMessage(),
                        $driver["document"]
                    ]);
                }
            }
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

        return $drivers;
    }

    //
    public function whereAvailableTaxiDriversBetween($earthDistanceToNorth, $earthDistanceToSouth, $vehicleTypeId, $rejectedDriversCount)
    {

        //
        $this->refreshAuth();
        //
        $maxDriverOrderNotificationAtOnce = (int) setting('maxDriverOrderNotificationAtOnce', 1) + $rejectedDriversCount;
        $baseUrl = "https://firestore.googleapis.com/v1/projects/" . setting("projectId", "") . "/databases/(default)/documents/:runQuery";

        // logger("Search range", [$earthDistanceToNorth, $earthDistanceToSouth, $maxDriverOrderNotificationAtOnce]);
        //
        $response = Http::withToken($this->authToken)->post(
            $baseUrl,
            [
                'structuredQuery' => [
                    'where' => [
                        'compositeFilter' => [
                            'op' => 'AND',
                            'filters' => [
                                0 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'earth_distance',
                                        ],
                                        'op' => 'LESS_THAN_OR_EQUAL',
                                        'value' => [
                                            'doubleValue' => $earthDistanceToNorth,
                                        ],
                                    ],
                                ],
                                1 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'earth_distance',
                                        ],
                                        'op' => 'GREATER_THAN_OR_EQUAL',
                                        'value' => [
                                            'doubleValue' => $earthDistanceToSouth,
                                        ],
                                    ],
                                ],
                                2 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'vehicle_type_id',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => $vehicleTypeId,
                                        ],
                                    ],
                                ],
                                3 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'online',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                                4 => [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'free',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'from' => [
                        0 => [
                            'collectionId' => 'drivers',
                        ],
                    ],
                    'limit' => $maxDriverOrderNotificationAtOnce,
                ],
            ]
        );

        //
        $drivers = [];
        if ($response->ok()) {
            $driversRawData = $response->json();
            foreach ($driversRawData as $driver) {
                if (empty($driver["document"])) {
                    continue;
                }
                //else get driver data
                try {

                    // logger("Driver doc", [$driver["document"]["fields"]]);
                    $drivers[] = [
                        "id" => $driver["document"]["fields"]["id"]["stringValue"] ?? $driver["document"]["fields"]["id"]["integerValue"],
                        "lat" => $driver["document"]["fields"]["lat"]["doubleValue"],
                        "long" => $driver["document"]["fields"]["long"]["doubleValue"],
                        "earth_distance" => $driver["document"]["fields"]["earth_distance"]["doubleValue"],
                    ];
                } catch (\Exception $ex) {
                    logger("Driver details Error", [
                        "2",
                        $ex->getMessage(),
                        $driver["document"]
                    ]);
                }
            }
        } else {
            $errorCode = $response->json()[0]["error"]["code"];
            if ($errorCode == 401 || $errorCode == 403) {
                $this->refreshAuth(true);
            }

            try {


                if ($response->json()[0]["error"]["status"] == "FAILED_PRECONDITION") {
                    logger("Please follow this link to create the required condition on your firebase", [$response->json()[0]["error"]["message"]]);
                } else {
                    logger("Error with drivers search", [$response->body()]);
                }
            } catch (\Exception $ex) {
                logger("Error with drivers search", [$response->body()]);
            }
            // throw new \Exception($response->body(), 1);
        }

        return $drivers;
    }
     */
    //
    public function exipredDriverNewOrders()
    {

        //
        $this->refreshAuth();
        //fetch firebase server timestamp

        //
        $baseUrl = "https://firestore.googleapis.com/v1/projects/" . setting("projectId", "") . "/databases/(default)/documents/:runQuery";
        $response = Http::withToken($this->authToken)->post(
            $baseUrl,
            [
                'structuredQuery' => [
                    // 'where' => [
                    //     'fieldFilter' => [
                    //         'field' => [
                    //             'fieldPath' => 'exipres_at_timestamp',
                    //         ],
                    //         'op' => 'LESS_THAN_OR_EQUAL',
                    //         'value' => [
                    //             'timestampValue' => "REQUEST_TIME",
                    //         ],
                    //     ],
                    // ],
                    'orderBy' => [
                        'field' => [
                            'fieldPath' => 'exipres_at',
                        ],
                        'direction' => 'ASCENDING',
                    ],
                    'from' => [
                        0 => [
                            'collectionId' => 'driver_new_order',
                        ],
                    ],
                    'limit' => 20,
                ],
            ]
        );

        //
        //
        $drivers = [];
        if ($response->ok()) {
            $driversRawData = $response->json();
            foreach ($driversRawData as $driver) {
                if (empty($driver["document"])) {
                    continue;
                }
                //else get driver data
                try {

                    // logger("driver", [$driver]);
                    $docName = $driver["document"]["name"];
                    $docName = explode("/", $docName);
                    $docNameCount = count($docName);
                    $docName = $docName[$docNameCount - 1];
                    //
                    $currentTime = new \Carbon\Carbon($driver["readTime"]);

                    $drivers[] = $driverObject =  [
                        "id" => $docName,
                        "exipres_at" => $driver["document"]["fields"]["exipres_at"]["integerValue"],
                        "current_time" => $currentTime->timestamp * 1000,
                    ];
                } catch (\Exception $ex) {
                    logger("Driver details Error", [
                        "3",
                        $ex->getMessage(),
                        $driver["document"]
                    ]);
                }
            }
        } else {
            $errorCode = $response->json()[0]["error"]["code"];
            if ($errorCode == 401 || $errorCode == 403) {
                $this->refreshAuth(true);
            }
            logger("Error with drivers search", [$response->body()]);
            // throw new \Exception($response->body(), 1);
        }

        return $drivers;
    }

    //
    public function deleteDocument($path, $recursive = true)
    {

        //
        $this->refreshAuth();

        //get the collections in that path
        $collectionIDs = $this->getCollections($path);
        if (empty($collectionIDs)) {
            $this->deleteDocumentData($path);
        } else {

            foreach ($collectionIDs as $collectionID) {
                $collectionPath = "{$path}/{$collectionID}";
                $this->deleteDocument($collectionPath);
            }
        }
    }

    public function getCollections($path): array
    {
        //
        $projectId = setting("projectId", "");
        $baseUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/{$path}/:listCollectionIds";
        $response = Http::withToken($this->authToken)->post($baseUrl, [
            "pageSize" => 20,
            // "pageToken" => null,
        ]);

        //
        $collections = [];
        if ($response->ok()) {
            logger("collections", [$response->json()]);
            $collections = $response->json()["collectionIds"];
        } else {
            logger("collections fetch error", [$response->json()]);
            $errorCode = ($response->json()[0] ??  $response->json())["error"]["code"];
            if ($errorCode == 401 || $errorCode == 403) {
                $this->refreshAuth(true);
            }

            try {


                if ($response->json()[0]["error"]["status"] == "FAILED_PRECONDITION") {
                    logger("Please follow this link to create the required condition on your firebase", [$response->json()[0]["error"]["message"]]);
                } else {
                    logger("Error with drivers search", [$response->body()]);
                }
            } catch (\Exception $ex) {
                logger("Error with drivers search", [$response->body()]);
            }
            // throw new \Exception($response->body(), 1);
        }

        return $collections;
    }

    public function deleteDocumentData($path)
    {

        $baseUrl = "https://firestore.googleapis.com/v1/projects/" . setting("projectId", "") . "/databases/(default)/documents/{$path}";
        $response = Http::withToken($this->authToken)->delete($baseUrl);

        //
        if (!$response->ok()) {
            $errorCode = $response->json()[0]["error"]["code"];
            if ($errorCode == 401 || $errorCode == 403) {
                $this->refreshAuth(true);
            }
            logger("Error with deleting documents", [$response->body()]);
            // throw new \Exception($response->body(), 1);
        }
    }








    //NEW WAY TO FETCH DRIVERS
    public function whereWithinGeohash($lat, $lng, $radius = 10, $rejectedDriversCount = 0, $vehicleTypeId = null)
    {
        //
        $limitItems = (int) setting('maxDriverOrderNotificationAtOnce', 1) + $rejectedDriversCount;
        $precision = $this->getGeohashPrecision($radius);
        $geoLocHash = Geohash::encode((float) $lat, (float) $lng, $precision ?? 9);
        // $geohashNeighbours = Geohash::neighbours($geoLocHash);

        // $geoLocBounds = $this->getBounds($lat, $lng, $radius);
        // $minLat = $geoLocBounds['minLat'];
        // $minLng = $geoLocBounds['minLng'];
        // $maxLat = $geoLocBounds['maxLat'];
        // $maxLng = $geoLocBounds['maxLng'];

        // //
        // $maxLocGeoHash = Geohash::encode((float) $maxLat, (float) $maxLng, 9);
        // $minLocGeoHash = Geohash::encode((float) $minLat, (float) $minLng, 9);

        //
        $maxLocGeoHash = $geoLocHash . "z";
        $minLocGeoHash = $geoLocHash;
        // $minLocGeoHash = $geoLocHash . "0";


        $this->refreshAuth();

        //vehcilte type filter
        $vehicleTypeFilter = [];
        if (!empty($vehicleTypeId)) {
            $vehicleTypeFilter = [
                'fieldFilter' => [
                    'field' => [
                        'fieldPath' => 'vehicle_type_id',
                    ],
                    'op' => 'EQUAL',
                    'value' => [
                        'doubleValue' => $vehicleTypeId,
                    ],
                ],
            ];
        } else {


            // $vehicleTypeFilter = [
            //     'fieldFilter' => [
            //         'field' => [
            //             'fieldPath' => 'vehicle_type_id',
            //         ],
            //         'op' => 'EQUAL',
            //         'value' => [
            //             'doubleValue' => 0,
            //         ],
            //     ],
            //     'unaryFilter' => [
            //         'field' => [
            //             'fieldPath' => 'vehicle_type_id',
            //         ],
            //         'op' => 'IS_NULL',
            //     ],
            // ];

            //
            $vehicleTypeFilter = [
                'compositeFilter' => [
                    'op' => 'OR',
                    'filters' => [
                        [
                            'unaryFilter' => [
                                'field' => [
                                    'fieldPath' => 'vehicle_type_id',
                                ],
                                'op' => 'IS_NULL',
                            ],
                        ],
                        [
                            'fieldFilter' => [
                                'field' => [
                                    'fieldPath' => 'vehicle_type_id',
                                ],
                                'op' => 'EQUAL',
                                'value' => [
                                    'doubleValue' => 0,
                                ],
                            ],
                        ],

                    ],
                ]
            ];
        }

        $baseUrl = "https://firestore.googleapis.com/v1/projects/" . setting("projectId", "") . "/databases/(default)/documents/:runQuery";
        $response = Http::withToken($this->authToken)->post(
            $baseUrl,
            $params = [
                'structuredQuery' => [
                    'startAt' => [
                        'values' => [
                            [
                                'stringValue' => $minLocGeoHash,
                            ],
                        ],
                        'before' => false,
                    ],
                    // 'endAt' => [
                    //     'values' => [
                    //         [
                    //             'stringValue' => $maxLocGeoHash,
                    //         ],
                    //     ],
                    //     'before' => true,
                    // ],
                    'where' => [
                        'compositeFilter' => [
                            'op' => 'AND',
                            'filters' => [
                                [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'free',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                                [
                                    'fieldFilter' => [
                                        'field' => [
                                            'fieldPath' => 'online',
                                        ],
                                        'op' => 'EQUAL',
                                        'value' => [
                                            'doubleValue' => 1,
                                        ],
                                    ],
                                ],
                                //vehicleTypeFilter
                                $vehicleTypeFilter
                            ],
                        ],
                    ],
                    'orderBy' => [
                        'field' => [
                            'fieldPath' => 'g.geohash',
                        ],
                        'direction' => 'ASCENDING',
                    ],
                    'from' => [
                        0 => [
                            'collectionId' => 'drivers',
                        ],
                    ],
                    'limit' => (int) $limitItems,
                ],
            ]
        );


        //
        $drivers = [];
        if ($response->ok()) {
            $driversRawData = $response->json();
            foreach ($driversRawData as $driver) {
                if (empty($driver["document"])) {
                    continue;
                }
                //else get driver data
                try {

                    $drivers[] = [
                        "id" => $driver["document"]["fields"]["id"]["stringValue"] ?? $driver["document"]["fields"]["id"]["integerValue"],
                        "lat" => $driver["document"]["fields"]["lat"]["doubleValue"] ?? $driver['document']['fields']['coordinates']['geoPointValue']['latitude'],
                        "long" => $driver["document"]["fields"]["long"]["doubleValue"] ?? $driver['document']['fields']['coordinates']['geoPointValue']['longitude'],
                        "earth_distance" => $driver["document"]["fields"]["earth_distance"]["doubleValue"],
                    ];
                } catch (\Exception $ex) {
                    logger("Driver details Error", [
                        "1",
                        $ex->getMessage(),
                        $driver["document"]
                    ]);
                }
            }
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

        return $drivers;
    }


    function getBounds($latitude, $longitude, $radius)
    {
        // Earth's radius in kilometers
        $earthRadius = 6371;

        // Convert latitude and longitude to radians
        $lat = deg2rad($latitude);
        $lng = deg2rad($longitude);

        // Calculate the north-most latitude
        $maxLat = rad2deg(asin(sin($lat) * cos($radius / $earthRadius) +
            cos($lat) * sin($radius / $earthRadius)));

        // Calculate the south-most latitude
        $minLat = rad2deg(asin(sin($lat) * cos($radius / $earthRadius) -
            cos($lat) * sin($radius / $earthRadius)));

        // Calculate the east-most longitude
        $maxLng = rad2deg($lng + atan2(
            sin($radius / $earthRadius) * cos($lat),
            cos($radius / $earthRadius) - sin($lat) * sin(deg2rad($maxLat))
        ));

        // Calculate the west-most longitude
        $minLng = rad2deg($lng + atan2(
            sin($radius / $earthRadius) * cos($lat),
            cos($radius / $earthRadius) - sin($lat) * sin(deg2rad($minLat))
        ));


        //round the values to max 9 decimal places
        $minLat = round($minLat, 9);
        $maxLat = round($maxLat, 9);
        $minLng = round($minLng, 9);
        $maxLng = round($maxLng, 9);

        // Return the bounds as an associative array
        return array(
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLng' => $minLng,
            'maxLng' => $maxLng
        );
    }

    function getGeohashPrecision($radiusInKm)
    {

        if ($radiusInKm <=  0.0012) {
            return 10;
        } elseif ($radiusInKm <= 0.0047) {
            return 9;
        } elseif ($radiusInKm <= 0.038) {
            return 8;
        } elseif ($radiusInKm <= 0.152) {
            return 7;
        } elseif ($radiusInKm <= 1.2) {
            return 6;
        } elseif ($radiusInKm <= 4.9) {
            return 5;
        } elseif ($radiusInKm <= 39) {
            return 4;
        } elseif ($radiusInKm <= 156) {
            return 3;
        } elseif ($radiusInKm <= 1250) {
            return 2;
        } elseif ($radiusInKm <= 5000) {
            return 1;
        } else {
            return 9;
        }
    }
}