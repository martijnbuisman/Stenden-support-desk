<?php
if ($user->hasPermission('admin')) {
//code
    echo "<h1>FAQ toevoegen</h1>";
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
                    $db->insert('faq', array(
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
        <h1>FAQ toevoegen
            <span>Alle velden invullen</span>
        </h1>
        <label>
            <span>Vraag :</span>
            <input id="question" type="text" name="question" placeholder="Vraag" value="<?php echo escape(Input::get('question')); ?>"/>
        </label>

        <label>
            <span>Antwoord :</span>
            <input id="answer" type="text" name="answer" placeholder="Antwoord" value="<?php echo escape(Input::get('answer')); ?>"/>
        </label>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
        <label>
            <span>&nbsp;</span> 
            <input type="submit" class="button" value="Update" /> 
        </label>    
    </form>
    <?php
} else {
    Redirect::to('index.php');
}
?>