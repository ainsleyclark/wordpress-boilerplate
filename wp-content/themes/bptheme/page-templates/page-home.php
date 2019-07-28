<?php
/*
Template Name: Homepage
*/
get_header(); ?>

    <section class="content">
        <div class="container">
            <h4>Admin Ajax Test Form</h4>
            <span id="msgs"></span>
            <form class="form" action="post" method="POST" id="adminAjaxTest" data-nonce="<?php echo $nonce; ?>">
                <?php
                    $nonce = wp_create_nonce("send_form_nonce");
                ?>
                <input type="hidden" name="nonce" id="nonce" value="<?php echo $nonce; ?>">
                <input type="hidden" name="action" id="action" value="send_example_form">
                <div class="form-group">
                    <label for="fname">First name</label>
                    <input type="text" class="form-control" id="fname" aria-describedby="nameHelp" placeholder="Your first name" name="fname">
                </div>
                <div class="form-group">
                    <label for="fname">Last name</label>
                    <input type="text" class="form-control" id="lname" aria-describedby="nameHelp" placeholder="Your last name" name="lname">
                </div>
                <div class="form-group">
                    <label for="emailaddr">Email address</label>
                    <input type="email" class="form-control" id="emailaddr" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                </div>
                <div class="form-group">
                    <label for="msg">Your message</label>
                    <textarea class="form-control" id="msg" rows="3" name="message"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit - $.ajax() function</button>
            </form>
        </div>
    </section>

<?php get_footer(); ?>