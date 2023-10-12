# User Manager

User Manager je webová aplikace, která slouží ke správě uživatelů

## Instalace

- V souboru ```app/config.php``` nastavte přístupy k databázy
- Ve složce ```installation``` jsou soubory ```db_structure.sql``` a ```db_data.sql```. Obsahují sql pro vytvoření struktury databáze a naplnění daty.
- Aplikace potřebuje PHP 8.1 a server Apache
- Po vytvoření databáze, naplnění daty a otevření stránky budete přesměrováni na přihlašovací obrazovku. Tam se přihlásíte uživatelským jménem ```a_431``` a heslem ```CWJ1gPKhHw```

## Struktura aplikace

- Hlavní část aplikace je ve složce ```app```
- Jedná se o ```aplikaci typu MVC```, podle toho jsou pojmenované složky
- Ve složce ```app/Core``` jsou základní třídy aplikace
- Základní hodnoty aplikace se nastavují v ```app/config.php```
- Celkové nastavení aplikace a rout se provvádí v  ```app/App.php ```
- Assety typu css, javascriptu atd. jsou umístěny ve složce  ```/www ```
- Všechny dotazy jsou prostřednictvím ```.htaccess``` nasměrovány na ```bootstrap.php```

## Struktura databáze
- Tabulka ```users``` obsahuje data uživatelů
- Tabulka ```address``` obsahuje adresy uživatelů. S tabulkou users je spojena cizím klíčem ```user_id```
- Tabulka ```admins``` obsahuje administrátory, kteří mohou upravovat uživatele

### Soubory - app/Controller

| Soubor | Popis |
| ------ | ------ |
| AuthController.php | Controller pro autorizaci administrátora |
| UsersController.php  | Controller pro správu uživatelů |

### Soubory - app/Model
| Soubor | Popis |
| ------ | ------ |
| AddressModel.php | Třída, pro vytváření a úpravu adres |
| UserModel.php  | Třída, pro vytváření a úpravu uživatelů |
| AdminModel.php  | Třída, pro autorizaci administrátora |

> **Poznámka**
Vlastnosti modelů mají viditelnost private a při jejich nastavení dochází zároveň k ošetření vstupů


### Soubory - app/View
| Soubor | Popis |
| ------ | ------ |
| page.php | Základní layout stránky |
| users.php  | Layout pro vypsání uživatelů |
| auth.php  | Layout pro přihlášení administrátora |

### Soubory - app/Core
| Soubor | Popis |
| ------ | ------ |
| Autoloader.php | Třída pro autoloading souborů |
| BaseController.php  | Abstraktní třída ze které dědí controllery |
| BaseModel.php  | Abstraktní třída ze které dědí Modely |
| Config.php  | Třída pro načítání a předávání konfigurací |
| View.php  | Třída obsahující základní funkce pro práci s View |

### Soubory - www/js
| Soubor | Popis |
| ------ | ------ |
| app.js | Slouží pro ovládání aplikace |
| User.js  | Třída uživatele. Obsahuje funkce pro upravy uživatelů a odesílání dat v requestu |
| Address.php  | Třída adresy. Používá se pro odeslání dat v requestu |

### Funkce aplikace
- Přidávání nových uživatelů
- Editace stávajících uživatelů
- Mazání uživatelů
- Administrátor si nemůže zobrazit heslo uživatele. Může pouze nastavit uživateli nové heslo