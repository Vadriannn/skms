<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SKMS</title>
</head>
<body>

    <h1>Login SKMS</h1>
    <p>Student Knowledge Management System</p>
    <hr>

    @if(session('success'))
        <p><strong>{{ session('success') }}</strong></p>
    @endif

    @if($errors->any())
        <p style="color: red;"><strong>{{ $errors->first() }}</strong></p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <p>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </p>

        <p>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </p>

        <p>
            <button type="submit">Login</button>
        </p>
    </form>

    <hr>
    <h3>Akun Demo untuk Pengujian:</h3>
    <ul>
        <li>Super Admin: admin@gmail.com / admin</li>
        <li>Validator: validator@gmail.com / validator</li>
        <li>Data Entry: dataentry@gmail.com / dataentry</li>
    </ul>

</body>
</html>
