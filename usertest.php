<?php

include_once "Baza.php";

    echo '<form method="get" action="usertest.php"><p>';
    echo 'Nazwisko: </br>'; echo '<input name="nazw" size="30"/><br/>';
    echo 'Wiek: </br>'; echo '<input name="wiek" size="30"/><br/>';
    echo 'Adres e-mail: </br>';  echo '<input name="email" size="30"/></br>';
    echo 'Zamawiane kursy: </br>'; echo '<input name="php" type="checkbox"/>PHP<input name="java" type="checkbox"/>Java<input name="cpp" type="checkbox"/>C++<br/>';
    echo 'Państwo: </br>'; echo '<select name="panstwo" <value>="panstwo" <option>Polska</option><option>Niemcy</option><option>Wielka Brytania</option><option>Czechy</option></select></br>';
    echo 'Płatność: </br>'; echo '<select name="platnosc" <value>="platnosc" <option>Visa</option><option>Master Card</option><option>Przelew</option></select></br>';
    
    echo '<input type="reset"  value="Anuluj"/>';
    echo '<input type="submit" value="Dodaj" name="Rejestruj"/>';
    echo '<input type="submit" value="Wyświetl" name="Wyswietl"/>';
    echo '<input type="submit" value="PHP" name="sphp"/>';
    echo '<input type="submit" value="CPP" name="scpp"/>';
    echo '<input type="submit" value="Java" name="sjava"</p></form>';
  
    
    
$ob = new Baza("localhost", "root", "", "klienci");
if (isset($_GET['Wyswietl'])) {
    echo $ob->select("select Nazwisko,Zamowienie from klienci",array("Nazwisko","Zamowienie"));
} else
if (isset($_GET['Rejestruj'])) {
    
    $nazwisko= $_GET['nazw'];
    $wiek=$_GET['wiek'];
    $email=$_GET['email'];
    $zamowienie="";
    if ( isset($_GET['php'])) $zamowienie="PHP"; 
    if ( isset($_GET['java']) && $zamowienie=="") $zamowienie="Java"; 
    if(isset($_GET['java']) &&$zamowienie=="PHP") $zamowienie=$zamowienie.",Java";
    if ( isset($_GET['cpp']) && $zamowienie=="") $zamowienie="CPP";   
    if(isset($_GET['cpp']) &&($zamowienie=="PHP" || $zamowienie=="PHP,Java" || $zamowienie=="Java")) $zamowienie=$zamowienie.",CPP";
    $panstwo=$_GET['panstwo'];
    $platnosc=$_GET['platnosc'];
    echo 'Państwo: '.$panstwo." Płatność: ".$platnosc."</br>"; 
    $poprawneDane = sprawdzDane($nazwisko, $wiek, $zamowienie, $email, $panstwo, $platnosc);
        if($poprawneDane ==1)
        {
            echo" poprawne dane";
            $sql = "INSERT INTO `klienci` (`Id`, `Nazwisko`, `Wiek`, `Panstwo`, `Email`, `Zamowienie`, `Platnosc`) VALUES (NULL,'$nazwisko', '$wiek', '$panstwo', '$email', '$zamowienie', '$platnosc')";
            $ob->insert($sql);     
        }else echo "Błędne dane!";    
   
}

if(isset($_GET['sphp']))
{
    $sql="SELECT * from `klienci` WHERE `Zamowienie` LIKE 'PHP'";
    $ob->pokazPHP($sql);
}
if(isset($_GET['sjava']))
{
    $sql="SELECT * from `klienci` WHERE `Zamowienie` LIKE 'Java'";
    $ob->pokazJava($sql);
}
if(isset($_GET['scpp']))
{
    $sql="SELECT * from `klienci` WHERE `Zamowienie` LIKE 'CPP'";
    $ob->pokazCPP($sql);
}

function sprawdzDane($nazwisko,$wiek,$zamowienie,$email,$panstwo,$platnosc)
{
    $tekstowe=array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"));
$poprawnyWiek=array("options"=>array('min_range'=>0,'max_range'=>200));
$licznik=0;

if(!filter_var($nazwisko,FILTER_VALIDATE_REGEXP,$tekstowe))
{
    echo "Błędne nazwisko </br>";
}
else {  $licznik++;}


if(!filter_var($email,FILTER_VALIDATE_EMAIL))
{
    echo "Błędny email </br>";
}
else {  $licznik++;}


if(!filter_var($wiek,FILTER_VALIDATE_INT,$poprawnyWiek))
{
    echo "Błędny wiek </br>";
}
else {  $licznik++; }


if($licznik==3)
{
    return 1;
}
else return 2;
}
?>