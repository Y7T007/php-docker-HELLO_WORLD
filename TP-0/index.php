<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TP - 0</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>


<?php 

// Dans ce cas j'ai utilise 3 criteres pour valider un email:

    function validateEmail($email) {
    // 1. Verifier si l'email est vide
        if (empty($email)) {
            return false;
        }

    // 2. Verifier si l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

    // 3. Verifier si le domaine de l'email existe
        $domain = explode('@', $email)[1];
        if (!checkdnsrr($domain, 'MX')) {
            return false;
        }
        
        return true;
    }

    function removeDuplicates($emails) {
        $uniqueEmails = array_unique($emails);
        $updatedEmails = implode(PHP_EOL, $uniqueEmails);
        file_put_contents('EmailsT.txt', $updatedEmails);
    }
    
?>
<body>
    <div class="table-responsive">
    <h1>Q0 : afficher tous les emails dans le fichier Emails.txt</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>File: Emails.txt</th>
                    <th>Status</th>
                    <th>Frequency</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $emails = file('./Emails.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $n=0;
                if (isset($emails)){
                    foreach($emails as $email){
                        $status = validateEmail($email)? "<td style='color:green;'>Valid</td>" :"<td style='color:red;'>Invalid</td>";
                        echo " 
                        <tr>
                            <td>Email $n :".$email."</td>
                            ".$status."
                            <td>".array_count_values($emails)[$email]."</td>
                        </tr>";
                        $n+=1;
                    }
                }

            ?>
            </tbody>
        </table>

        <center><h1>Q1 : supprimer les doublons de la liste des emails</h1></center>
        <table class="table">
            <thead>
                <tr>
                    <th>File: Emails.txt</th>
                    <th>Status</th>
                    <th>Frequency</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $emailCounts = array_count_values($emails);
                foreach($emails as $email){
                    $status = validateEmail($email)? "<td style='color:green;'>Valid</td>" :"<td style='color:red;'>Invalid</td>";
                    if(validateEmail($email) && $emailCounts[$email] > 1) {
                        echo " 
                        <tr>
                            <td>Email $n :".$email."</td>
                            ".$status."
                            <td>".$emailCounts[$email]."</td>
                        </tr>";
                    };  
                    $n+=1;
                }
            ?>
            <?php
                removeDuplicates($emails);
            ?>  
            </tbody>
        </table>

        <form method="POST" action="">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">Add Email</button>
                </form>

                <?php
                    if (isset($_POST['email'])) {
                        $email = $_POST['email'];
                        $emails = file('./Emails.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if (!in_array($email, $emails)) {
                            if (validateEmail($email)) {
                                file_put_contents('Emails.txt', $email . PHP_EOL, FILE_APPEND);
                                echo "<script>location.reload();</script>";
                            } else {
                                echo "<script>alert('Invalid Email');</script>";
                            }
                        } else {
                            echo "<script>alert('Email already exists');</script>";
                        }
                    }
                ?>         

            

    </div>
    <br><br><br><br><br><br><br><br>

   
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>