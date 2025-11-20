<?php
// students.php
declare(strict_types=1);
session_start();

// DB config
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'school';

// connect
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_errno) {
    die('Connection failed: ' . $conn->connect_error);
}

// initialize variables (for form)
$id = 0;
$name = $email = $phone = $father = $mother = '';
$error = '';

// Handle POST (Add / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // read posted values
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $father = trim($_POST['father_name'] ?? '');
    $mother = trim($_POST['mother_name'] ?? '');

    // basic validation
    if ($name === '') {
        $error = 'Name is required.';
    } elseif ($email === '') {
        $error = 'Email is required.';
    } else {
        // Insert or Update using prepared statements
        if ($id > 0) {
            // UPDATE
            $stmt = $conn->prepare(
                "UPDATE students SET name = ?, email = ?, phone = ?, father_name = ?, mother_name = ? WHERE id = ?"
            );
            $stmt->bind_param('sssssi', $name, $email, $phone, $father, $mother, $id);
        } else {
            // INSERT
            $stmt = $conn->prepare(
                "INSERT INTO students (name, email, phone, father_name, mother_name) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('sssss', $name, $email, $phone, $father, $mother);
        }

        if ($stmt === false) {
            $error = 'Prepare failed: ' . $conn->error;
        } else {
            if ($stmt->execute()) {
                // on success redirect to clear POST (prevents form resubmission)
                $stmt->close();
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error = 'Database error: ' . $stmt->error;
                $stmt->close();
            }
        }
    }
}

// Handle Delete (via GET ?delete=ID)
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        $stmt = $conn->prepare('DELETE FROM students WHERE id = ?');
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $stmt->close();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle Edit: load DB values into form when ?edit=ID
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($edit_id > 0) {
        $stmt = $conn->prepare('SELECT id, name, email, phone, father_name, mother_name FROM students WHERE id = ?');
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            // populate form variables safely
            $id = (int)$row['id'];
            $name = $row['name'];
            $email = $row['email'];
            $phone = $row['phone'];
            $father = $row['father_name'];
            $mother = $row['mother_name'];
        }
        $stmt->close();
    }
}

// Read all students for listing
$students = [];
$result = $conn->query('SELECT id, name, email, phone, father_name, mother_name FROM students ORDER BY id DESC');
if ($result) {
    while ($r = $result->fetch_assoc()) {
        $students[] = $r;
    }
    $result->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student CRUD</title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; }
  form { margin-bottom: 20px; }
  label { display:block; margin-top: 8px; }
  input[type="text"], input[type="email"] { width: 320px; padding: 6px; }
  .error { color: red; margin-bottom: 10px; }
  table { border-collapse: collapse; width: 90%; }
  th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
</style>
</head>
<body>
  <h2>Add / Edit Student</h2>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <label>Name:
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    </label>
    <label>Email:
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    </label>
    <label>Phone:
      <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
    </label>
    <label>Father Name:
      <input type="text" name="father_name" value="<?= htmlspecialchars($father) ?>">
    </label>
    <label>Mother Name:
      <input type="text" name="mother_name" value="<?= htmlspecialchars($mother) ?>">
    </label>

    <br>
    <button type="submit"><?= $id ? 'Update' : 'Add' ?></button>
    <?php if ($id): ?>
      <a href="<?= $_SERVER['PHP_SELF'] ?>">Cancel</a>
    <?php endif; ?>
  </form>

  <h2>Students List</h2>
  <?php if (empty($students)): ?>
    <p>No students found.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Name</th><th>Email</th><th>Phone</th><th>Father</th><th>Mother</th><th>Action</th>
      </tr>
      <?php foreach ($students as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td><?= htmlspecialchars($row['father_name']) ?></td>
          <td><?= htmlspecialchars($row['mother_name']) ?></td>
          <td>
            <a href="?edit=<?= (int)$row['id'] ?>">Edit</a> |
            <a href="?delete=<?= (int)$row['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</body>
</html>
