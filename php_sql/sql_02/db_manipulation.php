<?php
$configs = include('configuration_properties.php');

function getDatabaseConnection()
{
    global $configs;
    $host = $configs['host'];
    $dbname = $configs['database_name'];
    $username = $configs['username'];
    $password = $configs['password'];

    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    return $connection;
}

function insertElective($title, $description, $lecturer)
{
    global $configs;
    $table = $configs['table_name'];
    $connection = getDatabaseConnection();
    $insertQuery = "INSERT INTO $table(title,description,lecturer) VALUES (:title, :description, :lecturer)";

    $preparedSql = $connection->prepare($insertQuery) or die("Failed to prepare the sql query.");
    $preparedSql->bindParam(':title', $title);
    $preparedSql->bindParam(':description', $description);
    $preparedSql->bindParam(':lecturer', $lecturer);
    $preparedSql->execute() or die("Failed to execute sql query.");
}


function getElective($id)
{
    if (!isExistingId($id)) {
        die("The elective with the given id: $id is not existing.");
    }
    global $configs;
    $table = $configs['table_name'];
    $connection = getDatabaseConnection();
    $selectQuery = "SELECT * FROM $table WHERE id = :id;";

    $result = $connection->prepare($selectQuery) or die("Failed to prepare sql query.");
    $result->bindParam(':id', $id);
    $elective = $result->execute() or die("Failed to execute sql query.");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Subject ID: " . $row["id"] . "<br/>Title: " . $row["title"] . "<br/>Description: " . $row["description"] . "<br/>Lecturer: " . $row["lecturer"] . "<br/><br/>";
    }
}

function isExistingId($id)
{
    global $configs;
    $table = $configs['table_name'];
    $connection = getDatabaseConnection();
    $sql = "SELECT * FROM $table WHERE id = :id";

    $preparedSql = $connection->prepare($sql) or die("Failed to prepare sql query.");
    $preparedSql->bindParam(':id', $id);
    $preparedSql->execute() or die("Failed to execute sql query.");
    $result = $preparedSql->fetchAll();

    return count($result) !== 0;
}

function updateElective($id, $title, $description, $lecturer)
{
    if (!isExistingId($id)) {
        die("There is no elective with this ID.");
    }

    global $configs;
    $table = $configs['table_name'];

    $connection = getDatabaseConnection();
    $sql = "UPDATE $table SET title = :title, description = :description, lecturer = :lecturer WHERE id = :id";

    $preparedSql = $connection->prepare($sql) or die("Failed to prepare sql query.");
    $preparedSql->bindParam(':id', $id);
    $preparedSql->bindParam(':title', $title);
    $preparedSql->bindParam(':description', $description);
    $preparedSql->bindParam(':lecturer', $lecturer);

    $preparedSql->execute() or die("Failed to execute the sql query.");
}
