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
     * @return string
     */
    public function tags($files)
    {
        $html = '';

        foreach ($files as $file) {
           $html .= $this->tag($file);
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

    /**
     * @param  string $path
     * @return string
     */
    protected function path($path)
    {
        return preg_replace('#(^|[^:])//+#', '$1/', $path);
    }
}
