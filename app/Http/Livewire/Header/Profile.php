<?php

namespace App\Http\Livewire\Header;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\UserToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Profile extends BaseLivewireComponent
{

    public $locale;
    public $localeCode;
    public $selectedLanguage;
    public $languages = [];
    public $languageCodes = [];


    protected $listeners = [
        'logout' => 'logout',
        'changeFCMToken' => 'changeFCMToken',
    ];

    public function mount()
    {
        $this->loadLanguages();
    }

    public function render()
    {
        if (empty($this->languages)) {
            $this->loadLanguages();
        }
        return view('livewire.header.profile');
    }

    public function loadLanguages()
    {
        $languagesList = config('backend.languages', []);
        $languageCodesList = config('backend.languageCodes', []);
        $this->languages = [];
        foreach ($languagesList as $key => $language) {
            $this->languages[] = [
                "id" => $languageCodesList[$key],
                "name" => $language,
            ];
        }

        if (!\Schema::hasColumn("users", 'language')) {
            $this->locale =  setting('locale', 'en');
        } else {
            $this->locale =  \Auth::user()->language ?? "es";
        }
    }

    public function updatedLocale($value)
    {
        try {
            $this->isDemo();
            \DB::beginTransaction();
            $user = User::find(\Auth::id());
            $user->language = $value;
            $user->save();
            \DB::commit();
            $this->showSuccessAlert(__('Language updated successfully!'));
            return redirect(request()->header('Referer'));
        } catch (\Exception $ex) {
            \DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __('Error updating language'));
        }
    }




    public function logout()
    {
        UserToken::where('token', $this->fcmToken)->orWhere('user_id', \Auth::id())->delete();
        return redirect()->route('logout');
    }

    public $fcmToken;
    public function changeFCMToken($token, $deviceIdenfier = null)
    {

        $this->fcmToken = $token;
        if (Auth::check() && !empty($this->fcmToken)) {
            //
            UserToken::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'device_uuid' => $deviceIdenfier
                ],
                [
                    'user_id' => Auth::id(),
                    'token' => $this->fcmToken,
                    'is_mobile' => false,
                    'device_uuid' => $deviceIdenfier
                ],
            );
        }
    }
}
