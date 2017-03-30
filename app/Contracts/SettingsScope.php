<?php
/**
 * Author: mike
 * Date: 27.03.17
 * Time: 20:52
 */

namespace App\Contracts;


interface SettingsScope
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function title(): string;

    /**
     * @return array
     */
    public function fields(): array;

    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @param array $old
     * @param array $new
     * @return mixed
     */
    public function afterSave(array $old, array $new);
}