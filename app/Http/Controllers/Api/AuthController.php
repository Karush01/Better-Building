<?php

namespace App\Http\Controllers\Api;


use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{

   public function register(Request $request){

       $validateData = $request->validate([
           'name' => 'required|max:55',
           'email' => 'required|unique:users',
           'password' => 'required|confirmed',
       ]);
       $validateData['password'] = bcrypt($request->password);
       $user = User::create($validateData);

       $accessToken = $user->createToken('authToken')->accessToken;

       return response([
           'user' => $user,
           'access_token' => $accessToken,
       ]);
   }

    public function login(Request $request){

        $loginData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)){
            return response([
               'message' => 'invalid username or password'
            ]);
        }
        $user = User::where('email',$loginData['email'])->first();

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        if($user) {
            $user->access_token = $accessToken;
            $user->save();
        }
        return response([
            'user' => $user,
        ]);
    }

    protected function guard()
    {
        return Auth::guard('api');
    }

    public function logout(Request $request)
    {
        if (!$this->guard()->check()) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }
        $request->user('api')->token()->revoke();

        Auth::guard()->logout();
        Session::flush();

        return response()->json([
            'message' => 'User Logged Out',
        ], 200);
    }

//    public function resetPassword(Request $request)
//    {
//        $rules = [
//            'password' => 'required|min:6',
//        ];
//
//        $validator = \Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'error' => $validator->messages(),
//            ]);
//        } else {
//            $user = User::where('email', $request->input('check_email'))->first();
//
//            if ($user->email === $request->input('check_email')) {
//                $postArray = [
//                    'password' => bcrypt($request->input('password')),
//                ];
//                $user->update($postArray);
//                return [
//                    'message' => 'success',
//                ];
//            } else {
//                return response()->json([
//                    'error' => 'invalid email',
//                ], 401);
//            }
//        }
//    }
//
//    public function deletePasswordAndToken(Request $request)
//    {
//        $reset_password = DB::table('password_resets')->where('token', $request->input('token'))->first();
//
//        if (!empty($reset_password)) {
//            if ($reset_password->token === $request->input('token')) {
//                DB::table('password_resets')->where('token', $request->input('token'))->delete();
//                return ['message' => 'access'];
//            }
//        } else {
//            return ['error' => 'exit'];
//        }
//    }
//
//    public function forgotPassword(Request $request)
//    {
//        $token = Str::random(15);
//        $v = \Validator::make($request->all(), [
//            'email' => 'required|email'
//        ]);
//
//        if ($v->fails()) {
//            return response()->json([
//                'error' => $v->messages(),
//            ]);
//        }
//
//        $userEmail = strtolower($request->input('email'));
//        $user = User::where('email',$userEmail)->first();
//
//        if (!$user) {
//            return [
//                'error' => 'User not found',
//            ];
//        }
//
//        $postArray = [
//            'email' => $userEmail,
//            'token' => $token,
//            'created_at' => Carbon::now(),
//        ];
//
//        DB::table('password_resets')->insert($postArray);
//
////        \Mail::send('emails.forgot', ['email' => $userEmail, 'token' => $this->ApiToken],
////            function ($message) use ($userEmail) {
////                $message->to($userEmail)->subject('Запрос на сброс пароля');
////            });
//        $link = 'http://dev1.gp01.ru/' . $request->input('email') . '/' .$token;
//        $values = [
//            "ICON" => "lock",
//            "TITLE" => MailHelperFields::$user_passwordRestore,
//            "TEXT" => MailHelperFields::$user_toChangePass,
//            "BUTTON_TEXT" => MailHelperFields::$user_restorePassword,
//            "BUTTON_LINK" => $link,
//            "BUTTON_COMMENT" => MailHelperFields::$restore_orFollowLink . ' ' . $link
//        ];
//
//        EmailSender::sendEmail($user->email, $values["TITLE"], "davinchi.text_button", $values);
//        return [
//            'message' => 'password forgot',
//        ];
//    }
}
