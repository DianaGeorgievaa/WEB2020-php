<?php
define("MAX_TITLE_LENGTH", 128, true);
define("MAX_TEACHER_LENGTH", 1024, true);
define("MIN_DESCRIPTION_LENGTH", 10, true);

$host = "localhost";
$username = "root";
$pass = "";
$dbname = "test";

function validateTitle($title, &$errors)
{
  if (!$title) {
    $errors['title'] = "Името е задължително поле.";
  } elseif (strlen($title) > MAX_TITLE_LENGTH) {
    $errors['title'] = "Името има максимална дължина 150 символа.";
  }
  if (array_key_exists('title', $errors)) {
    echo $errors['title'];
  }
}

function validateTeacher($teacher, &$errors)
{
  if (!$teacher) {
    $errors['teacher'] = "Името на преподавател е задължително поле.";
  } elseif (strlen($teacher) > MAX_TEACHER_LENGTH) {
    $errors['teacher'] = "Името на преподавател има максимална дължина 200 символа.";
  } 
   if (array_key_exists('teacher', $errors)) {
    echo $errors['teacher'];
  }
}

function validateDescription($description, &$errors)
{
  if (!$description) {
    $errors['description'] = "Описанието на дисциплината е задължително поле.";
  } elseif (strlen($description) < MIN_DESCRIPTION_LENGTH) {
    $errors['description'] = "Описанието на дисциплината има минимална дължина 10 символа.";
  } 
    if (array_key_exists('description', $errors)) {
    echo $errors['description'];
  }
}

if ($_POST) {
  $errors = array();

  $description = $_POST['description'];
  $teacher = $_POST['teacher'];
  $title = $_POST['title'];

  validateTitle($title, $errors);
  validateTeacher($teacher, $errors);
  validateDescription($description, $errors);

  if (count($errors) === 0) {
    echo "The form was successfully submitted!";

    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $pass);
    $statement = $connection->prepare("INSERT INTO electives (title, description, lecturer) VALUES (:title, :teacher, :description);");
    $statement->bindParam(':title', $title);
    $statement->bindParam(':teacher', $teacher);
    $statement->bindParam(':description', $description);
    $statement->execute();
    echo "Successfully inserted to the database!";
  }
}
