<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>YG Entertainment Online Audition</title>
  <style>
    * {
      box-sizing: border-box;
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #111;
      color: #fff;
      line-height: 1.6;
    }

    header {
      background-color: #000;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
    }

    .logo {
      font-size: 30px;
      font-weight: bold;
      color: #fff;
      letter-spacing: 2px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      text-decoration: none;
      color: #aaa;
      font-size: 16px;
      transition: color 0.3s;
      padding: 5px 0;
      position: relative;
    }

    nav ul li a::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      background-color: #e50914;
      transform: scaleX(0);
      transition: transform 0.3s ease-in-out;
    }

    nav ul li a:hover::after {
      transform: scaleX(1);
    }

    nav ul li a:hover {
      color: #fff;
    }

    .signup a {
      background-color: #e50914;
      color: white !important;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s, transform 0.3s;
    }

    .signup a:hover {
      background-color: #c40610;
      transform: translateY(-2px);
    }

    section {
      padding: 80px 20px;
      max-width: 1200px;
      margin: auto;
    }

    .hero {
      background: url('BP.webp') no-repeat center center/cover;
      padding: 160px 40px;
      text-align: center;
      color: white;
      border-bottom: 4px solid #e50914;
    }

    .hero h1 {
      font-size: 60px;
      margin-bottom: 20px;
      font-weight: 700;
      color: #e50914;
    }

    .hero p {
      font-size: 24px;
      margin-bottom: 35px;
      font-weight: 300;
      color: #fff;
    }

    .cta-button {
      padding: 14px 35px;
      background-color: #e50914;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 18px;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: background 0.3s, transform 0.3s;
    }

    .cta-button:hover {
      background-color: #c40610;
      transform: translateY(-3px);
    }

    h2 {
      font-size: 38px;
      margin-bottom: 25px;
      color: #e50914;
      text-align: left;
      position: relative;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: #e50914;
    }

    p {
      font-size: 18px;
    }

    .about-container {
      display: flex;
      flex-direction: row;
      align-items: center;
      gap: 80px;
      flex-wrap: wrap;
      margin-top: 40px;
    }

    .about-image {
      flex: 1.5;
      text-align: center;
    }

    .about-text {
      flex: 1;
      font-size: 18px;
      line-height: 1.8;
    }

    .about-img {
      width: 100%;
      max-width: 700px;
      height: auto;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
      transition: transform 0.3s ease;
    }

    .about-img:hover {
      transform: scale(1.05);
    }

    .ceo-card {
      background-color: #333;
      padding: 15px 20px;
      border-radius: 16px;
      text-align: center;
      margin-bottom: 50px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    }

    .ceo-card img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #e50914;
      margin-bottom: 20px;
    }

    .ceo-card h3 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #e50914;
    }

    .ceo-card p {
      font-size: 17px;
      color: #ccc;
    }

    .ceo-group-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .ceo-group-card {
      background-color: #222;
      color: white;
      width: 280px;
      border-radius: 12px;
      overflow: hidden;
      margin: 20px auto;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .ceo-group-card:hover {
      transform: scale(1.05);
    }

    .ceo-group-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .ceo-group-card h3 {
      font-size: 20px;
      margin: 15px 0;
      color: #e50914;
    }

    .ceo-group-card p {
      font-size: 16px;
      margin: 10px 15px;
      color: #bbb;
    }

    #about {
      background-color: #222;
      padding: 80px 20px;
      border-radius: 8px;
      box-shadow: 0 6px 30px rgba(0, 0, 0, 0.5);
    }

    .about-text h2 {
      color: #e50914;
      font-size: 36px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .about-text p {
      color: #f4f4f4;
      font-size: 20px;
      line-height: 1.8;
      margin-bottom: 25px;
    }

    footer {
      background-color: #000;
      text-align: center;
      padding: 25px 10px;
      font-size: 14px;
      color: #999;
      margin-top: 80px;
    }

    @media (max-width: 768px) {
      .about-container {
        flex-direction: column;
        text-align: center;
      }

      .about-img {
        width: 90%;
        margin: 0 auto;
      }

      .hero h1 {
        font-size: 40px;
      }

      .hero p {
        font-size: 20px;
      }

      .cta-button {
        padding: 12px 25px;
        font-size: 16px;
      }

      .ceo-group-container {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">YG Entertainment</div>
    <nav>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#ceo-group">CEO & Kpop Idols</a></li>
        <li><a href="audition.php">Audition</a></li>
        <li><a href="message.php">Message</a></li>
        <li class="signup"><a href="login.php">Signup/Login</a></li>
      </ul>
    </nav>
  </header>

  <section id="home" class="hero">
    <h1>Become the Next K-pop Star</h1>
    <p>Dancer. Singer. Idol. This is your moment.</p>
    <a href="#audition" class="cta-button">Apply Now</a>
  </section>

  <section id="about">
    <div class="about-container">
      <div class="about-text">
        <h2>About YG Entertainment</h2>
        <p>
          YG Entertainment is a powerhouse in the K-pop industry, home to global acts like BLACKPINK, BIGBANG, and TREASURE.
          We are always on the lookout for the next star to rise — whether you're a singer, dancer, or all-around idol,
          this is your chance to shine.
        </p>
      </div>
      <div class="about-image">
        <img
          src="YG-Entertainments-Phenomenal-Success-Bringing-K-pop-To-A-Global-Scale.webp"
          alt="YG Entertainment Logo"
          class="about-img"
        />
      </div>
    </div>
  </section>

  <section id="ceo-group">
    <h2>Meet the CEO & Famous Kpop Idol</h2>
    <div class="ceo-card">
      <img src="YG.jpeg" alt="Yang Hyun-suk (CEO)" />
      <h3>Yang Hyun-suk</h3>
      <p>Founder & CEO of YG Entertainment. A visionary producer and talent scout who helped create the global K-pop wave.</p>
    </div>

    <div class="ceo-group-container">
      <div class="ceo-group-card">
        <img src="PINKCHELLA WALLPAPERS.jpg" />
        <h3>BLACKPINK</h3>
        <p>
          BLACKPINK is a South Korean girl group formed by YG Entertainment, known for blending fierce rap, powerful vocals,
          and stylish visuals. They're global icons in K-pop, breaking records and barriers with every release.
        </p>
      </div>
      <div class="ceo-group-card">
        <img src="th.jpeg" />
        <h3>BIGBANG</h3>
        <p>
          BIGBANG is a legendary K-pop boy band formed in 2006 by YG Entertainment. Known for their genre-blending music and
          iconic hits like "Fantastic Baby" and "BANG BANG BANG," they are pioneers in K-pop and have had a massive impact
          on music and pop culture.
        </p>
      </div>
      <div class="ceo-group-card">
        <img src="2NE1.jpeg" />
        <h3>2NE1</h3>
        <p>
          2NE1 was a groundbreaking K-pop girl group formed by YG Entertainment in 2009. Known for their bold style and
          hits like <strong>"I Am the Best"</strong> and <strong>"Fire"</strong>, they became icons in the K-pop industry
          before disbanding in 2016.
        </p>
      </div>
    </div>
  </section>

  <footer>
    &copy; 2025 YG Entertainment. All rights reserved.
  </footer>
</body>
</html>
