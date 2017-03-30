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
     */
    public function afterSave(array $old, array $new);

    /**
     * @param array $data
     * @return array
     */
    public function input(array $data): array;
}