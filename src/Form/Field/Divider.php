<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Divider extends Field
{
    protected $title;

    public function __construct($title = '')
    {
        $this->title = $title;
    }

    public function render()
    {
        if (empty($this->title)) {
            return '<hr>';
        }

        return <<<HTML
<div style="font-weight: 700; height: 20px; border-bottom: 1px solid #eee; text-align: left;margin-top: 20px;margin-bottom: 20px;">
  <span style="font-size: 18px; background-color: #ffffff; padding: 0 10px;">
    {$this->title}
  </span>
</div>
HTML;
    }
}
