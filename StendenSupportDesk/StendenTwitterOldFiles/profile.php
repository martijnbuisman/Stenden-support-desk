<!--
Cas van Dinter
384755
-->
<?php
require_once 'core/init.php';

if (!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $userProfile = new User($username);
    $user = new User();
    if (!$userProfile->exists()) {
        Redirect::to(404);
    } else {
        $data = $userProfile->data();
    }

    if (Input::get("submit") && (($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 200000)) {
        //the same as in upload_file.php
        if ($_FILES["file"]["error"] > 0) {
            echo "<div class='error'>Return Code: " . $_FILES["file"]["error"] . "</div><br />";
        } else {
            $path = "icons/" . escape($data->username) . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["file"]["tmp_name"], $path);
            DB::getInstance()->update('users', $data->id, array(
                'IconPath' => $path
            ));
            echo "<div class='succes'><p>Profile picture succesfully uploaded!</p></div>";
        }
    } else if (Input::get("submit")) {
        echo "<div class='error'>Invalid file</div>";
    }
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
                        <h2 style="text-align: center"><a href='index.php'>Home</a></h2>
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
                <div id="profileContainer">
                    <h1><?php echo escape($data->username); ?></h1>
                    <hr>
                    <div id="profile">
                        <table style="text-align: center; width: 100%;">
                            <tr>
                                <th>Profile Picture</th>
                                <th>Username</th>
                                <th>E-Mail</th>
                                <th>Full Name</th>
                                <th>Joined On</th>
                            </tr>
                            <tr>
                                <td><img src="<?php echo escape($data->IconPath); ?>" width="72px" height="72px"/></td>
                                <td><?php echo escape($data->username); ?></td>
                                <td><a href="mailto:<?php echo escape($data->mail); ?>"><?php echo escape($data->mail); ?></a></td>
                                <td><?php echo escape($data->name); ?></td>
                                <td><?php echo escape($data->joined); ?></td>
                            </tr>
                        </table>
                        <br/><hr><br/>
                        <?php
                        if ($user->isLoggedIn() && $data->id === $user->data()->id) {
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <label for="file">Change Profile Picture:</label>
                                <input type="file" name="file" id="file" />
                                <br/><br/>
                                <input type="submit" name="submit" value="Upload" id="Button"/>
                            </form>
                            <?php
                        }
                        ?>
                        <br/>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
}