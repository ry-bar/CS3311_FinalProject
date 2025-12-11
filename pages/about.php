<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Junkyard Origins — The Copper Crusaders</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="http://localhost/CS3311_FinalProject/">
  <link rel="stylesheet" href="style/styles.css" />
  <link rel="stylesheet" href="style/comic.css" />
  <script src="scripts/open_close_reg.js" defer></script>
  <script src="scripts/comic_lightbox.js" defer></script>
</head>
<body class="comic-body">
  <?php include __DIR__ . "/../partials/navbar.php"; ?>
  <main class="page-wrapper">

    <header class="comic-header">
      <h1>Junkyard Origins</h1>
      <h2>The Copper Crusaders</h2>
      <p>
        Every scrap yard has a story. Ours just happens to involve collapsing
        sheds, sentient washing machines, and one very determined raccoon.
        Here's how two kids with bolt cutters ended up building the place
        you're standing in.
      </p>
    </header>

    <!-- PAGE 1 -->
    <section class="comic-page page-1">
      <div class="comic-page-label">Page 1 — The Neighborhood That Made Them</div>
      <div class="comic-grid">

        <figure class="panel">
          <img src="images/junkyard_origins/page1/page1-panel1.png" alt="Two kids standing on scrap." />
          <figcaption class="panel-note">
            <span>Panel 1</span> The beginning: two kids, one rough neighborhood, and way too much junk.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page1/page1-panel2.png" alt="Angry washing machine." />
          <figcaption class="panel-note">
            <span>Panel 2</span> When even the washing machines look like they want to fight you.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page1/page1-panel3.png" alt="Teens with cutters in lightning." />
          <figcaption class="panel-note">
            <span>Panel 3</span> Some kids collected baseball cards. These two collected copper.
          </figcaption>
        </figure>

      </div>
    </section>

    <!-- PAGE 2 -->
    <section class="comic-page page-2">
      <div class="comic-page-label">Page 2 — The Great Copper Caper</div>
      <div class="comic-grid">

        <figure class="panel">
          <img src="images/junkyard_origins/page2/page2-panel1.png" alt="Crooked swamp shed." />
          <figcaption class="panel-note">
            <span>Panel 1</span> Nothing suspicious about a leaning swamp shed full of “free” metal.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page2/page2-panel2.png" alt="Teens sneaking toward shed with tools." />
          <figcaption class="panel-note">
            <span>Panel 2</span> Tactical mission: mud, mosquitos, and questionable decision making.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page2/page2-panel3.png" alt="Glowing coil of copper inside broken shed." />
          <figcaption class="panel-note">
            <span>Panel 3</span> Treasure? Yes. Structural integrity? Absolutely not.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page2/page2-panel4.png" alt="Kids and raccoon running from collapsing shed." />
          <figcaption class="panel-note">
            <span>Panel 4</span> They got the copper, kept the raccoon, and almost lost themselves.
          </figcaption>
        </figure>

      </div>
    </section>

    <!-- PAGE 3 -->
    <section class="comic-page page-3">
      <div class="comic-page-label">Page 3 — A Scrap Empire Begins</div>
      <div class="comic-grid">

        <figure class="panel">
          <img src="images/junkyard_origins/page3/page3-panel1.png" alt="Teens with mud cart and tools." />
          <figcaption class="panel-note">
            <span>Panel 1</span> If the cart moves and the mud is only knee deep, that’s still a win.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page3/page3-panel2.png" alt="Training montage: dismantling, pulling wire, coil prep." />
          <figcaption class="panel-note">
            <span>Panel 2</span> Years of busted knuckles, stripped bolts, and learning what everything is worth.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page3/page3-panel3.png" alt="Two older boys shaking hands under blueprint sketches." />
          <figcaption class="panel-note">
            <span>Panel 3</span> At some point, “we should open a yard” stopped being a joke.
          </figcaption>
        </figure>

      </div>
    </section>

    <!-- PAGE 4 -->
    <section class="comic-page page-4">
      <div class="comic-page-label">Page 4 — The Scrap Yard Today</div>
      <div class="comic-grid">

        <figure class="panel">
          <img src="images/junkyard_origins/page4/page4-panel1.png" alt="Busy modern scrap yard with cranes and workers." />
          <figcaption class="panel-note">
            <span>Panel 1</span> These days, the chaos is organized and the piles are taller.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page4/page4-panel2.png" alt="Founders standing heroically with capes." />
          <figcaption class="panel-note">
            <span>Panel 2</span> Same attitude, better tools, slightly less mud.
          </figcaption>
        </figure>

        <figure class="panel">
          <img src="images/junkyard_origins/page4/page4-panel3.png" alt="Close-up of founders smiling with raccoon wearing welding mask." />
          <figcaption class="panel-note">
            <span>Panel 3</span> Every visitor is part of the story now.
          </figcaption>
        </figure>

      </div>
    </section>

    <footer class="comic-footer">
      <p>
        <strong>TL;DR:</strong> We started with sketchy sheds and shopping carts.
        Now we run a proper yard built on what got us here: hard work, second
        chances, and a healthy respect for sharp metal.
      </p>
    </footer>

  </main>
  <div class="panel-lightbox" aria-hidden="true">
    <button class="lightbox-close" type="button" aria-label="Close expanded panel">&times;</button>
    <img class="lightbox-image" src="" alt="" />
    <p class="lightbox-caption"></p>
  </div>
  <?php include __DIR__ . "/../partials/footer.php"; ?>
</body>
</html>
