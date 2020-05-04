<?php

define("MAX_TITLE_LENGTH", 128, true);
define("MAX_TEACHER_LENGTH", 1024, true);
define("MAX_DESCRIPTION_LENGTH", 1024, true);

$valid = array();
$errors = array();

$host = "localhost";
$username = "root";
$pass = "";
$dbname = "test";

function validateFormField(String $formField, int $maxLength, &$valid, &$errors)
{
  $inputValue = $_POST["$formField"];
  if (!$inputValue) {
    $errors["$formField"] = "Полето е задължително.";
  } elseif (strlen($inputValue) > $maxLength) {
    $errors["$formField"] = "Полето има максимална дължина символа.";
  } else {
    $valid[$formField] = $inputValue;
  }
}


if ($_POST) {

  validateFormField('title', MAX_TITLE_LENGTH, $valid, $errors);
  validateFormField('teacher', MAX_TEACHER_LENGTH, $valid, $errors);
  validateFormField('description', MAX_DESCRIPTION_LENGTH, $valid, $errors);

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