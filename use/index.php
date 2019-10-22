<?php
require '../../site/use/site.php';
$view = new CL\Site\Doc\DocView($site);
$view->title = 'cl/survey';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="../../../../cl/site.css" type="text/css" rel="stylesheet" />
<?php echo $view->head(); ?>
</head>

<body>
<?php echo $view->header(); ?>

<p>The cl/exam component supports the construction of exams that can be written using
    PHP scripts and randomized and presented to users.</p>

<p>At this time, cl/exam does not support administering the exam.</p>

<ul>
    <li><a class="cl-autoback" href="install.php">Installation and Configuration</a></li>
</ul>
	
<ul>
	<li><a class="cl-autoback" href="create.php">Creating an exam</a></li>
	<li><a class="cl-autoback" href="crowdmark.php">Using Crowdmark</a></li>
</ul>
	
<h2>Question Classes</h2>
<ul>
	<li>...</li>
</ul>	

<?php echo $view->footer(); ?>
</body>
</html>
