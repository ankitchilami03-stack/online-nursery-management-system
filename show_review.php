<?php
session_start();
include('includes/config.php');
$connect = new PDO("mysql:host=localhost;dbname=nmsdb", "root", "");

$pid = $_GET['pid'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Reviews</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2e8b57;
            te
