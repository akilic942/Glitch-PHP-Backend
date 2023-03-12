<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="BaseSocialFields",
 *     title="Basie Social Model Model",
 *     description="The base model for social that is used on several models.",
 *      @OA\Property(
 *          property="twitter_page",
 *          description="The URL to the users Twitter Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="facebook_page",
 *          description="The URL to the users Facebook Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="instagram_page",
 *          description="The URL to the users Instagram Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="snapchat_page",
 *          description="The URL to the users Snapchat Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="tiktok_page",
 *          description="The URL to the users Tiktok Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="twitch_page",
 *          description="The URL to the users Twitch Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="youtube_page",
 *          description="The URL to the users Youtube Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="paetron_page",
 *          description="The URL to the users Paetron Page.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="twitter_handle",
 *          description="The URL to the users Twitter Handle.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="facebook_handle",
 *          description="The URL to the users Facebook Handle.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="instagram_handle",
 *          description="The URL to the users Instagram Handle.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="snapchat_handle",
 *          description="The URL to the users Snapchat Handle.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="tiktok_handle",
 *          description="The URL to the users Tiktok Handle.",
 *          type="string",
 *          maxLength=255,
 *     ),
 *      @OA\Property(
 *          property="twitch_handle",
 *          description="The URL to the users Twitch Handle.",
 *          type="string",
 *          maxLength=255
 *     ),
 *      @OA\Property(
 *          property="youtube_handle",
 *          description="The URL to the users Youtube Handle.",
 *          type="string",
 *          maxLength=255
 *     ),
 *      @OA\Property(
 *          property="paetron_handle",
 *          description="The URL to the users Paetron Handle.",
 *          type="string",
 *          maxLength=255
 *     )
 * )

 */



class BaseModel extends Model
{

    protected $_validator = null;

    public function validate($data, array $omitRules = array(), array $addRules = [])
    {

        $rules = $this->rules;

        foreach ($omitRules as $omit) {

            if (isset($rules[$omit])) {
                unset($rules[$omit]);
            }
        }

        if ($addRules) {
            $rules = $addRules + $rules;
        }

        // make a new validator object
        $this->_validator = Validator::make($data, $rules);

        return $this->_validator->passes();
    }

    public function getValidationErrors()
    {
        return $this->_validator->errors();
    }
}
