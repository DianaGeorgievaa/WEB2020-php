<?php
include "./db_manipulation.php";

define("MAX_TITLE_LENGTH", 128, true);
define("MAX_TEACHER_LENGTH", 1024, true);
define("MAX_DESCRIPTION_LENGTH", 1024, true);

$errors = array();

function validateFormField(String $formField, int $maxLength, &$errors)
{
  $inputValue = $_POST["$formField"];
  if (!$inputValue) {
    $errors["$formField"] = "Полето е задължително.";
  } elseif (strlen($inputValue) > $maxLength) {
    $errors["$formField"] = "Полето има максимална дължина символа.";
  }
}

$numberPattern = "/[0-9]+/";
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$pathId = basename($requestUri);
$dirname = dirname($requestUri);
$fileename = "electives.php";
if ($requestMethod == "POST") {

  validateFormField('title', MAX_TITLE_LENGTH, $errors);
  validateFormField('teacher', MAX_TEACHER_LENGTH, $errors);
  validateFormField('description', MAX_DESCRIPTION_LENGTH, $errors);

  $title = $_POST['title'];
  $lecturer = $_POST['teacher'];
  $description = $_POST['description'];

  if (count($errors) === 0) {
    if ($pathId == $fileename) {
      echo "The form was successfully submitted!";
      insertElective($title, $description, $lecturer);
      echo "Successfully inserted to the database!";
    } else {
      if (!preg_match($numberPattern, $pathId)) {
        die("Invalid path param");
      }
      updateElective($pathId, $title, $description, $lecturer);
      $elective = getElective($pathId);
    }
  }
} elseif ($requestMethod == "GET") {
  if (!preg_match($numberPattern, $pathId)) {
    die("Invalid path param");
  }
  $elective = getElective($pathId);
}
?>
<!DOCTYPE html>
<html lang="bg">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
  <title>Редактиране на избираем курс</title>
</head>

<body>
  <form method="post">
    <h1>Редактиране на избираема дисциплина</h1>
    <label for="course-title">Име на курс:</label>
    <input type="text" id="course-title" name="title" />

    <label for="course-teacher">Преподавател:</label>
    <input type="text " id="course-teacher" name="teacher" />

    <label for="course-description">Описание на избираемата дисциплина: </label>
    <input type="text " id="course-description" name="description" />
    <input type="submit" name="submit">
  </form>
</body>

</html>