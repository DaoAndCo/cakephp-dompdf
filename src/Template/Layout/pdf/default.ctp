<!DOCTYPE html>
<html>
    <head>
        <?= $this->fetch('head') ?>

        <?= $this->Dompdf->css('dompdf', true) ?>
        <style><?= $this->fetch('style') ?></style>
    </head>
    <body>
        <div class="header"><?= $this->fetch('header') ?></div>
        <div class="footer"><?= $this->fetch('footer') ?></div>

        <?= $this->fetch('content') ?>
    </body>
</html>