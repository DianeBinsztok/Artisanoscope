<?php 
function workshop_reminder_email_template($customer_name, $artisan, $image_url, $name, $date, $start_hour, $end_hour, $location){

    return '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel d\'atelier</title>
</head>
<body>
    <table style = "max-width:500px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; border-collapse:collapse;">
            <thead  style = "background-color:#008670;">
                <tr>
                    <th colspan="6"><img src="https://static.wixstatic.com/media/f14444_6157cea966124bb0955493b0bc6f842e~mv2.png/v1/fill/w_354,h_100,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/logo-LArtisanoscope.png" style=" width: 50%;"></th>
                </tr>
                <tr>
                    <th colspan="6" style = "color:#ffeed1;  font-size: 1.5em; font-weight: 400; padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; line-height: 2em;">Bonjour '.$customer_name.'! <span style = "font-weight: 600;">'.$artisan.'</span> vous attend le <br/><span style = "font-weight: 600; font-size: 1.2em;">'.$date.'</span> <br/>pour votre atelier:</th>
                </tr>
            </thead>
            <tr>
            <td colspan="6" style="overflow: hidden; text-align: center;"><img src="'.$image_url.'" style="height: 150px; width: 100%; object-fit: cover;"></td>
            </tr>
            <tr>
                <td colspan="6" style = "font-size: 2em; font-weight: 600; text-align: center; line-height: 1.3em; padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; color:#008670; ">'.$name.'</td>
            </tr>
            <tr>
                <td colspan="6" style="padding-top:1em; padding-bottom:1em; padding-right:1.5em; padding-left:1.5em; line-height: 2em;">Voici un petit rappel des infos pratiques pour démarrer dans les meilleures conditions:</td>
            </tr>
            <tr>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-left:3em; background-color: #ffffff;"></td>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-left:4em; background-color: #ffeed1;">&#9642;  Date:</td>
                <td colspan="3" style="padding-top:1em; padding-bottom:1em; padding-right:1.5em; background-color: #ffeed1;">Le <span style = "font-weight: 600;">'.$date.'</span></td>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-right:3em; background-color: #ffffff;"></td>
            </tr>
            <tr>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-left:3em; background-color: #ffffff;"></td>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-left:4em; background-color: #ffeed1;">&#9642;  Horaires:</td>
                <td colspan="3" style="padding-top:1em; padding-bottom:1em; padding-right:1.5em; background-color: #ffeed1;">De <span style = "font-weight: 600;">'.$start_hour.'</span> à <span style = "font-weight: 600;">'.$end_hour.'</span></td>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-right:3em; background-color: #ffffff;"></td>
            </tr>
            <tr style="vertical-align: text-top;">
                <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-left:3em; background-color: #ffffff;"></td>
                <td colspan="1" style="padding-top:1em; padding-bottom:1em;padding-left:4em; background-color: #ffeed1;">&#9642;  Lieu:</td>
                <td colspan="3" style="padding-top:1em; padding-bottom:1em; padding-right:1.5em; line-height: 1.5em; background-color: #ffeed1;">
                    <span style = "font-weight: 600;">'.$location.'</td>
                    <td colspan="1" style="padding-top:1em; padding-bottom:1em; padding-right:3em; background-color: #ffffff;"></td>

            </tr>
            <tr style="text-align: center;">
                <td colspan="6" style="padding-top:2em; padding-bottom:2em;">À très bientôt pour votre atelier!</td>
            </tr>
            <tfoot>
                <tr>
                    <td colspan="6" style="background-color:#008670;text-align: center; padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; "><a href="#" style="color:#ffeed1; text-decoration: none;">L\'Artisanoscope</a></td>
                </tr>
            </tfoot>
    </table>
    
    
</body>
</html>
    ';
}
function workshop_followup_email_template($customer_name, $artisan, $image_url, $name){
    return '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre retour sur l\'atelier</title>
</head>
<body>
    <table style = "max-width:100%; font-family: Arial, Helvetica, sans-serif; font-size: 15px; border-collapse:collapse;">
        <thead  style = "background-color:#ffeed1;">
            <tr>
                <th colspan="6"><img src="https://static.wixstatic.com/media/f14444_6157cea966124bb0955493b0bc6f842e~mv2.png/v1/fill/w_354,h_100,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/logo-LArtisanoscope.png" style=" width: 50%;"></th>
            </tr>
            <tr>
                <th colspan="6" style = "color:#008670;  font-size: 1.3em; font-weight: 400; padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; line-height: 2em;">Bonjour '.$customer_name.'! Comment s\'est passé votre atelier avec <span style = "font-weight: 600;">'.$artisan.' ?</span></th>
            </tr>
        </thead>
            <tr>
                <td colspan="6" style="overflow: hidden; text-align: center;"><img src="'.$image_url.'" style="height: 150px; width: 100%; object-fit: cover;"></td>
            </tr>
            <tr>
                <td colspan="6" style = "font-size: 1.3em; font-weight: 600; text-align: center; line-height: 1.3em; padding-top:0.8em;  padding-right:1em; padding-left:1em; color:#008670;">'.$name.'</td>
            </tr>
            <tr style="text-align: center;">
                <td colspan="1" style="padding-top:5em; padding-bottom:2em; padding-left:2em; background-color:#ffffff;"></td>
                <td colspan="4"><a href="#" style="padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; background-color:#008670; color:#ffeed1; text-decoration: none; border-radius: 7px; font-weight: 600;">Je laisse un témoignage</a></td>
                <td colspan="1" style="padding-top:2em; padding-bottom:2em; padding-right:2em;background-color:#ffffff;"></td>
            </tr>
            
            <tr style="text-align: center;">
                <td colspan="6" style="padding-top:2em; padding-bottom:2em;">Merci pour votre visite à l\'Artisanoscope et à bientôt!</td>
            </tr>
            <tfoot>
                <tr>
                    <td colspan="6" style="background-color:#008670;text-align: center; padding-top:1em; padding-bottom:1em; padding-right:1em; padding-left:1em; "><a href="#" style="color:#ffeed1; text-decoration: none;">L\'Artisanoscope</a></td>
                </tr>
            </tfoot>
    </table>
</body>
</html>
    ';
}