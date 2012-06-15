<?php 
function pr($value = array(), $name = '$undefined')
{
    echo '<table border="1">'
    . '<tr><th>' . $name . '</th></tr>'
    . '<tr><td><pre>' . print_r($value, true) . '</pre></td></tr>'
    . '</table>';
}

require_once ('jh_rewrite.class.php');
$jh_rewrite_obj = new JhRewrite('path');
$jh_rewrite_obj->addRoute('home', 'home', 'index.php');
$jh_rewrite_obj->addRoute('about', 'about', 'about.php');
$jh_rewrite_obj->addRoute('about_username', 'about-<:username>', 'about.php');
$jh_rewrite_obj->addRoute('edit_episode', 'edit-<:tvshow>-saison-<:season>-episode-<:episode>', array('controller' => 'episodes', 'action' => 'edit'));
$jh_rewrite_obj->addRoute('get_summary_1', 'get-summary-of-<:tvshow>-s<:season>x<:episode>.<:format>', array('controller' => 'episodes', 'action' => 'summary'));
$jh_rewrite_obj->addRoute('get_summary_2', '<:tvshow>/<:season>/<:episode>/summary.<:format>', array('controller' => 'episodes', 'action' => 'summary'));
$jh_rewrite_obj->addRoute('edit_username', 'edit/<:username>', array('controller' => 'users', 'action' => 'edit'));
$jh_rewrite_obj->addRoute('add_user', 'add-user', array('controller' => 'users', 'action' => 'add'));
$get = $jh_rewrite_obj->dispatch('home');
$_GET = $get['args'];

pr($get['href'], 'HREF');
echo '<br />';
pr($_GET, '$_GET');
echo '<br />';
pr($_POST, '$_POST');

echo '<ul>';
echo '<li><a href="' . $jh_rewrite_obj->url('home') . '">Home</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('about') . '">About</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('about_username', array('username' => 'josselin')) . '">About Josselin</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('edit_username', array('username' => 'josselin')) . '">Edit Josselin</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('edit_episode', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04')) . '">Edit Hawaii s02x04</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('get_summary_1', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04', 'format' => 'html')) . '">Get Hawaii s02x04 summary (1)</a></li>';
echo '<li><a href="' . $jh_rewrite_obj->url('get_summary_2', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04', 'format' => 'html')) . '">Get Hawaii s02x04 summary (2)</a></li>';
echo '</ul>';
?>

<h2>Method GET</h2>
<form name="monForm" action="<?php echo $jh_rewrite_obj->url('add_user'); ?>" method="get">
    <label for="login">Login</label>
    <input type="text" id="login" name="login" />
    <label for="email">eMail</label>
    <input type="text" id="email" name="email" />
    <input type="submit" />
</form>

<h2>Method POST</h2>
<form name="monForm" action="<?php echo $jh_rewrite_obj->url('add_user'); ?>" method="post">
    <label for="login">Login</label>
    <input type="text" id="login" name="login" />
    <label for="email">eMail</label>
    <input type="text" id="email" name="email" />
    <input type="submit" />
</form>

<hr />

<pre>
require_once ('jh_rewrite.class.php');
$jh_rewrite_obj = new JhRewrite('path');
$jh_rewrite_obj->addRoute('home', 'home', 'index.php');
$jh_rewrite_obj->addRoute('about', 'about', 'about.php');
$jh_rewrite_obj->addRoute('about_username', 'about-<:username>', 'about.php');
$jh_rewrite_obj->addRoute('edit_episode', 'edit-<:tvshow>-saison-<:season>-episode-<:episode>', array('controller' => 'episodes', 'action' => 'edit'));
$jh_rewrite_obj->addRoute('get_summary_1', 'get-summary-of-<:tvshow>-s<:season>x<:episode>.<:format>', array('controller' => 'episodes', 'action' => 'summary'));
$jh_rewrite_obj->addRoute('get_summary_2', '<:tvshow>/<:season>/<:episode>/summary.<:format>', array('controller' => 'episodes', 'action' => 'summary'));
$jh_rewrite_obj->addRoute('edit_username', 'edit/<:username>', array('controller' => 'users', 'action' => 'edit'));
$jh_rewrite_obj->addRoute('add_user', 'add-user', array('controller' => 'users', 'action' => 'add'));
$get = $jh_rewrite_obj->dispatch('home');
</pre>

<pre>
echo '&lt;ul>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('home') . '">Home&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('about') . '">About&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('about_username', array('username' => 'josselin')) . '">About Josselin&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('edit_username', array('username' => 'josselin')) . '">Edit Josselin&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('edit_episode', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04')) . '">Edit Hawaii s02x04&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('get_summary_1', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04', 'format' => 'html')) . '">Get Hawaii s02x04 summary (1)&lt;/a>&lt;/li>';
echo '&lt;li>&lt;a href="' . $jh_rewrite_obj->url('get_summary_2', array('tvshow' => 'hawaii', 'season' => '02', 'episode' => '04', 'format' => 'html')) . '">Get Hawaii s02x04 summary (2)&lt;/a>&lt;/li>';
echo '&lt;/ul>';
</pre>