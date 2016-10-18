<?php

namespace App\Widgets;

use App\Presenters\DropdownLanguagesPresenter;
use App\Presenters\DropdownNavbarLanguagesPresenter;
use App\Presenters\MenuPresenterInterface;
use App\Presenters\NavLanguagesPresenter;
use App\Presenters\SelectLanguagesPresenter;
use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;

class LanguagesWidget extends MenuWidget implements WidgetInterface
{
    public function __construct()
    {
        $this->presenters['dropdown'] = DropdownLanguagesPresenter::class;
        $this->presenters['dropdown_navbar'] = DropdownNavbarLanguagesPresenter::class;
        $this->presenters['select'] = SelectLanguagesPresenter::class;
    }

    /**
     * @return string
     */
    public static function title()
    {
        return trans('a.Languages');
    }

    public function form()
    {
        return view('admin.widget.form.languages', [
            'model' => $this->model,
            'presenters' => $this->getPresentersList()
        ]);
    }

    public function rules()
    {
        return [];
    }

    public function beforeSave(Request $request)
    {
        
    }

    public function render()
    {
        $type = $this->model->param('type');

        $presenter = $this->makePresenter($type);

        $languages = app(LanguageRepository::class)->enabled();

        $items = $presenter->render($languages, ['class' => $this->model->param('css_class')]);

        return $this->view('widget.languages', [
            'items' => $items
        ])->render();
    }

    /**
     * @param string $type
     * @return MenuPresenterInterface
     * @throws \InvalidArgumentException
     */
    protected function makePresenter($type)
    {
        $className = $type ? array_get($this->presenters, $type) : null;
        $defaultClassName = NavLanguagesPresenter::class;

        if (!$className) {
            $className = $defaultClassName;
        }

        if (class_exists($className)) {
            if ($className != $defaultClassName) {
                $reflect = new \ReflectionClass($className);
                if ($reflect->isSubclassOf(NavLanguagesPresenter::class)) {
                    $defaultClassName = $className;
                }
            }

            $options = [
                'locale' => app()->getLocale(),
            ];

            if ($type && $className != $defaultClassName) {
                $options = array_merge($options, call_user_func([$className, 'options']));
            }

            return new $defaultClassName($options);
        }

        throw new \InvalidArgumentException('Invalid Menu Presenter "' . $className . '"');
    }
}