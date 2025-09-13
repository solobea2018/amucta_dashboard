<?php


namespace Solobea\Dashboard\view;


class MainLayout
{
    public static function render2($content,$header=null,$title=null)
    {
        $org_name="Amucta";
        $org_logo="/images/logo.png";
        $title=$title??$org_name;
        $menu =self::menu();
        $layout=<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$title}</title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/sweetalert2.css">
    <link rel="stylesheet" href="/css/toastify.css">
    <link rel="icon" href="$org_logo">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="/js/others.js" type="text/javascript"></script>
    <script src="/js/sweetalert2.js" type="text/javascript"></script>
    <script src="/js/toastify.js" type="text/javascript"></script>
    $header
</head>
<body>
    <section class="header p-4">
    $menu
</section>
    <section class="main p-4">$content</section>
    <section class="footer"></section>
</body>
</html>
HTML;
        echo $layout;

    }

    public static function render($content,$header=null,$title=null)
    {
        $org_name="Amucta";
        $title=$title??"AMUCTA - Archbishop Mihayo University College of Tabora";
        $org_logo="/logo.png";
        $title=$title??$org_name;
        $menu =self::menu();
        $layout=<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>$title</title>
  <script type="text/javascript" src="/js/main.js"></script>
  <link rel="stylesheet" href="/css/styles.css"/>
  <link rel="icon" href="$org_logo">
  $header
</head>
<body>
<!-- Header with Enhanced Branding and Improved Menu -->
<header id="header">
  <div class="header-top">
    <a href="#contact">Contact Us</a>
    <a href="#contact">Staff Portal</a>
    <a href="#contact">Online Application</a>
    <a href="#contact">Alumni</a>
  </div>
  <div class="header-container">
    <a href="#home" class="logo"><img src="/logo.png" alt="AMUCTA Logo"></a>
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    <a href="/" class="logo-caption">AMUCTA</a>
    <span class="logo-caption">⋮</span>
    <nav class="navigation-container" id="navigation-container">
      <div class="flex flex-row justify-end w-full"><span class="menu-close" id="menu-close">✖</span></div>
      <ul class="nav-menu" id="navigation">
        <li><a href="#home" class="mobile-main-link">Home</a></li>
        <li class="dropdown-container">
          <a href="#about" class="mobile-main-link">About Us</a>
          <ul class="submenu">
            <li><a href="#vision-mission">Vision & Mission</a></li>
            <li><a href="#history">History</a></li>
            <li><a href="#governance">Governance</a></li>
            <li><a href="#leadership">Leadership</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="#admissions" class="mobile-main-link">Admissions</a>
          <ul class="submenu">
            <li><a href="#apply">How to Apply</a></li>
            <li><a href="#requirements">Entry Requirements</a></li>
            <li><a href="#fees">Fees & Funding</a></li>
            <li><a href="#deadlines">Application Deadlines</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="#programmes" class="mobile-main-link">Academics</a>
          <ul class="submenu">
            <li><a href="#undergraduate">Undergraduate</a></li>
            <li><a href="#postgraduate">Postgraduate</a></li>
            <li><a href="#certificates">Certificates & Diplomas</a></li>
            <li><a href="#short-courses">Short Courses</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="#faculties" class="mobile-main-link">Faculties</a>
          <ul class="submenu">
            <li><a href="#business">Faculty of Business</a></li>
            <li><a href="#arts">Faculty of Arts</a></li>
            <li><a href="#science">Faculty of Science</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="#departments" class="mobile-main-link">Departments</a>
          <ul class="submenu">
            <li><a href="#accounting">Accounting & Finance</a></li>
            <li><a href="#human-resources">Human Resource Management</a></li>
            <li><a href="#entrepreneurship">Entrepreneurship</a></li>
            <li><a href="#project-management">Project Management</a></li>
          </ul>
        </li>
        <li><a href="#research" class="mobile-main-link">Research</a></li>
        <li><a href="#library" class="mobile-main-link">Library & Resources</a></li>
        <li class="dropdown-container">
          <a href="#student-life" class="mobile-main-link">Student Life</a>
          <ul class="submenu">
            <li><a href="#portal">Student Portal</a></li>
            <li><a href="#services">Student Services</a></li>
            <li><a href="#accommodation">Accommodation</a></li>
            <li><a href="#clubs">Clubs & Societies</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="#staff" class="mobile-main-link">Staff Area</a>
          <ul class="submenu">
            <li><a href="#directory">Staff Directory</a></li>
            <li><a href="#email">Email Access</a></li>
            <li><a href="#hr">HR Resources</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>

<!-- Main Content Area - Placeholder for Dynamic Content -->
<main>
  <div class="main-content">
    $content
  </div>
</main>

<!-- Enhanced Footer -->
<footer>
  <!-- Stats Section -->
  <section class="stats-section">
    <div class="stats-container">
      <h2 class="stats-title">AMUCTA in Numbers</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number" style="color: #007BFF;">7</div>
          <p class="stat-label">Programs</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #4F46E5;">2,207</div>
          <p class="stat-label">Students</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #059669;">76</div>
          <p class="stat-label">Employees</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #7C3AED;">4,087+</div>
          <p class="stat-label">Graduates</p>
        </div>
        <div class="stat-item visitor-stat">
          <div class="stat-number">{{ visitorStats.total }}+ <!-- Placeholder for dynamic total --></div>
          <p class="stat-label">Visitors (All-Time)</p>
          <div class="visitor-details">
            <p>📅 Today: <span style="font-weight: 600;">{{ visitorStats.today }}</span></p> <!-- Placeholder -->
            <p>📊 This Week: <span style="font-weight: 600;">{{ visitorStats.thisWeek }}</span></p> <!-- Placeholder -->
            <p>🌍 Top Locations:</p>
            <ul>
              <li>{{ location.country }} - {{ location.count }}</li> <!-- Placeholder for each location -->
              <!-- Repeat li for top locations -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Main -->
  <div class="footer-main">
    <div class="footer-grid">
      <div class="newsletter-section">
        <h1>Subscribe our newsletter to get update.</h1>
        <div class="newsletter-form">
          <input id="email" type="email" placeholder="Email Address">
          <button class="subscribe-btn">Subscribe</button>
        </div>
      </div>
      <div class="summary-section">
        <p class="quick-links">Quick Summary</p>
        <p>As a constituent college of SAUT, AMUCTA is committed to teaching, research, and consultancy services and operates according to the regulations governing the institution as stipulated in SAUT Charter</p>
        <p class="quick-links">Quick Link:</p>
        <div class="links">
          <a href="https://www.tcu.go.tz/">TCU</a>
          <a href="https://www.rita.go.tz/">RITA</a>
          <a href="https://www.necta.go.tz/">NECTA</a>
          <a href="https://www.nacte.go.tz/">NACTE</a>
          <a href="https://www.heslb.go.tz/">HESLB</a>
        </div>
      </div>
      <div class="contact-section">
        <p>Contact Address</p>
        <div class="contact-details">
          <p>Archbishop Mihayo University College of Tabora</p>
          <p>P. O. Box 801</p>
          <p>Tabora, Tanzania</p>
          <p>Tel: +255 734 966 674</p>
          <p>E-mail: amucta@amucta.ac.tz</p>
        </div>
      </div>
    </div>

    <div class="social-section">
      <a href="https://www.tiktok.com/@amucta_official"><img src="https://amucta.ac.tz/assets/images/tiktok-removebg-preview.png" alt="Tiktok"></a>
      <a href="https://www.instagram.com/amucta_official/"><img src="https://amucta.ac.tz/assets/images/insta.png" alt="Instagram"></a>
      <a href="https://www.threads.com/@amucta_official?hl=en"><img src="https://amucta.ac.tz/assets/images/thread-removebg-preview.png" alt="Threads"></a>
      <a href="https://www.facebook.com/amuctaOfficial"><img src="https://amucta.ac.tz/assets/images/facebook.png" alt="Facebook"></a>
      <a href="https://www.youtube.com/@amucta_official"><img src="https://amucta.ac.tz/assets/images/youtub-removebg-preview.png" alt="YouTube"></a>
    </div>

    <hr class="footer-hr">

    <div class="footer-bottom">
      <a href="#" class="footer-logo">AMUCTA <!-- Add <img src="logo.png" alt="AMUCTA Logo" style="height: 40px;"> if possible --></a>
      <p class="copyright">Copyright © 2010 - 2024 - Archbishop Mihayo University College of Tabora (AMUCTA)</p>
    </div>
  </div>
</footer>
</body>
</html>
HTML;
        echo $layout;

    }

    public static function menu(): string
    {
        return <<<menu
<div class="flex flex-row flex-wrap">
    <a href="/" class="btn btn-primary"><i class="bi bi-house mx-2"></i>Home</a>
    <a href="/faculty/list" class="btn btn-primary"><i class="bi bi-dash-circle mx-2"></i>Faculty</a>
    <a href="/department/list" class="btn btn-primary"><i class="bi bi-house mx-2"></i>Department/Unit</a>
    <a href="/Level/list" class="btn btn-primary"><i class="bi bi-graph-up mx-2"></i>Level</a>
    <a href="/program/list" class="btn btn-primary"><i class="bi bi-book mx-2"></i>Program</a>
    <a href="/news/list" class="btn btn-primary"><i class="bi bi-newspaper mx-2"></i>News</a>
    <a href="/events/list" class="btn btn-primary"><i class="bi bi-calendar-event mx-2"></i>Events</a>
    <a href="/attachment/list" class="btn btn-primary"><i class="bi bi-file mx-2"></i>Attachments</a>
    <a href="/employee-role/list" class="btn btn-primary"><i class="bi bi-file mx-2"></i>Employee Role</a>
    <a href="/employee/list" class="btn btn-primary"><i class="bi bi-people mx-2"></i>Employee</a>
    <a href="/employee-research/list" class="btn btn-primary"><i class="bi bi-people mx-2"></i>Employee Research</a>
    <a href="/users/list" class="btn btn-primary"><i class="bi bi-people mx-2"></i>Users</a>
    <a href="/gallery/list" class="btn btn-primary"><i class="bi bi-medium mx-2"></i>Gallery</a>
    <a href="/visitors/dashboard" class="btn btn-primary"><i class="bi bi-medium mx-2"></i>Visitors</a>
    <a href="/login" class="btn btn-primary"><i class="bi bi-arrow-bar-left mx-2"></i>Login</a>
    <a href="/logout" class="btn btn-primary"><i class="bi bi-arrow-bar-left mx-2"></i>Logout</a>
</div> 
menu;

    }
}