<?php
    require_once(__DIR__ . '/database.php');
    require_once(__DIR__ . '/bbcode.php');

    function insertServer($pdo, $data)
    {
        $users = '';
		foreach ($data['users']['users'] as $user) {
			$users .= $user['name'] . ',';
		}
		$users = substr($users, 0, strlen($users) - 1);

        $exists = dbquery($pdo, 'SELECT * FROM servers WHERE host = ?', [$data['sv_host']]);
        if ($exists) {
            dbquery($pdo, 'UPDATE servers SET sv_name = ?, sv_timezone = ?, sv_topic = ?, sv_preview = ?, users = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [$data['sv_name'], $data['sv_timezone'], $data['sv_topic']['raw'], $data['sv_preview'], $users, $exists[0]['id']]);
        } else {
            dbquery($pdo, 'INSERT INTO servers (host, sv_name, sv_timezone, sv_topic, sv_preview, users) VALUES(?, ?, ?, ?, ?, ?)', [$data['sv_host'], $data['sv_name'], $data['sv_timezone'], $data['sv_topic']['raw'], $data['sv_preview'], $users]);
        }
    }

    function serverList($pdo, $max = 255)
    {
        return dbquery($pdo, 'SELECT * FROM servers WHERE updated_at >= NOW() - INTERVAL 20 MINUTE ORDER BY updated_at DESC LIMIT ' . strval($max));
    }

    $uri = strtolower($_SERVER['REQUEST_URI']);
    if ($uri === '/api/serverlist/add')
    {
        $pdo = new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE . ';charset=' . DB_CHARSET, DB_USER, DB_PASSWORD);

        insertServer($pdo, json_decode($_POST['data'], true));

        header('Content-Type: application/json');
        echo json_encode(['code' => 200]);

        exit();
    } else if ($uri === '/api/serverlist/fetch') {
        $pdo = new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE . ';charset=' . DB_CHARSET, DB_USER, DB_PASSWORD);

        $data = serverList($pdo);

        header('Content-Type: application/json');
        echo json_encode(['code' => 200, 'data' => $data]);

        exit();
    }

    try {
        $pdo = new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE . ';charset=' . DB_CHARSET, DB_USER, DB_PASSWORD);
        $serverlist = serverList($pdo);
    } catch (Exception $e) {
        $serverlist = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="author" content="Daniel Brendel">
        <meta name="description" content="Chat like you're playing CS 1.6 again">

        <meta name="og:title" property="og:title" content="SayText Chat">
        <meta name="og:description" property="og:description" content="Chat like you're playing CS 1.6 again">
        <meta name="og:url" property="og:url" content="/">
        <meta name="og:image" property="og:image" content="img/screenshot.png">

        <link rel="icon" type="image/png" href="img/favicon.png"/>

        <link rel="stylesheet" type="text/css" href="css/bulma.css"/>
        <link rel="stylesheet" type="text/css" href="css/app.css"/>

        <script src="js/fontawesome.js"></script>
        <script src="js/app.js"></script>

        <title>SayText Chat - Chat like you're playing CS 1.6 again</title>
    </head>

    <body style="background-image: url('img/background.jpg');">
        <div class="columns">
            <div class="column is-one-fifth"></div>

            <div class="column is-three-fifths">
                <div class="content">
                    <div class="header">
                        <img src="img/banner.png" alt="banner"/>
                    </div>

                    <div class="section">
                        <h1>Chat like you're playing CS 1.6 again</h1>

                        <a name="introduction"></a>

                        <p><strong>SayText Chat</strong> is a simple retro chat app that you can self-host on your own server.</p>

                        <p>Reactivate old neural pathways by diving into a long forgotten time around the early 2000s. Do you remember when you went home after school, turned on your PC and had a lot of fun playing CS 1.6? LAN-Parties, IRC gatherings and countless varying config settings were the shit back in the days.</p>

                        <p>Well, this chat application wants to give you back a portion of these old times! Send iconic chat messages, issue radio comamnds, use the VOX speech system and type leet messages. Unleash dopamine with nostalgia now!</p>

                        <p><i>Wh4t @r3 y0u w417!ng f0r?</i></p>
                            
                        <p>Lock and load... <b>Go, go, go!</b></p>
                    </div>

                    <div class="section">
                        <h2>Server list</h2>

                        <a name="servers"></a>

                        <p>Eager to chat? Choose one of the following servers.</p>

                        <div class="server-list">
                            <?php if (count($serverlist) > 0) { ?>
                                <?php foreach ($serverlist as $server) { ?>
                                    <?php $usercount = (!empty($server['users'])) ? count(explode(',', $server['users'])) : 0; ?>
                                    <a href="<?= $server['host'] ?>">
                                        <div class="server-item" style="background-image: url('<?= $server['sv_preview'] ?>');">
                                            <div class="server-item-count"><?= $usercount ?> chatters</div>

                                            <div class="server-item-info">
                                                <div class="server-item-info-name"><?= $server['sv_name'] ?></div>
                                                <div class="server-item-info-topic"><?= bbct($server['sv_topic']) ?></div>
                                            </div>
                                        </div>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <p><i>There are no servers online. Try again later.</i></p>
                            <?php } ?>
                        </div>

                        <p><a class="button is-warning" href="javascript:void(0);" onclick="location.href = window.location.origin + '/#servers';">Refresh</a></p>
                    </div>

                    <div class="section">
                        <h2>Features</h2>

                        <a name="features"></a>
                        
                        <p><strong>SayText Chat</strong> provides a lot of nostalgic features.</p>

                        <div class="features">
                            <div class="features-box">
                                <ul>
                                    <li>💬 Send text messages</li>
                                    <li>📻 Send radio commands</li>
                                    <li>🗣️ Send speech commands</li>
                                    <li>🕶️ Send leetspeak text</li>
                                    <li>🤝 Switch between teams</li>
                                </ul>
                            </div>

                            <div class="features-box">
                                <ul>
                                    <li>👨‍👩‍👧 View online count</li>
                                    <li>👨‍👩‍👦‍👦 View online users</li>
                                    <li>🔊 Toggle sound</li>
                                    <li>🌄 Switch backgrounds</li>
                                    <li>📱 Responsive design</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <h2>Self-host</h2>

                        <a name="installation"></a>

                        <p><strong>SayText Chat</strong> is made for self-hosting!</p>

                        <a class="is-highlight-link" href="https://github.com/danielbrendel/saytext-chat#installation">Read docker deployment instructions</a>
                    </div>

                    <div class="section">
                        <h2>Contact</h2>

                        <a name="contact"></a>

                        <div class="icons">
                            <a title="GitHub" href="https://github.com/danielbrendel/saytext-chat"><i class="fab fa-github fa-2x"></i></a>
                            <a title="Mastodon" href="https://fosstodon.org/@dbrendeldev"><i class="fab fa-mastodon fa-2x"></i></a>
                            <a title="LinkedIn" href="https://www.linkedin.com/in/daniel-brendel-developer"><i class="fab fa-linkedin fa-2x"></i></a>
                            <a title="Steam" href="https://store.steampowered.com/developer/danielbrendel"><i class="fab fa-steam fa-2x"></i></a>
                            <a title="E-Mail" href="mailto:daniel@danielbrendel.com"><i class="fas fa-envelope fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-one-fifth"></div>
        </div>
    </body>
</html>