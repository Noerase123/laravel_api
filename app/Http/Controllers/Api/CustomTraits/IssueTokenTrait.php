<?php
/**
 * @author Jeselle Bacosmo <jeselle@circus.ac>
 */

namespace App\Http\Controllers\Api\CustomTraits;

use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;

trait IssueTokenTrait{

    public function issueToken(Request $request, $grantType, $scope = "*"){
		$params = [
    		'grant_type' => $grantType,
    		'client_id' => $this->client->id,
    		'client_secret' => $this->client->secret,
    		'scope' => $scope
        ];

        if($grantType !== 'social'){
            $params['username'] = $request->username ?: $request->email;
        }

        $request->request->add($params);

    	$proxy = Request::create('oauth/token', 'POST');
    	return Route::dispatch($proxy);
	}
}