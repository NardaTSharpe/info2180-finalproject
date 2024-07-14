<?php session_start();    
    $host = 'localhost';
    $username = '';
    $password = '';
    $dbname = 'dolphin_crm';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $stmt = $conn->query("select * from users;");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($_SESSION['role'] == "Admin"):
        // echo $_SESSION['role'];
?>

<div class="main-header">
    <h1 class="title">Users</h1>
    <button class="add-user-btn">
            <a href="adduser.html">
                <i class="fa-solid fa-plus"></i>
                <p>Add User</p>
            </a>
    </button>

</div>

<div class="user-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($results as $row): ?>
                <tr>
                    <td class="name"><?php echo $row['firstname'] . " " . $row['lastname'] ?></td>
                    <td class="email"><?php echo $row['email'] ?></td>
                    <td class="role"><?php echo $row['role'] ?></td>
                    <td class="created_at"><?php echo $row['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>                   
        </tbody>
    </table>
</div>



<?php $conn = null; ?>

<?php else: ?>
    <div class="user-container-non-admin">
        <p><?php echo "This functionality is not available to non-admin users. Please login as an admin or return to homepage.";?></p>
    </div>

<?php endif;?>

