<?php
session_start(); // Start session if not already started

if (isset($_POST['hm_remove_submit'])) {
    require 'config.inc.php';

    $username = $_POST['hm_uname'];
    $hostel_name = $_POST['hostel_name'];
    $Adminpassword = $_POST['pass'];

    if (empty($username) || empty($hostel_name) || empty($Adminpassword)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: ../admin/create_hm.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM Hostel_Manager WHERE Username = '$username'";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {

            $sql2 = "SELECT * FROM Hostel WHERE Hostel_name = '$hostel_name'";
            $result2 = mysqli_query($conn, $sql2);
            if ($row2 = mysqli_fetch_assoc($result2)) {
                $HNO = $row2['Hostel_id'];
                if ($HNO == $row['Hostel_id']) {
                    $pwdCheck = password_verify($Adminpassword, $_SESSION['PSWD']);
                    if ($pwdCheck == false) {
                        $_SESSION['error'] = "Wrong admin password.";
                        header("Location: ../admin/create_hm.php?error=wrongpwd");
                        exit();
                    } else {
                        $sql3 = "DELETE FROM Hostel_Manager WHERE Username = '$username'";
                        $result3 = mysqli_query($conn, $sql3);
                        if ($result3) {
                            $_SESSION['success'] = "Hostel Manager removed successfully.";
                            header("Location: ../admin/create_hm.php?DeletionSuccessful");
                            exit();
                        } else {
                            $_SESSION['error'] = "Failed to remove Hostel Manager.";
                            header("Location: ../admin/create_hm.php?error=DeletionFailed");
                            exit();
                        }
                    }
                } else {
                    $_SESSION['error'] = "Hostel does not match.";
                    header("Location: ../admin/create_hm.php?error=wronghostel");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Hostel not found.";
                header("Location: ../admin/create_hm.php?error=nohostel");
                exit();
            }
        } else {
            $_SESSION['error'] = "Hostel Manager not found.";
            header("Location: ../admin/create_hm.php?error=nouser");
            exit();
        }
    }
} elseif (isset($_POST['appoint_submit'])) { // Assuming the appointment form is submitted using a different button
    // Handle appointment logic similarly as above, and set success message accordingly
    // Redirect to the appropriate page with the success message
} else {
    header("Location: ../index.php");
    exit();
}
?>
