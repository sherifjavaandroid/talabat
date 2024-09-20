<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Storage;
use MrShan0\PHPFirestore\FirestoreClient;
use MrShan0\PHPFirestore\Authentication\FirestoreAuthentication;

trait FirebaseAuthTrait
{

    private function getFirebaseFactory()
    {
        try {
            return (new Factory)
                ->withServiceAccount(Storage::get(setting('serviceKeyPath', 'vault/firebase_service.json')));
        } catch (\Exception $ex) {
            throw new \Exception(__("Please setup firebase on backend"), 1);
        }
    }


    private function getFirebaseMessaging()
    {
        return $this->getFirebaseFactory()->createMessaging();
    }

    private function getFirebaseAuth()
    {
        return $this->getFirebaseFactory()->createAuth();
    }

    private function getFirebaseStore()
    {
        return $this->getFirebaseFactory()->createFirestore();
    }

    private function getFirebaseStoreClient(): FirestoreClient
    {
        $client = new FirestoreClient(setting('projectId', ""), setting('apiKey', ""), [
            'database' => '(default)',
        ]);
        //set auth for client
        $this->firestoreClientAuth($client);
        return $client;
    }


    public function verifyFirebaseIDToken($idTokenString)
    {
        //
        $auth = $this->getFirebaseAuth();
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        $uid = $verifiedIdToken->claims()->get('sub');
        return $auth->getUser($uid);
    }

    //firestore 
    public function firestoreClientAuth($firestoreClient)
    {

        $authToken = session('fbToken');
        $authTokenExpiry = session('fbTokenExpiry');

        if (empty($authToken) || empty($authTokenExpiry) || $authTokenExpiry < time()) {
            $uId = "user_id_" . \Auth::id() . "";
            $customToken = $this->getFirebaseAuth()->createCustomToken($uId);
            $signInResult = $this->getFirebaseAuth()->signInWithCustomToken($customToken);
            $authToken = $signInResult->idToken();
            session(['fbToken' => $authToken]);

            //refresh token every 60mins/1hr
            $authTokenExpiry = time() + 3600;
            session(['fbTokenExpiry' => $authTokenExpiry]);
        }

        $auth = new FirestoreAuthentication($firestoreClient);
        $auth->setCustomToken($authToken);
    }
}
