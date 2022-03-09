<?php
function display_errors($errors){
    $display = '<ul class="bg-danger">';// vytvorime premennu display
    foreach($errors as $error){
        $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
}
function sanitize($dirty){ //aby sa nebrali <> do nazvov do databazy atd..
    return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}
function money($number){
    return  number_format($number,2).'€'; //v admin/products.php nam to pridava € do za cenu
}
function login($user_id){
    $_SESSION['SBUuser'] = $user_id; //SBUuser je SESSION NAME
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
    $_SESSION['success_flash'] = 'Teraz ste prihlásený!';
    header('Location: index.php');
}
function is_logged_in(){
    if(isset($_SESSION['SBUuser']) && $_SESSION['SBUuser'] > 0){
        return true;    
    }
    return false;
}
function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'Na prístup na túto stránku musíte byť prihlásení.';
    header('Location: '.$url);
}
function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'Na prístup na túto stránku nemáte oprávnenia.';
    header('Location: '.$url);
}
function has_permission($permission = 'admin'){
    global $user_data;
    $permissions = explode(',', $user_data['permissions']);
    if(in_array($permission, $permissions, true)){ //tato funkcia zistuje, ci admin sa nachadza v array
        return true;
    } 
    return false;
}
function pretty_date($date){
    return date("M d, Y h:i A",strtotime($date));
}
function get_category($child_id){
    global $db;
    $id = sanitize($child_id);
    $sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child' 
        FROM categories c
        INNER JOIN categories p 
        ON c.parent = p.id
        WHERE c.id = '$id'";
    $query = $db->query($sql);
    $category =  mysqli_fetch_assoc($query);
    return $category; 
}