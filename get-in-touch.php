<?php
    // Database connection details
    $db_hostname = "127.0.0.1";  // Localhost
    $db_username = "root";       // Your database username (default is usually root)
    $db_password = "";           // Your database password (default is usually empty)
    $db_name = "wanderlust";     // Name of your database (updated for Wanderlust project)

    // Establish connection to the database(his function establishes a connection to the MySQL database using the credentials above. If the connection is successful, it returns a connection object ($conn); otherwise, it returns false.
    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

    // Checking if the connection was successful
    if (!$conn) {
        echo "Connection failed: " . mysqli_connect_error();
        exit;
    }

    // Getting form data from POST request(its a superglobal array that collects form data when the method POST is used in the form)
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare and execute the SQL query to insert form data into the contact table(This SQL query is used to insert the data into the contact table in the MySQL database.
    $sql = "INSERT INTO contact (Name, Email, Phone, Subject, Message) VALUES ('$name', '$email', '$phone', '$subject', '$message')";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful or error handling
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    // Success message
    echo "Thank you for reaching out! We'll get in touch with you soon.";

    // Close the database connection
    mysqli_close($conn);
?>
