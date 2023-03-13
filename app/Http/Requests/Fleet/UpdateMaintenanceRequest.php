<?php

namespace App\Http\Requests\Fleet;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Fleet\Maintenance;

class UpdateMaintenanceRequest extends FormRequest
{
    private $excludeKeys = []; 

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $permissionName = 'maintenances-update';
        return Auth::user()->can($permissionName);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Maintenance::$rules;
        $idMaintenance = explode('/',$this->getRequestUri())[3];    
        
        $rules = $this->excludeKeys ? array_diff_key($rules, array_combine($this->excludeKeys, $this->excludeKeys)) : $rules;
        $vehicleId = $this->get('vehicle_id');        
        if($vehicleId){
            $lastMaintenance = Maintenance::select(['odometer'])->where('id','<', $idMaintenance)->whereVehicleId($vehicleId)->orderBy('id','desc')->first();
            \Log::error('$lastMaintenance '.$lastMaintenance);
            if($lastMaintenance){
                $odometer = $rules['odometer'];
                $rules['odometer'] = $odometer.'|min:'.$lastMaintenance->odometer;
            }            
        }
        return $rules;
    }

    /**
     * Get all of the input based value from property fillable  in model and files for the request.
     *
     * @param null|array|mixed $keys
     *
     * @return array
    */
    public function all($keys = null){
        $additionalKeys = ['services', 'spareparts'];
        $keys = (new Maintenance)->fillable;
        $keys = $this->excludeKeys ? array_diff($keys, $this->excludeKeys) : $keys;
        $keys = array_merge($keys, $additionalKeys);
        return parent::all($keys);
    }
}