<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Listeners\Api\Family;

use App\Models\User;
use App\Models\Family;
use App\Repository\FamilyRepository;
use App\Events\Api\User\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFamilyForOfw
{
    /**
     * family repository
     *
     * @var \App\Repository\FamilyRepository
     */
    protected $families;

    /**
     * Create the event listener.
     *
     * @param  \App\Repository\FamilyRepository $families
     */
    public function __construct(Request $request, FamilyRepository $families)
    {
        $this->families = $families;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Api\User\UserCreated  $event
     *
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        // do not continue if current user is not an ofw
        if ($user->isFamilyMember()) {
            return;
        }

        // create a new family with a code
        $family = $this->families->createCode();

        // add this ofw to the family members
       $this->families->addMemberToFamily($family, $user);
    }
}
