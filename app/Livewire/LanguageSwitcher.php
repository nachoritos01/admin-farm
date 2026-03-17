<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale = 'es';

    public function mount(): void
    {
        $user = auth()->user();
        $this->locale = ($user ? $user->locale : null) ?? config('app.locale') ?? 'es';
    }

    public function switchLocale(string $locale): void
    {
        if (! in_array($locale, config('app.available_locales', ['es', 'en']))) {
            return;
        }

        $user = auth()->user();
        if ($user) {
            $user->update(['locale' => $locale]);
        }

        app()->setLocale($locale);
        $this->locale = $locale;

        $this->redirect(request()->header('Referer', '/admin'));
    }

    public function render(): View
    {
        return view('livewire.language-switcher');
    }
}
