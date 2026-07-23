<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah berlangganan.',
        ]);

        if ($validator->fails()) {
            return back()->with('subscriber_error', $validator->errors()->first('email'))->withFragment('newsletter');
        }

        Subscriber::create(['email' => $request->email]);

        return back()->with('subscriber_success', 'Terima kasih telah berlangganan berita dari LAMR Bengkalis!')->withFragment('newsletter');
    }
}
