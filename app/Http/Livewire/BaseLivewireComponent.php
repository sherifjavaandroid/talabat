<?php

namespace App\Http\Livewire;

use App\Models\UserToken;
use App\Models\Vendor;
use App\Models\VendorManager;
use App\Models\User;
use App\Traits\AutocompleteTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Exception;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GeoSot\EnvEditor\Facades\EnvEditor;

class BaseLivewireComponent extends Component
{
    use WithPagination, WithFileUploads;
    use AutocompleteTrait;
    use LivewireAlert;

    public $perPage = 6;
    public $showPassword = false;
    public $model;
    public $selectedModel;
    public $photoInfo;
    public $secondPhotoInfo;
    public $photo;
    public $secondPhoto;

    protected $listeners = [
        'showCreateModal' => 'showCreateModal',
        'showEditModal' => 'showEditModal',
        'showDetailsModal' => 'showDetailsModal',
        'showAssignModal' => 'showAssignModal',
        'initiateEdit' => 'initiateEdit',
        'initiateDelete' => 'initiateDelete',
        'removeModel' => 'removeModel',
        'initiateAssign' => 'initiateAssign',
        'initiateSubcategoriesAssign' => 'initiateSubcategoriesAssign',
        'initiatePayout' => 'initiatePayout',
        'initiateEarningWalletTransfer' => 'initiateEarningWalletTransfer',
        'dismissModal' => 'dismissModal',
        'refreshView' => '$refresh',
        'select2Change' => 'select2Change',
        'productsChange' => 'productsChange',
        'vendorsChange' => 'vendorsChange',
        'managersChange' => 'managersChange',
        'paymentMethodsChange' => 'paymentMethodsChange',
        'categoriesChange' => 'categoriesChange',
        'vendorChange' => 'vendorChange',
        'changeVendorTiming' => 'changeVendorTiming',
        'changeFCMToken' => 'changeFCMToken',
        'logout' => 'logout',
        'reviewPayment' => 'reviewPayment',
        'customerChange' => 'customerChange',
        'deliveryAddressesChange' => 'deliveryAddressesChange',
        'autocompleteDriverSelected' => 'autocompleteDriverSelected',
        'autocompleteAddressSelected' => 'autocompleteAddressSelected',
        'autocompleteProductSelected' => 'autocompleteProductSelected',
        'autocompleteVendorSelected' => 'autocompleteVendorSelected',
        'autocompleteUserSelected' => 'autocompleteUserSelected',
        'autocompleteCategorySelected' => 'autocompleteCategorySelected',
        'photoSelected' => 'photoSelected',
        'refreshDataTable' => 'refreshDataTable',
        'initiateLoginAs' => 'initiateLoginAs',
        'openNewTab' => 'openNewTab',
    ];

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function refreshDataTable()
    {
        $this->emit('refreshTable');
    }

    //Alert
    public function showSuccessAlert($message = "", $time = 3000)
    {
        $this->alert('success', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showWarningAlert($message = "", $time = 3000)
    {
        $this->alert('warning', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showErrorAlert($message = "", $time = 3000)
    {
        $this->alert('error', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showSelect2($selectorID, $data, $onChange, $options = null)
    {
        $this->emit('showSelect2', [
            $selectorID,
            $data,
            $onChange,
            $options
        ],);
    }

    public function setQuillTextarea($selectorID, $data)
    {
        $this->emit('setQuillTextarea', [
            $selectorID,
            $data,
        ],);
    }

    public function openNewTab($link)
    {
        return $this->emitUp($link);
    }


    // Modal management
    public $showCreate = false;
    public $showEdit = false;
    public $showDetails = false;
    public $showAssign = false;
    public $stopRefresh = false;
    public function showCreateModal()
    {
        $this->resetErrorBag();
        $this->showCreate = true;
        $this->stopRefresh = true;
    }

    public function showEditModal()
    {
        $this->resetErrorBag();
        $this->showEdit = true;
        $this->stopRefresh = true;
    }

    public function showDetailsModal($id)
    {
        if ($id != null) {
            $this->selectedModel = $this->model::find($id);
        }
        $this->showDetails = true;
        $this->stopRefresh = true;
    }

    public function showAssignModal()
    {
        $this->showAssign = true;
        $this->stopRefresh = true;
    }





    public function dismissModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->stopRefresh = false;
        $this->reset();
    }

    public function closeModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->stopRefresh = false;
    }

    //
    //
    public function updatedPhoto()
    {

        $file = array();

        if ($this->photo != null) {
            $filePath = pathinfo($this->photo->getRealPath());
            $file['name'] = $filePath['filename'];
            $file['extension'] = $filePath['extension'];
            //convert size to MB
            $file['size'] = number_format(filesize($filePath['dirname'] . '/' . $filePath['basename']) * 0.000001, 2);
        }

        $this->photoInfo = $file;
    }

    public function updatedSecondPhoto()
    {

        $file = array();

        if ($this->secondPhoto != null) {
            $filePath = pathinfo($this->secondPhoto->getRealPath());
            $file['name'] = $filePath['filename'];
            $file['extension'] = $filePath['extension'];
            //convert size to MB
            $file['size'] = number_format(filesize($filePath['dirname'] . '/' . $filePath['basename']) * 0.000001, 2);
        }

        $this->secondPhotoInfo = $file;
    }







    //
    public function orderStatus()
    {
        return ['scheduled', 'pending', 'preparing', 'ready', 'enroute', 'failed', 'cancelled', 'delivered'];
    }

    public function orderPaymentStatus()
    {
        return ['pending', 'request', 'review', 'failed', 'cancelled', 'successful'];
    }



    //
    public function isDemo()
    {
        if (!App::environment('production')) {
            throw new Exception(__("App is in demo version. Some changes can't be made"));
        };
    }

    public function inDemo()
    {
        return !App::environment('production');
    }


    public function setEnvKey($key, $value)
    {
        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, $value);
        } else {
            EnvEditor::addKey($key, $value);
        }
    }




    //FCM
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


    public function logout()
    {
        UserToken::where('token', $this->fcmToken)->delete();
        return redirect()->route('logout');
    }

    public function initiateLoginAs($vendorId)
    {
        if (\Schema::hasTable("vendor_managers")) {
            $vendorManager = VendorManager::where('vendor_id', $vendorId)->first();
            if (!empty($vendorManager)) {
                $manager = User::find($vendorManager->user_id) ?? null;
            }
        } else {
            $manager = Vendor::find($vendorId)->managers->first() ?? null;
        }


        if (empty($manager)) {
            $this->showWarningAlert(
                __("No manager is assigned to vendor, so you can login into the vendor. Please create and assign at least one manager to the vendor")
            );
        } else {
            //logout and login as the manager
            Auth::logout(); // for end current session
            Auth::loginUsingId($manager->id);
            return redirect()->route('dashboard');
        }
    }


    public function genColor()
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }
}