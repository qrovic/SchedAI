<?php
require_once("config.php");

$facultyid = trim(stripslashes(htmlspecialchars($_POST['facultyid'])));
$fname = trim(stripslashes(htmlspecialchars($_POST['fname'])));
$mname = trim(stripslashes(htmlspecialchars($_POST['mname'])));
$lname = trim(stripslashes(htmlspecialchars($_POST['lname'])));
$contactno = trim(stripslashes(htmlspecialchars($_POST['contactno'])));
$bday = trim(stripslashes(htmlspecialchars($_POST['bday'])));
$gender = trim(stripslashes(htmlspecialchars($_POST['gender'])));
$username = trim(stripslashes(htmlspecialchars($_POST['username'])));
$password = trim(stripslashes(htmlspecialchars($_POST['password'])));
$type = trim(stripslashes(htmlspecialchars($_POST['type'])));
$startdate = trim(stripslashes(htmlspecialchars($_POST['startdate'])));
$departmentid = trim(stripslashes(htmlspecialchars($_POST['departmentid'])));
$teachinghours = trim(stripslashes(htmlspecialchars($_POST['teachinghours'])));
$rank = trim(stripslashes(htmlspecialchars($_POST['rank'])));

if (isset($_POST['rank'])&&($_POST['rank']=='phd')) {
    $masters='yes';
    $phd='yes';
}elseif(isset($_POST['rank'])&&($_POST['rank']=='masters')) {
    $masters='yes';
    $phd='no';
}else{
    $masters='no';
    $phd='no';
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM faculty WHERE facultyid = :facultyid");
$stmt->bindParam(':facultyid', $facultyid);
$stmt->execute();
$facultyexists = $stmt->fetchColumn();

if ($facultyexists) {
    header("Location: ../admin/faculty.php?faculty=exist");
    exit();
} else {
    
}
$hashedpassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO faculty (facultyid, fname, mname, lname, contactno, bday, gender, username, password, type, startdate, departmentid, teachinghours, masters, phd, rank) VALUES (:facultyid, :fname, :mname, :lname, :contactno, :bday, :gender, :username, :password, :type, :startdate, :departmentid, :teachinghours, :masters, :phd, :rank)");
    $stmt->bindParam(':facultyid', $facultyid);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':mname', $mname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':contactno', $contactno);
    $stmt->bindParam(':bday', $bday);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedpassword); 
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':startdate', $startdate);
    $stmt->bindParam(':departmentid', $departmentid);
    $stmt->bindParam(':teachinghours', $teachinghours);
    $stmt->bindParam(':masters', $masters);
    $stmt->bindParam(':phd', $phd);
    $stmt->bindParam(':rank', $rank);
    $stmt->execute();

        $_SESSION['msg']="addfaculty";
    header('Location: ../admin/faculty.php');
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}



?>