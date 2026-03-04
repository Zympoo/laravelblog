@php
    /**
     * Deze partial wordt gebruikt door create én later edit.
     *
     * old('field') bevat vorige input na validation error.
     * old('field', fallback) => old wint, anders fallback.
     *
     * $user kan null zijn (create) of een User model (edit).
     */
@endphp

<div class="row g-3">

    {{-- ========================= NAME ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Name</label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $user?->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            autocomplete="name"
        >

        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= EMAIL ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Email</label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $user?->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror"
            autocomplete="email"
        >

        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= ROLE ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Role</label>

        <select name="role_id"
                class="form-select @error('role_id') is-invalid @enderror">

            <option value="">Select role</option>

            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    @selected((string) old('role_id', $user?->role_id ?? '') === (string) $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach

        </select>

        @error('role_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= STATUS ========================= --}}
    <div class="col-12 col-md-3">
        <label class="form-label">Status</label>

        <select name="is_active"
                class="form-select @error('is_active') is-invalid @enderror">

            <option value="1"
                @selected((string) old('is_active', $user?->is_active ?? '1') === '1')>
                Active
            </option>

            <option value="0"
                @selected((string) old('is_active', $user?->is_active ?? '1') === '0')>
                Inactive
            </option>

        </select>

        @error('is_active')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= VERIFIED ========================= --}}
    <div class="col-12 col-md-3">
        <label class="form-label">Verified</label>

        <div class="form-check mt-2">
            <input
                class="form-check-input @error('verified') is-invalid @enderror"
                type="checkbox"
                name="verified"
                value="1"
                id="verified"
                @checked(old('verified', $user?->email_verified_at ? 1 : 0))
            >

            <label class="form-check-label" for="verified">
                Mark as verified
            </label>

            @error('verified')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>


    {{-- ========================= PASSWORD ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Password</label>

        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            autocomplete="new-password"
        >

        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(!empty($isEdit))
            <div class="form-text">
                Leave blank to keep the current password.
            </div>
        @endif
    </div>


    {{-- ========================= CONFIRM PASSWORD ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Confirm password</label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            autocomplete="new-password"
        >

        @error('password_confirmation')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- ========================= ACTIONS ========================= --}}
    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">
            {{ $submitLabel ?? 'Save' }}
        </button>

        <a class="btn btn-outline-secondary"
           href="{{ route('backend.users.index') }}">
            Cancel
        </a>
    </div>

</div>
