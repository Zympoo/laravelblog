<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('frontend.contact');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Mail::to('warre.neirinck@gmail.com')
            ->send(new ContactMail($data));

        return redirect()
            ->route('frontend.contact')
            ->with('status', 'Bericht succesvol verzonden.');
    }
}
