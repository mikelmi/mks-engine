<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 20.09.16
 * Time: 18:42
 */

namespace App\Services;


use Anhskohbo\NoCaptcha\NoCaptcha;
use Mews\Captcha\Captcha;

class CaptchaManager
{
    /**
     * @var
     */
    private $captcha;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * CaptchaManager constructor.
     * @param $captcha
     * @param Settings $settings
     */
    public function __construct($captcha, Settings $settings)
    {
        $this->captcha = $captcha;
        $this->settings = $settings;
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return $this->captcha != null;
    }

    /**
     * @return array
     */
    public function rules()
    {
        if ($this->captcha) {
            if ($this->captcha instanceof Captcha) {
                return [
                    'captcha' => 'required|captcha',
                ];
            }

            if ($this->captcha instanceof NoCaptcha) {
                return [
                    'g-recaptcha-response' => 'required|captcha'
                ];
            }
        }

        return [];
    }

    /**
     * @return string
     */
    public function display($withInput = false)
    {
        $result = null;

        if ($this->captcha) {
            if ($this->captcha instanceof Captcha) {
                $template = $this->settings->get('captcha.config.template', 'default');
                $result = $this->captcha->img($template);
                if ($withInput) {
                    $height = config('captcha.'.$template.'.height');
                    $styleHeight = 'height:'.($height ? ($height+2).'px':'auto');
                    $result = strtr(
                        '<div class="input-group captcha-row">
                            <span class="input-group-addon" style="padding:0">{img}</span>
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="button" style="{height}" data-toggle="captcha-refresh" data-url="{refresh-url}">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </span>
                            {input}
                        </div>',
                        [
                            '{img}' => $result,
                            '{input}' => $this->inputField(['class'=>'form-control', 'style'=>$styleHeight]),
                            '{height}' => $styleHeight,
                            '{refresh-url}' => route('captcha.image')
                        ]
                    );
                }
            } elseif ($this->captcha instanceof NoCaptcha) {
                $result = $this->captcha->display();
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function hasInput()
    {
        return $this->captcha instanceof Captcha;
    }

    /**
     * @param array $attr
     * @return string
     */
    public function inputField(array $attr = [])
    {
        if ($this->hasInput()) {
            $attributes = [
                'name' => 'captcha'
            ];

            return '<input ' . html_attr(array_merge($attributes, $attr)) . ' />';
        }
    }

    /**
     * @return string
     */
    public function fieldName()
    {
        if ($this->captcha) {
            if ($this->captcha instanceof Captcha) {
                return 'captcha';
            }

            if ($this->captcha instanceof NoCaptcha) {
                return 'g-recaptcha-response';
            }
        }

        return '-';
    }

    /**
     * @return \Intervention\Image\ImageManager|null
     */
    public function getImage()
    {
        if ($this->captcha instanceof Captcha) {
            return $this->captcha->create($this->settings->get('captcha.config.template', 'default'));
        }

        return null;
    }
}