<?php
namespace Dompdf\View;

use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;

class PdfView extends View {

	private $config = [
		'dpi'				=> 192,
		'isRemoteEnabled'	=> true,
		'isPhpEnabled'		=> true,
		'size'				=> 'A4',
		'orientation'		=> 'portrait',
		'render'			=> 'download',
		'filename' 			=> 'document',
	];

    public function initialize() {
        parent::initialize();
        $this->loadHelper('Dompdf.Dompdf');
    }

	public function __construct(Request $request = null, Response $response = null, EventManager $eventManager = null, array $viewOptions = []) {
		parent::__construct($request, $response, $eventManager, $viewOptions);

		if ( isset($viewOptions['config']) )
			$this->config = array_merge($this->config, $viewOptions['config']);
    }

	public function render($view = null, $layout = null) {

		$pdf = new Dompdf($this->config);
		$pdf->setPaper($this->config['size'], $this->config['orientation']);
		$pdf->loadHtml(parent::render($view, $layout));

		$pdf->render();

		switch ($this->config['render']) {
            case 'browser':
            case 'stream':
                return $pdf->output();

			case 'upload':
                $output = $pdf->output();
                if ( ! file_put_contents($this->config['upload_filename'], $output) )
                    return false;

                return $output;

			default: return $pdf->stream($this->config['filename']);
		}
	}
}