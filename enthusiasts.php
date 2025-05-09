<?php
// enthusiasts.php

require_once __DIR__ . '/config2.php';
$pdo = getPDO();

// Handle actions (delete, edit, etc.)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                $enthusiastId = (int)$_GET['id'];
                $pdo->beginTransaction();
                try {
                    $stmt = $pdo->prepare("DELETE FROM enthusiasts WHERE enthusiast_id = ?");
                    $stmt->execute([$enthusiastId]);

                    $stmt = $pdo->prepare("DELETE FROM enthusiastinfo WHERE enthusiast_id = ?");
                    $stmt->execute([$enthusiastId]);

                    $stmt = $pdo->prepare("DELETE FROM artpreferences WHERE enthusiast_id = ?");
                    $stmt->execute([$enthusiastId]);

                    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = (SELECT user_id FROM enthusiasts WHERE enthusiast_id = ?)");
                    $stmt->execute([$enthusiastId]);

                    $pdo->commit();
                    $_SESSION['message'] = "Enthusiast deleted successfully";
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $_SESSION['error'] = "Error deleting enthusiast: " . $e->getMessage();
                }
                header("Location: enthusiasts.php");
                exit();
            }
            break;
    }
}

$stmt = $pdo->query("
    SELECT 
        e.enthusiast_id,
        u.user_id,
        u.username,
        u.email,
        u.created_at,
        u.last_login,
        ei.fullname,
        ei.phone_number,
        ei.shipping_address,
        ap.mediums,
        ap.styles,
        ap.budget_min,
        ap.budget_max
    FROM enthusiasts e
    JOIN users u ON e.user_id = u.user_id
    LEFT JOIN enthusiastinfo ei ON e.enthusiast_id = ei.enthusiast_id
    LEFT JOIN artpreferences ap ON e.enthusiast_id = ap.enthusiast_id
    ORDER BY u.created_at DESC
");
$enthusiasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enthusiasts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-light: #a4e0dd;
            --primary: #78cac5;
            --primary-dark: #4db8b2;
            --secondary-light: #f2e6b5;
            --secondary: #e7cf9b;
            --secondary-dark: #96833f;
            --light: #EEF9FF;
            --dark: #173836;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
            font-family: 'Segoe UI', sans-serif;
        }

        .top-navbar {
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hero-section {
            background: linear-gradient(to right, var(--primary-light), var(--secondary-light));
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .hero-section h2 {
            color: var(--dark);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-light);
            color: var(--dark);
            font-weight: 600;
            border-bottom: 2px solid var(--primary-dark);
        }

        .table-hover tbody tr:hover {
            background-color: var(--secondary-light);
        }

        .table td, .table th {
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }

        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 20px;
        }

        .badge.bg-primary {
            background-color: var(--primary) !important;
        }

        .badge.bg-secondary {
            background-color: var(--secondary-dark) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-warning {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            color: white;
        }

        .btn-info {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
            color: var(--dark);
        }

        .btn-info:hover {
            background-color: var(--primary);
            color: white;
        }

        .btn i {
            margin-right: 4px;
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
        }

        .modal-content,
        .alert {
            transition: all 0.3s ease-in-out;
            border-radius: 10px;
        }

        input.form-control, textarea.form-control {
            border-radius: 8px;
            border: 1px solid var(--primary-light);
        }

        input.form-control:focus, textarea.form-control:focus {
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 0.2rem rgba(120, 202, 197, 0.25);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <a href="admindashboard.php" class="btn btn-primary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <h4 class="mb-0 d-inline-block"><i class="fas fa-users me-2"></i>Manage Enthusiasts</h4>
            </div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEnthusiastModal">
                <i class="fas fa-plus me-1"></i> Add Enthusiast
            </a>
        </div>

        <div class="hero-section">
            <h2>Art Enthusiasts</h2>
            <p class="mb-0">Manage users who love collecting and discovering art.</p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Enthusiasts List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Preferences</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enthusiasts as $enthusiast): ?>
                            <tr>
                                <td><?= htmlspecialchars($enthusiast['enthusiast_id']) ?></td>
                                <td><?= htmlspecialchars($enthusiast['username']) ?></td>
                                <td><?= htmlspecialchars($enthusiast['fullname'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($enthusiast['email']) ?></td>
                                <td><?= htmlspecialchars($enthusiast['phone_number'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($enthusiast['mediums']): ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars($enthusiast['mediums']) ?></span>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($enthusiast['styles']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Not specified</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($enthusiast['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="view_enthusiast.php?id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="edit_enthusiast.php?id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?action=delete&id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this enthusiast?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Enthusiast Modal -->
    <div class="modal fade" id="addEnthusiastModal" tabindex="-1" aria-labelledby="addEnthusiastModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEnthusiastModalLabel"><i class="fas fa-user-plus me-2"></i>Add New Enthusiast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="add_enthusiast.php" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mediums" class="form-label">Preferred Mediums</label>
                                <input type="text" class="form-control" id="mediums" name="mediums" placeholder="e.g., painting, sculpture">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="styles" class="form-label">Preferred Styles</label>
                                <input type="text" class="form-control" id="styles" name="styles" placeholder="e.g., abstract, realism">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="budget_min" class="form-label">Minimum Budget</label>
                                <input type="number" class="form-control" id="budget_min" name="budget_min" step="0.01" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="budget_max" class="form-label">Maximum Budget</label>
                                <input type="number" class="form-control" id="budget_max" name="budget_max" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Enthusiast</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>