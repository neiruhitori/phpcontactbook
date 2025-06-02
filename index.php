<?php
// Show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contacts_db";

// Local dev
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add contact
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO contacts (name, phone, email) VALUES ('$name', '$phone', '$email')";

    if ($conn->query($sql) === TRUE) {
        $message = "Contact added successfully.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete contact
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM contacts WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Contact deleted successfully.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update contact
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "UPDATE contacts SET name='$name', phone='$phone', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $message = "Contact updated successfully.";
    } else {
        $message = "Error updating contact: " . $conn->error;
    }
}

// Fetch contacts
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #00c3ff 0%, #ffff1c 100%);
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 32px 32px 24px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            backdrop-filter: blur(4px);
        }

        .reset-button {
            background: linear-gradient(90deg, #ffb347 0%, #ffcc33 100%);
            color: #333;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
            transition: background 0.3s;
        }

        .reset-button:hover {
            background: linear-gradient(90deg, #ffcc33 0%, #ffb347 100%);
        }

        h2 {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 0;
            color: #222;
            letter-spacing: 2px;
        }

        h4 {
            text-align: center;
            font-style: italic;
            color: #666;
            margin-top: 8px;
            margin-bottom: 24px;
        }

        form {
            margin-bottom: 28px;
        }

        label {
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px 10px;
            margin-bottom: 14px;
            border-radius: 7px;
            border: 1px solid #bbb;
            font-size: 1em;
            background: #f9f9f9;
            transition: border 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border: 1.5px solid #00c3ff;
            outline: none;
        }

        input[type="submit"] {
            background: linear-gradient(90deg, #00c3ff 0%, #ffff1c 100%);
            color: #222;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, color 0.3s;
        }

        input[type="submit"]:hover {
            background: linear-gradient(90deg, #ffff1c 0%, #00c3ff 100%);
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #00c3ff;
            color: #fff;
            font-weight: 600;
            letter-spacing: 1px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f0faff;
        }

        .notification {
            background: linear-gradient(90deg, #00c3ff 0%, #ffff1c 100%);
            color: #222;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        a {
            color: #00c3ff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        a:hover {
            color: #ffb347;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>PHP ContactBook Brr</h2>
        <h4 style="font-style: italic;">Simple contact management app written in PHP</h4>
        <?php if (isset($message)): ?>
            <div class="notification"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['edit'])) { ?>
            <?php
            $sql = "SELECT * FROM contacts WHERE id=" . $_GET['edit'];
            $records = $conn->query($sql);
            $editedContact = $records->fetch_assoc();
            ?>
        <?php } else { ?>
            <?php $editedContact = ['name' => '', 'phone' => '', 'email' => '']; ?>
        <?php } ?>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php if (isset($_GET['edit'])) echo $_GET['edit']; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" placeholder="Contact's full name" value="<?php if (isset($_GET['edit'])) echo $editedContact['name']; ?>" required><br>
            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" placeholder="Contact's phone number" value="<?php if (isset($_GET['edit'])) echo $editedContact['phone']; ?>" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" placeholder="Contact's email address" value="<?php if (isset($_GET['edit'])) echo $editedContact['email']; ?>" required><br><br>
            <a class="reset-button" href="index.php">Reset</a>
            <?php if (isset($_GET['edit'])): ?>
                <input type="submit" name="update" value="Update Contact">
            <?php else: ?>
                <input type="submit" name="add" value="Add Contact">
            <?php endif; ?>
        </form>
        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td><a href='?edit=" . $row["id"] . "'>Edit</a> | <a href='?delete=" . $row["id"] . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                if (!$result) {
                    echo "<tr><td colspan='4' style='color:red;'>Query error: " . $conn->error . "</td></tr>";
                } else {
                    echo "<tr><td colspan='4'>No contacts found.</td></tr>";
                }
            }
            ?>
        </table>
    </div>

</body>

</html>

<?php
$conn->close();
?>