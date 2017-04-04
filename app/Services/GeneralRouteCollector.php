<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:51
 */

namespace App\Services;


use App\Contracts\RouteCollector;
use App\Models\Page;
use App\Repositories\LanguageRepository;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GeneralRouteCollector implements RouteCollector
{

    /**
     * @return array
     */
    public function map(): array
    {
        $users = __('general.Users');

        return [
            /*'home' => [
                'text' => __('general.Home'),
                'priority' => 100
            ],*/
            'page' => [
                'text' => __('general.Page'),
                'extended' => true,
                'priority' => 99
            ],

            'login' => [
                'text' => __('user.Auth'),
                'group' => $users,
                'priority' => -97
            ],
            'register' => [
                'text' => __('user.Registration'),
                'group' => $users,
                'priority' => -97
            ],
            'user.profile' => [
                'text' => __('user.My Profile'),
                'group' => $users,
                'priority' => -97
            ],
            'user' => [
                'text' => __('user.Profile'),
                'group' => $users,
                'priority' => -97,
                'extended' => true,
            ],
            'password.request' => [
                'text' => __('auth.Reset Password'),
                'group' => $users,
                'priority' => -97
            ],

            'search' => [
                'text' => __('general.Search'),
                'priority' => -98
            ],

            'language.change' => [
                'text' => __('general.Language'),
                'priority' => -99,
                'extended' => 'select',
            ],

            'filemanager' => [
                'text' => __('filemanager.page_title'),
                'priority' => -100
            ],
        ];
    }

    public function params(): array
    {
        return [
            'page' => 'collectPagePath',
            'page.id' => 'collectPageId',
            'user' => 'collectUsers',
            'language.change' => 'collectLanguages'
        ];
    }

    private function collectPageParams(Collection $data, array $columns)
    {
        $pages = Page::ordered()->select($columns);
        $search = request('q');

        if ($search) {
            $pages->where('title', 'like', '%'.$search.'%');
        }

        $pagination = $pages->paginate(10)->toArray();

        //set lang icons
        if (in_array('lang', $columns)) {
            $iconRoute = route('lang.icon');

            foreach ($pagination['data'] as &$item) {
                if (!$item['lang']) {
                    continue;
                }
                $item['lang'] = sprintf('<img src="%s" alt=""> %s', $iconRoute . '/' .$item['lang'], $item['lang']);
            }

            $data->put('html_columns', ['lang']);
        }

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', __('general.Pages'));
    }

    public function collectPageId(Collection $data)
    {
        $this->collectPageParams($data, ['id', 'title']);

        $data->put('columns', [
            'id' => 'ID',
            'title' => __('general.Title')
        ]);
    }

    public function collectPagePath(Collection $data)
    {
        $this->collectPageParams($data, ['path', 'title', 'lang']);

        $data->put('columns', [
            'title' => __('general.Title'),
            'lang' => __('general.Language'),
            'path' => 'URI',
        ]);
    }

    public function collectUsers(Collection $data)
    {
        $columns = ['id', 'name', 'email'];

        /** @var Builder $items */
        $items = User::orderBy('name')->active()->select($columns);
        $search = request('q');

        if ($search) {
            $items->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        }

        $pagination = $items->paginate(10)->toArray();

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', __('general.Users'));

        $data->put('columns', [
            'id' => 'ID',
            'title' => __('general.Title'),
            'email' => 'E-mail',
        ]);
    }

    public function collectLanguages(Collection $data)
    {
        /** @var LanguageRepository $langRepo */
        $langRepo = resolve(LanguageRepository::class);
        $items = $langRepo->getSelectList();

        $data->put('items', $items);
        $data->put('title', __('general.Language'));
    }
}