<?php

namespace App\Http\Livewire;

use App\Exports\CategoriesExport;
use App\Exports\MenuExport;
use App\Exports\ProductsExport;
use App\Exports\ServicesExport;
use App\Exports\SubCategoriesExport;
use App\Exports\VendorsExport;
use App\Exports\EarningsExport;
use App\Exports\PayoutsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportLivewire extends BaseLivewireComponent
{


    public $dataType;
    public $dataTypeName;
    public $photo;

    public function render()
    {
        return view('livewire.exports');
    }


    public function exportData($dataType, $fileName)
    {


        try {

            $this->isDemo();

            switch ($dataType) {
                case 1:
                    return Excel::download(new CategoriesExport, '' . $fileName . '.xlsx');
                    break;
                case 2:
                    return Excel::download(new SubCategoriesExport, '' . $fileName . '.xlsx');
                    break;
                case 3:
                    return Excel::download(new VendorsExport, '' . $fileName . '.xlsx');
                    break;
                case 4:
                    return Excel::download(new MenuExport, '' . $fileName . '.xlsx');
                    break;

                case 5:
                    return Excel::download(new ProductsExport, '' . $fileName . '.xlsx');
                    break;

                case 6:
                    return Excel::download(new ServicesExport, '' . $fileName . '.xlsx');
                    break;

                case 7:
                    return Excel::download(new EarningsExport, '' . $fileName . '.xlsx');
                    break;

                case 8:
                    return Excel::download(new PayoutsExport, '' . $fileName . '.xlsx');
                    break;

                default:
                    # code...
                    break;
            }
            //

            $this->showSuccessAlert(__("Data exported successfully!") . $dataType . "");


            $this->reset();
            $this->showCreate = false;
        } catch (\Exception $error) {
            logger("error", [$error]);
            $this->showErrorAlert($error->getMessage() ?? __("Data export failed!"));
        }
    }
}
