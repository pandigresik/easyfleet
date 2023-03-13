<?php

namespace App\Http\Requests\API\Fleet;

use App\Models\Fleet\VehicleDocument;
use InfyOm\Generator\Request\APIRequest;

class UpdateVehicleDocumentAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = VehicleDocument::$rules;
        
        return $rules;
    }
}
