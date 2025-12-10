<x-mail::message>
  <div class="text-base">
    Hallo {{ $name }}
  </div>
  <br>
  <div class="text-base">
    Ein Zugang wurde für Sie bei Formaform AG erstellt:
  </div>
  <br>
  <div class="text-base">
    <strong>E-Mail</strong><br>
    {{ $email }}
  </div>
  <br>
  <div class="text-base">
    <strong>Passwort</strong><br>
    {{ $password }}
  </div>
  <br>
  <div class="text-base">
    Bei Fragen stehen wir Ihnen gerne zur Verfügung.
  </div>
  <br>
  <div class="text-base">
    Freundliche Grüsse<br><br>
    Formaform AG<br>
    +41 55 440 12 65<br>
    info@formaform.swiss
  </div>
</x-mail::message>
