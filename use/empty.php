<?php
require '../../site/use/site.php';
$view = new CL\Site\Doc\DocView($site);
$view->title = 'cl/exam TITLE';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../../../../cl/site.css" type="text/css" rel="stylesheet" />
<?php echo $view->head(); ?>
</head>

<body>
<?php echo $view->header(); ?>
<?php echo Backto::to("cl/exam", "./"); ?>

<p>...</p>


<?php echo Backto::to("cl/exam", "./"); ?>
<?php echo $view->footer(); ?>
</body>
</html>
