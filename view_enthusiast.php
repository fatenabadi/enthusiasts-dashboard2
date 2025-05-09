<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Enthusiast</title>
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
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
        }
        
        .top-navbar {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        h4, h5, h6 {
            color: var(--dark);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="top-navbar">
            <div>
                <a href="enthusiasts.php" class="btn btn-primary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <h4 class="mb-0 d-inline-block">Enthusiast Details</h4>
            </div>
            <div>
                <a href="edit_enthusiast.php?id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="?action=delete&id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this enthusiast? This action cannot be undone.')">
                    <i class="fas fa-trash me-1"></i> Delete
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="text-center"><?= htmlspecialchars($enthusiast['fullname'] ?? $enthusiast['username']) ?></h4>
                        <p class="text-muted text-center">Enthusiast</p>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <h6>Username</h6>
                            <p><?= htmlspecialchars($enthusiast['username']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Email</h6>
                            <p><?= htmlspecialchars($enthusiast['email']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Phone</h6>
                            <p><?= htmlspecialchars($enthusiast['phone_number'] ?? 'N/A') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Joined</h6>
                            <p><?= date('M d, Y', strtotime($enthusiast['created_at'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Last Login</h6>
                            <p><?= $enthusiast['last_login'] ? date('M d, Y H:i', strtotime($enthusiast['last_login'])) : 'Never' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Shipping Address</h5>
                            <p><?= htmlspecialchars($enthusiast['shipping_address'] ?? 'Not specified') ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Art Preferences</h5>
                            <?php if ($enthusiast['mediums'] || $enthusiast['styles']): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Preferred Mediums</h6>
                                        <p><?= htmlspecialchars($enthusiast['mediums'] ?? 'Not specified') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Preferred Styles</h6>
                                        <p><?= htmlspecialchars($enthusiast['styles'] ?? 'Not specified') ?></p>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6>Budget Range</h6>
                                        <p>
                                            <?php if ($enthusiast['budget_min'] || $enthusiast['budget_max']): ?>
                                                $<?= number_format($enthusiast['budget_min'] ?? 0, 2) ?> - $<?= number_format($enthusiast['budget_max'] ?? 0, 2) ?>
                                            <?php else: ?>
                                                Not specified
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Favorite Artists</h6>
                                        <p>
                                            <?php 
                                                $artists = array_filter([$enthusiast['artist1'], $enthusiast['artist2'], $enthusiast['artist3']]);
                                                echo $artists ? implode(', ', $artists) : 'Not specified';
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No art preferences specified</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>