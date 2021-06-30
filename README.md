<p align="center"><a href="https://curio.nl" target="_blank"><img src="http://gebouw-t.nl/wp-content/uploads/2019/10/curio-01-zwart-logo-rgb.png" width="400"></a></p>

## Hoe installeer ik het project

- Zet een Apache/Nginx webserver met PHP7.4 en MySQL (MariaDB) klaar. Ga dan naar de web folder van je webserver (www, htdocs, enz.) Voer dan vanaf die map het volgende commando in de terminal uit:

  `git clone https://github.com/StevenVanRosendaal/curio-uitleensysteem.git`
  
- Maak een database genaamd "uitleensysteem" aan met een gekoppelde nieuwe gebruiker. Het kan ook gedaan worden door de volgende queries uit te voeren over de database:

  `CREATE DATABASE uitleensysteem;`
 
  `GRANT ALL PRIVILEGES ON uitleensysteem.* TO 'curio_uitleensysteem_admin'@'localhost' identified by '[VUL HIER EEN WACHTWOORD IN]';`
 
  `FLUSH PRIVILEGES;`

- Kopieer in de root folder van dit project de .env.example file als .env in dezelfde (root) folder. Of voer het volgende commando uit:

  `cp .env.example .env`
  
- Open de .env file in een text editor, en zet de instellingen voor de database goed. Gebruik username "uitleensysteem" en het wachtwoord zoals dat in de vorige stap ingesteld is.

- Voer de volgende terminal commando's uit in de root folder van dit project

  `composer install`

  `php artisan key:generate`

  `npm install`
  
  `php artisan storage:link`

  `npm run prod`
  
  `php artisan migrate:fresh --seed`
  
- Er wordt een admin account gegenereerd. Het wachtwoord hiervoor wordt in de console getoond. Sla deze ergens op. Deze heb je nodig om in te loggen in de backend van het systeem. Je kan later nieuwe gebruikers aanmaken.

- De site zou nu moeten draaien. Stel je webserver zo in dat er met een URL de site bereikt kan worden. Open dan met de browser die URL. Veel succes!

## Image Optimizer

Het uitleensysteem komt met een image optimizer die plaatjes verkleint zodat er ruimte op de server schijf wordt bespaard. Zodat dit tooltje werkt, moet het volgende commando als super user uit worden gevoerd op de server:

`sudo apt-get install jpegoptim optipng pngquant gifsicle webp`

Dit installeert de pakketten die de optimizer gebruikt om de plaatjes te optimalizeren.

## Credits

Het Curio Uitleensysteem is ontwikkeld door Steven van Rosendaal van Curio Breda. Voor vragen, stuur een mail naar <a href="mailto:s.vanrosendaal@curio.nl">s.vanrosendaal@curio.nl</a>
