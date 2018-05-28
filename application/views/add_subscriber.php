<!doctype html>
<html lang="en">
<head>
    <title>Mailchimp subscribe form</title>
</head>

<body>
<a href="<?= base_url('welcome/view_all_lists'); ?>">View All Lists on Your Mailchimp Account</a>
<br>
<form method="post" action="<?= base_url('welcome/subscriber_post')?>">
    <div>
        <label>First Name</label> <input type="text" name="firstname">
    </div>
    <div>
        <label>Last Name</label> <input type="text" name="lastname">
    </div>
    <div>
        <label>Email</label> <input type="text" name="email">
    </div>
    <div>
        <button type="submit">Subscribe</button>
    </div>
</form>
</body>
</html>