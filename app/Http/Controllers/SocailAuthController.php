<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class SocailAuthController extends Controller
{
    const SUPPORTED_PROVIDERS = [
        'facebook',
        'google'
    ];

    private static function checkSupportedProvider(string $provider) {
        // check if provider is supported
        if (!in_array(
            $provider,
             self::SUPPORTED_PROVIDERS,
            true
            )) {
            throw new \InvalidArgumentException("Unsupported provider '{$provider}'");
        }
    }

    public function redirectToSocial(string $provider)
    {
        static::checkSupportedProvider($provider);
        return Socialite::driver($provider)->redirect();
    }


    public function handleSocialCallback(Request $request, string $provider)
    {
        static::checkSupportedProvider($provider);
        // check the presense of the 'code'
        if (!$request->input('code')) {
            return redirect('login')->withErrors('Login failed: '.$request->input('error').' - '.$request->input('error_reason'));
        }

        // resolve provider related db column
        $provider_column = "{$provider}_id";

        try {
            $socialUser = Socialite::driver($provider)->user();         
            $exisitedUser = User::where($provider_column, $socialUser->id)->first();
         
            if ($exisitedUser) {
                Auth::login($exisitedUser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::updateOrCreate(['email' => $socialUser->email],[
                        'name' => $socialUser->name,
                        $provider_column=> $socialUser->id,
                    ]);
    
                Auth::login($newUser);    
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}
