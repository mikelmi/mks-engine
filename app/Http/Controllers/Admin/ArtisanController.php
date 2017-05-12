<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Contracts\Console\Kernel;

class ArtisanController extends AdminController
{
    private $enabledCommands = [
        'clear-compiled',
        'down',
        'env',
        'help',
        'inspire',
        'list',
        'migrate',
        'optimize',
        'up',
        'app:name',
        'auth:clear-resets',
        'cache:clear',
        //'cache:table',
        'config:cache',
        'config:clear',
        //'db:seed',
        'key:generate',
        //'migrate:install',
        'migrate:rollback',
        'migrate:status',
        'module:cache-clear',
        'module:migrate',
        'module:rollback',
        'queue:failed',
        'queue:flush',
        'queue:forget',
        'route:cache',
        'route:clear',
        'route:list',
        'vendor:publish',
        'view:clear',
        'responsecache:flush'
    ];

    public function index()
    {
        return view('admin.artisan.index');
    }

    public function commands()
    {
        $commands = array_map(function($command) {
            /** @var Command $command */

            $arguments = $options =[];

            $def = $command->getDefinition();

            foreach ($def->getArguments() as $arg) {
                $arguments[] = [
                    'name' => $arg->getName(),
                    'default' => $arg->getDefault(),
                    'is_array' => $arg->isArray(),
                    'required' => $arg->isRequired(),
                    'description' => $arg->getDescription(),
                ];
            }

            foreach ($def->getOptions() as $option) {
                $options[] = [
                    'name' => $option->getName(),
                    'description' => $option->getDescription(),
                    'default' => $option->getDefault(),
                    'accept_value' => $option->acceptValue(),
                    'is_array' => $option->isArray(),
                    'required' => $option->isValueRequired()
                ];
            }

            return [
                'name' => $command->getName(),
                'description' => $command->getDescription(),
                'help' => $command->getHelp(),
                'arguments' => $arguments,
                'options' => $options,
            ];
        }, $this->getCommands());

        return array_values($commands);
    }

    public function run(Request $request)
    {
        $this->validate($request, [
            'command' => 'required'
        ]);

        $command = $this->getCommand($request->get('command'));

        if (!$command) {
            abort(404, 'Command not found');
        }

        $rules = $params = [];

        $def = $command->getDefinition();

        foreach ($def->getArguments() as $arg) {
            $name = $arg->getName();

            if ($arg->isRequired()) {
                $rules['arguments.'.$name] = 'required';
            }

            $value = $request->input('arguments.'.$name);

            if ($value !== null) {
                $params[$name] = $value;
            }
        }

        foreach ($def->getOptions() as $opt) {
            $name = $opt->getName();

            if ($opt->isValueRequired()) {
                $rules['options.'.$name] = 'required';
            }

            $value = $request->input('options.'.$name);

            if (!$opt->acceptValue()) {
                $params['--'.$name] = (bool) $value;
            } elseif ($value !== null) {
                $params['--'.$name] = $value;
            }
        }

        $this->validate($request, $rules);

        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);

        $kernel->call($command->getName(), $params);

        if ($request->get('flash')) {
            $this->flashInfo($kernel->output());
        }

        return $kernel->output();
    }

    /**
     * @return Command[]
     */
    private function getCommands()
    {
        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);

        return array_only($kernel->all(), $this->enabledCommands);
    }

    /**
     * @param string $name
     * @return Command|null
     */
    private function getCommand($name)
    {
        return array_get($this->getCommands(), $name);
    }
}