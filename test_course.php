<?php
require_once "db.php";
$course_id = (int)($_GET["id"] ?? 1);
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Course ID: " . $course_id . PHP_EOL;
echo "Course Name: " . ($course ? $course["name"] : "NOT FOUND") . PHP_EOL;
