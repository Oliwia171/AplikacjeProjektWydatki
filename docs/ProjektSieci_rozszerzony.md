# Logika przydzielania adresów IP - Dynamiczne DHCP vs Rezerwacja DHCP

**Autor:** Oliwia Kwasek  
**Kierunek:** Informatyka i ekonometria  
**Numer albumu:** 135129  
**Temat:** Logika przydzielania adresów IP - porównanie dynamicznego DHCP i rezerwacji DHCP  

## Strona tytułowa

Niniejszy projekt przedstawia sposób działania protokołu DHCP w małej sieci lokalnej oraz wyjaśnia, dlaczego różne typy urządzeń powinny otrzymywać adresy IP w różny sposób. W pracy porównano dwa podejścia: klasyczne dynamiczne przydzielanie adresu IP dla stacji roboczej oraz stałe przypisanie adresu dla urządzenia usługowego, takiego jak drukarka sieciowa.

Projekt został przygotowany na podstawie konfiguracji wykonanej w środowisku Cisco Packet Tracer. W symulacji wykorzystano router pełniący funkcję serwera DHCP, przełącznik, komputer pracownika oraz drukarkę sieciową.

<!-- PAGE_BREAK -->

# Spis treści

1. Cel i zakres zadania .................................................. 3  
2. Podstawy teoretyczne DHCP ............................................. 4  
3. Analiza logiczna i biznesowa .......................................... 5  
4. Środowisko testowe i topologia ........................................ 6  
5. Plan adresacji i konfiguracja routera ................................. 7  
6. Szczegółowa analiza procesu DORA ...................................... 8  
7. Scenariusze testowe i wyniki weryfikacji .............................. 9  
8. Bezpieczeństwo, eksploatacja i dobre praktyki ......................... 10  
9. Wnioski końcowe ....................................................... 11  
10. Bibliografia ......................................................... 11  

## Wykaz skrótów

- **DHCP** - Dynamic Host Configuration Protocol.
- **IP** - Internet Protocol.
- **MAC** - Media Access Control, fizyczny adres karty sieciowej.
- **DORA** - Discover, Offer, Request, Acknowledgment.
- **LAN** - Local Area Network, lokalna sieć komputerowa.

<!-- PAGE_BREAK -->

# 1. Cel i zakres zadania

Celem projektu jest demonstracja oraz analiza różnic w sposobie przydzielania adresów sieciowych przez serwer DHCP dla urządzeń końcowych oraz urządzeń usługowych. W sieci firmowej komputer pracownika i drukarka mogą wyglądać podobnie od strony podstawowej konfiguracji, ponieważ oba urządzenia mogą korzystać z opcji „Uzyskaj adres IP automatycznie”. Różnica pojawia się jednak po stronie administratora sieci i sposobu obsługi tych urządzeń przez serwer DHCP.

Zakres pracy obejmuje:

- opis działania dynamicznego przydzielania adresów IP,
- opis rezerwacji DHCP dla urządzeń wymagających stałego adresu,
- porównanie rezerwacji DHCP z ręcznym ustawieniem adresu statycznego,
- przygotowanie topologii w Cisco Packet Tracer,
- przedstawienie przykładowego planu adresacji,
- analizę procesu DORA,
- wykonanie testów dla komputera PC i drukarki,
- sformułowanie wniosków oraz dobrych praktyk administracyjnych.

W projekcie przyjęto, że komputer pracownika jest typowym klientem sieci, który nie musi być dostępny pod tym samym adresem przez cały czas. Drukarka natomiast jest zasobem współdzielonym, dlatego jej adres IP powinien być przewidywalny i niezmienny z punktu widzenia pozostałych użytkowników sieci.

<!-- PAGE_BREAK -->

# 2. Podstawy teoretyczne DHCP

DHCP jest protokołem umożliwiającym automatyczne przekazywanie urządzeniom parametrów sieciowych. Najważniejszym parametrem jest adres IP, ale serwer DHCP może przekazać także maskę podsieci, bramę domyślną, adresy serwerów DNS, czas dzierżawy oraz dodatkowe opcje zależne od organizacji sieci.

W dynamicznym DHCP adres jest pobierany z puli dostępnych adresów. Serwer sprawdza, które adresy są wolne, a następnie wydaje klientowi jeden z nich na określony czas. Po zakończeniu dzierżawy urządzenie może odnowić adres lub otrzymać inny. Takie rozwiązanie jest wygodne dla komputerów pracowników, laptopów, telefonów i innych urządzeń, które nie świadczą usług dla reszty sieci.

Rezerwacja DHCP działa inaczej. Administrator przypisuje konkretny adres MAC urządzenia do konkretnego adresu IP. Gdy urządzenie zgłasza się do serwera DHCP, serwer rozpoznaje jego adres MAC i zwraca zawsze ten sam adres IP. Dzięki temu urządzenie nadal korzysta z centralnej konfiguracji DHCP, ale zachowuje stały adres sieciowy.

Rezerwacja DHCP jest często lepsza niż ręczna konfiguracja statyczna. Przy ręcznym wpisaniu adresu IP każde urządzenie trzeba zmieniać osobno. Przy rezerwacji administrator modyfikuje parametry w jednym miejscu, na serwerze DHCP. Jeśli zmieni się brama domyślna albo adres DNS, urządzenie po odnowieniu dzierżawy pobierze aktualne ustawienia bez ręcznej ingerencji.

<!-- PAGE_BREAK -->

# 3. Analiza logiczna i biznesowa

Z punktu widzenia użytkownika końcowego konfiguracja może wyglądać bardzo podobnie: urządzenie jest podłączone do sieci i automatycznie otrzymuje adres. Z punktu widzenia organizacji sieci są to jednak dwa różne przypadki użycia.

Komputer pracownika jest konsumentem usług sieciowych. Łączy się z Internetem, systemami firmowymi, drukarkami lub udziałami plików, ale inne urządzenia zazwyczaj nie muszą znać jego adresu IP. Dlatego przydzielanie adresu z puli dynamicznej jest wystarczające i wygodne. Ułatwia to obsługę większej liczby urządzeń oraz ogranicza ryzyko ręcznych pomyłek w konfiguracji.

Drukarka sieciowa jest zasobem usługowym. Inne komputery muszą wiedzieć, gdzie wysłać zadanie drukowania. Jeżeli adres drukarki zmieniałby się po restarcie lub po wygaśnięciu dzierżawy, użytkownicy mogliby tracić dostęp do usługi. Stały adres IP ogranicza ten problem i upraszcza konfigurację stanowisk.

W praktyce biznesowej rezerwacja DHCP zwiększa przewidywalność działania infrastruktury. Administrator może utrzymywać centralną listę urządzeń kluczowych, takich jak drukarki, serwery plików, punkty dostępowe Wi-Fi lub kamery IP. Jednocześnie zachowuje możliwość zarządzania maską, bramą i DNS z jednego miejsca.

<!-- PAGE_BREAK -->

# 4. Środowisko testowe i topologia

Projekt zrealizowano w środowisku symulacyjnym Cisco Packet Tracer. Zbudowano odizolowaną sieć lokalną, w której router pełni funkcję bramy domyślnej oraz serwera DHCP.

## Elementy topologii

- **Router Cisco** - urządzenie brzegowe sieci LAN, adres bramy: `192.168.1.1`.
- **Switch 2960** - przełącznik warstwy drugiej łączący urządzenia końcowe.
- **PC-Pracownik** - komputer testowy korzystający z puli dynamicznej.
- **Drukarka sieciowa** - urządzenie usługowe wymagające stałego adresu IP.

## Założenia adresacji

| Element | Rola | Adres IP |
| --- | --- | --- |
| Router | Brama i serwer DHCP | 192.168.1.1 |
| PC-Pracownik | Klient dynamiczny | 192.168.1.2 z puli DHCP |
| Drukarka | Urządzenie usługowe | 192.168.1.25 |
| Sieć LAN | Podsieć lokalna | 192.168.1.0/24 |

Tak dobrana topologia pozwala jasno pokazać różnicę między komputerem, który może otrzymać dowolny wolny adres z puli, a urządzeniem usługowym, które powinno być dostępne pod przewidywalnym adresem.

<!-- PAGE_BREAK -->

# 5. Plan adresacji i konfiguracja routera

Na routerze pełniącym funkcję bramy sieciowej wdrożono logiczny podział adresów. Adres `192.168.1.1` przypisano interfejsowi routera, natomiast zakres dynamiczny przeznaczono dla komputerów pracowników. Adres `192.168.1.25` przeznaczono dla drukarki.

Przykładowa konfiguracja routera:

```text
enable
configure terminal

interface gigabitEthernet0/0
 ip address 192.168.1.1 255.255.255.0
 no shutdown
exit

ip dhcp excluded-address 192.168.1.1
ip dhcp excluded-address 192.168.1.25

ip dhcp pool PULA-DYNAMICZNA
 network 192.168.1.0 255.255.255.0
 default-router 192.168.1.1
 dns-server 8.8.8.8
exit
```

W prawdziwym systemie Cisco IOS rezerwację można oprzeć o adres MAC urządzenia. W Packet Tracer obsługa tej funkcji w trybie CLI bywa ograniczona, dlatego w symulacji drukarka została skonfigurowana tak, aby zachować docelowy efekt: komputer korzysta z puli dynamicznej, a drukarka posiada stały adres.

Przykładowy docelowy model rezerwacji:

```text
Adres MAC drukarki: 000D.BD44.E69E
Adres IP drukarki: 192.168.1.25
Maska: 255.255.255.0
Brama: 192.168.1.1
```

<!-- PAGE_BREAK -->

# 6. Szczegółowa analiza procesu DORA

Aby urządzenie mogło otrzymać adres IP z DHCP, wykonuje czteroetapową wymianę komunikatów nazywaną procesem DORA.

1. **DHCP Discover** - klient, który nie zna jeszcze swojej konfiguracji sieciowej, wysyła komunikat rozgłoszeniowy. W praktyce pyta wszystkie urządzenia w sieci, czy znajduje się w niej serwer DHCP mogący przydzielić adres.
2. **DHCP Offer** - serwer DHCP odpowiada propozycją. Jeżeli urządzenie jest zwykłym klientem, serwer wybiera wolny adres z puli. Jeżeli rozpozna adres MAC objęty rezerwacją, przygotowuje przypisany wcześniej adres.
3. **DHCP Request** - klient potwierdza, że chce skorzystać z otrzymanej oferty. Ten etap jest istotny zwłaszcza wtedy, gdy w sieci istnieje więcej niż jeden serwer DHCP.
4. **DHCP Acknowledgment** - serwer wysyła potwierdzenie i finalny zestaw parametrów, czyli adres IP, maskę, bramę domyślną, DNS oraz czas dzierżawy.

W przypadku komputera PC proces kończy się przydzieleniem pierwszego wolnego adresu z puli. W przypadku drukarki proces powinien kończyć się otrzymaniem stałego adresu wynikającego z rezerwacji. Różnica nie polega więc na zachowaniu użytkownika, ale na regułach skonfigurowanych po stronie serwera DHCP.

<!-- PAGE_BREAK -->

# 7. Scenariusze testowe i wyniki weryfikacji

## Test 1: komputer pracownika

Komputer PC ustawiono w trybie automatycznego pobierania adresu IP. Po odświeżeniu konfiguracji karta sieciowa wysłała zapytanie DHCP i otrzymała adres `192.168.1.2`. Wynik potwierdza poprawne działanie puli dynamicznej.

Oczekiwany rezultat:

- adres IP pochodzi z podsieci `192.168.1.0/24`,
- brama domyślna ma wartość `192.168.1.1`,
- komputer może komunikować się z routerem,
- adres może zmienić się w przyszłości, jeżeli dzierżawa wygaśnie lub pula zostanie przebudowana.

## Test 2: drukarka sieciowa

Drukarka została przypisana do adresu `192.168.1.25`. Dzięki temu komputery w sieci mogą kierować zadania drukowania na jeden stały adres. Weryfikacja polegała na sprawdzeniu konfiguracji IP drukarki oraz możliwości komunikacji z pozostałymi urządzeniami w podsieci.

Oczekiwany rezultat:

- drukarka ma stały adres IP,
- adres drukarki nie koliduje z pulą dynamiczną,
- komputer może korzystać z drukarki bez zmiany konfiguracji po restarcie urządzeń,
- administrator zachowuje kontrolę nad planem adresacji.

<!-- PAGE_BREAK -->

# 8. Bezpieczeństwo, eksploatacja i dobre praktyki

Poprawna konfiguracja DHCP wpływa nie tylko na wygodę użytkowników, ale także na bezpieczeństwo i utrzymanie sieci. Jeżeli pula dynamiczna jest źle zaplanowana, może dojść do konfliktu adresów. Konflikt może pojawić się na przykład wtedy, gdy administrator ręcznie ustawi drukarce adres znajdujący się w zakresie wydawanym automatycznie komputerom.

Dobrą praktyką jest wykluczanie z puli DHCP adresów używanych przez router, drukarki, serwery i inne urządzenia infrastrukturalne. W projekcie adres `192.168.1.25` został potraktowany jako adres przeznaczony dla drukarki i nie powinien być losowo przydzielany komputerom.

W większych sieciach warto prowadzić dokumentację adresacji. Powinna ona zawierać nazwę urządzenia, adres IP, adres MAC, lokalizację fizyczną i osobę odpowiedzialną. Ułatwia to diagnozowanie awarii oraz ogranicza ryzyko niekontrolowanych zmian.

W środowisku produkcyjnym można dodatkowo stosować zabezpieczenia takie jak DHCP Snooping na przełącznikach. Mechanizm ten pomaga blokować nieautoryzowane serwery DHCP, które mogłyby rozdawać błędne adresy, fałszywe bramy lub nieprawidłowe serwery DNS.

<!-- PAGE_BREAK -->

# 9. Wnioski końcowe

System został zaprojektowany poprawnie. Analiza potwierdziła, że dynamiczne DHCP jest odpowiednie dla standardowych stacji roboczych, ponieważ zapewnia automatyzację i upraszcza administrację. Komputery pracowników nie muszą mieć stałego adresu IP, dlatego przydział z puli dynamicznej jest rozwiązaniem praktycznym i skalowalnym.

Urządzenia usługowe, takie jak drukarki, powinny mieć stały i przewidywalny adres. Najlepszym rozwiązaniem organizacyjnym jest rezerwacja DHCP, ponieważ łączy zalety adresu stałego z centralnym zarządzaniem konfiguracją. W symulacji Packet Tracer uzyskano ten sam efekt logiczny przez ustawienie drukarki pod adresem `192.168.1.25`.

Najważniejszy wniosek z projektu jest następujący: sposób przydzielania adresów IP powinien zależeć od roli urządzenia w sieci. Klienci końcowi mogą korzystać z adresów dynamicznych, natomiast zasoby współdzielone powinny mieć adresy stałe lub zarezerwowane.

# 10. Bibliografia

1. Cisco Systems, *IP Addressing: DHCP Configuration Guide*, dokumentacja Cisco IOS.
2. Cisco Networking Academy, *Introduction to Networks*, materiały szkoleniowe dotyczące adresacji IP i usług DHCP.
3. J. F. Kurose, K. W. Ross, *Computer Networking: A Top-Down Approach*, Pearson.
4. Microsoft Learn, *Dynamic Host Configuration Protocol (DHCP) overview*.
5. Materiały własne z konfiguracji wykonanej w środowisku Cisco Packet Tracer.
