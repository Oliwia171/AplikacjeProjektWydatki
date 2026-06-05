# Dokumentacja projektu: System rozliczania wydatków grupowych

## 1. Informacje ogólne

**Nazwa projektu:** System rozliczania wydatków grupowych  
**Technologie:** Laravel 12, PHP 8.2+, Blade, Tailwind CSS, Alpine.js, Vite, SQLite/MySQL  
**Charakter projektu:** aplikacja webowa do tworzenia grup rozliczeniowych, dodawania wydatków, dzielenia rachunków między członków grupy oraz zarządzania użytkownikami przez administratora.

Projekt umożliwia:

- rejestrację i logowanie użytkowników,
- tworzenie grup rozliczeniowych,
- dodawanie użytkowników do grup,
- dodawanie wydatków do konkretnej grupy,
- automatyczny podział kosztów między członków grupy,
- dodawanie pozycji z paragonu i przypisywanie ich do konkretnych osób,
- podgląd sald członków grupy,
- zarządzanie rolami użytkowników w panelu administratora,
- edycję profilu użytkownika,
- przełączanie trybu jasnego i ciemnego.

## 2. Godziny niekontaktowe

**Praca własna studenta:** 20h

Zakres pracy własnej:

- analiza wymagań projektu,
- przygotowanie modeli danych i migracji,
- implementacja logowania, rejestracji i ról użytkowników,
- implementacja grup rozliczeniowych,
- implementacja rachunków i podziału kosztów,
- implementacja panelu administratora,
- przygotowanie widoków Blade,
- dostosowanie wyglądu aplikacji,
- przygotowanie dokumentacji.

## 3. Instrukcja uruchomienia projektu

### 3.1. Wymagania systemowe

Do uruchomienia projektu wymagane są:

- PHP 8.2 lub nowszy,
- Composer,
- Node.js i npm,
- SQLite albo MySQL,
- przeglądarka internetowa.

### 3.2. Pobranie i instalacja zależności

```bash
cd sciezka/do/projektu
composer install
npm install
```

### 3.3. Konfiguracja środowiska

Skopiuj plik środowiskowy:

```bash
cp .env.example .env
```

Wygeneruj klucz aplikacji:

```bash
php artisan key:generate
```

### 3.4. Konfiguracja bazy danych SQLite

Projekt może działać lokalnie na SQLite. Należy utworzyć plik bazy danych:

```bash
touch database/database.sqlite
```

W pliku `.env` ustaw:

```env
DB_CONNECTION=sqlite
```

Następnie uruchom migracje z danymi testowymi:

```bash
php artisan migrate:fresh --seed
```

### 3.5. Budowanie zasobów frontendowych

Tryb produkcyjny:

```bash
npm run build
```

Tryb developerski:

```bash
npm run dev
```

### 3.6. Uruchomienie serwera

```bash
php artisan serve
```

Aplikacja będzie dostępna pod adresem:

```text
http://127.0.0.1:8000
```

### 3.7. Konta testowe

Po uruchomieniu seedera dostępne są konta:

| Rola | Email | Hasło |
| --- | --- | --- |
| administrator | oliwia@example.com | password |
| użytkownik | adam@example.com | password |
| użytkownik | ewa@example.com | password |

## 4. Podręcznik użytkownika

### 4.1. Rejestracja

Użytkownik może utworzyć konto przez formularz rejestracji.

Wymagane pola:

- imię/nazwa użytkownika,
- adres email,
- hasło,
- potwierdzenie hasła.

Walidacja po stronie serwera:

- nazwa jest wymagana i może mieć maksymalnie 255 znaków,
- email jest wymagany, musi mieć poprawny format i musi być unikalny,
- hasło musi spełniać reguły bezpieczeństwa Laravel,
- potwierdzenie hasła musi zgadzać się z hasłem.

### 4.2. Logowanie

Użytkownik loguje się adresem email i hasłem. Po poprawnym logowaniu zostaje przekierowany do panelu głównego.

### 4.3. Panel główny

Po zalogowaniu użytkownik widzi panel główny z linkiem do zarządzania grupami rozliczeniowymi.

### 4.4. Grupy rozliczeniowe

W zakładce **Moje Grupy** użytkownik może:

- zobaczyć listę grup,
- dodać nową grupę,
- wejść w szczegóły grupy,
- edytować grupę, jeżeli jest jej właścicielem albo administratorem,
- usunąć grupę, jeżeli jest jej właścicielem albo administratorem.

Administrator widzi wszystkie grupy w systemie. Zwykły użytkownik widzi tylko grupy, do których należy.

### 4.5. Szczegóły grupy

W szczegółach grupy użytkownik widzi:

- nazwę grupy,
- listę członków,
- formularz dodawania członka po adresie email,
- panel rozliczeń,
- sumę wydatków,
- historię rachunków,
- formularz dodawania wydatku,
- formularz dodawania pozycji z paragonu do konkretnego rachunku.

### 4.6. Dodawanie wydatku

Formularz dodawania wydatku znajduje się w szczegółach grupy.

Pola formularza:

- opis wydatku,
- kwota,
- płatnik wybierany z listy członków grupy.

Użytkownik nie wpisuje ręcznie identyfikatora płatnika. Wybiera go z listy, co ogranicza liczbę błędów i odwzorowuje relację między rachunkiem a użytkownikiem.

### 4.7. Pozycje z paragonu

Dla każdego rachunku można dodać pozycję z paragonu.

Pola formularza:

- nazwa pozycji,
- cena,
- liczba sztuk,
- lista użytkowników, do których pozycja ma być przypisana.

To pozwala oznaczyć, kto korzystał z konkretnej pozycji, np. kto zamówił daną potrawę.

### 4.8. Profil użytkownika

W profilu użytkownik może:

- zmienić nazwę,
- zmienić email,
- zmienić hasło,
- usunąć własne konto po potwierdzeniu hasłem.

## 5. Dostęp użytkownika niezalogowanego

Użytkownik niezalogowany ma dostęp do:

- strony powitalnej `/`,
- formularza logowania `/login`,
- formularza rejestracji `/register`,
- formularzy resetowania hasła.

Zasoby aplikacji, takie jak grupy, rachunki, panel administratora i profil, są zabezpieczone middleware `auth` oraz `verified`. Oznacza to, że użytkownik niezalogowany:

- nie może tworzyć grup,
- nie może przeglądać szczegółów grup,
- nie może dodawać wydatków,
- nie może edytować danych,
- nie może usuwać danych,
- nie ma dostępu do panelu administratora.

W obecnej implementacji publiczny, niezalogowany podgląd zasobów biznesowych nie jest udostępniony. Dostęp do danych rozliczeniowych wymaga zalogowania, ponieważ zawierają one dane użytkowników, wydatki i informacje finansowe.

## 6. Role użytkowników

W projekcie występują dwie role:

| Rola | Opis |
| --- | --- |
| `user` | zwykły użytkownik aplikacji |
| `admin` | administrator systemu |

Rola jest przechowywana w kolumnie `role` tabeli `users`.

Metoda sprawdzająca rolę:

```php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

## 7. Uprawnienia użytkowników

### 7.1. Użytkownik zwykły

Zwykły użytkownik może:

- przeglądać własne grupy,
- tworzyć nowe grupy,
- wejść w szczegóły grup, których jest członkiem,
- dodawać członków do grup, do których ma dostęp,
- dodawać wydatki w grupach, do których należy,
- dodawać pozycje z paragonu,
- usuwać rachunki w grupach, do których ma dostęp,
- edytować i usuwać grupę, jeżeli jest jej właścicielem,
- edytować własny profil,
- usunąć własne konto.

### 7.2. Administrator

Administrator może:

- przeglądać wszystkie grupy,
- wchodzić w szczegóły wszystkich grup,
- edytować i usuwać wszystkie grupy,
- zarządzać wydatkami w grupach,
- wejść do panelu administratora,
- przeglądać listę użytkowników,
- zmieniać role użytkowników,
- usuwać konta innych użytkowników.

Administrator nie może usunąć własnego konta z poziomu panelu administratora. Zabezpiecza to aplikację przed przypadkowym usunięciem ostatniego aktywnego konta administracyjnego.

## 8. Zarządzanie profilami użytkowników przez administratora

Panel administratora znajduje się pod adresem:

```text
/admin/users
```

Panel jest chroniony middleware `admin`.

Administrator widzi listę użytkowników posortowaną alfabetycznie po nazwie. Dla każdego użytkownika dostępne są:

- imię/nazwa,
- email,
- rola,
- akcje administracyjne.

Administrator może:

- zmienić rolę użytkownika na `user` albo `admin`,
- usunąć użytkownika,
- zobaczyć podstawowe dane profilu użytkownika.

Walidacja aktualizacji profilu w panelu administratora:

- `role` jest wymagane i musi mieć wartość `user` albo `admin`,
- `name` jest wymagane i może mieć maksymalnie 255 znaków.

## 9. Model danych i relacje

### 9.1. Tabela `users`

Przechowuje konta użytkowników.

Najważniejsze pola:

- `id`,
- `name`,
- `email`,
- `password`,
- `role`,
- `email_verified_at`.

Relacje:

- użytkownik należy do wielu grup (`belongsToMany Group`),
- użytkownik może być właścicielem wielu grup,
- użytkownik może być płatnikiem wielu rachunków,
- użytkownik może mieć przypisane pozycje z paragonu.

### 9.2. Tabela `groups`

Przechowuje grupy rozliczeniowe.

Najważniejsze pola:

- `id`,
- `name`,
- `owner_id`,
- `total_amount`.

Relacje:

- grupa należy do właściciela (`owner_id -> users.id`),
- grupa ma wielu użytkowników przez tabelę `group_user`,
- grupa ma wiele rachunków.

### 9.3. Tabela `group_user`

Tabela łącząca użytkowników z grupami.

Relacje:

- wiele grup może mieć wielu użytkowników,
- jeden użytkownik może należeć do wielu grup.

### 9.4. Tabela `bills`

Przechowuje rachunki/wydatki przypisane do konkretnej grupy.

Najważniejsze pola:

- `id`,
- `group_id`,
- `payer_id`,
- `description`,
- `amount`,
- `date`.

Relacje:

- rachunek należy do grupy,
- rachunek ma płatnika,
- rachunek ma wiele pozycji z paragonu,
- rachunek ma wiele podziałów kosztów.

### 9.5. Tabela `bill_splits`

Przechowuje automatyczny podział kosztów rachunku między członków grupy.

Najważniejsze pola:

- `bill_id`,
- `user_id`,
- `amount`,
- `is_paid`.

### 9.6. Tabela `bill_items`

Przechowuje pozycje z paragonu.

Najważniejsze pola:

- `bill_id`,
- `name`,
- `price`,
- `quantity`.

### 9.7. Tabela `bill_item_user`

Tabela przypisująca pozycje z paragonu do użytkowników.

Relacja:

- jedna pozycja może dotyczyć wielu użytkowników,
- jeden użytkownik może mieć wiele pozycji.

## 10. CRUD zasobu zależnego od drugiego zasobu

Jako zasób zależny wybrano **rachunek/wydatki (`Bill`)**, ponieważ każdy rachunek musi należeć do konkretnej **grupy (`Group`)**.

Relacja:

```text
Group 1 ---- * Bill
```

Oznacza to, że rachunek nie istnieje samodzielnie. Jest zawsze dodawany w kontekście konkretnej grupy.

### 10.1. CREATE - tworzenie rachunku

Adres:

```text
POST /groups/{group}/bills
```

Kontroler:

```text
BillController@store
```

Formularz znajduje się w szczegółach grupy.

Pola formularza:

| Pole | Typ | Opis |
| --- | --- | --- |
| `description` | tekst | opis wydatku, np. obiad, paliwo, nocleg |
| `amount` | liczba | kwota rachunku |
| `payer_id` | select | płatnik wybrany z listy członków grupy |

Formularz jest dostosowany do relacji w systemie, ponieważ płatnik nie jest wpisywany ręcznie jako identyfikator. Użytkownik wybiera płatnika z listy osób należących do grupy.

Walidacja po stronie klienta:

- pole opisu jest oznaczone jako `required`,
- pole kwoty jest polem liczbowym,
- płatnik jest wybierany z listy `select`, dzięki czemu użytkownik nie wpisuje ręcznie błędnego identyfikatora.

Walidacja po stronie serwera:

```php
$request->validate([
    'description' => 'required|string|max:255',
    'amount' => 'required|numeric|min:0.01',
    'payer_id' => 'required|exists:users,id',
]);
```

Dodatkowa walidacja biznesowa:

```php
abort_unless($group->users->contains($request->payer_id), 422, 'Platnik musi byc czlonkiem grupy.');
```

Znaczenie:

- opis jest wymagany,
- kwota musi być liczbą większą od zera,
- płatnik musi istnieć w tabeli użytkowników,
- płatnik musi należeć do tej samej grupy.

Po dodaniu rachunku aplikacja automatycznie tworzy rekordy w tabeli `bill_splits`, dzieląc koszt równo między członków grupy.

### 10.2. READ - odczyt rachunków

Rachunki są widoczne w szczegółach grupy:

```text
GET /groups/{group}
```

Kontroler:

```text
GroupController@show
```

Dane ładowane w widoku:

```php
$group->load(['bills.payer', 'bills.items.users', 'bills.splits.user', 'users']);
```

Lista rachunków pokazuje:

- opis wydatku,
- płatnika,
- kwotę,
- przypisane podziały kosztów,
- pozycje z paragonu,
- użytkowników przypisanych do pozycji.

Filtrowanie dostępu:

- administrator widzi wszystkie grupy i ich rachunki,
- zwykły użytkownik widzi tylko rachunki w grupach, do których należy.

Sortowanie:

- lista użytkowników w panelu administratora jest sortowana alfabetycznie po nazwie,
- rachunki są prezentowane w kontekście grupy zgodnie z kolejnością pobrania z relacji Eloquent.

Możliwe rozszerzenie funkcjonalne:

- dodanie filtrów rachunków po płatniku,
- filtrowanie po zakresie kwot,
- filtrowanie po dacie,
- sortowanie po kwocie lub dacie.

### 10.3. UPDATE - aktualizacja danych

W obecnej implementacji aplikacja posiada aktualizację:

- grupy (`GroupController@update`),
- profilu użytkownika (`ProfileController@update`),
- roli użytkownika w panelu administratora (`Admin\UserController@update`).

Dla rachunku (`Bill`) w kodzie nie ma osobnego formularza edycji rachunku. Migracja MySQL zawiera jednak trigger obsługujący aktualizację kwoty rachunku:

```sql
CREATE TRIGGER update_group_total_after_bill_update
AFTER UPDATE ON bills
FOR EACH ROW
BEGIN
    UPDATE groups
    SET total_amount = total_amount - OLD.amount + NEW.amount
    WHERE id = NEW.group_id;
END
```

Oznacza to, że warstwa bazy danych jest przygotowana na aktualizację rachunku, ale interfejs użytkownika nie zawiera jeszcze formularza edycji rachunku.

Przykład istniejącej aktualizacji grupy:

```text
PUT/PATCH /groups/{group}
```

Walidacja:

```php
$request->validate(['name' => 'required|string|max:255']);
```

### 10.4. DELETE - usuwanie rachunku

Adres:

```text
DELETE /groups/{group}/bills/{bill}
```

Kontroler:

```text
BillController@destroy
```

Formularz usuwania:

- korzysta z metody `POST` z dyrektywą Blade `@method('DELETE')`,
- zawiera token CSRF,
- wymaga potwierdzenia akcji w oknie `confirm('Usunac rachunek?')`, co ogranicza ryzyko przypadkowego usunięcia.

Zabezpieczenia:

- użytkownik musi mieć dostęp do grupy,
- rachunek musi należeć do wskazanej grupy.

Kod sprawdzający poprawność:

```php
$this->authorizeGroupAccess($group);
abort_unless($bill->group_id === $group->id, 404);
```

Po usunięciu rachunku:

- rachunek znika z historii,
- powiązane dane zależne są usuwane dzięki relacjom i kluczom obcym,
- w SQLite suma grupy jest zmniejszana w kodzie PHP,
- w MySQL suma grupy jest aktualizowana przez trigger.

## 11. CRUD grup rozliczeniowych

Grupa jest podstawowym zasobem biznesowym aplikacji.

### 11.1. CREATE

Adres:

```text
POST /groups
```

Walidacja:

```php
'name' => 'required|string|max:255'
```

Po utworzeniu grupy aktualny użytkownik zostaje automatycznie:

- właścicielem grupy,
- pierwszym członkiem grupy.

### 11.2. READ

Adres:

```text
GET /groups
GET /groups/{group}
```

Zasady widoczności:

- administrator widzi wszystkie grupy,
- zwykły użytkownik widzi tylko swoje grupy.

### 11.3. UPDATE

Adres:

```text
PATCH /groups/{group}
```

Aktualizować grupę może:

- właściciel grupy,
- administrator.

### 11.4. DELETE

Adres:

```text
DELETE /groups/{group}
```

Usunąć grupę może:

- właściciel grupy,
- administrator.

Po usunięciu grupy usuwane są zależne rekordy, np. powiązania użytkowników i rachunki.

## 12. Zarządzanie zasobami przez użytkowników

### 12.1. Tworzenie grup

Każdy zalogowany użytkownik może utworzyć grupę. System automatycznie przypisuje go jako właściciela.

### 12.2. Dodawanie członków do grupy

Członka dodaje się przez email. System sprawdza:

- czy email jest wymagany,
- czy ma poprawny format,
- czy istnieje w tabeli użytkowników,
- czy użytkownik nie jest już członkiem grupy.

Walidacja:

```php
$request->validate(['email' => 'required|email|exists:users,email']);
```

### 12.3. Dodawanie wydatków

Wydatki dodawane są w kontekście grupy. Użytkownik wybiera płatnika z członków grupy.

### 12.4. Dodawanie pozycji z paragonu

Pozycje z paragonu są zależne od rachunku. Można je przypisać do jednego lub wielu użytkowników.

Walidacja:

```php
$request->validate([
    'name' => 'required|string|max:255',
    'price' => 'required|numeric|min:0.01',
    'quantity' => 'required|integer|min:1',
    'user_ids' => 'required|array|min:1',
    'user_ids.*' => 'exists:users,id',
]);
```

## 13. Dodatkowa logika biznesowa

### 13.1. Automatyczne dzielenie kosztów

Po dodaniu rachunku aplikacja automatycznie tworzy podział kosztów między wszystkich członków grupy.

Algorytm:

1. Pobierani są członkowie grupy.
2. Kwota rachunku jest dzielona przez liczbę członków.
3. Dla każdego członka tworzony jest rekord `BillSplit`.
4. Osoba, która zapłaciła rachunek, ma `is_paid = true`.

Kod:

```php
$share = round($bill->amount / $members->count(), 2);

foreach ($members as $member) {
    $bill->splits()->create([
        'user_id' => $member->id,
        'amount' => $share,
        'is_paid' => $member->id === $payerId,
    ]);
}
```

### 13.2. Obliczanie salda użytkownika

Dla każdego członka grupy system oblicza:

```text
saldo = suma zapłacona - suma należna
```

Jeżeli saldo jest dodatnie, użytkownik zapłacił więcej niż powinien i inni powinni mu oddać pieniądze. Jeżeli saldo jest ujemne, użytkownik powinien dopłacić.

### 13.3. Funkcja SQL dla MySQL

Na MySQL aplikacja tworzy funkcję:

```sql
get_user_net_balance(p_user_id, p_group_id)
```

Funkcja zwraca saldo użytkownika w danej grupie.

### 13.4. Triggery MySQL

Projekt zawiera triggery:

| Trigger | Cel |
| --- | --- |
| `update_group_total_after_bill_insert` | zwiększa sumę wydatków grupy po dodaniu rachunku |
| `update_group_total_after_bill_update` | aktualizuje sumę wydatków po zmianie rachunku |
| `update_group_total_after_bill_delete` | zmniejsza sumę wydatków po usunięciu rachunku |
| `validate_user_in_group_before_item_assign` | blokuje przypisanie pozycji paragonu osobie spoza grupy |

Na SQLite triggery MySQL nie są wykonywane, dlatego aplikacja aktualizuje sumę grupy w kodzie PHP.

### 13.5. Walidacja użytkownika przypisanego do pozycji

Na poziomie MySQL istnieje trigger walidujący, czy użytkownik przypisywany do pozycji paragonu należy do grupy rachunku. To zabezpiecza dane przed niespójnymi powiązaniami.

## 14. Funkcjonalności dla użytkowników końcowych wykraczające poza prosty CRUD

Projekt zawiera funkcjonalności wykraczające poza proste dodawanie, edycję i usuwanie rekordów:

1. **Automatyczny podział kosztów**  
   Użytkownik dodaje tylko kwotę i płatnika, a system sam tworzy podział między członków grupy.

2. **Panel rozliczeń**  
   Aplikacja pokazuje, kto ile zapłacił, ile powinien zapłacić i jakie ma saldo.

3. **Pozycje z paragonu przypisywane do osób**  
   Użytkownik może doprecyzować, które osoby korzystały z konkretnych pozycji.

4. **Role i panel administratora**  
   Administrator może zarządzać użytkownikami i ich rolami.

5. **Tryb jasny i ciemny**  
   Użytkownik może przełączyć motyw interfejsu, a wybór jest zapisywany w przeglądarce.

6. **Ochrona dostępu do grup**  
   Zwykły użytkownik nie może wejść do grupy, której nie jest członkiem.

## 15. Walidacja danych

Projekt korzysta z walidacji Laravel po stronie serwera.

Po stronie klienta formularze wykorzystują podstawowe mechanizmy HTML5:

- atrybut `required` dla pól obowiązkowych,
- `type="email"` dla adresów email,
- `type="number"` dla kwot i ilości,
- `step="0.01"` dla kwot pieniężnych,
- `min="1"` dla ilości,
- listy `select` dla wyboru użytkownika powiązanego z rachunkiem,
- checkboxy dla przypisywania pozycji paragonu do użytkowników,
- okna `confirm()` przy operacjach usuwania.

Walidacja klienta poprawia wygodę korzystania z aplikacji, ale nie zastępuje walidacji serwerowej. Najważniejsze reguły są zawsze sprawdzane w kontrolerach Laravel.

Przykłady:

### Rejestracja

- wymagane imię,
- wymagany poprawny email,
- email musi być unikalny,
- wymagane hasło,
- potwierdzenie hasła.

### Grupa

- nazwa jest wymagana,
- nazwa może mieć maksymalnie 255 znaków.

### Rachunek

- opis jest wymagany,
- kwota jest wymagana,
- kwota musi być większa od zera,
- płatnik musi istnieć,
- płatnik musi należeć do grupy.

### Pozycja z paragonu

- nazwa jest wymagana,
- cena musi być większa od zera,
- ilość musi być liczbą całkowitą większą lub równą 1,
- musi zostać wybrany co najmniej jeden użytkownik.

### Profil użytkownika

- nazwa jest wymagana,
- email jest wymagany,
- email musi być unikalny,
- przy zmianie emaila status weryfikacji zostaje wyczyszczony.

## 16. Bezpieczeństwo

Projekt stosuje następujące mechanizmy bezpieczeństwa:

- middleware `auth` dla zasobów wymagających logowania,
- middleware `verified` dla głównej części aplikacji,
- middleware `admin` dla panelu administratora,
- walidacja danych wejściowych po stronie serwera,
- haszowanie haseł użytkowników,
- ochrona CSRF w formularzach Blade,
- kontrola dostępu do grup,
- blokada usuwania własnego konta przez administratora z panelu admina.

## 17. Struktura najważniejszych tras

| Metoda | Ścieżka | Nazwa | Opis |
| --- | --- | --- | --- |
| GET | `/` | - | strona powitalna |
| GET | `/dashboard` | `dashboard` | panel główny |
| GET | `/groups` | `groups.index` | lista grup |
| POST | `/groups` | `groups.store` | tworzenie grupy |
| GET | `/groups/{group}` | `groups.show` | szczegóły grupy |
| GET | `/groups/{group}/edit` | `groups.edit` | edycja grupy |
| PATCH/PUT | `/groups/{group}` | `groups.update` | zapis edycji grupy |
| DELETE | `/groups/{group}` | `groups.destroy` | usunięcie grupy |
| POST | `/groups/{group}/add-user` | `groups.add-user` | dodanie członka grupy |
| POST | `/groups/{group}/bills` | `bills.store` | dodanie rachunku |
| DELETE | `/groups/{group}/bills/{bill}` | `bills.destroy` | usunięcie rachunku |
| POST | `/groups/{group}/bills/{bill}/items` | `bill-items.store` | dodanie pozycji paragonu |
| GET | `/admin/users` | `admin.users.index` | panel administratora |
| PATCH | `/admin/users/{user}` | `admin.users.update` | zmiana roli użytkownika |
| DELETE | `/admin/users/{user}` | `admin.users.destroy` | usunięcie użytkownika |
| GET | `/profile` | `profile.edit` | edycja profilu |
| PATCH | `/profile` | `profile.update` | zapis profilu |
| DELETE | `/profile` | `profile.destroy` | usunięcie konta |

## 18. Podsumowanie wymagań na ocenę

### 18.1. Zakres na ocenę 3.0

Dokumentacja zawiera:

- instrukcję uruchomienia projektu,
- podręcznik użytkownika,
- opis CRUD grup i rachunków zależnych od grup,
- opis walidacji formularzy,
- opis dostępu użytkownika niezalogowanego.

### 18.2. Zakres na ocenę 4.0

Dokumentacja zawiera:

- role użytkowników,
- uprawnienia użytkowników,
- zarządzanie zasobami przez użytkowników,
- zarządzanie profilami użytkowników przez administratora.

### 18.3. Zakres na ocenę 5.0

Dokumentacja zawiera:

- nietrywialną logikę biznesową,
- automatyczne dzielenie kosztów,
- obliczanie sald,
- triggery i funkcję SQL dla MySQL,
- przypisywanie pozycji paragonu do użytkowników,
- funkcje dla użytkownika końcowego wykraczające poza prosty CRUD.
