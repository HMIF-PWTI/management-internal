<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function registerAction(): Action
    {
        return Action::make('register')
            ->button()
            ->outlined()
            ->color('primary')
            ->size('sm')
            ->label('Sign up for an account')
            ->url(filament()->getRegistrationUrl());
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('filament-panels::auth/pages/login.multi_factor.subheading');
        }

        if (! filament()->hasRegistration()) {
            return null;
        }

        return new HtmlString(
            '<div class="mt-3 flex justify-center">' .
            $this->registerAction->toHtml() .
            '</div>'
        );
    }
}
