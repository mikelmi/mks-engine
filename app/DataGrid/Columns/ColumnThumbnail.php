<?php

namespace App\DataGrid\Columns;


use Mikelmi\MksAdmin\DataGrid\Columns\Column;

class ColumnThumbnail extends Column
{
    protected $searchType = '';
    
    protected $preset = '';

    protected function cell(): string
    {
        $v = 'row.'.$this->getKey();

        return sprintf(
            '<div ng-if="%s" style="width: 100px">
				<img ng-if="%1$s" ng-src="%s/{{%1$s}}%s" class="img-thumbnail img-fluid" />
			</div>',
            $v,
            route('thumbnail'),
            $this->preset ? '?p='.$this->preset : ''
        );
    }

    public function setPreset(string $preset)
    {
		$this->preset = $preset;
		return $this;
	}
}
