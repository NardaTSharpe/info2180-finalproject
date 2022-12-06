<?php session_start();
    $host = 'localhost';
    $username = '';
    $password = '';
    $dbname = 'dolphin_crm';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    $useremail= filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL); 
    $useremail= filter_var($useremail,FILTER_VALIDATE_EMAIL); 
    $userpassword= filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING); 
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :useremail;");
    $stmt->bindParam(':useremail', $useremail);
    $stmt->execute();
    $results = $stmt->fetchAll();

    if(empty($results)){
        echo "<span class='resMsg'>Login failed: User not found.</span>";
    } else {
        $findpssd = $conn->prepare("SELECT password FROM users WHERE email = :useremail;");
        $findpssd->bindParam(':useremail', $useremail);
        $findpssd->execute();
        $pssd = $findpssd->fetchAll();

        if(isset($pssd)){
            $validpssd=$pssd[0]['password'];
            if($useremail == "admin@project2.com"){
                if($validpssd === md5($userpassword)){
                    $_SESSION['email']=$results[0]['email'];
                    $_SESSION['firstname']=$results[0]['firstname'];
                    $_SESSION['lastname']=$results[0]['lastname'];
                    $_SESSION['user_id']=$results[0]['id'];
                    $_SESSION['role']=$results[0]['role'];
                    echo "redirect";
                    // echo header("Location: dashboard.html");
                }else {
                    echo "<span class='resMsg'>Login failed: Invalid email or password entered.</span>";
                }
            } else {
                if(password_verify($userpassword, $validpssd)){
                    $_SESSION['email']=$results[0]['email'];
                    $_SESSION['firstname']=$results[0]['firstname'];
                    $_SESSION['lastname']=$results[0]['lastname'];
                    $_SESSION['user_id']=$results[0]['id'];
                    $_SESSION['role']=$results[0]['role'];
                    echo "redirect";
                }else {
                    echo "<span class='resMsg'>Login failed: Invalid email or password entered.</span>";
                }
            }
        }
    }
    ?>
