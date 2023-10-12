SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `phone`, `password`) VALUES
    (1,	'Martin',	'Kocáb',	'kocab@mail.to',	NULL,	''),
    (2,	'Petr',	'Pavel',	'petrpavel@hrad.cz',	'+420602584422',	''),
    (3,	'Dagmar',	'Čápová',	'dada@email.cz',	NULL,	''),
    (4,	'Vlastislav',	'Müller',	'spammer@superspam.cz',	'+420402875942',	''),
    (5,	'Jan',	'Skočdopole',	'jane.skoc@skokan.cz',	'+420456456457',	''),
    (6,	'Michal',	'Horak',	'aaa@bbb.cz',	'+485452604548',	'$2y$10$/XckBnT6QuK7PZkmFo6Rw.SJm5mXOC.WRzcuUDp9tWTis.8V6dREu'),
    (7,	'Martin',	'Keller',	'martinkel@email.cz',	'557846412',	'$2y$10$QYC9A8B8pZa74EbWzs5fs.AhGnVTdhhJQJ6a5jLhrHYKh1USTYvne'),
    (8,	'Petr',	'Malý',	'malypeter@peter.cz',	'847570420',	'$2y$10$lQDHn8w6uGLtTGUPISrZT.i3UyoHUPMxl3S15neC1pJsHXBtKSGP.'),
    (9,	'Petr',	'Malý',	'malypeter@peter.cz',	NULL,	'$2y$10$q69IY/7KCzSVg2ev4nI6Ju.BZVXpdve9zn1MDcJVpKs34F8W2rZ1u'),
    (10,	'Michal',	'Pokorný',	'pokoratom@gmail.com',	'+420687456512',	'$2y$10$8UpDTblX77IgKpQ5YqMTLuAJa4DDXj3GKEqwExp2g1P/an7ryYH.m'),
    (11,	'Martin',	'Malý',	'aaa@bbb.cz',	'+420687456512',	'$2y$10$m8fe3YMWdR4XPMWbHuvsreaS/pY9opQn3CWUEsUPPW6jJ4wqK1m9u'),
    (12,	'Petr',	'Jakeš',	'jakeska@jakes.net',	'+420524789416',	'$2y$10$FRfACvoU72Dyiz1ME.NG2OCR3QUa6GLeJcDXJbtggBP.SSxVWlYyu'),
    (13,	'Tadeáš',	'Skočdopole',	'tade@gustav.cz',	'+421715418921',	'$2y$10$/586AH.l0AZzurrR/PIL/Ot09E5X6dSPmLzN.f4gHKxNSYFmH.BIC');

INSERT INTO `address` (`id`, `user_id`, `street`, `city`, `zip`) VALUES
    (1,	2,	'Milady Horákové 36',	'Brno',	61400),
    (2,	5,	'Melicharova 5',	'Telč',	41462),
    (3,	7,	'Liškova 15',	'Uhříněves',	22000),
    (4,	8,	'Třeboňská 56',	'Třeboň',	14200),
    (5,	12,	'Hradní 1',	'Praha',	10000);

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
    (1,	'a_431',	'$2a$12$rIAY6I55xkeP.gbraxsx8Op7yfTtHzp0MeOALuHX0JvT.fEOGygcS');
