<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form with Validation</title>
    <style>
    .error {
        color: red;
        font-size: 12px;
    }
    </style>
</head>

<body>
    <h2>Contact Us</h2>
    <form id="contactForm" action="send_mail.php" method="POST" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
        <span id="nameError" class="error"></span><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <span id="emailError" class="error"></span><br><br>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" maxlength="15">
        <span id="phoneError" class="error"></span><br><br>

        <label for="services">Services:</label>
        <select id="services" name="services">
            <option value="">-- Select a Service --</option>
            <option value="consultation">Consultation</option>
            <option value="treatment">Treatment</option>
            <option value="check-up">Check-up</option>
        </select>
        <span id="servicesError" class="error"></span><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5"></textarea>
        <span id="messageError" class="error"></span><br><br>

        <button type="submit">Submit</button>
    </form>

    <script>
    function validateForm() {
        let isValid = true;

        // Clear error messages
        document.getElementById("nameError").innerHTML = "";
        document.getElementById("emailError").innerHTML = "";
        document.getElementById("phoneError").innerHTML = "";
        document.getElementById("servicesError").innerHTML = "";
        document.getElementById("messageError").innerHTML = "";

        // Validate Name
        const name = document.getElementById("name").value;
        if (name.length < 2) {
            document.getElementById("nameError").innerHTML = "Name must be at least 2 characters.";
            isValid = false;
        }

        // Validate Email
        const email = document.getElementById("email").value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            document.getElementById("emailError").innerHTML = "Please enter a valid email address.";
            isValid = false;
        }

        // Validate Phone Number
        const phone = document.getElementById("phone").value;
        const phonePattern = /^[0-9]{10,}$/; // Ensure at least 10 digits
        if (!phonePattern.test(phone)) {
            document.getElementById("phoneError").innerHTML =
                "Please enter a valid phone number with at least 10 digits.";
            isValid = false;
        }

        // Validate Services
        const services = document.getElementById("services").value;
        if (services === "") {
            document.getElementById("servicesError").innerHTML = "Please select a service.";
            isValid = false;
        }

        // Validate Message
        const message = document.getElementById("message").value;
        if (message.length < 10) {
            document.getElementById("messageError").innerHTML = "Message must be at least 10 characters.";
            isValid = false;
        }

        return isValid; // If any validation fails, the form won't submit
    }
    </script>
</body>

</html>