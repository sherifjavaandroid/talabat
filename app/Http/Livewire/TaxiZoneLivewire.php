<?php

namespace App\Http\Livewire;

use App\Models\TaxiZone;
use App\Models\TaxiZonePoint;
use App\Traits\GoogleMapApiTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class TaxiZoneLivewire extends BaseLivewireComponent
{

    use GoogleMapApiTrait;

    public $model = TaxiZone::class;
    public $name;
    public $is_active;
    public $coordinates;
    public $coordinateCollection;

    protected $rules = [
        "name" => "required",
        "coordinates" => "required",
    ];


    public function getListeners()
    {
        return $this->listeners + [
            'selectedCoordinates' => 'selectedCoordinates',
        ];
    }


    public function render()
    {
        return view('livewire.taxi_zones');
    }


    public function selectedCoordinates($coordinates)
    {
        $this->coordinateCollection = $coordinates;
        $this->coordinates = "";
        //
        foreach ($coordinates as $coordinate) {
            $this->coordinates .= "(" . $coordinate['lat'] . "," . $coordinate['lng'] . "), ";
        }
    }


    //
    function save()
    {

        $this->validate();

        try {

            //
            if ($this->coordinateCollection == null || empty($this->coordinateCollection)) {
                $this->showErrorAlert(__("Coordinates is required"));
                return;
            }

            // 
            $centerCoordinate = $this->getCenter($this->coordinateCollection);
            $centerCoordinateDistance = $this->getLinearDistance(
                "" . $centerCoordinate[0] . "," . $centerCoordinate[1] . "",
                "" . $this->coordinateCollection[0]['lat'] . "," . $this->coordinateCollection[0]['lng'] . "",
            );

            DB::beginTransaction();
            $taxiZone = new TaxiZone();
            $taxiZone->name = $this->name;

            $taxiZone->latitude = $centerCoordinate[0];
            $taxiZone->longitude = $centerCoordinate[1];
            $taxiZone->radius = $centerCoordinateDistance;
            //
            $taxiZone->is_active = $this->is_active ?? false;
            $taxiZone->save();
            //points
            foreach ($this->coordinateCollection as $coordinate) {
                $taxiZonePoint = new TaxiZonePoint();
                $taxiZonePoint->taxi_zone_id = $taxiZone->id;
                $taxiZonePoint->lat = $coordinate['lat'];
                $taxiZonePoint->lng = $coordinate['lng'];
                $taxiZonePoint->save();
            }

            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery Zone") . " " . __('created successfully!'));
            $this->emit('resetMap');
            $this->emit('refreshTable');
        } catch (\Exception $ex) {
            DB::rollback();
            logger("Error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Delivery Zone") . " " . __('creation failed!'));
        }
    }


    //
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->is_active = $this->selectedModel->is_active;

        //
        $coordinates = [];
        foreach ($this->selectedModel->points as $point) {
            $coordinates[] = [
                "lat" => (float) $point->lat,
                "lng" => (float) $point->lng,
            ];
        }

        $this->selectedCoordinates($coordinates);
        $this->emit('initiateEditMap', $coordinates);
        $this->showEditModal();
    }


    function update()
    {

        $this->validate();

        try {

            // 
            $centerCoordinate = $this->getCenter($this->coordinateCollection);
            $centerCoordinateDistance = $this->getLinearDistance(
                "" . $centerCoordinate[0] . "," . $centerCoordinate[1] . "",
                "" . $this->coordinateCollection[0]['lat'] . "," . $this->coordinateCollection[0]['lng'] . "",
            );


            DB::beginTransaction();
            $taxiZone = $this->selectedModel;
            $taxiZone->name = $this->name;

            $taxiZone->latitude = $centerCoordinate[0];
            $taxiZone->longitude = $centerCoordinate[1];
            $taxiZone->radius = $centerCoordinateDistance;


            $taxiZone->is_active = $this->is_active ?? false;
            $taxiZone->save();
            //
            $taxiZone->points()->delete();
            // //points
            foreach ($this->coordinateCollection as $coordinate) {
                $taxiZonePoint = new TaxiZonePoint();
                $taxiZonePoint->taxi_zone_id = $taxiZone->id;
                $taxiZonePoint->lat = $coordinate['lat'];
                $taxiZonePoint->lng = $coordinate['lng'];
                $taxiZonePoint->save();
            }

            DB::commit();
            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert(__("Delivery Zone") . " " . __('created successfully!'));
            $this->emit('refreshTable');
            $this->emit('resetMap');
        } catch (\Exception $ex) {
            DB::rollback();
            logger("Error", [$ex]);
            $this->showErrorAlert($ex->getMessage() ?? __("Delivery Zone") . " " . __('creation failed!'));
        }
    }
}
