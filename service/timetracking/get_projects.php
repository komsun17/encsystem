<?php
session_start();
require '../connect.php';

$sql = "SELECT id, project_name FROM projects ORDER BY project_name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "response" => $projects
]);
