<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;

class BaseAuthModel extends Authenticatable {

    public function validate($data, array $omitRules = array(), array $addRules = [])
    {
    
        $rules = $this->rules;

        foreach($omitRules as $omit){

            if(isset($rules[$omit])) {
                unset($rules[$omit]);
            }
            
        }

        if($addRules) {
            $rules += $addRules;
        }

       // make a new validator object
       $this->_validator = Validator::make($data, $rules);

       return $this->_validator->passes();

    }

    public function getValidationErrors() {
        return $this->_validator->errors();
    }
}