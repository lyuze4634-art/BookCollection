<?php
session_start();

if (!isset($_SESSION['book_access']) || $_SESSION['book_access'] !== true) {
    header("Location: index.php");
    exit;
}