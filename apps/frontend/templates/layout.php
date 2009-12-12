<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php include_slot('title', 'Pump Pro Edits') ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <header><h1><a href="<?php echo url_for('@homepage'); ?>">Pump Pro Edits</a></h1></header>
    <article>
      <?php echo $sf_content ?>
    </article>
    <nav>
      <p>Fix this!</p>
      <p>
<a href="/login">Log In</a>
or <a href="/register">Register</a>?
</p>
<ul>
<li>
    <h4>Members</h4>
    <ul>

    <li><a href="/register">Register</a></li>
    <li><a href="/confirm">Confirm Account</a></li>
    <li><a href="/login">Log In</a></li>
    <li><a href="/help">Account Help</a></li>
    <li><a href="/reset">Reset Password</a></li>
    </ul>

</li>
<li>
    <h4>Edits</h4>
    <ul>
    <li><a href="<?php echo url_for('@base_edit'); ?>">Base Edits</a></li>
    <li><a href="/stats">Edit Stat Getter</a></li>
    <li><a href="/edits">Edit List</a></li>
    </ul>

</li>
<li>
    <h4>Everyone</h4>
    <ul>
    <li><a href="/news">Previous Updates</a></li>
    <li><a href="/contact">Contact</a></li>
    <li><a href="<?php echo url_for('@thanks'); ?>">Credits/Thanks</a></li>
    </ul>

</li>
</ul>

    </nav>
    <footer>This website is © <a href="mailto:jafelds@gmail.com">Jason “Wolfman2000” Felds</a></footer>
  </body>
</html>
