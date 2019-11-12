<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Listeners\Api\Family;

use App\Models\User;
use App\Models\Family;
use Illuminate\Http\Request;
use App\Events\Api\User\UserCreated;
use App\Repository\FamilyRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserToFamilyGroup
{
    /**
     * request instance
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * family repository
     *
     * @var \App\Repository\FamilyRepository
     */
    protected $families;

    /**
     * Create the event listener.
     *
     * @param  \Illuminate\Http\Request         $request
     * @param  \App\Repository\FamilyRepository $families
     */
    public function __construct(Request $request, FamilyRepository $families)
    {
        $this->request = $request;
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
        // do nothing if not a valid family member
        if ($event->user->type != User::USER_TYPE_FAMILY_MEMBER) {
            return;
        }

        $family = $this->families->findFamilyByCode($this->request->familyInvitationCode);

        // if a family was found from code then add the current created user
        if ($family) {
            $this->families->addMemberToFamily($family, $event->user);
        }
    }
}
