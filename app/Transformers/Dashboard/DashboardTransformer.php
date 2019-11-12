<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Transformers\Dashboard;

use App\Support\Resource\ResourceItem;
use League\Fractal\TransformerAbstract;

class DashboardTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($dashboard)
    {
        return [
            'total_users' => $dashboard['countUsers'],
            'users' => $dashboard['totalUsers'],
            'total_investment' => $dashboard['totalInvestments'],
            'total_visions' => $dashboard['totalVisions'],
            'visions' => $dashboard['visions'] ,
        ];
    }

}
