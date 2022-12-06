<?php session_start();
    $host = 'localhost';
    $username = '';
    $password = '';
    $dbname = 'dolphin_crm';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $stmt = $conn->query("SELECT * FROM users;");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $q = filter_input(INPUT_GET, 'load', FILTER_SANITIZE_STRING);

    if(isset($q)):

?>
    <label for="assigned-to">Assigned To</label> 
    <select name="assigned-to" required> 
        <?php foreach ($results as $row): ?> 
            <option value="<?php echo $row['firstname']. " " . $row['lastname']?>"><?php echo $row['firstname'] . " " . $row['lastname']?></option>;
        <?php endforeach;?>
    </select>
    <?php else:?>
<?php
   $title= filter_input(INPUT_POST,"title",FILTER_SANITIZE_STRING); 
   $firstname= filter_input(INPUT_POST,"firstname",FILTER_SANITIZE_STRING); 
   $lastname= filter_input(INPUT_POST,"lastname",FILTER_SANITIZE_STRING); 
   $email= filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL); 
   $telephone= filter_input(INPUT_POST,"telephone",FILTER_SANITIZE_STRING); 
   $company= filter_input(INPUT_POST,"company",FILTER_SANITIZE_STRING); 
   $type= filter_input(INPUT_POST,"type",FILTER_SANITIZE_STRING); 
   $assigned_to= filter_input(INPUT_POST,"assigned-to",FILTER_SANITIZE_STRING); 

   function filter_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
   }

   function set_data_filtered_flag(){
    global $title, $firstname, $lastname, $email, $telephone, $company, $type, $assigned_to, $conn;

    $existing_id_stmt = $conn->prepare("SELECT id FROM contacts WHERE email=:email");
    $existing_id_stmt->bindParam(':email', $email);
    $existing_id_stmt->execute();
    $existing_id_res = $existing_id_stmt->fetchAll();

    if(empty($firstname)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's first name.</span>";
        return 0;
    }     
    if(empty($lastname)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's last name.</span>";
        return 0;
    } 
    if(empty($email)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's email.</span>";
        return 0;
    } 
    if(empty($telephone)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's phone number.</span>";
        return 0;
    } 
    if(empty($company)){ 
        echo "<span class='resMsg'>Add contact failed: Enter contact's company.</span>";
        return 0;
    } 
    if(!preg_match("/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/", $telephone)){
        echo "<span class='resMsg'>Add contact failed: Phone number is not valid.</span>";
        return 0;
    }
    if(!empty($existing_id_res)){
        echo "<span class='resMsg'>Add contact failed: Contact already exists. Please use a different email.</span>";
        return 0;
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "<span class='resMsg'>Add contact failed: Email is not valid.</span>";
        return 0;
    }    

    $title = filter_data($title);
    $firstname = filter_data($firstname);
    $lastname = filter_data($lastname);
    $telephone = filter_data($telephone);
    $company = filter_data($company);
    $type = filter_data($type);
    $assigned_to = filter_data($assigned_to);
    return 1;
}


    if(set_data_filtered_flag() == 1){
        $assigned_to_name = explode(" ", $assigned_to);
        $assigned_to_fname = $assigned_to_name[0];
        $assigned_to_lname = $assigned_to_name[1];
    
        $assigned_to_stmt = $conn->prepare("SELECT id FROM users WHERE firstname = :assigned_to_fname AND lastname=:assigned_to_lname;");
        $assigned_to_stmt->bindParam(':assigned_to_fname', $assigned_to_fname);
        $assigned_to_stmt->bindParam(':assigned_to_lname', $assigned_to_lname);
        $assigned_to_stmt->execute();
        $assigned_to_results = $assigned_to_stmt->fetchAll();

        $assigned_to_id = $assigned_to_results[0]['id'];
        echo '<pre>'; print_r($assigned_to_results); echo '</pre>';

        $logged_in_user_id = $_SESSION['user_id'];

        $sql_stmt = $conn->prepare("INSERT INTO contacts VALUES (DEFAULT, :title, :firstname, :lastname, :email, :telephone, :company, :ctype, :assigned_to_id, :logged_in_user_id, DEFAULT, CURRENT_TIMESTAMP);");
        $sql_stmt->bindParam(':title', $title);
        $sql_stmt->bindParam(':firstname', $firstname);
        $sql_stmt->bindParam(':lastname', $lastname);
        $sql_stmt->bindParam(':email', $email);
        $sql_stmt->bindParam(':telephone', $telephone);
        $sql_stmt->bindParam(':company', $company);
        $sql_stmt->bindParam(':ctype', $type);
        $sql_stmt->bindParam(':assigned_to_id', $assigned_to_id);
        $sql_stmt->bindParam(':logged_in_user_id', $logged_in_user_id);

        if($sql_stmt->execute()){
            echo "<span class='resMsgSuccess'>New contact successfully submitted!</span><br>";
    
        } else{
            echo  "<span class='resMsg'>Add contact failed: Error adding to database.</span><br>";
        }
    } 
endif;
?>