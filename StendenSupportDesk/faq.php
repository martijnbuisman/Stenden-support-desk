<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer') || $user->hasPermission('ol') || $user->hasPermission('gb')) {
    //code
    $faqs = $db->query("SELECT * FROM faq");
    echo "<h1>Frequently Asked Questions</h1><br/>";
    if ($faqs->count()) {
        foreach ($faqs->results() as $faq) {
            ?>
            <div class="faqContainter">
                <div class="faqQuestion">
                    <h1><?php echo escape($faq->question) ?></h1>
                </div>
                <div class="faqAnswer">
                    <h2><?php echo escape($faq->answer) ?></h2>
                </div>
            </div>
            <?php
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