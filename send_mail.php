<?php

require 'PHPMailer/PHPMailerAutoload.php'; // This will autoload PHPMailer

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_form";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
    $services = filter_var(trim($_POST['services']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Server-side validation
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (empty($phone) || !preg_match('/^[0-9]{10,}$/', $phone)) {
        $errors[] = "Please enter a valid phone number with at least 10 digits.";
    }
    if (empty($services)) {
        $errors[] = "Please select a service.";
    }
    if (empty($message) || strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters.";
    }

    // If no errors, proceed to send email and save data
    if (count($errors) == 0) {

        // Save data into the database
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, services, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $services, $message);
 
        if ($stmt->execute()) {
            echo "Message saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

        // Send email using PHPMailer and SMTP
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'ashwanishukla669@gmail.com'; // Replace with your email
            $mail->Password = 'kuvp qoly byea tbzu'; // Replace with your email password or app-specific password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email settings
            $mail->setFrom('ashwanishukla669@gmail.com', 'Your Name'); // Replace with your email
            $mail->addAddress('ashwani@omlogic.co.in', 'Recipient Name'); // Replace with recipient's email
            $mail->addReplyTo($email, $name); // Optional: reply to the sender's email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "<p><strong>Name:</strong> $name</p>
                              <p><strong>Email:</strong> $email</p>
                              <p><strong>Phone:</strong> $phone</p>
                              <p><strong>Service:</strong> $services</p>
                              <p><strong>Message:</strong><br>$message</p>";
            $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\nService: $services\nMessage:\n$message"; // Plain text version

            $mail->send();
            echo "Email has been sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}

$conn->close();
?>
