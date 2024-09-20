<?php

namespace App\Http\Livewire\Select;

use App\Http\Livewire\BaseLivewireComponent;
use App\Models\User;

class LanguageSelector extends BaseLivewireComponent
{

    public $languages = [];
    public $lan = "en";
    protected $queryString = ['lan'];
    public $link;
    //
    public function mount()
    {
        //seeting url for next session use
        $this->link = url()->full();
        session(['c_link' => $this->link]);
    }

    public function render()
    {
        if (empty($this->languages)) {
            $this->loadLanguages();
        }
        return view('livewire.component.language-selector');
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

        if (empty($this->lan)) {
            if (!\Auth::user()) {
                $this->lan = session('lan', 'en');
            } else if (!\Schema::hasColumn("users", 'language')) {
                $this->lan = setting('locale', 'en');
            } else {
                $this->lan = \Auth::user()->language ?? "es";
            }
        }
    }

    public function updatedLan($value)
    {
        try {
            $this->isDemo();
            if (!\Auth::user() && !\Schema::hasColumn("users", 'language')) {
                \DB::beginTransaction();
                $user = User::find(\Auth::id());
                $user->language = $value;
                $user->save();
                \DB::commit();
            }

            $this->showSuccessAlert(__('Language updated successfully!'));
            session(['lan' => $value]);
            $link = session('c_link', $this->link);
            $link = explode("?", $link)[0];
            $link = $link . "?lan=" . $value . "";
            return redirect($link);
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            \DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __('Error updating language'));
        }
    }
}
