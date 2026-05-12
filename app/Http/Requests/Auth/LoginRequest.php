<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $roleHint = $this->input('role_hint');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'id_based';

        if ($field === 'id_based') {
            $query = \App\Models\User::query();
            
            if ($roleHint === 'student') {
                $query->whereHas('studentProfile', function($q) use ($login) {
                    $q->where('enrollment_number', $login);
                });
            } elseif ($roleHint === 'teacher') {
                $query->whereHas('teacherProfile', function($q) use ($login) {
                    $q->where('teacher_id', $login);
                });
            } elseif ($roleHint === 'parent') {
                // Logic: Find the student, then find the parent linked to that student
                $studentProfile = \App\Models\StudentProfile::where('enrollment_number', $login)->first();
                if ($studentProfile) {
                    $parentProfile = $studentProfile->parents()->first();
                    if ($parentProfile) {
                        $user = $parentProfile->user;
                    }
                }
            } else {
                // Fallback: Check both student and teacher IDs
                $query->where(function($q) use ($login) {
                    $q->whereHas('studentProfile', fn($sq) => $sq->where('enrollment_number', $login))
                      ->orWhereHas('teacherProfile', fn($tq) => $tq->where('teacher_id', $login));
                });
            }

            if (!isset($user)) {
                $user = $query->first();
            }
            
            if ($user && Auth::attempt(['email' => $user->email, 'password' => $this->input('password')], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } else {
            $credentials = ['email' => $login, 'password' => $this->input('password')];
            if ($roleHint && in_array($roleHint, ['admin', 'teacher', 'student', 'parent'])) {
                $credentials['role'] = $roleHint;
            }

            if (Auth::attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
