@props([
/**
* Session keys die we als "flash messages" willen ondersteunen.
* Dit laat controllers toe om semantisch te werken:
* ->with('success', '...')
* ->with('error', '...')
*
* Je kan dit later uitbreiden (bv. 'status'), zonder je component te
herschrijven.
*/
'keys' => ['success', 'error', 'warning', 'info'],
])
@php
    /**
    * Bootstrap heeft vaste alert types:
    * success | danger | warning | info | secondary
    *
    * Veel Laravel devs gebruiken 'error' als key, maar Bootstrap noemt dat
    'danger'.
    * Daarom mappen we:
    * error -> danger
    *
    * Zo blijft je controller-code logisch en leesbaar.
    */
    $typeMap = [
    'success' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    'info' => 'info',
    ];
@endphp
{{--
========================================================================= -
-}}
{{-- 1) VALIDATIEFOUTEN (errors bag) --}}
{{--
========================================================================= -
-}}
{{--
Als je FormRequest gebruikt, valideert Laravel vóór je controller
methode.
Bij failure:
- redirect automatisch terug naar het formulier
- $errors wordt gevuld
- old() wordt gevuld (input blijft bewaard)
Dit is dus NIET hetzelfde als session('error'), maar een aparte errors
container.
--}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>
            <i class="fas fa-triangle-exclamation me-1"></i>
            Please fix the following errors:
        </strong>
        {{-- Alle foutmeldingen in één overzicht --}}
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        {{-- Bootstrap close button --}}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>
    </div>
@endif
{{--
========================================================================= -
-}}
{{-- 2) SESSION FLASH MESSAGES --}}
{{--
========================================================================= -
-}}
{{--
Flash messages worden gezet vanuit controllers:
- success: na geslaagde actie (create/update/delete)
- error: bij business error (DB issue, exception, ...)
- warning/info: optioneel voor UX
--}}
@foreach ($keys as $key)
    @if (session()->has($key))
        @php
            $bootstrapType = $typeMap[$key] ?? 'secondary';
            $value = session()->get($key);
            $messages = is_array($value) ? $value : [$value];
            // HARD FIX: verwijder na render zodat het zeker niet blijft hangen
            session()->forget($key);
        @endphp
        <div class="alert alert-{{ $bootstrapType }} alert-dismissible fade show" role="alert">
            @if (count($messages) === 1)
                {{ $messages[0] }}
            @else
                <ul class="mb-0">
                    @foreach ($messages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif
@endforeach
