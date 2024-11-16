<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grouped Table with Toggle Switches</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Switch styles */
        .toggle {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(14px);
        }

        .group-header {
            background-color: #e0e0e0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Grouped Items List</h2>
<table>
    <thead>
        <tr>
            <th>Page Name</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
            <th><input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)"></th>
        </tr>
    </thead>
    <tbody>
        <tr class="group-header">
            <td colspan="" align="left">Group 1: Main Pages</td>
			<td colspan="4" align="left"></td>
        </tr>
        <tr>
            <td>Page 1</td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
        </tr>
        <tr>
            <td>Page 2</td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
        </tr>

        <tr class="group-header">
            <td colspan="" align="left">Group 2: Additional Pages</td>
			<td colspan="4" align="left"></td>
        </tr>
        <tr>
            <td>Page 3</td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
        </tr>
        <tr>
            <td>Page 4</td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
            <td><label class="toggle"><input type="checkbox" class="checkbox" onclick="updateSelectAll()"><span class="slider"></span></label></td>
        </tr>
    </tbody>
</table>

<script>
function toggleAllCheckboxes(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function updateSelectAll() {
    const checkboxes = document.querySelectorAll('.checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.checked = [...checkboxes].every(checkbox => checkbox.checked);
}
</script>

</body>
</html>
