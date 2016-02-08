<?php
namespace Dompdf\View;

use Cake\View\View;
use Dompdf\Dompdf;
use Dompdf\FontMetrics;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;

class PdfView extends View {

	private $config = [
		'dpi'				=> 192,
		'isRemoteEnabled'	=> true,
		'size'				=> 'A4',
		'orientation'		=> 'portrait',
		'render'			=> 'download',
		'filename' 			=> 'document',
        'paginate'          => false,
	];

    private $pdf;

    private $_pagination = [
        'x'     => 0,
        'y'     => 0,
        'font'  => null,
        'size'  => 12,
        'text'  => "{PAGE_NUM} / {PAGE_COUNT}",
        'color' => [0,0,0],
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

		$this->pdf = new Dompdf($this->config);
		$this->pdf->setPaper($this->config['size'], $this->config['orientation']);

        $this->set(compact('pdf'));

		$this->pdf->loadHtml(parent::render($view, $layout));

		$this->pdf->render();

        if ( is_array($this->config['paginate']) ) {
            $this->paginate();
        }

		switch ($this->config['render']) {
            case 'browser':
            case 'stream':
                return $this->pdf->output();

			case 'upload':
                $output = $this->pdf->output();
                if ( ! file_put_contents($this->config['upload_filename'], $output) )
                    return false;

                return $output;

			default: return $this->pdf->stream($this->config['filename']);
		}
	}


    /**
     * Write pagination on the pdf
     */
    private function paginate() {
        $canvas = $this->pdf->get_canvas();
        $c = array_merge($this->_pagination, $this->config['paginate']);
        $canvas->page_text($c['x'], $c['y'], $c['text'], $c['font'], $c['size'], $c['color']);
    }
}