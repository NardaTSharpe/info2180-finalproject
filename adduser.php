<?php 
    $host = 'localhost';
    $username = ''; 
    $password = '';
    $dbname = 'dolphin_crm';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    $firstname= filter_input(INPUT_POST,"firstname",FILTER_SANITIZE_STRING); 
    $lastname= filter_input(INPUT_POST,"lastname",FILTER_SANITIZE_STRING); 
    $email= filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL); 
    $password= filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING); 
    $role= filter_input(INPUT_POST,"role",FILTER_SANITIZE_STRING); 
?>

<?php
    function filter_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function set_data_filtered_flag(){
        global $firstname, $lastname, $email, $password, $role, $conn;
        $pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
        $existing_id_stmt = $conn->prepare("SELECT id FROM users WHERE email=:email");
        $existing_id_stmt->bindParam(':email', $email);
        $existing_id_stmt->execute();
        $existing_id_res = $existing_id_stmt->fetchAll();

        if(empty($firstname)){ 
            echo "<span class='resMsg'>Add user failed: Enter user's first name.</span>";
            return 0;
        }     
        if(empty($lastname)){ 
            echo "<span class='resMsg'>Add user failed: Enter user's last name.</span>";
            return 0;
        } 
        if(empty($email)){ 
            echo "<span class='resMsg'>Add user failed: Enter user's email.</span>";
            return 0;
        } 
        if(empty($password)){ 
            echo "<span class='resMsg'>Add user failed: Enter user's password.</span>";
            return 0;
        } 
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            echo "<span class='resMsg'>Add user failed: Email is not valid.</span>";
            return 0;
        }
        if(!empty($existing_id_res)){
            echo "<span class='resMsg'>Add user failed: User already exists. Please use a different email.</span><br>";
            return 0;
        }
        if(!preg_match($pattern, $password)) {
            echo "<span class='resMsg'>Add user failed: Password should have at least 1 number and 1 letter, at least 1 capital letter and be at least 8 characters long</span><br>";
            return 0;
        }
        $firstname = filter_data($firstname);
        $lastname = filter_data($lastname);
        $password =  password_hash($password, PASSWORD_DEFAULT);
        return 1;
    }
    
    if(set_data_filtered_flag() == 1){
        $stmt = $conn->prepare("insert into users (firstname, lastname, password, email, role) 
        values (:firstname, :lastname, :password, :email, :role)" );
        
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindValue(':password', $password);
        $stmt->bindParam(':email', $email);    
        $stmt->bindParam(':role', $role); 

        if($stmt->execute()){
            echo "<span class='resMsgSuccess'>New user successfully submitted!</span><br>";
            $conn = null;
        } else{
           echo  "<span class='resMsg'>Add user failed: Error adding to database.</span><br>";
        }

    } 
?>

