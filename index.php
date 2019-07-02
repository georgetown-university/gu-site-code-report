<!DOCTYPE html>
<html>
  <head>
    <title>Georgetown Site Reports</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
  </head>

  <body>
    <header>
      <h1>Georgetown Site Reports</h1>

      <p>
        This report lists all instances of <code>&lt;script&gt;</code>, <code>&lt;iframe&gt;</code>,
        <code>&lt;object&gt;</code>, and <code>&lt;form&gt;</code> tags in the main content area of
        any Georgetown site.
      </p>

      <p>
        Report created: <?php print date('d F Y'); ?>
      </p>
    </header>

    <main>
      <?php require( 'inc/report.php' ); ?>
    </main>
  </body>
</html>
