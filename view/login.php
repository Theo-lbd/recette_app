<?php include 'header.php'; ?>

<form method="post" action="index.php?controller=user&action=login">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

<?php include 'footer.php'; ?>
