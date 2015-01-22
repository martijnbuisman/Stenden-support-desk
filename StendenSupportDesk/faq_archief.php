<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
//code
    if(Input::get('delete')){
        $id = Input::get('delete');
        $existFaq = $db->query("SELECT * FROM faq WHERE id = '{$id}'");
        if($existFaq->count()){
            $db->query("DELETE FROM faq WHERE id = '{$id}'");
            Session::flash('home', 'FAQ succesfully removed.');
            Redirect::to('index.php?page=faq_archief');
        } else {
            Session::flash('error', 'That FAQ ID does not exist.');
            Redirect::to('index.php?page=faq_archief');
        }
    }
    $faqs = $db->query("SELECT * FROM faq");
    echo "<h1>FAQ Archief</h1><br/>";
    if ($faqs->count()) {
        ?>
        <div class="buttonContainer">
            <a class="Button" href='index.php?page=faq_new'>Nieuw</a>
        </div>
        <table class="contentTable">
            <tr>
                <th width="20%">
                    Vraag
                </th>
                <th width="30%">
                    Antwoord
                </th>
                <th width="20%">
                    Actie
                </th>
            </tr>
            <?php
            foreach ($faqs->results() as $faq) {
                echo "<tr>";
                echo "<td><b>{$faq->question}</b></td>";
                echo "<td>{$faq->answer}</td>";
                echo "<td><a class='ButtonUpdate' href='index.php?page=faq_update&update={$faq->id}'>Update</a><a class='ButtonDelete' href='index.php?page=faq_archief&delete={$faq->id}'>Verwijderen</a></td>";
                echo "</tr>";
            }
        } else {
            ?>
            <div class="faqContainter">
                <div class="faqQuestion">
                    <h1>Er zijn nog geen FAQ toegevoegd.</h1>
                </div>
            </div>
            <?php
        }
    } else {
        Redirect::to('index.php');
    }
    ?>