<?php
include "db-connection.php";
$conn = openCon();

$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : false;
$password = isset($_POST['password']) ? $_POST['password'] : false;
$role = isset($_POST['role']) ? $_POST['role'] : false;

$Email = $_POST["email"];
$firstname = $_POST["firstname"];
$password = $_POST["password"];
$role = $_POST["role"];

if ($firstname && $password && $role) {
  if (strcmp($_POST['role'], "student") == 0 || strcmp($_POST['role'], "teacher") == 0){
  $selectStatement = "SELECT COUNT(*) FROM person WHERE email = :email";
  $email = $conn->prepare($selectStatement);
  $email->bindParam(':email', $_POST["email"]);
  $email->execute();
  $result = $email->fetch();

  if ($result[0] == 0) {
    $insertStatement = "INSERT INTO person (email, firstname, password, role) values(:email, :firstname, :password, :role);";
    $stmt = $conn->prepare($insertStatement);
    $stmt->bindParam(":email", $Email);
    $stmt->bindParam(":firstname", $firstname);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":role", $role);
    $stmt->execute() or die("Failed to query!");
    echo ("You are registered as: " . $_POST["email"] . " with role: " .  $_POST["role"]);
  } else {
    die("There is existing user with this email");
  }
}
} else {
  echo "All fields are required!";
}
