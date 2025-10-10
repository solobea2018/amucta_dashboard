<?php


namespace Solobea\Dashboard\view;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Helper;

class MainLayout
{
    public static function render($content,$header=null,$title=null)
    {
        $org_name="Amucta";
        $title=$title??"AMUCTA - Archbishop Mihayo University College of Tabora";
        $org_logo="/logo.png";
        $title=$title??$org_name;
        $menu=self::header();
        $auth=new Authentication();
        $menu_admin = $auth->is_admin() ? MainLayout::menu() : "";
        $csrf_token=self::generateCsrfToken();

        $layout= <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles_v1.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/sweetalert2.css">
    <link rel="stylesheet" href="/css/toastify.css">
    <link rel="stylesheet" href="/css/chat.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <script src="/js/others_v1.js" type="text/javascript"></script>
    <script src="/js/sweetalert2.js" type="text/javascript"></script>
    <script src="/js/toastify.js" type="text/javascript"></script>
    <title>$title</title>
    <script type="text/javascript" src="/js/main_v1.js" defer></script>
  <link rel="icon" href="$org_logo">
  $header
</head>
<body>
<!-- Header with Enhanced Branding and Improved Menu -->
<header id="header">
  <div class="header-top no-print">
    <a href="/contact">Contact Us</a>
    <a href="https://amucta.ac.tz:2096">Emails</a>
    <a href="/staff">Staff Portal</a>
    <a href="https://oas.amucta.ac.tz">Online Application</a>
    <a href="https://sims.amucta.ac.tz">SIMS</a>
    <a href="https://library.amucta.ac.tz">Library</a>
    <a href="/alumni">Alumni</a>
  </div>
  $menu
</header>

<!-- Main Content Area - Placeholder for Dynamic Content -->
<main>
$menu_admin
  <div class="main-content">
    {$content}
  </div>
</main>

<footer class="no-print">
  <!-- Stats Section -->
  

  <!-- Footer Main -->
  <div class="footer-main">
    <div class="footer-grid">
      <div class="newsletter-section">
        <h1>Subscribe our newsletter to get update.</h1>
        <form action="/subscription/save" method="post" onsubmit="sendFormSweet(this,event)" class="newsletter-form">
          <input id="email" type="email" placeholder="Email Address">
          <button class="subscribe-btn">Subscribe</button>
        </form>
        <div class="footer-cta">
            <a href="/donation/donate" class="cta-btn">💚 Donate Now</a>
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
      <a href="https://www.tiktok.com/@amucta_official" data-aos="fade-up" data-aos-duration="500"><img src="https://amucta.ac.tz/assets/images/tiktok-removebg-preview.png" alt="Tiktok"></a>
      <a href="https://www.instagram.com/amucta_official/" data-aos="fade-up" data-aos-duration="1000"><img src="https://amucta.ac.tz/assets/images/insta.png" alt="Instagram"></a>
      <a href="https://www.threads.com/@amucta_official?hl=en" data-aos="fade-up" data-aos-duration="1500"><img src="https://amucta.ac.tz/assets/images/thread-removebg-preview.png" alt="Threads"></a>
      <a href="https://www.facebook.com/amuctaOfficial" data-aos="fade-up" data-aos-duration="2000"><img src="https://amucta.ac.tz/assets/images/facebook.png" alt="Facebook"></a>
      <a href="https://www.youtube.com/@amucta_official" data-aos="fade-up" data-aos-duration="2500"><img src="https://amucta.ac.tz/assets/images/youtub-removebg-preview.png" alt="YouTube"></a>
    </div>

    <hr class="footer-hr">

    <div class="footer-bottom">
      <a href="#" class="footer-logo">AMUCTA</a>
      <p class="copyright">Copyright &copy; 2010 - 2024 - Archbishop Mihayo University College of Tabora (AMUCTA)</p>
    </div>
  </div>
</footer>
<!-- Floating Chat Icon and Window -->
<div id="chatbot-container" class="no-print">
  <div id="chat-window" class="chat-closed">
    <div class="chat-header">
      <span>💬 Niulize Mimi</span>
      <button id="chat-close-btn">&times;</button>
    </div>
    <div class="chat-body">
      <div class="chat-message bot">Hello 👋, how can I help you today?</div>
    </div>
    <div class="chat-footer">
      <input type="hidden" id="csrf_token" value="$csrf_token">
      <input type="text" id="chat-input" placeholder="Type your message..." />
      <button id="chat-send-btn" class="btn btn-amucta">Send</button>
    </div>
  </div>

  <div id="chat-icon">
    <i class="bi bi-chat-dots-fill"></i>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,   // animation duration in ms
    once: true,       // whether animation should happen only once
  });
</script>

</body>
</html>
HTML;
        echo $layout;

    }

    public static function menu(): string
    {
        return <<<menu
<div class="flex flex-row flex-wrap">
    <a href="/" class="btn btn-amucta"><i class="bi bi-house mx-2"></i>Home</a>
    <a href="/faculty/list" class="btn btn-amucta"><i class="bi bi-dash-circle mx-2"></i>Faculty</a>
    <a href="/department/list" class="btn btn-amucta"><i class="bi bi-house mx-2"></i>Department/Unit</a>
    <a href="/Level/list" class="btn btn-amucta"><i class="bi bi-graph-up mx-2"></i>Level</a>
    <a href="/program/list" class="btn btn-amucta"><i class="bi bi-book mx-2"></i>Program</a>
    <a href="/news/list" class="btn btn-amucta"><i class="bi bi-newspaper mx-2"></i>News</a>
    <a href="/events/list" class="btn btn-amucta"><i class="bi bi-calendar-event mx-2"></i>Events</a>
    <a href="/attachment/list" class="btn btn-amucta"><i class="bi bi-file mx-2"></i>Attachments</a>
    <a href="/home-content/list" class="btn btn-amucta"><i class="bi bi-file mx-2"></i>Homepage Contents</a>
    <a href="/employee-role/list" class="btn btn-amucta"><i class="bi bi-file mx-2"></i>Employee Role</a>
    <a href="/employee/list" class="btn btn-amucta"><i class="bi bi-people mx-2"></i>Employee</a>
    <a href="/employee-research/list" class="btn btn-amucta"><i class="bi bi-people mx-2"></i>Employee Research</a>
    <a href="/users/list" class="btn btn-amucta"><i class="bi bi-people mx-2"></i>Users</a>
    <a href="/gallery/list" class="btn btn-amucta"><i class="bi bi-medium mx-2"></i>Gallery</a>
    <a href="/visitors/dashboard" class="btn btn-amucta"><i class="bi bi-medium mx-2"></i>Visitors</a>
    <a href="/contact/list" class="btn btn-amucta"><i class="bi bi-medium mx-2"></i>Contacts</a>
    <a href="/contact/ai" class="btn btn-amucta"><i class="bi bi-medium mx-2"></i>AI chats</a>
    <a href="/errors/list" class="btn btn-amucta"><i class="bi bi-medium mx-2"></i>Logs</a>
    <a href="/login" class="btn btn-amucta"><i class="bi bi-arrow-bar-left mx-2"></i>Login</a>
    <a href="/logout" class="btn btn-amucta"><i class="bi bi-arrow-bar-left mx-2"></i>Logout</a>
</div> 
menu;

    }

    public static function header(): string
    {
        $db=new Database();
        $roles=$db->select("SELECT * FROM role_group order by name");
        $stf="";
        if (sizeof($roles)>0){
            foreach ($roles as $role) {
                $name=Helper::slugify($role['name']);
                $id=$role['id'];
                $stf.="<li><a href=\"/staff/amucta/{$name}/$id\">{$role['name']}</a></li>";
            }
        }
        $levels=$db->select("SELECT id,name FROM level");
        $lvs="";
        if (sizeof($levels)>0){
            foreach ($levels as $level) {
                $name=Helper::slugify($level['name']);
                $id=$level['id'];
                $lvs.="<li><a href=\"/programmes/level/{$name}/$id\">{$level['name']}</a></li>";
            }
        }
        $deps=$db->select("SELECT id,name FROM department where category='department' order by name");
        $dp="";
        if (sizeof($deps)>0){
            foreach ($deps as $dep) {
                $name=Helper::slugify($dep['name']);
                $id=$dep['id'];
                $dp.="<li><a href=\"/departments/department/{$name}/{$id}\">{$dep['name']}</a></li>";
            }
        }
        $fcts=$db->select("SELECT id,name FROM faculty where active=1 order by name");
        $ft="";
        if (sizeof($fcts)>0){
            foreach ($fcts as $dep) {
                $name=Helper::slugify($dep['name']);
                $id=$dep['id'];
                $ft.="<li><a href=\"/faculties/faculty/{$name}/{$id}\">{$dep['name']}</a></li>";
            }
        }
        return <<<header
<div class="header-container no-print">
    <a href="/" class="logo"><img src="/logo.png" alt="AMUCTA Logo"></a>
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    <a href="/" class="logo-caption">AMUCTA</a>
    <span class="logo-caption">⋮</span>
    <nav class="navigation-container" id="navigation-container">
      <div class="flex flex-row justify-end w-full"><span class="menu-close" id="menu-close">✖</span></div>
      <ul class="nav-menu" id="navigation">
        <a href="/" class="btn btn-amucta">Home</a>
        <li class="dropdown-container">
          <a href="/about" class="mobile-main-link">About</a>
          <ul class="submenu">
            <li><a href="/about/about">About us</a></li>
            <li><a href="/about/history">History</a></li>           
            <li><a href="/about/message">Vice Chancellor Message</a></li>
            <li><a href="/about/privacy">Privacy &amp; Policy</a></li>
            <li><a href="/about/terms">Terms &amp; Conditions</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="/admissions" class="mobile-main-link">Admissions</a>
          <ul class="submenu">
            <li><a href="/admissions/admission">How to Apply</a></li>
            <li><a href="/admissions/joining">Join Instructions</a></li>
            <li><a href="/admissions/requirements">Entry Requirements</a></li>
            <li><a href="/admissions/fees">Fees &amp; Funding</a></li>
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="/programmes" class="mobile-main-link">Academics</a>
          <ul class="submenu">
            $lvs
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="/faculties" class="mobile-main-link">Faculties</a>
          <ul class="submenu">
            {$ft}
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="/departments" class="mobile-main-link">Departments</a>
          <ul class="submenu">
            {$dp}
          </ul>
        </li>
        <li class="dropdown-container">
          <a href="/research" class="mobile-main-link">Research</a>
          <ul class="submenu">
            <li><a href="/research/research">Research Overview</a></li>
            <li><a href="/research/student_research">Student Research</a></li>
            <li><a href="/research/centers">Research Centers</a></li>
            <li><a href="/research/collaborations">Collaborations</a></li>                   
          </ul>
        </li>       
        <li class="dropdown-container">
          <a href="/library" class="mobile-main-link">ICT &amp; Other Services</a>
          <ul class="submenu">
            <li><a href="/library/ict">ICT Services</a></li>  
            <li><a href="/library/index">Library</a></li>
            <li><a href="/student-life/student">Student Life</a></li>
            <li><a href="/student-life/by_laws">Students By-laws</a></li>
            <li><a href="/student-life/dispensary">Medical Care</a></li>
            <li><a href="/student-life/accommodation">Accommodation</a></li>
            <li><a href="/student-life/others">Others</a></li>                    
          </ul>
        </li>       
        <li class="dropdown-container">
          <a href="/staff" class="mobile-main-link">Staff Area</a>
          <ul class="submenu">
            $stf
          </ul>
        </li>
      </ul>
    </nav>
  </div>
header;

    }
    private static function generateCsrfToken(): string
    {
        // Generate a random token
        $token = bin2hex(random_bytes(32)); // 32 bytes = 64 hex characters
        // Save it into session
        $_SESSION['csrf_token'] = $token;
        // Optional: store token generation time
        $_SESSION['csrf_token_time'] = time();
        return $token;
    }
}