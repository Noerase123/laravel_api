<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace  App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\CustomTraits\IssueTokenTrait;
use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Passport\Client;
use App\Http\Requests\Api\User\StoreUserRequest;

use App\Repository\UserRepository;
use App\Events\Api\User\UserCreated;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use App\Support\Contracts\ServerErrorType;
use Illuminate\Http\Exceptions\HttpResponseException;


class SocialAuthController extends Controller
{
    use IssueTokenTrait;
    private $client;

    public function __construct(UserRepository $users)
    {
        $this->client = Client::find(1);
        $this->users = $users;
    }

    public function socialAuth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'provider' => 'required|in:facebook,google',
            'provider_user_id' => 'required'
        ]);

        $socialAccount = SocialAccount::where('provider', $request->provider)->where('provider_user_id', $request->provider_user_id)->first();

        if($socialAccount)
        {
            return $this->issueToken($request, 'social');
        } else {
            return response()->json("Invalid Account");
        }

    }

}
