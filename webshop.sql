-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 11 apr 2019 om 20:34
-- Serverversie: 10.1.30-MariaDB
-- PHP-versie: 7.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `product_cat` int(1) NOT NULL,
  `product_price` varchar(250) NOT NULL,
  `product_description` text NOT NULL,
  `product_date_added` date NOT NULL,
  `voorraad` int(11) NOT NULL,
  `sale_price` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_cat`, `product_price`, `product_description`, `product_date_added`, `voorraad`, `sale_price`) VALUES
(2, 'DJI Goggles Racing Edition', 1, '448,99', '<h4><span style=\"font-size: 24px; font-family: Impact, Charcoal, sans-serif;\">Productinformatie</span></h4><h5><span style=\"font-size: 18px;\">Plus- en minpunten</span></h5><p><span style=\"font-size: 18px;\">Volgens onze VR brilspecialist</span></p><div data-component=\'[{\"name\":\"fullPageProsAndCons\",\"options\":{\"labelText\":\"Bekijk alle plus- en minpunten\",\"fullPageViewOptions\":{\"eventTrackingCategory\":\"responsive product page\",\"eventTrackingAction\":\"Pros and cons mobile collapsible\",\"eventTrackingLabelOpen\":\"Open\",\"eventTrackingLabelClose\":\"Close\",\"title\":\"Plus- en minpunten\",\"viewKey\":\"pros-and-cons-full-page-view\"}},\"isActiveOn\":[\"mobile\"],\"processed\":true,\"id\":\"0\"}]\'><ul><li><p><span style=\"font-size: 18px;\">Pluspunt:</span></p><p><span style=\"font-size: 18px;\">Dankzij de Full HD kwaliteit van de schermen zie je precies wat jouw drone ook ziet.</span></p></li><li><p><span style=\"font-size: 18px;\">Pluspunt:</span></p><p><span style=\"font-size: 18px;\">Je bestuurt de drone met hoofdbewegingen door de gimbal controls.</span></p></li><li><p><span style=\"font-size: 18px;\">Pluspunt:</span></p><p><span style=\"font-size: 18px;\">Met frequency-hopping behoud je een stabiel signaal, bijvoorbeeld wanneer er meerdere (racing) drones in de buurt vliegen.</span></p></li><li><p><span style=\"font-size: 18px;\">Minpunt:</span></p><p><span style=\"font-size: 18px;\">Door het relatief hoge gewicht van de goggles is deze niet geschikt voor langdurig gebruik.</span></p></li><li><p><span style=\"font-size: 18px;\">Minpunt:</span></p><p><span style=\"font-size: 18px;\">Voor gebruik met de DJI Spark en Phantom 4 Pro heb je een apart verkrijgbare OTG kabel nodig.</span></p></li></ul></div><p><span style=\"font-size: 18px;\"><br></span></p><div data-curtain-label-down=\"Toon meer\" data-curtain-label-up=\"Toon minder\" data-min-lines=\"6\" data-offset=\"22\" data-skip-mobile=\"true\" data-track-action=\"Product description\" data-track-category=\"responsive product page\"><div data-component=\'[{\"name\":\"fullPageDescription\",\"options\":{\"fullPageViewOptions\":{\"eventTrackingCategory\":\"responsive product page\",\"eventTrackingAction\":\"Description mobile collapsible\",\"eventTrackingLabelOpen\":\"Open\",\"eventTrackingLabelClose\":\"Close\",\"title\":\"Omschrijving\",\"viewKey\":\"full-page-description\"},\"shortDescriptionTitle\":\"Omschrijving\",\"fullDescriptionButtonLabel\":\"Bekijk volledige omschrijving\"},\"isActiveOn\":[\"mobile\"],\"processed\":true,\"id\":\"0\"}]\'><h3><span style=\"font-size: 24px; font-family: Impact, Charcoal, sans-serif;\">Productomschrijving</span></h3><div lang=\"nl\"><p><span style=\"font-size: 18px;\"><br></span></p><p><span style=\"font-size: 18px;\">Met de DJI Goggles Racing Edition op je hoofd zie je de wereld door de &#39;ogen&#39; van jouw drone. Deze bril geschikt voor de DJI Phantom 4, Mavic Pro, Spark en Inspire drones. Je ervaart scherpe en heldere beelden, dankzij de twee Full HD schermen. De kijkhoek is 148 graden met een lage vervorming. Dankzij de gimbal controls is het mogelijk om de drone te laten draaien door je hoofd te bewegen. Het OcuSync systeem en de krachtige cameramodule hebben een maximaal bereik van 7 kilometer en wisselen automatisch tussen de juiste frequenties voor een stabiele verbinding. Terwijl je vliegt toont het scherm alle essenti&euml;le informatie, zoals signaalkwaliteit en de resterende accuduur van je drone.</span></p></div></div></div>', '2019-03-26', 23, ''),
(3, 'HOMIDO VIRTUAL REALITY HEADSET V2', 1, '69,99', '<h2><span style=\"font-size: 24px;\">Homido Virtual Reality Headset V2</span></h2><p>De Homido Virtual Reality Headset V2 is de nieuwste versie van de originele Homodi VR-bril. Door uw smartphone in deze bril te bevestigen, kunt u genieten van verschillende video&#39;s en games. Bijna elk soort smartphone past in de behuizing van de bril en de bril is geschikt voor alle doelgroepen. De Homido VR Headset is comfortabel en makkelijk in gebruik. Door &eacute;&eacute;n druk op de actieknop krijgt u tijdens het gamen interactie met uw smartphone. Daarnaast is het mogelijk om de afstand van de lenzen handmatig aan te passen. Dat doet u eenvoudig door aan de draaiknop te draaien.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Homido Virtual Reality Headset V2:</span></h3><ul><li>100&deg; gezichtsveld</li><li>Verstelbare afstand tussen de lenzen van 55-70mm</li><li>Werkt met smartphones met 4.2-6 inch (10.7-15.24cm) scherm</li><li>Bruikbaar voor 360&deg; video&#39;s, VR-games en 3D-films</li><li>Actieknop voor interactie met uw smartphone</li></ul>', '2019-03-28', 43, NULL),
(4, 'DJI Goggles - wit', 1, '384,99', '<h2><span style=\"font-size: 24px;\">DJI Goggles</span></h2><p>De DJI Goggles is een speciale en comfortabele bril die het mogelijk maakt om met een first-person view te vliegen met DJI drones. De bril heeft twee hoogwaardige schermen, een optisch design, intelligente vliegmodi en een draadloos OcuSync-transmissiesysteem, waarmee u een uitstekende verbinding heeft met de drone en daardoor alles direct en zonder haperingen ziet. De Goggles bieden een 720p resolutie met 60fps en een 1080p resolutie met 30fps. Daarnaast zorgen de antennes in de hoofdband voor een 360 graden dekking. Met de Fixed-Wing Mode kunt u de drone rechtdoor laten vliegen en met de Head Tracking Mode kunt u uw hoofd gebruiken om de camera van de drone te besturen. Andere vliegmodi zijn Terrain Follow, ActiveTrack, Tapfly, Cinematic Mode en Tripod Mode. Met deze bril heeft u echt een vogelperspectief. De bril is geschikt voor de Mavic Pro, Phantom 4 drones en Inspire drones.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de DJI Goggles:</span></h3><ul><li>Bril om met een FPV te vliegen met DJI drones</li><li>Geschikt voor: de Mavic Pro drone, Phantom 4 drones en Inspire drones</li><li>Ge&iuml;ntegreerd Touchpad</li><li>Scherm resolutie: 3840x1080</li><li>Werk frequentie: 2.4GHz</li><li>Video resolutie: 1080p30, 720p60, 720p30</li><li>Batterij capaciteit: 9440mAh</li><li>Batterij vermogen: 35.44Wh</li><li>Maximale bedrijfstijd: 6 uur</li><li>Werkt bij een temperatuur tussen 0 en 40 graden Celsius</li><li>Ingangen: micro USB input, HDMI input, microSD kaart en audio ingang</li><li>Vliegmodi: Fixed-Wing Mode, Head Tracking Mode, Terrain Follow, ActiveTrack, TapFly, Cinematic Mode en Tripod Mode</li><li>Gewicht: 495g (Goggles) en 500g (hoofdband)</li><li>Afmetingen: 195x155x110mm (Goggles) en 255x205x92mm (hoofdband)</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de DJI Goggles:</span></h3><ul><li>DJI Goggles</li><li>Twee jaar garantie</li></ul>', '2019-03-28', 23, NULL),
(5, 'SALORA VR HAWK VIRTUAL REALITY BRIL', 1, '14,95', '<h2><span style=\"font-size: 24px;\">Salora VR Hawk Virtual Reality Bril</span></h2><p>De Salora VR HAWK is een stijlvol vormgegeven kunststof Virtual Reality bril voor smartphones van 3,5 tot 6 inch. Lens en focus kunnen op maat worden ingesteld. De voorzijde kan open geschoven worden, zodat de camera van uw telefoon vrijkomt; ideaal voor Augmented Reality applicaties. Verder is het heel eenvoudig om de telefoon in en uit de bril te halen. Met de Salora VR Hawk duikt u samen met uw smartphone in een virtuele wereld!</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Salora VR Hawk Virtual Reality Bril: </span></h3><ul><li><p>Stijlvolle kunststof VR bril</p></li><li><p>Comfortabele pasvorm</p></li><li><p>Lens en focus kunnen op maat worden ingesteld</p></li><li><p>Geschikt voor 3,5&quot; tot 6&quot; smartphones</p></li><li><p>Lens diameter: 25 mm</p></li><li><p>Focus afstand: 60 mm</p></li><li><p>Pupil afstand: 65 mm</p></li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de Salora VR Hawk Virtual Reality Bril:</span></h3><ul><li>Salora VR Hawk Virtual Reality Bril</li><li>Twee jaar garantie</li></ul>', '2019-03-28', 10, NULL),
(7, 'HYPER VIRTUAL REALITY HEADSET', 1, '29,99', '<h2><span style=\"font-size: 24px;\">Hyper Virtual Reality Headset</span></h2><p>De Hyper Virtual Reality Headset is en geschikt voor iedere Android en iOS smartphone van 4 tot 6 inch. Deze virtual reality headset is voorzien van een high performance koptelefoon met zuivere tonen en een explosieve bass voor een realistische 3D surround sound ervaring. Zo geniet u altijd van beeld in combinatie met het juiste geluid. De VR bril is geheel draadloos voor een maximale bewegingsvrijheid en u kunt de afstand tussen de lenzen en de hoofdband eenvoudig verstellen zodat hij altijd perfect past. Bovendien kunt u deze Headset ook gebruiken voor het bekijken van 3D films en is de bril voorzien van ventilatie voor uw gezicht en smartphone. Kortom, een veelzijdige en comfortabele VR bril.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Hyper Virtual Reality Headset:</span></h3><ul><li>Virtual Reality Headset</li><li>Voor elke iPhone en Samsung smartphone van 4&quot; tot 6&quot;</li><li>Ook geschikt voor brildragers</li><li>Draadloos: ja</li><li>Gezichtsveld: 120&deg;</li><li>Ge&iuml;ntegreerde speakers</li><li>Met hoofdband en oogkussen</li><li>Buttons voor volume, play/pause en inkomende gesprekken</li><li>Verstelbare koptelefoon en hoofdbanden</li><li>Smartphone- en gezichtsventilatie</li><li>Gewicht: 410g</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de Hyper Virtual Reality Headset:</span></h3><ul><li>Hyper Virtual Reality Headset</li><li>Twee jaar fabrieksgarantie</li></ul>', '2019-03-28', 12, NULL),
(8, 'Brofish Cardboard VR', 1, '4,95', '<h2><span style=\"font-size: 24px;\">Brofish Cardboard VR</span></h2><p>Deze Brofish Cardboard Virtual Reality Glasses is een betaalbare oplossing om met uw smartphone in een virtuele wereld te stappen. Plaats uw uw smartphone in de Cardboard VR Glasses en cre&euml;er zo uw eigen virtual reality headset. De bril is geschikt voor smartphones met een maximaal schermdiagonaal van 5.1 inch. U kunt de speciale apps downloaden via de iTunes of Play Store.</p><p>De Cardboard VR Glasses is geen kant-en-klaarproduct, maar een eenvoudig bouwpakket met instructies op het karton voor het in elkaar zetten. Na het starten van &eacute;&eacute;n van de compatibele Apps kunt u uw smartphone plaatsen aan de voorkant van deze bril. Aan de achterzijde van de Cardboard VR Glasses bevinden zich twee lensjes waardoor het scherm van uw smartphone vergroot te zien is. Door om u heen te kijken krijgt u het gevoel dat u in een virtuele wereld bent. Bepaalde applicaties kunnen gebruikmaken van de magneten aan de zijkant van de Cardboard VR Glasses.</p><p>Er zijn verschillende Apps beschikbaar voor de Cardboard VR Glasses. Zo is er een speciale Cardboard App van Google waarmee u Google Maps en Youtube kunt gebruiken en er is ook een App waarmee speciale 3D-filmpjes van Youtube bekeken kunnen worden. Ook zijn er eenvoudige spelletjes te gebruiken en vooral de virtual reality Apps waarbij u in een achtbaan of onder een hangglider hangt, zijn zeer de moeite waard. De makkelijkste manier om beschikbare Apps te vinden is om in de Google Play store of in de Apple iTunes Store te zoeken op de term &quot;Cardboard&quot;.</p><p>&nbsp;</p><h3><span style=\"font-size: 24px;\">Kenmerken:</span></h3><ul><li>Geschikt voor smartphones met een maximaal schermdiagonaal van 5.1&quot;</li><li>Compatibel met zowel iPhones als Android-smartphones</li><li>Incl. twee magneten en elastiek</li></ul>', '2019-03-28', 200, NULL),
(9, 'ZEISS VR ONE PLUS', 1, '59,99', '<h2><span style=\"font-size: 24px;\">Zeiss VR one plus</span></h2><p>Met de Zeiss VR one plus virtual realitybril beleeft u de ongelooflijke wereld van 3D, door enkel uw smartphone in de bril te schuiven. De Zeiss VR one plus is geschikt voor Android- en iOS-smartphones met een schermmaat van 4,7&quot; tot 5,5&quot;. U kunt bijvoorbeeld uw Iphone 6s of 6s Plus gemakkelijk in deze bril schuiven. Naast dat deze opvolger van de Zeiss VR one geschikt is voor smartphones met een groter scherm, is ook de hoofdband makkelijk ter verwijderen en kan het schuimrubber aan de binnenkant van de bril vervangen worden, wat zorgt voor betere hygi&euml;ne. Ook de lenzen aan de binnenkant (waar de telefoon ingeschoven wordt) kunnen beter gereinigd worden. Kortom, de Zeiss VR one plus is een meer gepolijste versie, die een meeslepende kijkervaring combineert met optimaal comfort.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Zeiss VR one plus:</span></h3><ul><li>Geschikt voor: smartphones met een schermmaat van 4,7&quot; tot 5,5&quot;</li><li>Gezichtsveld van ca. 100 graden</li><li>Afneembare elastische hoofdband voor een optimale pasvorm</li><li>Geschikt voor brildragers</li><li>Schuim binnenkant kan verwijderd worden voor betere hygi&euml;ne</li><li>Ventilatieopeningen zorgen voor een probleemloze ventilatie en voorkomen dat de headset beslaat</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de Zeiss VR one plus:</span></h3><ul><li>Gebruikershandleiding en veiligheidsinformatie</li><li>Universele Lade</li><li>2x Hoofdband</li><li>AR Cube</li><li>Twee jaar garantie</li></ul>', '2019-03-28', 44, '49,99'),
(10, 'FONTASTIC ESSENTIAL', 1, '14,95', '<h2><span style=\"font-size: 24px;\">Fontastic Essential Virtual Reality Bril zwart/wit</span></h2><p>De Fontastic Essential Virtual Reality Bril is geschikt voor het spelen van games en het kijken van video&#39;s. U plaatst uw smartphone eenvoudig in de bril en wordt zo volledig opgezogen door uw omgeving. De bril is voorzien van een Anti Blue Light lens.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Fontastic Essential Virtual Reality Bril zwart/wit</span></h3><ul><li>VR-bril voor smartphones</li><li>Geschikt voor zowel Android als iOs</li><li>Geschikt voor brildragers</li><li>Ook comfortabel bij langdurig gebruik</li><li>Aansluiting voor koptelefoon en oplaadkabel</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de Fontastic Essential Virtual Reality Bril zwart/wit</span></h3><ul><li>VR-bril</li><li>2 jaar garantie</li></ul>', '2019-03-28', 56, '9,99'),
(11, 'DJI GOGGLES RACING EDITION ANTENNE', 2, '9,99', '<h2><span style=\"font-size: 24px;\">DJI Goggles Racing Edition Part 5 OcuSync Pagoda Antenna (MMCX interface)</span></h2><p>De DJI Goggles Racing Edition Part 5 OcuSync Pagoda Antenna is een kleine 5,8GHz frequentie LHCP omnidirectionele antenne, die ontworpen is voor drone racen. Deze antenne kan worden bevestigd op de Air Unit via de MMCX interface. U kunt deze antenne niet gebruiken voor 2,4GHz tranmissies. Deze antenne zorgt voor vloeiende video-overdrachten.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de DJI Goggles Racing Edition Part 5 OcuSync Pagoda Antenna (MMCX interface):</span></h3><ul><li>Kleine 5,8GHz frequentie LHCP omnidirectionele antenne</li><li>Ontworpen voor drone racen</li><li>Kan worden bevestigd op de Air Unit via de MMCX interface</li><li>Niet te gebruiken voor 2,4GHz transmissies</li><li>Zorgt voor vloeiende video-overdrachten</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de DJI Goggles Racing Edition Part 5 OcuSync Pagoda Antenna (MMCX interface):</span></h3><ul><li>DJI Goggles Racing Edition Part 5 OcuSync Pagoda Antenna (MMCX interface)</li><li>Twee jaar garantie</li></ul>', '2019-03-28', 60, NULL),
(12, 'EPSON MOVERIO BT-300', 1, '669,99', '<h2><span style=\"font-size: 24px;\">Epson Moverio BT-300</span></h2><p>De Epson Moverio BT-300 is een geavanceerde Augmented Reality (AR) bril met een op silicium gebaseerde OLED-scherm. De Epson Moverio BT-300 is de lichtste binoculaire see-through multimediabril op de markt met een OLED-scherm van zeer hoge beeldkwaliteit. Deze bril biedt u de ultieme Augmented Reality ervaring dankzij het HD-display met hoog contrast voor een echte see-through beleving.</p><p>Aan de voorzijde zit een 5mp camera die POV-foto&#39;s en video&#39;s kan maken. De 1,44 GHz processor levert uitstekende prestaties, zelfs bij veeleisende toepassingen met veel content. Er zitten uitgebreide (bewegings)sensoren in de bril die de ervaring voor AR uniek maken.</p><p>De Moverio BT-300 werkt dankzij een app samen met uw DJI Drone zodat u in First Person-weergave (FPV) kunt fotograferen en filmen vanuit de lucht. U bestuurt uw drone terwijl u nog steeds oog heeft voor uw eigen omgeving. Speciale zonnekleppen in de bril zorgen ervoor dat u uw drone altijd in het zicht houdt als het zonnig is.</p><h3><span style=\"font-size: 24px;\">Belangrijkste kenmerken van de Epson Moverio BT-300:</span></h3><ul><li>HD-display (720p) geeft scherp beeld</li><li>Si-OLED-technologie voor optimale beeldkwaliteit</li><li>Hoog contrast om werkelijke AR te ervaren</li><li>Intel Atom x5 1,44 GHz Quad Core CPU</li><li>HD Camera (720p/5mp) voorzijde</li><li>Veel draadloze mogelijkheden (Wi-Fi, Bluetooth en Miracast)</li><li>Batterijduur van zes uur</li><li>Android besturingssysteem</li><li>Gewicht: 69 gram</li></ul><h3><span style=\"font-size: 24px;\">Standaard meegeleverd met de Epson Moverio BT-300:</span></h3><ul><li>Epson Moverio BT-300</li><li>Wisselstroomadapter</li><li>Draagtas</li><li>Binnenmontuur voor optische lenzen</li><li>In-ear-koptelefoon met microfoon</li><li>Korte installatiehandleiding</li><li>Getint opzetstuk</li><li>USB-kabel</li><li>Gebruikershandleiding (cd-rom)</li><li>Twee jaar garantie</li></ul>', '2019-03-28', 19, NULL),
(13, 'HTC Vive', 1, '599', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2160x1200</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td>Functies</td><td>Accelerometer, Camera, Gyroscoop, Verstelbare lenzen</td></tr><tr><td>Aansluitingen</td><td>HDMI, USB 2.0, USB 3.0</td></tr><tr><td>Kleur</td><td>Zwart</td></tr><tr><td>Bijzonderheden</td><td>Meegeleverde headset, 2 draadloze controllers en kabels</td></tr></tbody></table><p><br></p>', '2019-04-11', 45, NULL),
(14, 'Lenovo Explorer Headset', 1, '369,99', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2880x1440</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">110 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Camera, Gyroscoop, Magnetometer</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\"><br></td></tr></tbody></table>', '2019-04-11', 3, NULL),
(15, 'Google Daydream View', 1, '73,94', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Aansluitingen</td><td style=\"width: 50.0000%;\">USB 3.0(Type C)</td></tr><tr><td style=\"width: 50.0000%;\">Hoogte</td><td style=\"width: 50.0000%;\">100,2mm</td></tr><tr><td style=\"width: 50.0000%;\">Breedte</td><td style=\"width: 50.0000%;\">117mm</td></tr><tr><td style=\"width: 50.0000%;\"><br></td><td style=\"width: 50.0000%;\"><br></td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Geen eigen display, controller(s) meegeleverd</td></tr></tbody></table>', '2019-04-11', 23, NULL),
(16, 'Acer Mixed Reality Headset', 1, '349', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2880x1440</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">100 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Camera, Gyroscoop, Magnetometer<br></td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\"><br></td></tr></tbody></table>', '2019-04-11', 7, NULL),
(17, 'Dell Visor', 1, '413,34', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2880x1440</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">110 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Camera, Gyroscoop, Magnetometer</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">2x Controllers, headset</td></tr></tbody></table>', '2019-04-11', 5, NULL),
(18, 'Homido Smartphone Virtual Reality Headset', 1, '10,99', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Minimaal formaat smartphone</td><td style=\"width: 50.0000%;\">4&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Maximaal formaat smartphone</td><td style=\"width: 50.0000%;\">5,7&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Gyroscoop, Verstelbare lenzen</td></tr><tr><td style=\"width: 50.0000%;\">Kleur</td><td style=\"width: 50.0000%;\">Zwart</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Inclusief opbergtas</td></tr></tbody></table>', '2019-04-11', 2, NULL),
(19, 'Oculus Rift Bundle', 1, '507,99', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2160x1200</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">110 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Gyroscoop, Koptelefoon, Magnetometer</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Microfoon, headset</td></tr></tbody></table>', '2019-04-11', 7, NULL),
(20, 'Oculus Go 64GB', 1, '263,90', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2560x1440</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">72Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">100 graden</td></tr><tr><td style=\"width: 50.0000%;\">Aansluiting</td><td style=\"width: 50.0000%;\">3,5mm</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\"><br></td></tr></tbody></table>', '2019-04-11', 3, NULL),
(21, 'Samsung New Gear VR', 1, '121', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">101 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Gyroscoop</td></tr><tr><td style=\"width: 50.0000%;\">Hoogte</td><td style=\"width: 50.0000%;\">98,6mm</td></tr><tr><td style=\"width: 50.0000%;\"><br></td><td style=\"width: 50.0000%;\"><br></td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Controllers<br>Geschikt voor Samsung Galaxy S6, S7 en S8</td></tr></tbody></table>', '2019-04-11', 53, NULL),
(22, 'Salora VR Gecko', 1, '24,51', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Minimaal formaat smartphone</td><td style=\"width: 50.0000%;\">3,5&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Maximaal formaat smartphone</td><td style=\"width: 50.0000%;\">6&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Kleur</td><td style=\"width: 50.0000%;\">Wit</td></tr><tr><td style=\"width: 50.0000%;\"><br></td><td style=\"width: 50.0000%;\"><br></td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\"><br></td></tr></tbody></table>', '2019-04-11', 63, NULL),
(23, 'Celexon VRG-1', 1, '14,99', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Minimaal formaat smartphone</td><td style=\"width: 50.0000%;\">4,7&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Maximaal formaat smartphone</td><td style=\"width: 50.0000%;\">6&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Kleur</td><td style=\"width: 50.0000%;\">Wit, Zwart</td></tr><tr><td style=\"width: 50.0000%;\"><br></td><td style=\"width: 50.0000%;\"><br></td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Geen eigen display</td></tr></tbody></table>', '2019-04-11', 8, NULL),
(24, 'Hyper BoboVR Z4', 1, '29', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">Smartphone</td></tr><tr><td style=\"width: 50.0000%;\">Minimaal formaat smartphone</td><td style=\"width: 50.0000%;\">4&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Maximaal formaat smartphone</td><td style=\"width: 50.0000%;\">6&quot;</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">120 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Koptelefoon</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\"><br></td></tr></tbody></table>', '2019-04-11', 53, NULL),
(25, 'Sony Playstation VR ', 1, '298', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PlayStation 4</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">1920x1080 (Full HD)</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">100 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Gyroscoop</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Headset</td></tr></tbody></table>', '2019-04-11', 23, NULL),
(26, 'Medion Erazer X1000 Mixed Reality Headset', 1, '449', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2880x1440</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">110 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Accelerometer, Camera, Gyroscoop, Magnetometer</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">Headset</td></tr></tbody></table>', '2019-04-11', 13, NULL),
(27, 'HTC Vive Pro (Starter Kit)', 1, '1149', '<table style=\"width: 100%;\"><tbody><tr><td style=\"width: 50.0000%;\">Platform</td><td style=\"width: 50.0000%;\">PC</td></tr><tr><td style=\"width: 50.0000%;\">Resolutie</td><td style=\"width: 50.0000%;\">2880x1600</td></tr><tr><td style=\"width: 50.0000%;\">Refresh rate</td><td style=\"width: 50.0000%;\">90Hz</td></tr><tr><td style=\"width: 50.0000%;\">Gezichtsveld</td><td style=\"width: 50.0000%;\">110 graden</td></tr><tr><td style=\"width: 50.0000%;\">Functies</td><td style=\"width: 50.0000%;\">Camera, Gyroscoop, Koptelefoon, Magentometer, Microfoon, Verstelbare lenzen</td></tr><tr><td style=\"width: 50.0000%;\">Bijzonderheden</td><td style=\"width: 50.0000%;\">2x Controller</td></tr></tbody></table>', '2019-04-11', 9, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `img_name` varchar(2500) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `product_images`
--

INSERT INTO `product_images` (`id`, `img_name`, `product_id`) VALUES
(4, '999333.jpg', 2),
(5, '999334.jpg', 2),
(6, '1000201.jpg', 2),
(7, '1000203.jpg', 2),
(8, '1000217.jpg', 2),
(9, 'product.jpg', 2),
(10, '1a9e8228-64d2-444a-b80b-227d60220f35.jpg', 3),
(11, 'b4ea1af1-768a-4ca3-8995-71a0f7f10884.png', 3),
(12, 'fada5a5b-ce22-47d1-9c5d-79eb31b88e2f.jpg', 3),
(13, '30d95b06-e622-460c-a27c-3eeab89814cc.jpg', 4),
(14, '884d41e2-408b-4518-be11-530faf2634dc.jpg', 4),
(15, 'cae119a9-6a2f-4b11-8144-916f0d6f6dcd.jpg', 4),
(16, 'd5e85fa8-e716-4d87-9054-58a9e6a21483.jpg', 4),
(17, 'e4bd8e2a-84e8-4333-9ecd-8ce7a6186651.jpg', 4),
(18, 'fada5a5b-ce22-47d1-9c5d-79eb31b88e2f.jpg', 4),
(19, '2c69a6bc-23d0-46de-bce2-c8da7fb4e1c2.jpg', 5),
(20, '8f75f64b-db1b-4230-a80a-6a91d83df934.jpg', 5),
(21, '41cd42e5-2bba-40b4-b56b-5f731eadbdfa.jpg', 5),
(22, '90f3cd9a-d5f6-46aa-a962-a8f795c7a107.jpg', 7),
(23, '6199a782-4d20-4ca7-9bea-1280b883a531.jpg', 7),
(24, 'ba610222-c53d-4a9c-860e-d3b76089b1f6.jpg', 7),
(25, 'c21ac3ce-e631-494c-8a48-86cba52dc49c.jpg', 7),
(26, '8bb7de18-6698-4d98-9c5d-4f2bd133e7f5.jpg', 8),
(27, '9ba7ea1c-2d6f-45f1-b744-07cd0ae5e16e.jpg', 8),
(28, '9431b18b-2d9c-473e-a4dc-3b7ceb56b654.jpg', 8),
(29, 'b3d6be42-9f50-4dbc-8a0d-0a7d21098b41.jpg', 8),
(30, 'c24c849e-a81c-4280-abbe-5360af39ba23.jpg', 8),
(31, '19a2709c-5302-48b7-9cd5-ffc3dbc06007.jpg', 9),
(32, '94a66f21-898d-4568-b479-80bcadea9459.jpg', 9),
(33, '6775fed9-98be-4f40-8d38-d663bbcc4234.jpg', 9),
(34, 'aa53eb81-3484-41fd-94af-2ff1d9d2f223.jpg', 9),
(35, 'c44d4609-da82-497b-83ef-f4e401ea9844.jpg', 9),
(36, 'd04183f6-efa7-48be-96c6-51107e864d1b.jpg', 9),
(37, 'e37e005f-74c0-4ee8-aa2a-558f3485fd26.jpg', 9),
(38, 'ef177140-4353-4617-84ff-39bb6ff74ef2.jpg', 9),
(39, 'fc6f8587-ca97-4aab-8d07-1100b10b442c.jpg', 9),
(40, '28b65e57-ba99-42ac-8a76-446285905e8b.jpg', 10),
(41, '7acdd0ff-e1df-47fd-8d83-d21e687fb4b1.jpg', 11),
(42, '52aca3f4-0bf9-4055-9e58-fda8218278df.jpg', 11),
(43, '68ccac12-b767-40e3-a879-5f3a44005336.jpg', 11),
(44, '4c5e7af8-595f-4ed5-b084-98defc7f1d7a.jpg', 12),
(45, '6ac2f42a-503c-4571-ab43-35454e1957a8.jpg', 12),
(46, '14f5fce7-ce45-4a9f-9e72-79fc4333b6fb.jpg', 12),
(47, 'c58b72f6-0723-40ff-94a2-5898db337a74.jpg', 12),
(48, 'e7c9b7c1-6b54-41e2-9d96-87fddfb8e468.jpg', 12),
(49, '2001712845.jpeg', 14),
(50, '2001712847.jpeg', 14),
(51, '2001712849.jpeg', 14),
(52, '2001253193.jpeg', 15),
(53, '2001712799.png', 16),
(54, '2001712801.png', 16),
(55, '2001712803.png', 16),
(56, '2001740465.jpeg', 17),
(57, '2000566018.png', 18),
(58, '2000566019.png', 18),
(59, '2000566020.jpeg', 18),
(60, '2002572292.jpeg', 19),
(61, '2001477121.png', 21),
(62, '2001477123.png', 21),
(63, '2001477125.png', 21),
(64, '2001740159.jpeg', 22),
(65, '2001740161.jpeg', 22),
(66, '2001740163.jpeg', 22),
(67, '2001740165.jpeg', 22),
(68, '2001740167.jpeg', 22),
(69, '2001740465.jpeg', 22),
(70, '2002280261.jpeg', 23),
(71, '2001764669.jpeg', 24),
(72, '2001890499.png', 25),
(73, '2001691081.jpeg', 26),
(74, '2001691083.jpeg', 26),
(75, '2001691085.jpeg', 26),
(76, '2001691087.jpeg', 26),
(77, '2001691089.jpeg', 26),
(78, '2002476424.png', 27),
(79, '2002476426.jpeg', 27),
(80, '2002476428.png', 27),
(81, '2002509794.jpeg', 27);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `shop_catogories`
--

CREATE TABLE `shop_catogories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `shop_catogories`
--

INSERT INTO `shop_catogories` (`id`, `cat_name`) VALUES
(1, 'VR Brillen'),
(2, 'VR accessoires'),
(3, '360 graden camera');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `prioriteit` int(11) NOT NULL,
  `titel` varchar(250) NOT NULL,
  `bericht` varchar(2500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(70) NOT NULL,
  `email` varchar(250) NOT NULL,
  `token` varchar(64) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `token`, `ip`, `active`, `role`) VALUES
(1, 'Lex', '0765E7C599BBCC53E101A9FAD4D8C9FC7E8C9628', 'hello@lexmakesyour.site\r\n', '8586013ec5c26a5792ede2485c4fda4e108a7d57aa419c574a9ee9c40a88ad89', '::1', 1, 3),
(19, 'Oeh', '1eec2a86912f53a70d45ff9c95523ae37c245118', 'adsfsfda@asfdfs.com', '', '', 0, 0),
(21, 'Lex12313', '0765E7C599BBCC53E101A9FAD4D8C9FC7E8C9628', 'lelelele@lelele.com', 'dc78baddf9343661be848634084cbd4610b9aa46b6f55413350a47175c599fee', '::1', 0, 0),
(22, 'Falco', '0765e7c599bbcc53e101a9fad4d8c9fc7e8c9628', 'test@falco.nl', '2c1abbbeb8c3c23ccb7e73c64a16f99275decebf1996c63d2359454c042818ce', '::1', 0, 3),
(23, 'TestAdmin', '0765e7c599bbcc53e101a9fad4d8c9fc7e8c9628', 'lalala@lalala.com', 'b10397105c3ef7026bcb2d29a02c2a6c85d4e1b24a87178fd8ce02e99c413d2c', '::1', 0, 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_cat` (`product_cat`);

--
-- Indexen voor tabel `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexen voor tabel `shop_catogories`
--
ALTER TABLE `shop_catogories`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT voor een tabel `shop_catogories`
--
ALTER TABLE `shop_catogories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`product_cat`) REFERENCES `shop_catogories` (`id`);

--
-- Beperkingen voor tabel `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
