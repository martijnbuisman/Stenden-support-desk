<?php
if ($user->hasPermission('admin')) {
//code
    if (Input::get('update')) {
        $id = Input::get('update');
        $faq = $db->query("SELECT * FROM faq WHERE id = '{$id}'");
        if ($faq->count()) {
            echo "<h1>FAQ updaten</h1>";
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {

                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'question' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 100
                        ),
                        'answer' => array(
                            'required' => true,
                            'min' => 2
                        )
                    ));

                    if ($validation->passed()) {
                        try {
                            $db->update('faq', $id, array(
                                'question' => Input::get('question'),
                                'answer' => Input::get('answer')
                            ));

                            Session::flash('home', 'FAQ succesfully updated!');
                            Redirect::to('index.php?page=faq_archief');
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
            }
            ?>
            <form action="" method="post" class="basic-grey">
                <h1>FAQ updaten
                    <span>Alle velden invullen</span>
                </h1>
                <label>
                    <span>Vraag :</span>
                    <input id="question" type="text" name="question" placeholder="Vraag" value="<?php echo escape($faq->first()->question); ?>"/>
                </label>

                <label>
                    <span>Antwoord :</span>
                    <input id="answer" type="text" name="answer" placeholder="Antwoord" value="<?php echo escape($faq->first()->answer); ?>"/>
                </label>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                <label>
                    <span>&nbsp;</span> 
                    <input type="submit" class="button" value="Update" /> 
                </label>    
            </form>
            <?php
        } else {
            Session::flash('error', 'That FAQ ID does not exist.');
            Redirect::to('index.php?page=faq_archief');
        }
    } else {
        Session::flash('error', 'That FAQ ID does not exist.');
        Redirect::to('index.php?page=faq_archief');
    }
} else {
    Redirect::to('index.php');
}
?>