<?php

namespace App\Widgets;


use App\Models\Menu;
use App\Models\MenuItem;
use App\Presenters\ListMenuPresenter;
use App\Presenters\MenuPresenterInterface;
use App\Presenters\NavbarMenuPresenter;
use App\Presenters\NavInlineMenuPresenter;
use App\Presenters\NavMenuPresenter;
use App\Presenters\PillsMenuPresenter;
use App\Presenters\PillsStackedMenuPresenter;
use App\Presenters\TabsMenuPresenter;
use Illuminate\Http\Request;

class MenuWidget extends WidgetBase implements WidgetInterface
{
    protected $presenters = [
        'nav' => NavMenuPresenter::class,
        'navbar' => NavbarMenuPresenter::class,
        'nav-inline' => NavInlineMenuPresenter::class,
        'nav-tabs' => TabsMenuPresenter::class,
        'pills' => PillsMenuPresenter::class,
        'pills-stacked' => PillsStackedMenuPresenter::class,
        'list' => ListMenuPresenter::class,
    ];

    public function getPresentersList()
    {
        /**
         * @var MenuPresenterInterface $class
         */
        foreach ($this->presenters as $key => $class) {
           yield $key => $class::title();
        }
    }

    /**
     * @return string
     */
    public static function title()
    {
        return trans('a.Menu');
    }

    public function form()
    {
        $menu = Menu::ordered()->get();

        return view('admin.widget.form.menu', [
            'model' => $this->model,
            'menu' => $menu,
            'presenters' => $this->getPresentersList()
        ]);
    }

    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }

    public function beforeSave(Request $request)
    {
        $this->model->content = $request->input('content');
    }
    
    public function render()
    {
        if (!$this->model->content) {
            return;
        }

        $type = array_get($this->presenters, $this->model->param('type', ''));

        $presenter = $this->makePresenter($type);

        $items = MenuItem::getTree($this->model->content);

        $items = $presenter->render($items, ['class' => $this->model->param('css_class')]);

        return $this->view('widget.menu', [
            'items' => $items
        ])->render();
    }

    /**
     * @param string $className
     * @return MenuPresenterInterface
     * @throws \InvalidArgumentException
     */
    protected function makePresenter($className)
    {
        if (!$className) {
            $className = NavMenuPresenter::class;
        }

        if (class_exists($className)) {
            $presenter = new $className();
            if ($presenter instanceof MenuPresenterInterface) {
                return $presenter;
            }
        }

        throw new \InvalidArgumentException('Invalid Menu Presenter "' . $className . '"');
    }
}