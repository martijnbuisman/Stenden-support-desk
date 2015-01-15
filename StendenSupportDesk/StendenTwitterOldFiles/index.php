<!--
Cas van Dinter
384755
-->
<?php
require_once 'core/init.php';

if (Session::exists('home')) {
    echo "<div class='succes'><p>" . Session::flash('home') . "</p></div>";
}
if (Session::exists('tweet')) {
    echo "<div class='succes'><p>" . Session::flash('tweet') . "</p></div>";
}
if (Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'tweet' => array(
            'required' => true,
            'min' => 2,
            'max' => 250,
        )
    ));
    if ($validation->passed()) {
//Add message
        $user = new User();
        try {
            DB::getInstance()->insert('messages', array(
                'user_id' => $user->data()->id,
                'message' => Input::get('tweet')
            ));
            Session::flash('tweet', 'Tweet has been sent!');
            Redirect::to('index.php');
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    } else {
//output errors
//print_r($validation->errors());
        foreach ($validation->errors() as $error) {
            echo "<div class='error'>" . $error . "</div>";
        }
    }
}


$user = new User();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href = "styles.css" type = "text/css" rel = "stylesheet"/>
        <title>Stenden Twitter</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <div id="headerHome">
                    <a href='index.php'><img src="icons/logo.jpg" width="180px" height="40px"/></a>
                </div>
                <div id="headerAccount">
                    <?php
                    if ($user->isLoggedIn()) {
                        ?>
                        <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>! <a href="logout.php">Logout</a></p>
                        <?php
                    } else {
                        echo "<a href='login.php'>Log in</a> || <a href='register.php'>Register</a></p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="container">
            <div id="message">
                <h1>Tweet</h1>
                <br/>
                <?php
                if ($user->isLoggedIn()) {
                    ?>
                    <form action="" method="post">
                        <textarea name = "tweet" id = "tweet" placeholder = "What's happening?" style="width: 90%; height: 20%;"></textarea>
                        <br/>
                        <input id = "Button" type = "submit" value = "Send"/>
                        <br/><br/>
                    </form>
                    <?php
                } else {
                    echo "You need to <a href='login.php'>log in</a> to tweet.</p><br/>";
                }
                ?>
            </div>
            <div id = "tweetsContainer">
                <h1>Tweets</h1>
                <hr>
                <?php
                $db = DB::getInstance();
                $tweets = $db->query("SELECT * FROM messages ORDER BY id DESC");
                if ($tweets->count()) {
                    foreach ($tweets->results() as $tweet) {
                        $tweetSQL = $db->query("SELECT name, IconPath FROM users WHERE id = '{$tweet->user_id}'");
                        foreach ($tweetSQL->results() as $user_id) {
                            ?>
                            <div class = "tweet">
                                <div class = "tweetPic">
                                    <img src = "<?php echo escape($user_id->IconPath) ?>" width = "72px" height = "72px"/>
                                </div>
                                <h3><a href="profile.php?user=<?php echo escape($tweet->user_id) ?>"><?php echo escape($user_id->name) ?></a></h3>
                                <p><?php echo escape($tweet->message) ?></p>
                            </div>
                            <?php
                            echo "<hr>";
                        }
                    }
                } else {
                    echo "<h3> There are no tweets yet </h3>";
                }
                ?>
                <br/>
            </div>
        </div>
    </body>
</html>