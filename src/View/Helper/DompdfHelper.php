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
     * Ajout d'un fichier CSS
     * @param  string $path : nom du fichier sans l'extension
     * @return string <link>
     */
    public function css($path, $plugin = false) {
        $path = ($plugin) ? "dompdf/css/{$path}" : "css/{$path}";
    	return "<link rel=\"stylesheet\" href=\"{$path}.css\">";
    }

    /**
     * Balise image
     * @param  string $path    : nom de l'image
     * @param  array  $options : attributs
     * @return string  <img>
     */
    public function image($path, $options = []) {

    	$options['src'] = "img/{$path}";
    	$options['alt'] = (isset($options['alt'])) ? $options['alt'] : '';

    	return $this->Html->tag('img', null, $options);
    }

}
