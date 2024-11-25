<?php
$MySQL = mysqli_connect("localhost", "root", "", "vjezba18") or die('Error connecting to MySQL server.');

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM users WHERE id = '$edit_id'";
    $result = mysqli_query($MySQL, $query);
    $user = mysqli_fetch_array($result);
}

if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $country_id = $_POST['country_id'];

    $update_query = "UPDATE users SET user_firstname = '$user_firstname', user_lastname = '$user_lastname', country_id = '$country_id' WHERE id = '$user_id'";
    mysqli_query($MySQL, $update_query);
    header("Location: index.php");
}

$query = "SELECT u.id, u.user_firstname, u.user_lastname, u.email, c.country_name
          FROM users u
          LEFT JOIN countries c ON u.country_id = c.id";

$result = mysqli_query($MySQL, $query);

$countries_query = "SELECT * FROM countries";
$countries_result = mysqli_query($MySQL, $countries_query);

?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prikaz korisnika i uređivanje podataka</title>
</head>
<body>
    <h1>Popis korisnika i njihovi podaci</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Edit</th>
                </tr>";

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>
                    <td>{$row['user_firstname']}</td>
                    <td>{$row['user_lastname']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['country_name']}</td>
                    <td><a href='?edit_id={$row['id']}'>Edit</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nema podataka za prikaz!</p>";
    }

    if (isset($edit_id)) {
    ?>
        <h2>Uredi korisnika</h2>
        <form method="POST" action="index.php">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <label for="user_firstname">First Name:</label>
            <input type="text" name="user_firstname" value="<?php echo $user['user_firstname']; ?>" required><br><br>
            <label for="user_lastname">Last Name:</label>
            <input type="text" name="user_lastname" value="<?php echo $user['user_lastname']; ?>" required><br><br>
            <label for="country_id">Country:</label>
            <select name="country_id" required>
                <?php
                while ($country = mysqli_fetch_array($countries_result)) {
                    $selected = ($country['id'] == $user['country_id']) ? 'selected' : '';
                    echo "<option value='{$country['id']}' $selected>{$country['country_name']}</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" name="update" value="Spremi promjene">
        </form>
    <?php
    }

    mysqli_close($MySQL);
    ?>
</body>
</html>
