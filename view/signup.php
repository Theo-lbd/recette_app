<?php include 'header.php'; ?>

<form method="post" action="index.php?action=register">
    First Name: <input type="text" name="firstname" required><br>
    Last Name: <input type="text" name="lastname" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Sign Up">
</form>

<?php include 'footer.php'; ?>
