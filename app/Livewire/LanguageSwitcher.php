<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public function mount()
    {
        $this->currentLocale = auth()->user()->locale ?? app()->getLocale();
    }

    public function setLocale(string $locale)
    {
        if (! in_array($locale, ['id', 'en'])) {
            return;
        }

        auth()->user()->update(['locale' => $locale]);
        app()->setLocale($locale);
        $this->currentLocale = $locale;

        return redirect(url()->previous());
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
