<?php
namespace Laravel\Packer\Providers;

abstract class ProviderBase
{
    protected $settings;

    /**
     * @param  array  $settings
     * @return string
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;

        if (empty($this->settings['attributes'])) {
            $this->settings['attributes'] = [];
        }
    }

    /**
     * @param  array  $files
     * @param  array  $attributes
     * @return string
     */
    public function tags($files, array $attributes = [])
    {
        $html = '';

        foreach ($files as $file) {
            $html .= $this->tag($file, $attributes);
        }

        return $html;
    }

    /**
     * @param  array  $attributes
     * @return string
     */
    protected function attributes(array $attributes)
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= $key.'="'.htmlspecialchars($value).'" ';
        }

        return trim($html);
    }
}
