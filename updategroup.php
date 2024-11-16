<?php
include 'db.php'; // Database connection

$id = $_POST['id'];
$groupname = $_POST['groupname'];
$view_dept = isset($_POST['view_dept']) ? 1 : 0;
$add_dept = isset($_POST['add_dept']) ? 1 : 0;
$edit_dept = isset($_POST['edit_dept']) ? 1 : 0;
$delete_dept = isset($_POST['delete_dept']) ? 1 : 0;

$query = "UPDATE usergroups 
          SET groupname = '$groupname', view_dept = '$view_dept', add_dept = '$add_dept', 
              edit_dept = '$edit_dept', delete_dept = '$delete_dept' 
          WHERE id = $id";

if (mysqli_query($conn, $query)) {
    echo 'Success';
} else {
    echo 'Error: ' . mysqli_error($conn);
}
?>
