<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enthusiast | Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-light: #a4e0dd;
            --primary: #78cac5;
            --primary-dark: #4db8b2;
            --secondary-light: #f2e6b5;
            --secondary: #e7cf9b;
            --secondary-dark: #96833f;
            --light: #f8f9fa;
            --dark: #173836;
            --accent: #e83e8c;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            font-family: 'Montserrat', sans-serif;
        }
        
        h1, h2, h3, h4, .card-header {
            font-family: 'Playfair Display', serif;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 0 !important;
            padding: 18px 25px;
            border-bottom: 3px solid rgba(255,255,255,0.1);
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
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
        }
        
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
            transform: translateY(-2px);
        }
        
        .top-navbar {
            background-color: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(120, 202, 197, 0.25);
        }
        
        .section-title {
            color: var(--primary-dark);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            border-radius: 8px;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
        }
        
        .is-valid {
            border-color: #28a745 !important;
        }
        
        @media (max-width: 768px) {
            .top-navbar {
                flex-direction: column;
                text-align: center;
            }
            
            .top-navbar > div {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <a href="enthusiasts.php" class="btn btn-primary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Enthusiasts
                </a>
                <h4 class="mb-0">Edit Enthusiast Profile</h4>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-3">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="enthusiastForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($enthusiast['username']) ?>" 
                                       required
                                       pattern="[a-zA-Z0-9_]{4,20}"
                                       title="Username must be 4-20 characters long and can only contain letters, numbers, and underscores">
                                <div class="invalid-feedback">Username must be 4-20 characters long and can only contain letters, numbers, and underscores</div>
                                <small class="text-muted">4-20 characters, letters, numbers, and underscores only</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($enthusiast['email']) ?>" 
                                       required
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Please enter a valid email address">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($enthusiast['fullname'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($enthusiast['phone_number'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3"><?= htmlspecialchars($enthusiast['shipping_address'] ?? '') ?></textarea>
                    </div>
                    
                    <h5 class="section-title mt-4">
                        <i class="fas fa-palette"></i> Art Preferences
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mediums" class="form-label">Preferred Mediums</label>
                                <input type="text" class="form-control" id="mediums" name="mediums" value="<?= htmlspecialchars($enthusiast['mediums'] ?? '') ?>" placeholder="e.g., painting, sculpture, photography">
                                <small class="text-muted">Separate multiple mediums with commas</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="styles" class="form-label">Preferred Styles</label>
                                <input type="text" class="form-control" id="styles" name="styles" value="<?= htmlspecialchars($enthusiast['styles'] ?? '') ?>" placeholder="e.g., abstract, realism, surrealism">
                                <small class="text-muted">Separate multiple styles with commas</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_min" class="form-label">Minimum Budget ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="budget_min" name="budget_min" value="<?= htmlspecialchars($enthusiast['budget_min'] ?? '') ?>" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget_max" class="form-label">Maximum Budget ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="budget_max" name="budget_max" value="<?= htmlspecialchars($enthusiast['budget_max'] ?? '') ?>" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="artist1" class="form-label">Favorite Artist 1</label>
                                <input type="text" class="form-control" id="artist1" name="artist1" value="<?= htmlspecialchars($enthusiast['artist1'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="artist2" class="form-label">Favorite Artist 2</label>
                                <input type="text" class="form-control" id="artist2" name="artist2" value="<?= htmlspecialchars($enthusiast['artist2'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="artist3" class="form-label">Favorite Artist 3</label>
                                <input type="text" class="form-control" id="artist3" name="artist3" value="<?= htmlspecialchars($enthusiast['artist3'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="view_enthusiast.php?id=<?= $enthusiastId ?>" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div>
                            <a href="?action=delete&id=<?= $enthusiast['enthusiast_id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this enthusiast? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Profile
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('enthusiastForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            let valid = true;

            // Validate username
            const usernameRegex = /^[a-zA-Z0-9_]{4,20}$/;
            if (!usernameRegex.test(username.value)) {
                username.classList.add('is-invalid');
                valid = false;
            } else {
                username.classList.remove('is-invalid');
            }

            // Validate email
            const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            if (!emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                valid = false;
            } else {
                email.classList.remove('is-invalid');
            }

            if (!valid) {
                e.preventDefault();
                // Scroll to first invalid field
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Add real-time validation feedback
        document.getElementById('username').addEventListener('input', function() {
            const regex = /^[a-zA-Z0-9_]{4,20}$/;
            if (regex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });

        document.getElementById('email').addEventListener('input', function() {
            const regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            if (regex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });

        // Add focus styling to form controls
        document.querySelectorAll('.form-control').forEach(control => {
            control.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.color = 'var(--primary-dark)';
            });
            
            control.addEventListener('blur', function() {
                this.parentElement.querySelector('.form-label').style.color = 'var(--primary-dark)';
            });
        });
    </script>
</body>
</html>