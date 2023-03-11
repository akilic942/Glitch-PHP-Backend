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
 *          title="twitter_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="facebook_page",
 *          description="The URL to the users Facebook Page.",
 *          title="facebook_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="instagram_page",
 *          description="The URL to the users Instagram Page.",
 *          title="instagram_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="snapchat_page",
 *          description="The URL to the users Snapchat Page.",
 *          title="snapchat_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="tiktok_page",
 *          description="The URL to the users Tiktok Page.",
 *          title="tiktok_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="twitch_page",
 *          description="The URL to the users Twitch Page.",
 *          title="twitch_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="youtube_page",
 *          description="The URL to the users Youtube Page.",
 *          title="youtube_page",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="paetron_page",
 *          description="The URL to the users Paetron Page.",
 *          title="paetron_page",
 *          type="string"
 *     ),
 * 
 * 
 *      @OA\Property(
 *          property="twitter_handle",
 *          description="The URL to the users Twitter Handle.",
 *          title="twitter_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="facebook_handle",
 *          description="The URL to the users Facebook Handle.",
 *          title="facebook_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="instagram_handle",
 *          description="The URL to the users Instagram Handle.",
 *          title="instagram_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="snapchat_handle",
 *          description="The URL to the users Snapchat Handle.",
 *          title="snapchat_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="tiktok_handle",
 *          description="The URL to the users Tiktok Handle.",
 *          title="tiktok_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="twitch_handle",
 *          description="The URL to the users Twitch Handle.",
 *          title="twitch_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="youtube_handle",
 *          description="The URL to the users Youtube Handle.",
 *          title="youtube_handle",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="paetron_handle",
 *          description="The URL to the users Paetron Handle.",
 *          title="paetron_handle",
 *          type="string"
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
