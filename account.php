<?php
function nahoda() //Refresh stránky
{
    $znaky = "1234567890asdfghjklqwertyuiopzxcvbnm";
    $vystup = "";
    for ($i = 0; $i < 10; $i++) {
        $vystup .= $znaky[rand(0, strlen($znaky) - 1)];
    }
    return $vystup;
}

require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $sql = "SELECT * FROM adresy WHERE userid = {$userid}";
    $result = mysqli_query($db,$sql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0){
        $row = mysqli_fetch_assoc($result);
    }
}
?>
    <div class="accountLeft">
        <div class="left">
            <div class="container-fluid-signup">
                <section class="login-form">
                    <div class="wrap">
                        <h2>Nová adresa:</h2>
                        <h4>* požadované</h4>
                        <div id="errorA" style="color:red;"></div>
                        <form id="form" onsubmit="return validate2();" action="includes/addaddress.inc.php" method="post">
                            <input type="text" id="adresa" name="adresa" placeholder="*Adresa">
                            <input type="text" id="adresa2" name="adresa2" placeholder="Druhá adresa">
                            <input type="text" id="mesto" name="mesto" placeholder="*Mesto">
                            <input type="text" id="stat" name="stat" placeholder="*Štát">
                            <input type="text" id="zipcode" name="zipcode" placeholder="*Smerovacie číslo">
                            <button type="submit" name="submit">Odoslať adresu</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <div class="right">
            <h3>Vaša aktuálna adresa:</h3>
            <?php if($numRows > 0): ?>
                <h4 id="adresa1"><?= $row['adresa1'];?></h4>
                <h4 id="adresa2"><?= $row['adresa2'];?></h4>
                <h4 id="mesto"><?= $row['mesto'];?></h4>
                <h4 id="stat"><?= $row['stat'];?></h4>
                <h4 id="smerovaciecislo"><?= $row['smerovaciecislo'];?></h4>
            <?php else:?>
                <h4>Nemáte pridanú žiadnu adresu k Vášmu účtu.</h4>
            <?php endif;?>
        </div>
    </div>
    <div class="accountRight">
        <div class="container-fluid-signup">
            <section class="login-form">
                <div class="wrap">
                    <h2>Zmena hesla:</h2>
                    <div id="error" style="color:red;"></div>
                    <form id="form" action="includes/changepassword.inc.php" method="post">
                        <input type="password" id="pwdOld" name="pwdOld" placeholder="Staré heslo">
                        <input type="password" id="pwd" name="pwd" placeholder="Nové heslo">
                        <input type="password" id="pwdCheck" name="pwdCheck" placeholder="Potvrdenie nového hesla">
                        <button type="button" onclick="validate();" name="submit">Zmeniť heslo</button>
                        <button  type="submit" name="submit" style="display: none;" id="checkBtn"></button>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script>
        function validate(){
        let old = document.querySelector('#pwdOld').value;
        let pwd = document.querySelector('#pwd').value;
        let check = document.querySelector('#pwdCheck').value;
        let errorElement = document.querySelector("#error");
        let submit = document.getElementById("checkBtn");
        let messages = [];
        errorElement.innerHTML = ''; 
    
        

        if(old == '' || pwd == '' || check == ''){
            messages.push('Vyplnte všetky polia!');  
        }
        if(pwd !== check){
            messages.push("Heslá sa nezhodujú!");
        }
        if(pwd.length < 6){
            messages.push("Heslo musí mať aspoň 6 znakov!");
        }

        

        if(messages.length > 0){
            errorElement.innerHTML = messages.join('<br>');   
        }else{
            var data = 'oldpassword=' + old;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'includes/passwordverify.inc.php',true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if(this.status == 200){
                    if(this.response == 0){
                        messages.push("Vaše staré heslo nezodpovedá našim záznamom!");
                        errorElement.innerHTML = messages.join('<br>'); 
                    }
                    else if(this.response == 1){
                        alert("Vaše heslo bolo úspešne zmenene!");
                        submit.click();

                    }
                }
            }
            xhr.send(data);
        }    
    }
    function validate2(){
        let adresa = document.querySelector('#adresa').value;
        let adresa2 = document.querySelector('#adresa2').value;
        let mesto = document.querySelector('#mesto').value;
        let stat = document.querySelector('#stat').value;
        let zipcode = document.querySelector('#zipcode').value;
        let errorElement2 = document.querySelector("#errorA");
        let submit = document.getElementById("checkBtn");
        let errors = [];
        errorElement2.innerHTML = ''; 

        if(adresa == '' || mesto == '' || stat == '' || zipcode == ''){
            errors.push('Vyplnte všetky požadované polia!'); 
        }
        if(errors.length > 0){
            errorElement2.innerHTML = errors.join('<br>');
            return false;  
        }else{
            return true;
        }
    }
    </script>
<?php
include 'includes/footer.php';
?>