<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <header>

    </header>
    
    <main class="container d-flex justify-content-center">
        <section id="registerFormSection" class="card w-50 mt-5 p-2">
            <fieldset class="card-body">
                <legend class="card-title">Login</legend>

                <form method="post" id="userLoginForm">
                    
                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Username</label>
                        <input type="email" name="username" id="emailInput" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label ">Password</label>
                        <div class="input-group ">                        
                            <input type="password" name="password" class="form-control" id="passwordInput">
                            <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn">
                                <i class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <input type="submit" value="Login" class="btn btn-primary my-3">
                    </div>
                </form>
                <div class="my-2">
                    Don't have an account yet? <a href="{{ route('auth.register') }}" class="card-link link-offset-2">Create one now</a>.
                </div>
            </fieldset>
        </section>
    </main>

    <footer>

    </footer>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        $(document).ready(function () {
            setupPasswordVisibilityToggle();

            setupFormSubmit({
                selector: '#userLoginForm', 
                requestType: 'POST', 
                actionUrl: "{{ route('api.auth.login') }}",
                successCallback: function (response) {
                    localStorage.setItem('auth-token', response.token);
                    window.location.href = "{{ route('task.list') }}"
                }
            });
        });

    </script>
</body>
</html>