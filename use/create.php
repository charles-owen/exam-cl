<?php
require '../../site/use/site.php';
$view = new CL\Site\Doc\DocView($site);
$view->title = 'cl/exam Creating an exam';
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



<h2>Directory Structure</h2>
<p>There are two things that must be save for an exam: the exam page itself and the question classes. Typically, the exam itself will go into some directory off of the root directory of the site. Question classes can then go in that directory or any other suitable directory. This documentation assumes all question classes are in a directory exam/cl/<em>category</em>, where category is a category of test questions.</p>
<h2>Exam template</h2>
<p>This is an example exam, with 6 questions and question FAQs:</p>
<pre class="code">&lt;?php
require_once "../site.php";
$view = new CL\Exam\ExamView($site, $_GET);
$view-&gt;title = 'Exam 1';
HTML;

// The question classes we will use
require '../exams/cls/Terminology/ExamTerminology1.php';
require '../exams/cls/ExamVirtual1.php';
require '../exams/cls/ExamBadSandwich.php';
require '../exams/cls/ExamBedAndBreakfast.php';
require '../exams/cls/ExamNutritionUML.php';
require '../exams/cls/ExamAirlineTicketSearchUML.php';

?&gt;
&lt;!doctype html&gt;
&lt;html lang=en-US&gt;
&lt;head&gt;
	&lt;link href="../cl/course.css" type="text/css" rel="stylesheet" /&gt;
	&lt;?php echo $view-&gt;head(); ?&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;?php
echo $view-&gt;header();
?&gt;


&lt;?php
	//
	// Question 1: Terminology
	//

  $q = new ExamTerminology1($view, '1. (10 pts)');
  echo $q-&gt;present_actual();

  //
  // Question 2: Polymorphism and virtual functions
  //

  echo $view-&gt;page();
  $q = new ExamVirtual1($view, '2. (20 pts) ');
  echo $q-&gt;present_actual();

  // etc.



?&gt;

&lt;?php
echo $view-&gt;footer();
?&gt;
&lt;/body&gt;
&lt;/html&gt;</pre>
<h3>Force a new page</h3>
<pre class="code">  echo $view-&gt;page();</pre>

<h3>Adding an FAQ page (for questions that include FAQs):</h3>
<pre class="code">  //
  // FAQ page
  //
  echo $view-&gt;page();
  echo '&lt;h2 class="center"&gt;Exam 1 FAQ&lt;/h2&gt;';
  echo $q5-&gt;present_faq('Question 5 FAQ');
  echo $q6-&gt;present_faq('Question 6 FAQ');
</pre>
	
<h3>Multipart questions</h3>	
	
<?php echo Backto::to("cl/exam", "./"); ?>
<?php echo $view->footer(); ?>
</body>
</html>
