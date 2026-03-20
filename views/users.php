
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Users List</title>
    </head>
    <?php include ('topNavigation.php'); ?>
    </br>
    <body>
        <table>
            <tr>
                <th>Name</th>
                <th>Balance</th>
                <th>ID</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user->get_name(); ?></td>
                    <td><?php echo $user->get_balance(); ?></td>
                    <td><?php echo $user->get_id(); ?></td>
                </tr>

            <?php endforeach; ?>
        </table>
        </br>
        <h2>Add or Update User</h2>
        <form action="users.php" method="post"> 
            <label>Name:</label> 
            <input type="text" name="name"/><br> 
            <label>Balance:</label> 
            <input type="text" name="balance"/><br> 
            <label>ID:</label> 
            <input type="text" name="id"/><br> 
            <input type="hidden" name='action' value='insert_or_update'/>
            <input type="radio" name="insert_or_update" value="insert" checked>Add</br>
            <input type="radio" name="insert_or_update" value="update">Update</br>
            <label>&nbsp;</label>
            <input type="submit" value="Submit"/> 
        </form>
        </br>
        <h2>Delete User</h2>
        <form action="users.php" method="post"> 
            <label>ID:</label> 
            <input type="text" name="id"/><br> 
            <input type="hidden" name='action' value='delete'/>
            <label>&nbsp;</label>
            <input type="submit" value="Delete User"/> 
        </form>
    </body>
    </br>
    <?php include ('footer.php'); ?>
</html>