<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\KontakAduan;
use App\Models\SiteSetting;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KontakController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::instance();
        return view('public.kontak.index', compact('setting'));
    }

    public function kirim(Request $request)
    {
        // Instansiasi rule terpisah agar bisa mengambil score setelah validasi
        $recaptchaRule = new RecaptchaRule(
            action:    'kontak_kirim',
            threshold: 0.5,
        );

        $validator = Validator::make($request->all(), [
            'nama_pengadu'         => ['required', 'string', 'max:200'],
            'email'                => ['required', 'email', 'max:200'],
            'no_telp'              => ['nullable', 'string', 'max:30'],
            'subjek'               => ['required', 'string', 'max:300'],
            'isi_aduan'            => ['required', 'string', 'max:5000'],
            'g-recaptcha-response' => ['required', 'string', $recaptchaRule],
        ], [
            'nama_pengadu.required'         => 'Nama lengkap wajib diisi.',
            'email.required'                => 'Alamat email wajib diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'subjek.required'               => 'Subjek pesan wajib diisi.',
            'isi_aduan.required'            => 'Isi pesan wajib diisi.',
            'isi_aduan.max'                 => 'Isi pesan tidak boleh melebihi 5.000 karakter.',
            'g-recaptcha-response.required' => 'Verifikasi keamanan tidak ditemukan. Pastikan JavaScript diaktifkan.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali isian formulir.');
        }

        // Simpan aduan ke database — termasuk skor reCAPTCHA untuk audit spam
        KontakAduan::create([
            'nama_pengadu'    => strip_tags($request->nama_pengadu),
            'email'           => $request->email,
            'no_telp'         => $request->no_telp ? strip_tags($request->no_telp) : null,
            'subjek'          => strip_tags($request->subjek),
            'isi_aduan'       => strip_tags($request->isi_aduan),
            'status'          => KontakAduan::STATUS_BARU,
            'recaptcha_score' => $recaptchaRule->getScore(),   // ← skor audit dari rule
            'ip_address'      => $request->ip() ?? '0.0.0.0',
        ]);

        return redirect()
            ->route('kontak')
            ->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
