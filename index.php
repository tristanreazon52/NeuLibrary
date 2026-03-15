
<?php
require_once 'database.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['log_visit'])) {
    $student_id = $_POST['student_id'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $gmail = $_POST['gmail'];
    $program = $_POST['program'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO students_visits (student_id, lastname, firstname, gmail, program, reason) 
            VALUES (:student_id, :lastname, :firstname, :gmail, :program, :reason)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $student_id,
            ':lastname' => $lastname,
            ':firstname' => $firstname,
            ':gmail' => $gmail,
            ':program' => $program,
            ':reason' => $reason
        ]);
        $success_message = "Visit logged successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

$query = "SELECT * FROM students_visits ORDER BY visit_time DESC LIMIT 15";
$stmt = $pdo->query($query);
$recent_visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEU Library | Visitor Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #121212;
            --card-dark: #1e1e1e;
            --accent-green: #006400; /* NEU Green */
            --text-main: #e0e0e0;
            --gold: #d4af37;
        }

        body { 
            background-color: var(--bg-dark); 
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #000;
            border-bottom: 2px solid var(--accent-green);
            padding: 1rem;
        }

        .logo-img {
            height: 60px;
            margin-right: 15px;
        }

        .card { 
            background-color: var(--card-dark); 
            border: 1px solid #333;
            border-radius: 12px;
            color: var(--text-main);
        }

        .form-control, .form-select {
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: white;
        }

        .form-control:focus, .form-select:focus {
            background-color: #333;
            color: white;
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.25 row rgba(0, 100, 0, 0.25);
        }

        .btn-primary {
            background-color: var(--accent-green);
            border: none;
        }

        .btn-primary:hover {
            background-color: #008000;
        }

        .table { color: var(--text-main); }
        .table-dark { --bs-table-bg: #1e1e1e; }
        
        .header-title {
            color: var(--gold);
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<nav class="navbar mb-5">
    <div class="container d-flex align-items-center">
        <img src="logo.png" alt="NEU Logo" class="logo-img"> 
        <div>
            <h3 class="header-title mb-0">NEW ERA UNIVERSITY</h3>
            <small class="text-secondary">Library Visitor Management System</small>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-lg p-4">
                <h4 class="mb-4 text-center" style="color: var(--gold);">Visitor Entry</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">STUDENT ID</label>
                        <input type="text" name="student_id" class="form-control" placeholder="00-0000-000" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label small text-secondary">FIRST NAME</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label small text-secondary">LAST NAME</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">PROGRAM/COURSE</label>
                        <input type="text" name="program" class="form-control" placeholder="e.g. BSIT" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">REASON</label>
                        <select name="reason" class="form-select" required>
                            <option value="Research">Research</option>
                            <option value="Study">Self-Study</option>
                            <option value="Borrow/Return">Borrow/Return</option>
                        </select>
                    </div>
                    <button type="submit" name="log_visit" class="btn btn-primary w-100 mt-2 py-2">LOG VISIT</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-lg p-4">
                <h4 class="mb-4" style="color: var(--gold);">Recent Logs</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr class="text-secondary">
                                <th>Time</th>
                                <th>Student</th>
                                <th>Program</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_visits as $visit): ?>
                                <tr>
                                    <td class="small"><?php echo date('h:i A', strtotime($visit['visit_time'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($visit['firstname'] . ' ' . $visit['lastname']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($visit['program']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($visit['reason']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>