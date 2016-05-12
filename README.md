# Dompdf plugin for CakePHP

## Requirements
- PHP version 5.4.16 or higher
- CakePhp 3.0 or higher
- Dompdf 0.7

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require daoandco/cakephp-dompdf
```

After installation, generate symlink for CSS (http://book.cakephp.org/3.0/en/deployment.html#symlink-assets)
```
// In a shell
bin/cake plugin assets symlink
```

## Quick Start

Loading the Plugin
```PHP
  // In config/bootstrap.php
  Plugin::load('Dompdf');
```

Activate pdf extension (http://book.cakephp.org/3.0/en/development/routing.html#routing-file-extensions)
```PHP
  // In config/routes.php
  Router::scope('/', function ($routes) {

    $routes->extensions(['pdf']);
    ...
  }
```

Loading component RequestHandler
```PHP
  // In src/controller/AppController.php
  public function initialize() {
    parent::initialize();

    $this->loadComponent('RequestHandler');
  }
```

In a controller
```PHP
class YopController extends AppController {

    public function view($filename) {

        $this->viewBuilder()
            ->className('Dompdf.Pdf')
            ->layout('Dompdf.default')
            ->options(['config' => [
                'filename' => $filename,
                'render' => 'browser',
            ]]);
    }
}
```

Create a view (pdf content)
```HTML
<!-- src/Template/Yop/pdf/view.ctp -->
<?php $this->start('header'); ?>
    <p>Header.</p>
<?php $this->end(); ?>

<?php $this->start('footer'); ?>
    <p>Footer.</p>
<?php $this->end(); ?>


<h1>My title</h1>

<p>Banana</p>

<p>Boom !!!</p>
```

Show the pdf in your browser :
http://dev.local/myproject/yop/view/test.pdf


## Configuration
Use `$this->viewBuilder()` with :

- ->className() : set the view classname
http://api.cakephp.org/3.1/class-Cake.View.ViewBuilder.html#_className
Use the plugin view by default `className('Dompdf.Pdf')`

- ->layout() : set the name of the layout file to render the view
http://api.cakephp.org/3.1/class-Cake.View.ViewBuilder.html#_layout
Use the plugin layout by default `layout('Dompdf.default')`

- ->options() : Set additional options for the view
http://api.cakephp.org/3.1/class-Cake.View.ViewBuilder.html#_options
Use array with key `config` and value `array` with dompdf config
  - filename : pdf name
  - upload_filename : path with filename for upload render
  - render : (see [render](#render) )
    - browser : show in browser
    - download : download the pdf by browser
    - upload : save file on the server
    - stream : return a stream resource for sending file without save
  - size : paper size : default `A4`
  - orientation : paper orientation (`portait` OR `landscape`) : default `portrait`
  - dpi : Image DPI setting : default `192`
  - isRemoteEnabled : Enable remote file access : default `true`
  - paginate: activate pagination (array) : default `false` (see [paginate](#paginate) )
  - More options : see dompdf documention https://github.com/dompdf/dompdf/wiki

## View
### Header
*with default layout and `dompdf.css`*
```PHP
$this->start('header');
    echo '<p>I'm a header</p>';
$this->end();
```

### Footer
*with default layout and `dompdf.css`*
```PHP
$this->start('footer');
    echo '<p>I'm a footer</p>';
$this->end();
```

### Image
**use Helper**
```PHP
/**
  * Générate an image
  * @param  string $path : Path to the image file, relative to the app/webroot/img/ directory
  * @param  array  $options : Array of HTML attributes
  * @return string <img>
  */
public function image($path, $options = false) {
  ...
}
```
Exemple :
```PHP
echo $this->Dompdf->image('test.png', ['class' => 'imgclass']);
```

### CSS stylesheets
**use Helper**
```PHP
/**
  * Creates a link element for CSS stylesheets
  * @param  string $path : The name of a CSS style sheet
  * @param  bool $plugin : (true) add a plugin css file || (false) add a file in webroot/css /// default : false
  * @return string <link>
  */
public function css($path, $plugin) {
  ...
}
```
Exemple :
```PHP
echo $this->Dompdf->css('mycss');
```

### Page break
*with `dompdf.css`*
```HTML
<p>Page 1</p>

<?= $this->Dompdf->page_break(); ?>

<p>Page 2</p>
```

## Render

### Display on browser
``` PHP
$this->viewBuilder()
    ->className('Dompdf.Pdf')
    ->layout('Dompdf.default')
    ->options(['config' => [
        'render' => 'browser',
    ]]);
```

### Force download on browser
``` PHP
$this->viewBuilder()
    ->className('Dompdf.Pdf')
    ->layout('Dompdf.default')
    ->options(['config' => [
        'filename' => 'mydocument',
        'render' => 'download',
    ]]);
```

### Upload on server
``` PHP
$this->viewBuilder()
    ->className('Dompdf.Pdf')
    ->layout('Dompdf.default')
    ->options(['config' => [
        'upload_filename' => WWW_ROOT.'pdf/mydocument.pdf',
        'render' => 'upload',
    ]]);
```

### Stream
``` PHP
use Cake\View\ViewBuilder;

$builder = new ViewBuilder();
$builder->className('Dompdf.Pdf')
        ->layout('Dompdf.pdf/default')
        ->template('Pdf/pdf/view')
        ->options(['config' => [
            'render' => 'stream',
        ]]);
$view = $builder->build();

$stream = $view->render();
```

## Paginate

### With helper
You can show page number but not number of pages
```HTML
<!-- In a view -->
<?php $this->start('footer'); ?>
    <p><?= $this->Dompdf->page_number(); ?></p>
<?php $this->end(); ?>
```

### With PdfView
You can show page number and number of pages
Use paginate key in view config
```PHP
$this->viewBuilder()
    ->className('Dompdf.Pdf')
    ->layout('Dompdf.default')
    ->options(['config' => [
        'filename' => $filename,
        'render' => 'browser',
        'paginate' => [
            'x' => 550,
            'y' => 5,
        ],
    ]]);
```
Paginate options :
- x : left position : default `0`
- y : top position : default `0`
- font : font family : default `null`
- size : font size : default `12`
- text : default `"{PAGE_NUM} / {PAGE_COUNT}"`
- color : rgb (array) : default `[0,0,0]` = black
