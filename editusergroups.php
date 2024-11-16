<?php
include 'db.php'; // Database connection

$id = 1;
$query = "SELECT * FROM usergroups WHERE id = $id";
$result = mysqli_query($conn, $query);
$usergroup = mysqli_fetch_assoc($result);

?>

<form class="form" name="Form1" method="POST" id="groupForm">
    <div class="card-body">
        <input type="hidden" name="id" value="<?php echo $usergroup['id']; ?>">
        
        <div class="form-group row">
            <div class="col-lg-12">
                <label>Group Name: <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded" name="groupname" value="<?php echo $usergroup['groupname']; ?>" maxlength="96">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Permission</th>
                            <th>View</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Department</td>
                            <td><input type="checkbox" name="view_dept" <?php echo $usergroup['view_dept'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="add_dept" <?php echo $usergroup['add_dept'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="edit_dept" <?php echo $usergroup['edit_dept'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="delete_dept" <?php echo $usergroup['delete_dept'] ? 'checked' : ''; ?>></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button name="submit" type="button" class="btn btn-primary" onclick="updateGroup()">Update</button>
        <button class="btn btn-secondary" onclick="window.location.href='usergroups.php'">Back</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateGroup() {
    $.ajax({
        url: 'updategroup.php',
        type: 'POST',
        data: $('#groupForm').serialize(),
        success: function(response) {
            alert('Group updated successfully');
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
}
</script>
