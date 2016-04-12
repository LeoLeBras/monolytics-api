<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="{{ URL }}assets/css/style.css" />
</head>
<body class="bg-blue">
  <form class="login" action="login" method="post">
    <h2 class="login-title">Login</h2>
    {% if error %}
    <div class="login-error">
      Erreur : identifiants incorrects
    </div>
    {% endif %}
    <div class="login-fieldset">
      <input class="login-input" placeholder="Email" type="text" name="email" value="">
      <input class="login-input" placeholder="Password" type="password" name="password" value="">
    </div>
    <input class="login-submit" type="submit" value="Se connecter">
    <a class="login-subscribe" href="subscribe">Inscription</a>
  </form>
</body>
</html>
