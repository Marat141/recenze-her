<?php
try {
    $db = new PDO('mysql:host=localhost;port=8889;charset=utf8', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE DATABASE IF NOT EXISTS recenze_her CHARACTER SET utf8 COLLATE utf8_unicode_ci");
    $db->exec("USE recenze_her");
    $db->exec("CREATE TABLE IF NOT EXISTS recenze (
        id INT AUTO_INCREMENT PRIMARY KEY,
        jmeno VARCHAR(100) NOT NULL,
        prijmeni VARCHAR(100) NOT NULL,
        hra VARCHAR(200) NOT NULL,
        email VARCHAR(200) NOT NULL,
        cislo VARCHAR(50),
        zprava TEXT,
        hvezdicky TINYINT NOT NULL,
        datum DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die('Chyba připojení k databázi: ' . $e->getMessage());
}

$chyba = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno     = trim($_POST['first-name'] ?? '');
    $prijmeni  = trim($_POST['last-name'] ?? '');
    $hra       = trim($_POST['company'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $cislo     = trim($_POST['phone-number'] ?? '');
    $zprava    = trim($_POST['message'] ?? '');
    $hvezdicky = (int)($_POST['hs-ratings-readonly'] ?? 0);

    if ($jmeno && $prijmeni && $hra && $email && $hvezdicky >= 1) {
        $stmt = $db->prepare("INSERT INTO recenze (jmeno, prijmeni, hra, email, cislo, zprava, hvezdicky) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$jmeno, $prijmeni, $hra, $email, $cislo, $zprava, $hvezdicky]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $chyba = 'Vyplňte prosím všechna povinná pole a vyberte hodnocení.';
    }
}

$recenze = $db->query("SELECT * FROM recenze ORDER BY datum DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-950 min-h-screen">
    <div class="bg-gray-950 border-b border-gray-800 flex justify-end gap-6 py-2 px-8">
        <a class="text-sm font-bold text-gray-400 hover:text-white transition-colors" href="">Počítače</a>
        <a class="text-sm font-bold text-gray-400 hover:text-white transition-colors" href="">Mobily</a>
        <a class="text-sm font-bold text-gray-400 hover:text-white transition-colors" href="">Hry na PC</a>
        <a class="text-sm font-bold text-gray-400 hover:text-white transition-colors" href="">Hry na PS</a>
    </div>
    <header class="bg-gray-900 border-b border-gray-800 px-4 py-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <img class="w-12 h-12 rounded-full" src="/obrázky/dark-ninja-mascot-logo-for-team-esport-gaming-vector.jpg" alt="Logo">
                <h1 class="font-bold text-2xl text-white leading-tight">Recenze na hry</h1>
            </div>
            <div class="flex flex-col items-end gap-4">
                <div class="flex items-center bg-gray-800 border border-gray-700 rounded-full overflow-hidden w-72">
                    <input class="w-full px-4 py-2 bg-transparent outline-none text-sm text-gray-200 placeholder-gray-500" type="text" placeholder="Hledat hru...">
                    <button class="bg-violet-600 px-4 py-2 text-white text-sm font-semibold hover:bg-violet-500 transition-colors">Hledat</button>
                </div>
                <nav class="flex items-center gap-4 text-sm font-semibold text-gray-300">
                    <a class="hover:text-violet-400 transition-colors" href="">Recenze</a>
                    <a class="hover:text-violet-400 transition-colors" href="">Nejlepší hry</a>
                    <a class="hover:text-violet-400 transition-colors" href="">Hry na Xbox</a>
                    <a class="hover:text-violet-400 transition-colors" href="">Hry na PS</a>
                </nav>
            </div>
        </div>
    </header>
    <main class="bg-gray-950 px-4 py-16">
        <div class="max-w-7xl mx-auto flex flex-col items-center gap-12">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-violet-400 tracking-tight mb-4">Napište recenzi</h1>
                <p class="text-gray-400 text-lg font-medium">Sdílejte svůj názor na hru s ostatními hráči</p>
            </div>
    <section class="flex items-center">
        <div class="">
            <div class="isolate bg-gray-900 px-6 py-24 sm:py-32 lg:px-8">
                <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-1/2 -z-10 aspect-1155/678 w-144.5 max-w-none -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-40rem)] sm:w-288.75"></div>
            </div>
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-4xl font-semibold tracking-tight text-balance text-white sm:text-5xl">Recenze</h2>
            <p class="mt-2 text-lg/8 text-gray-400">Vyplňte prosím všechny pole.</p>
        </div>
            <form id="muj-formular" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
        <?php if ($chyba): ?>
          <p class="text-red-400 text-sm mb-4"><?= htmlspecialchars($chyba) ?></p>
        <?php endif; ?>
        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
        <div>
            <label for="first-name" class="block text-sm/6 font-semibold text-white">Jméno</label>
        <div class="mt-2.5">
            <input id="first-name" type="text" name="first-name" autocomplete="given-name" class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500" />
        </div>
        </div>
        <div>
            <label for="last-name" class="block text-sm/6 font-semibold text-white">Příjmení</label>
        <div class="mt-2.5">
          <input id="last-name" type="text" name="last-name" autocomplete="family-name" class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500" />
        </div>
        </div>
            <div class="sm:col-span-2">
            <label for="company" class="block text-sm/6 font-semibold text-white">Napište hru</label>
        <div class="mt-2.5">
          <input id="company" type="text" name="company" autocomplete="organization" class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500" />
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="email" class="block text-sm/6 font-semibold text-white">E-mail</label>
        <div class="mt-2.5">
          <input id="email" type="email" name="email" autocomplete="email" class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500" />
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="phone-number" class="block text-sm/6 font-semibold text-white">telefoní číslo</label>
        <div class="mt-2.5">
          <div class="flex rounded-md bg-white/5 outline-1 -outline-offset-1 outline-white/10 has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-indigo-500">
            <div class="grid shrink-0 grid-cols-1 focus-within:relative">
              <select id="country" name="country" autocomplete="country" aria-label="Country" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-transparent py-2 pr-7 pl-3.5 text-base text-gray-400 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                <option>US</option>
                <option>CA</option>
                <option>EU</option>
                <option>CS</option>
              </select>
              <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-400 sm:size-4">
                <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
              </svg>
            </div>
            <input id="phone-number" type="text" name="phone-number" placeholder="123-456-7890" class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
          </div>
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="message" class="block text-sm/6 font-semibold text-white">Chcete něco dodat?</label>
        <div class="mt-2.5">
          <textarea id="message" name="message" rows="4" class="block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500"></textarea>
        </div>
      </div>
      <!-- Rating — DOM pořadí 5→1 + flex-row-reverse = vizuální zobrazení 1→5 zleva doprava -->
        <div class="sm:col-span-2">
          <label class="block text-sm/6 font-semibold text-white mb-2">Hodnocení</label>
          <div class="flex flex-row-reverse justify-end items-center gap-1">
          <input id="hs-ratings-readonly-5" type="radio" class="peer -ms-5 size-6 bg-transparent border-0 text-transparent cursor-pointer appearance-none checked:bg-none focus:bg-none focus:ring-0 focus:ring-offset-0" name="hs-ratings-readonly" value="5">
          <label for="hs-ratings-readonly-5" class="peer-checked:text-yellow-400 text-gray-500 cursor-pointer">
            <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </label>
          <input id="hs-ratings-readonly-4" type="radio" class="peer -ms-5 size-6 bg-transparent border-0 text-transparent cursor-pointer appearance-none checked:bg-none focus:bg-none focus:ring-0 focus:ring-offset-0" name="hs-ratings-readonly" value="4">
          <label for="hs-ratings-readonly-4" class="peer-checked:text-yellow-400 text-gray-500 cursor-pointer">
            <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </label>
          <input id="hs-ratings-readonly-3" type="radio" class="peer -ms-5 size-6 bg-transparent border-0 text-transparent cursor-pointer appearance-none checked:bg-none focus:bg-none focus:ring-0 focus:ring-offset-0" name="hs-ratings-readonly" value="3">
          <label for="hs-ratings-readonly-3" class="peer-checked:text-yellow-400 text-gray-500 cursor-pointer">
            <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </label>
          <input id="hs-ratings-readonly-2" type="radio" class="peer -ms-5 size-6 bg-transparent border-0 text-transparent cursor-pointer appearance-none checked:bg-none focus:bg-none focus:ring-0 focus:ring-offset-0" name="hs-ratings-readonly" value="2">
          <label for="hs-ratings-readonly-2" class="peer-checked:text-yellow-400 text-gray-500 cursor-pointer">
            <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </label>
          <input id="hs-ratings-readonly-1" type="radio" class="peer -ms-5 size-6 bg-transparent border-0 text-transparent cursor-pointer appearance-none checked:bg-none focus:bg-none focus:ring-0 focus:ring-offset-0" name="hs-ratings-readonly" value="1">
          <label for="hs-ratings-readonly-1" class="peer-checked:text-yellow-400 text-gray-500 cursor-pointer">
            <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
          </label>
        </div>
        </div>
<!-- End Rating -->
      <div class="flex gap-x-4 sm:col-span-2">
        <div class="flex h-6 items-center">
          <div class="group relative inline-flex w-8 shrink-0 rounded-full bg-white/5 p-px inset-ring inset-ring-white/10 outline-offset-2 outline-indigo-500 transition-colors duration-200 ease-in-out has-checked:bg-indigo-500 has-focus-visible:outline-2">
            <span class="size-4 rounded-full bg-white shadow-xs ring-1 ring-gray-900/5 transition-transform duration-200 ease-in-out group-has-checked:translate-x-3.5"></span>
            <input id="agree-to-policies" type="checkbox" name="agree-to-policies" aria-label="Agree to policies" class="absolute inset-0 size-full appearance-none focus:outline-hidden" />
          </div>
        </div>
        <label for="agree-to-policies" class="text-sm/6 text-gray-400">
          Tím že tohle odškrtnete tak souhlasíte s našemi
          <a href="#" class="font-semibold whitespace-nowrap text-indigo-400">Právními sluvami</a>.
        </label>
      </div>
    </div>
    <div class="mt-10">
      <button type="submit" class="block w-full rounded-md bg-violet-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-violet-500 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-500">Odeslat recenzi</button>
    </div>
  </form>
</div>
      <section class="w-full max-w-3xl mx-auto px-4 py-12">
              <h2 class="text-3xl font-extrabold text-white mb-6">Recenze hráčů</h2>

              <div id="zpravy-kontejner" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  <?php foreach ($recenze as $r): ?>
                  <div class="bg-gray-800 rounded-xl p-4">
                    <div class="flex justify-between items-center mb-1">
                      <span class="font-semibold text-white"><?= htmlspecialchars($r['jmeno'] . ' ' . $r['prijmeni']) ?></span>
                      <span class="text-yellow-400"><?= str_repeat('★', $r['hvezdicky']) . str_repeat('☆', 5 - $r['hvezdicky']) ?></span>
                    </div>
                    <p class="text-indigo-300 text-sm mb-1">🎮 <?= htmlspecialchars($r['hra']) ?></p>
                    <?php if ($r['zprava']): ?>
                      <p class="text-gray-500 text-xs mt-2">Chcete ještě něco dodat?</p>
                      <p class="text-gray-300 text-sm"><?= htmlspecialchars($r['zprava']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-500 text-xs mt-2"><?= $r['datum'] ?></p>
                  </div>
                  <?php endforeach; ?>
                  <?php if (empty($recenze)): ?>
                    <p class="text-gray-500">Zatím žádné recenze. Buď první!</p>
                  <?php endif; ?>
              </div>
          </section>
    </section>
        </div>
    </main>
    <footer class="bg-gray-900 border-t border-gray-800">
        <div class="mx-auto w-full max-w-7xl">
          <div class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-4">
            <div>
                <h2 class="mb-6 text-sm font-semibold text-heading uppercase text-gray-400">Company</h2>
                <ul class="text-body font-medium text-gray-400">
                    <li class="mb-4">
                        <a href="#" class=" hover:underline">About</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Careers</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Brand Center</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Blog</a>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="mb-6 text-sm font-semibold text-heading uppercase text-gray-400">Help center</h2>
                <ul class="text-body font-medium text-gray-400">
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Discord Server</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Twitter</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Facebook</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="mb-6 text-sm font-semibold text-heading uppercase text-gray-400">Legal</h2>
                <ul class="text-body font-medium text-gray-400">
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Privacy Policy</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Licensing</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="mb-6 text-sm font-semibold text-heading uppercase text-gray-400">Download</h2>
                <ul class="text-body font-medium text-gray-400">
                    <li class="mb-4">
                        <a href="#" class="hover:underline">iOS</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Android</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">Windows</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="hover:underline">MacOS</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="px-4 py-6 bg-gray-800 border-t border-gray-700 md:flex md:items-center md:justify-between text-gray-400">
            <span class="text-sm text-body sm:text-center">© 2023 <a href="https://flowbite.com/">Flowbite™</a>. All Rights Reserved.
            </span>
            <div class="flex mt-4 sm:justify-center md:mt-0 space-x-2 rtl:space-x-reverse">
              <a href="#" class="text-body hover:text-heading">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/></svg>
                    <span class="sr-only">Facebook page</span>
                </a>
                <a href="#" class="text-body hover:text-heading ms-5">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M18.942 5.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.586 11.586 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3 17.392 17.392 0 0 0-2.868 11.662 15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.638 10.638 0 0 1-1.706-.83c.143-.106.283-.217.418-.331a11.664 11.664 0 0 0 10.118 0c.137.114.277.225.418.331-.544.328-1.116.606-1.71.832a12.58 12.58 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM8.678 14.813a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.929 1.929 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z"/></svg>
                    <span class="sr-only">Discord community</span>
                </a>
                <a href="#" class="text-body hover:text-heading ms-5">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/></svg>
                <span class="sr-only">Twitter page</span>
                </a>
                <a href="#" class="text-body hover:text-heading ms-5">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.006 2a9.847 9.847 0 0 0-6.484 2.44 10.32 10.32 0 0 0-3.393 6.17 10.48 10.48 0 0 0 1.317 6.955 10.045 10.045 0 0 0 5.4 4.418c.504.095.683-.223.683-.494 0-.245-.01-1.052-.014-1.908-2.78.62-3.366-1.21-3.366-1.21a2.711 2.711 0 0 0-1.11-1.5c-.907-.637.07-.621.07-.621.317.044.62.163.885.346.266.183.487.426.647.71.135.253.318.476.538.655a2.079 2.079 0 0 0 2.37.196c.045-.52.27-1.006.635-1.37-2.219-.259-4.554-1.138-4.554-5.07a4.022 4.022 0 0 1 1.031-2.75 3.77 3.77 0 0 1 .096-2.713s.839-.275 2.749 1.05a9.26 9.26 0 0 1 5.004 0c1.906-1.325 2.74-1.05 2.74-1.05.37.858.406 1.828.101 2.713a4.017 4.017 0 0 1 1.029 2.75c0 3.939-2.339 4.805-4.564 5.058a2.471 2.471 0 0 1 .679 1.897c0 1.372-.012 2.477-.012 2.814 0 .272.18.592.687.492a10.05 10.05 0 0 0 5.388-4.421 10.473 10.473 0 0 0 1.313-6.948 10.32 10.32 0 0 0-3.39-6.165A9.847 9.847 0 0 0 12.007 2Z" clip-rule="evenodd"/></svg>
                    <span class="sr-only">GitHub account</span>
                </a>
                <a href="#" class="text-body hover:text-heading ms-5">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.093 20.093 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM10 3.707a8.82 8.82 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.755 45.755 0 0 0 10 3.707Zm-6.358 6.555a8.57 8.57 0 0 1 4.73-5.981 53.99 53.99 0 0 1 3.168 4.941 32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.641 31.641 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM12 20.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 15.113 13a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z" clip-rule="evenodd"/></svg>
                    <span class="sr-only">Dribbble account</span>
                </a>
            </div>
          </div>
        </div>
    </footer>
</body>
</html>