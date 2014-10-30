<?php require_once 'vendor/autoload.php'; ?>

<?php $loader = new Twig_Loader_Filesystem('src/views'); ?>

<?php $twig = new Twig_Environment($loader); ?>

<?php echo $twig->render('index.html.twig', array('text' => 'Hello world!')); ?>