<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->isLoggedIn()) {
    //code
    if (Input::get("submit") && (($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 200000)) {
        //the same as in upload_file.php
        if ($_FILES["file"]["error"] > 0) {
            echo "<div class='error'>Return Code: " . $_FILES["file"]["error"] . "</div><br />";
        } else {
            $path = "icons/" . escape($user->data()->username) . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["file"]["tmp_name"], $path);
            $db->update('users', $user->data()->id, array(
                'IconPath' => $path
            ));
            Session::flash('home', 'Profile picture was succesfully uploaded!');
            Redirect::to('index.php');
        }
    } else if (Input::get("submit") && ($_FILES["file"]["size"] > 2000)) {
        echo "<div class='error'>File too big: " . $_FILES["file"]["size"] . "</div>";
    } else if (Input::get("submit")) {
        echo "<div class='error'>Invalid file</div>";
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data" class="basic-grey">
        <h1>Verander gebruikersfoto
            <span>.gif - .png - .jpeg - .pjpeg || size 200kb</span>
        </h1>
        <label>
            <span>Foto uploaden :</span>
            <input type="file" name="file" id="file" />
        </label>

        <label>
            <span>&nbsp;</span> 
            <input type="submit" name="submit" value="Upload" class="Button"/>
        </label>
    </form>
    <?php
} else {
    Redirect::to('index.php');
}
?>