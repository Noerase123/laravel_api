<?php

namespace App\Http\Resources\Vision;

use App\Models\Vision;
use App\Support\Resource\ResourceItem;


class VisionResource extends ResourceItem
{
    /**
     * create instance
     *
     * @param \App\Models\User $user
     */
    public function __construct(Vision $vision)
    {
        parent::__construct($vision);
    }

    /**
     * {@inheritDoc}
     */
    public function transform($vision)
    {
        return [
            'id' => $vision->getKey(),
            'author_id' => $vision->author_id,
            'target_amount' => $vision->target_amount,
            'target_cat_name' => $vision->target_cat_name,
            'target_cat_id' => $vision->target_cat_id,
            'target_date' => $vision->target_date,
            'description' => $vision->description,
            'family_code' => $vision->family_code,
        ];
    }

}
