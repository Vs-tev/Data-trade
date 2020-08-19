<?php
require_once 'init.php';
print_r ($_SESSION);
$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
        'username' =>  array(
        'required' => true,
        'min' => 4,
        'max' => 40,    
        )
        ));
        if($validation->passed()) { //method passed da proverq kvo tochno pravi 
            
            try {
                $user->update(array(
                 'username' => Input::get('username')
                ));
                Session::flash('home', 'Your deteils have been updated');
                 Redirect::to('update.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

?>

<form method="post" action="">
    <div class="fields">
    <label>Name</label>
        <input type="text" name="username" value="<?php echo escape($user->data()->username); ?>">
        
        <input type="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
    </div>

</form>