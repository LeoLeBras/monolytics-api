<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{% block title %}{% endblock %}</title>
  <link rel="stylesheet" href="{{ URL }}assets/css/style.css" />
  {% block head %}{% endblock %}
</head>
<body>
  <header class="header">
    <div class="header-wrapper">
      <div class="header-user">
        {% if is_logged %}
        <span class="header-username">{{ user.name }}</span>
        <a class="header-logout" href="{{URL}}logout">Se déconnecter</a>
        {% endif %}
      </div>
      {% block headerSubheader %}{% endblock %}
      <h1 class="header-title">{{ block('title') }}</h1>
      {% block headerContent %}{% endblock %}
    </div>
  </header>
  <div class="main">
    {% block content %}{% endblock %}
  </div>
  {% block javascript %}{% endblock %}
</body>
</html>
