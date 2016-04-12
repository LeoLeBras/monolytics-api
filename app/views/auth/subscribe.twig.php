<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="{{ URL }}assets/css/style.css" />
</head>
<body class="bg-blue">
  <form class="subscribe" action="subscribe" method="post">
    <h2 class="subscribe-title">Inscription</h2>
    {% if error %}
    <div class="subscribe-error">
      Inscription impossible
    </div>
    {% endif %}
    <div class="subscribe-fieldset">
      <input class="subscribe-input {{ (errors.name|length != 0) ? '--error' : '' }}" placeholder="Name" type="text" name="name" value="{{ form.name }}">
      {% if errors.name|length != 0 %}
      <div class="subscribe-input-labelError">
        {% for error in errors.name %}
          {{ error }}
        {% endfor %}
      </div>
      {% endif %}
      <input class="subscribe-input {{ (errors.email|length != 0) ? '--error' : '' }}
" placeholder="Email" type="text" name="email" value="{{ form.email }}">
      {% if errors.email|length != 0 %}
      <div class="subscribe-input-labelError">
        {% for error in errors.email %}
          {{ error }}
        {% endfor %}
      </div>
      {% endif %}
      <input class="subscribe-input {{ (errors.password|length != 0) ? '--error' : '' }}" placeholder="Password" type="password" name="password" value="{{ form.password }}">
      {% if errors.password|length != 0 %}
      <div class="subscribe-input-labelError">
        {% for error in errors.password %}
          {{ error }}
        {% endfor %}
      </div>
      {% endif %}
    </div>
    <input class="subscribe-submit" type="submit" value="Valider">
    <a class="login-subscribe" href="login">Se connecter</a>
  </form>
</body>
</html>
