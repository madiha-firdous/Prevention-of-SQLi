<?php
    include('connection.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


  /*  if (isset($_POST['submit'])) {
        $username = $_POST['user'];
        $password = $_POST['pass']; */ 



//---------------------------------------input escaping prevention method------------------------------------//
     /*  $username=stripcslashes($username);
        $password=stripcslashes($password);
        $username=mysqli_real_escape_string($conn, $username);
        $password=mysqli_real_escape_string($conn, $password);*/
        //================================================================//

       /* $sql = "select * from login where username = '$username' and password = '$password'";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);*/


//--------------------------------------prepared statements with parameterized queries. --------------------------//        
     /* // Prepare an SQL statement with placeholders
    $stmt = $->prepare("SELECT * FROM login WHERE username = ? AND password = ?");

    // Bind parameters to the placeholders
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    // Check the number of rows returned
    $count = $stmt->num_rows;

    // Close the statement
    $stmt->close();*/
    //============================================//




   /* if($count>0)
    {
        echo "<h1><center> Login Successfull!</center></h1>";
    }
    else
    {
        echo "<h1><center> Login Failed. Incorrect username/password!</center></h1>";
    }

    if($count>0)
    {
        echo "<div id='GFG'>";
        echo "<table>
        <tr bgcolor='#CCC'>
        <th>Username</th>
        <th>Password</th>
        </tr>";
        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr align=left style='font-size:18px;'>";
            echo "<td align=center>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td align=left>" . htmlspecialchars($row['password']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";

    }*/

   
    
//-------------------------------------------password hashing-------------------------------------------------//    
  /* // Hash the password
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert user with hashed password
    $stmt = $conn->prepare("INSERT INTO login (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        //echo "<h1><center>Registration Successful!</center></h1>";
        // Redirect the user or display a success message
    } else {
        echo "<h1><center>Registration Failed.</center></h1>";
    }*/
    //==========================================================//

//}



//-------------------------------------------WAF-------------------------------------------------------//
include('connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function detect_sql_injection($input) {
    // Define common SQL injection patterns
    $sql_injection_patterns = array(
        '/\bunion\b/i',
        '/\bselect\b/i',
        '/\binsert\b/i',
        '/\bupdate\b/i',
        '/\bdelete\b/i',
        '/\bdrop\b/i',
        '/\btruncate\b/i',
        '/\bcreate\b/i',
        '/\balter\b/i',
        '/\bexec\b/i',
        '/\bexecute\b/i',
        '/\bscript\b/i',
        '/\bjavascript\b/i',
        '/\biframe\b/i',
        '/\balert\b/i'
    );

    // Check for SQL injection patterns in the input
    foreach ($sql_injection_patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            return true; // SQL injection detected
        }
    }

    return false; // No SQL injection detected
}

if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // Check for potential SQL injection in username
    if (detect_sql_injection($username) || detect_sql_injection($password)) {
        // Potential SQL injection detected, block the request
        echo "<h1><center>SQL Injection Attempt Detected. Request Blocked.</center></h1>";
        exit;
    }

    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check the number of rows returned
    $count = $result->num_rows;

    if ($count > 0) {
        echo "<h1><center>Login Successful!</center></h1>";
        // Display user data if needed
    } else {
        echo "<h1><center>Login Failed. Incorrect username/password!</center></h1>";
    }

    // Close the statement
    $stmt->close();
}

?>
