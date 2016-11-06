<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
  </head>
  <body>
    <h1>Test Form</h1>
    <form action="{$route}" method="post">
      <input type="text" name="text">
      <input type="submit" name="submit" value="Lets go">
      {csrfToken()}
    </form>

    <pre>
      <? print_r($_GET); ?>
      <? print_r($_SERVER) ?>
    </pre>
  </body>
</html>
