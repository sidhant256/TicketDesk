<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - TicketDesk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
            <div class="login-logo"><b>Ticket</b>Desk</div>
            <div class="card">
                <div class="card-body login-card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus />
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required/>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </form>
                </div>
            </div>
    </div>
    
</body>
</html>