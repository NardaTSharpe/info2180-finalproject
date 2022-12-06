<?php session_start();
    $host = 'localhost';
    $username = '';
    $password = '';
    $dbname = 'dolphin_crm';


    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $stmt = $conn->query("SELECT * FROM contacts;");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $user_id = $_SESSION['user_id'];

    $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
?>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Type</th>
            <th></th>
        </tr>
    </thead>

    <?php if(!isset($q) || $q=="All"): ?>
    <tbody>
        <?php foreach ($results as $row): 
            
            $TYPE = $row['type'];
            if($TYPE == "Sales Lead"):
                $TYPE_CLASS = 'lead';
            else:
                $TYPE_CLASS = 'support';
            endif;
        ?>
            <tr>
                <td class="name"><?php echo $row['title'] . " " .$row['firstname'] . " " . $row['lastname'] ?></td>
                <td class="email"><?php echo $row['email'] ?></td>
                <td class="company"><?php echo $row['company'] ?></td>
                <td class="type <?php echo $TYPE_CLASS?>"><button><?php echo $TYPE ?></button></td>
                <td class="view"><a href="viewcontact.php?contact=<?php echo $row['firstname'] . '-' . $row['lastname']; ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <?php else:  ?>
        <?php if ($q == "Sales Leads"): 
                $stmt = $conn->query("SELECT * FROM contacts WHERE type = 'Sales Lead';");
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $row): 
        ?>
        <tbody>
            <tr>
                <td class="name"><?php echo $row['title'] . " " .$row['firstname'] . " " . $row['lastname'] ?></td>
                <td class="email"><?php echo $row['email'] ?></td>
                <td class="company"><?php echo $row['company'] ?></td>
                <td class="type <?php echo 'lead'?>"><button><?php echo 'Sales Lead' ?></button></td>
                <td class="view"><a href="viewcontact.php?contact=<?php echo $row['firstname'] . '-' . $row['lastname']; ?>">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <?php elseif($q == "Support"):
            $stmt = $conn->query("SELECT * FROM contacts WHERE type = 'Support';");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row):     
        ?>
        <tbody>
            <tr>
                <td class="name"><?php echo $row['title'] . " " .$row['firstname'] . " " . $row['lastname'] ?></td>
                <td class="email"><?php echo $row['email'] ?></td>
                <td class="company"><?php echo $row['company'] ?></td>
                <td class="type <?php echo 'support'?>"><button><?php echo 'Support' ?></button></td>
                <td class="view"><a href="viewcontact.php?contact=<?php echo $row['firstname'] . '-' . $row['lastname']; ?>">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <?php elseif($q == "Assigned to me"):
            $stmt = $conn->prepare("SELECT * FROM contacts WHERE assigned_to=:user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $results = $stmt->fetchAll();

            foreach ($results as $row): 
                $TYPE = $row['type'];
                if($TYPE == "Sales Lead"):
                    $TYPE_CLASS = 'lead';
                else:
                    $TYPE_CLASS = 'support';
                endif;    
        ?>
       <tbody>
            <tr>
                <td class="name"><?php echo $row['title'] . " " .$row['firstname'] . " " . $row['lastname'] ?></td>
                <td class="email"><?php echo $row['email'] ?></td>
                <td class="company"><?php echo $row['company'] ?></td>
                <td class="type <?php echo $TYPE_CLASS?>"><button><?php echo $TYPE ?></button></td>
                <td class="view"><a href="viewcontact.php?contact=<?php echo $row['firstname'] . '-' . $row['lastname']; ?>">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <?php endif;?>
    <?php endif; ?>
</table>