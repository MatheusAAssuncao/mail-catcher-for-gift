<?php 
    echo view('templates/header');
?>

<div id="content" class="container">
    <?= $this->renderSection('content') ?>
</div>

<?php 
    echo view('templates/footer');
?>