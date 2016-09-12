<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12.09.16
 * Time: 12:44
 */

namespace App\Services;


use App\Events\WidgetTypesCollect;
use App\Widgets\WidgetInterface;
use Illuminate\Support\Collection;

class WidgetManager
{
    /**
     * @var Collection
     */
    private $types;

    /**
     * @return Collection
     */
    public function getTypes()
    {
        if (!($this->types instanceof Collection)) {

            $this->types = new Collection();

            $dir = app_path('Widgets');

            foreach (glob($dir . '/*.php') as $file) {
                $className = '\App\Widgets\\' . basename($file, '.php');
                $class = new \ReflectionClass($className);

                if (!$class->isInterface() && !$class->isAbstract()
                    && $class->implementsInterface(WidgetInterface::class)
                ) {
                    $this->types->put($class->getName(), $className::title());
                }
            }

            event(new WidgetTypesCollect($this->types));
        }

        return $this->types;
    }

    /**
     * @param string $class
     * @return WidgetInterface
     * @throws \Exception
     */
    public static function make($class)
    {
        $class = str_replace('/', '\\', $class);

        if (!class_exists($class)) {
            throw new \Exception('Class \'' . $class. '\' not found');
        }

        /** @var WidgetInterface $widget */
        $widget = new $class();

        if (!($widget instanceof WidgetInterface)) {
            throw new \Exception('Class \'' . $class. '\' does not implement widget interface');
        }

        return $widget;
    }

    public function title($type, $default = null)
    {
        return $this->getTypes()->get($type, $default);
    }
}