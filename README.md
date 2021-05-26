Beschrijving van de applicatie

Deze applicatie is een laravel sail applicatie die  bestaat uit een frontend en backend.
De frontend is puur bedoeld om op het challenge json bestand te uploaden en te verwerken. 
Deze is te bereiken hier http://0.0.0.0:8084/
Het json bestand wordt pas verwerkt als het volgende command wordt gebruikt:

./vendor/bin/sail artisan queue:work --timeout=600

In de database zijn de volgende tables accounts, creditcards, failed_jobs, jobs en job_statuses.
De database is te bereiken via phpmyadmin via dit url http://0.0.0.0:8085/. Username is root Er is geen Password: 

In de tables accounts en creditcards is alle data te zien van Challenge.json. 
Hiernaast zijn alle taken te zien in jobs en alle gefaalde taken in failed_jobs. In de job_statuses 
tabel is de status en de vooruitgang te zien van de verschillende taken die zijn aangemaakt.


Na de installatie van laravel sail is de app op te starten via dit command:
./vendor/bin/sail up
 
Als het command sail up succesvol is moet  het onderstaand command uitgevoerd worden om de 
database klaar te zetten: 
./vendor/bin/sail artisan migrate:fresh


Als een json file geupload is kan deze worden verwerkt met het volgende command
./vendor/bin/sail artisan queue:work --timeout=600

Mocht een job crashen kan deze opnieuw worden verwerkt via het onderstaande command:
./vendor/bin/sail artisan queue:retry all

en dan opnieuw ./vendor/bin/sail artisan queue:work --timeout=600  aanroepen.

-------------------------------------------------------

