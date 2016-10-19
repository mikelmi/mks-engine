<?php

namespace App\Traits;


trait MoreText
{
    public function getTextAttribute($value) {
        $result = $this->intro_text;
        if (trim($this->full_text)) {
            $result .= '<!--more-->' . $this->full_text;
        }
        return $result;
    }

    public function setTextAttribute($value) {
        if (preg_match('/(.+)<!--more-->(<\/[^>]+>)?(.+)/s', $value, $m)) {
            $this->intro_text = str_replace('<!--more-->', '', $m[1]).$m[2];
            $this->full_text = str_replace('<!--more-->', '', $m[3]);
        } else {
            $this->full_text = str_replace('<!--more-->', '', $value);
        }
    }
}