<?php
require '../../site/use/site.php';
$view = new CL\Site\Doc\DocView($site);
$view->title = 'cl/exam Using Crowdmark';
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

<p>To use Crowdmark, add these lines to the initial PHP section of the exam file:</p>
<pre class="code">&lt;?php
require_once "../site.php";
$view = new CL\Exam\ExamView($site, $_GET);
$view-&gt;title = 'Exam 1';
<span class="highlight">$view-&gt;crowdmark = true;
$view-&gt;titlePageContent = &lt;&lt;&lt;HTML
&lt;p&gt;Notice: Blank space has been provided to answer the questions. 
Do not use the backs of any pages.&lt;/p&gt;
HTML;</span></pre>
<p>This will create a title page and include sufficient space for the crowdmark header on subsequent pages. The title page content appears on that page.</p>


<?php echo Backto::to("cl/exam", "./"); ?>
<?php echo $view->footer(); ?>
</body>
</html>
