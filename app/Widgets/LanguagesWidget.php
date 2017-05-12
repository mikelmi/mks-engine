<?php

namespace App\Widgets;


use App\Models\Language;
use App\Repositories\LanguageRepository;
use Illuminate\Support\Collection;

class LanguagesWidget extends WidgetPresenter
{
    public function render(): string
    {
        /** @var Collection $languages */
        $languages = resolve(LanguageRepository::class)->enabled();

        if ($languages->isEmpty()) {
            return '';
        }

        if (!($current = $this->getCurrentLanguage())) {
            $current = $languages->first();
        }

        return $this->view('widget.languages', compact('languages', 'current'))->render();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.Languages');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'languages';
    }

    /**
     * @return Language|null
     */
    protected function getCurrentLanguage()
    {
        return resolve(LanguageRepository::class)->get(app()->getLocale());
    }
}