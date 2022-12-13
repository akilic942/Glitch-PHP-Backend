<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
 * )
 * Class BaseModel
 *
 * @package App\Models
 */
class BaseModel extends Model {

    protected $_validator = null;

    public function validate($data, array $omitRules = array(), array $addRules = [])
    {
    
        $rules = $this->rules;

        foreach($omitRules as $omit){

            if(isset($rules[$omit])) {
                unset($rules[$omit]);
            }
            
        }

        if($addRules) {
            $rules = $addRules + $rules;
        }
        
       // make a new validator object
       $this->_validator = Validator::make($data, $rules);

       return $this->_validator->passes();

    }

    public function getValidationErrors() {
        return $this->_validator->errors();
    }
}