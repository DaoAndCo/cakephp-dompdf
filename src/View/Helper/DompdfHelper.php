<?php
namespace Dompdf\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Dompdf helper
 */
class DompdfHelper extends Helper
{
	public $helpers = ['Html'];
    protected $_defaultConfig = [];

    /**
     * Creates a link element for CSS stylesheets
     * @param  string $path : The name of a CSS style sheet
     * @param  bool $plugin : (true) add a plugin css file || (false) add a file in webroot/css /// default : false
     * @return string <link>
     */
    public function css($path, $plugin = false) {
        $path = ($plugin) ? "dompdf/css/{$path}" : "css/{$path}";
    	return "<link rel=\"stylesheet\" href=\"{$path}.css\">";
    }

    /**
     * Générate an image
     * @param  string $path : Path to the image file, relative to the app/webroot/img/ directory
     * @param  array  $options : Array of HTML attributes
     * @return string <img>
     */
    public function image($path, $options = []) {

    	$options['src'] = "img/{$path}";
    	$options['alt'] = (isset($options['alt'])) ? $options['alt'] : '';

    	return $this->Html->tag('img', null, $options);
    }

    /**
     * Generate a page break
     * @return string <div>
     */
    public function page_break() {
        return $this->Html->tag('div', null, ['class' => 'page_break']);
    }

    /**
     * Write page number (use in header or footer)
     * @return string <span>
     */
    public function page_number() {
        return $this->Html->tag('span', null, ['class' => 'page_number']);
    }
}
