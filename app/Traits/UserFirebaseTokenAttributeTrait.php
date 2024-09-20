<?php

namespace App\Traits;

use App\Models\UserToken;

trait UserFirebaseTokenAttributeTrait
{

    public function getNotificationTokensAttribute()
    {
        return $this->firebaseTokens->pluck('token')->toArray();
    }

    public function firebaseTokens()
    {
        return $this->hasMany(UserToken::class);
    }


    public function syncFCMTokens($tokens = null)
    {
        if ($tokens == null) {
            $tokens = request()->tokens ?? [];
        }
        //
        if ($tokens == null || empty($tokens)) {
            return;
        }

        //if tokens is not array, attach it
        if (!is_array($tokens)) {
            $tokens = [$tokens];
        }

        //
        $userTokens = [];
        foreach ($tokens as $token) {
            $userTokens[] = new UserToken(['token' => $token]);
        }
        //if token is array, sync it
        try {
            $this->firebaseTokens()->saveMany($userTokens);
        } catch (\Exception $e) {
            logger("Error syncing tokens: ", [$e]);
        }
    }

    public function deListTokens($tokens = null)
    {
        if ($tokens == null) {
            $tokens = request()->tokens ?? [];
        }
        if ($tokens == null || empty($tokens)) {
            return;
        }
        $this->firebaseTokens()->detach($tokens);
    }
}
