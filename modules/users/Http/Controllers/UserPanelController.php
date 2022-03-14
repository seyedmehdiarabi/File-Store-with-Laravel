<?php

namespace Modules\users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\users\Models\User;

class UserPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return CView('users::profile.index', ['user' => $user]);
    }

    public function edit_profile(Request $request)
    {
        if (request()->get('request-type') == 'axios') {
            $user = User::findOrFail(Auth::user()->id);
            if ($request->get('image') == 'delete') {
                $user->pic_profile = null;
                remove_file($user->pic_profile, 'upload');
                $user->update();
                $message = 'حذف عكس نمايه با موفقيت انجام شد';
            } else {
                $image_url = upload_file($request, 'image', 'upload');
                if (!empty($image_url)) {
                    remove_file($user->pic_profile, 'upload');
                    $user->pic_profile = $image_url;
                }
                if ($user->update($request->all())) {
                    $message = 'تغييرات با موفقيت اعمال شدند.';
                } else {
                    $message = 'عمليات با خطا رو به رو شد.';
                }
            }
            return [
                'message' => $message,
            ];
        }
    }

}
