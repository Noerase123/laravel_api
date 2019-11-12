<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Transformers\User;

use App\Models\User;
use App\Support\Resource\ResourceItem;
use App\Models\SocialAccount;
use League\Fractal\TransformerAbstract;


class UserCountTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($user)
    {
        return [
            'number_of_users' => $user,
        ];
    }

}
