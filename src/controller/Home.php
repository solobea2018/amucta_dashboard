<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Home
{
    public function index()
    {
        $query="select id, name from program order by level_id,department_id";
        $db=new Database();
        $progs=$db->select($query);
        $intro=$this->intro($db);
        $news=$this->news($db);
        $prog_list="";
        if (!empty($progs)){
            foreach ($progs as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $prog_list.="<option value='$id'>{$name}</option>";
            }
        }
        $head="<link type='text/css' rel='stylesheet' href='/css/home.css'>";
        $content = <<<content
<section class="section">
  <div class="text-center">
    <h1 class="uni-title">Archbishop Mihayo University College of Tabora</h1>
    <a href="https://oas.amucta.ac.tz" target="_blank" class="apply-btn">🎓 Apply Now</a>
  </div>
</section>

<section class="gradient-card">
  <div class="gradient-inner">
    <div class="badge">Second Window • Bachelor Applicants</div>
    <h2>Admissions <span class="underline">OPEN</span> <span class="animate">NOW</span></h2>
    <p>Missed the first call? This is your moment. Limited slots, rolling review—earlier submissions get priority. Secure your spot before the window closes.</p>
    
    <div class="flex flex-row flex-wrap items-center justify-start">
    <div class="highlight-box">Diploma Programmes<br>Bachelor Programmes<br>Postgraduate Programmes</div>
    <div class="highlight-box">Mode<br><strong>Online Application</strong></div>
    <div class="highlight-box">Status<br><strong>OPEN</strong></div>
</div>

    <div class="deadline">
      <span class="deadline-dot"></span>
      <span>Closes: <strong>September 21, 2025</strong> • Don’t wait—applications are reviewed as they arrive.</span>
    </div>
  </div>
</section>

<div class="banner">
  Second Application Window is OPEN — limited seats, submit early for priority review.
  <span>September 21, 2025</span>
</div>

<section class="search-box">
  <h2>Find Your Program</h2>
  <form action="/programmes/search" method="post" class="search-form">
    <select name="course" required>
      <option value="" disabled selected>Select a course</option>
      $prog_list
    </select>
    <button type="submit">🔍 Search</button>
  </form>
</section>

$intro
$news
content;

        MainLayout::render($content,$head);
    }

    private function news(Database $database): string
    {
        $query="select name, file_url, created_at from attachments order by id desc limit 6";
        $attachments=$database->select($query);
        $attachments_list="";
        if (!empty($attachments)){
            foreach ($attachments as $prog) {
                $name=$prog['name'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $attachments_list.=<<<atta
            <li class="attachment-item">
                <h3 class="attachment-title">{$name}</h3>
                <div class="attachment-meta">
                    <a href="{$prog['file_url']}" class="download-link">📁 Download</a>
                    <span>|</span>
                    <span class="date">📅 {$date}</span>
                </div>
            </li>
atta;
            }
        }

        $query="select id, feature_image, name, content, created_at, expire, category, attachment from news order by created_at desc limit 4";
        $news=$database->select($query);
        $news_list="";
        if (!empty($news)){
            foreach ($news as $prog) {
                $name=$prog['name'];
                $feature_image=$prog['feature_image'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $content = $prog['content'];
                $expire  = $prog['expire'];
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="new-icon">';
                }
                $shortContent = mb_substr(strip_tags($content), 0, 100);
                if (strlen(strip_tags($content)) > 100) {
                    $shortContent .= "...";
                }
                $attachment =$prog['attachment']??"#";
                $news_list.=<<<atta
            <div class="news-item">
                <img src="$feature_image" class="news-img" alt="News">
                <div class="news-content">
                    <div class="news-title" onclick="popHtml('$name','$content')">{$name} $img</div>
                    <p class="news-desc">
                        {$shortContent} <a href="$attachment" class="read-more">Read More →</a>
                    </p>
                    <p class="news-date">📅 Posted on: {$date}</p>
                </div>
            </div>
atta;
            }
        }

        return<<<news
<div class="news-section">
    <!-- Latest Announcements -->
    <div>
        <h2 class="section-title">Download Center</h2>
        <ul class="attachments-list">
            $attachments_list
        </ul>
        <a href="/attachment/all" class="view-more">View More</a>
    </div>

    <!-- Latest News -->
    <div>
        <h2 class="section-title">Latest News</h2>
        <div class="news-list">
            $news_list
        </div>
        <div class="read-more-container">
            <a href="/news/all" class="read-more-btn">Read More News</a>
        </div>
    </div>
</div>
news;
    }
    private function intro(Database $database): string
    {
        $query="select id, name, start_date, end_date,location,feature_image from events order by start_date desc limit 4";
        $events=$database->select($query);
        $events_list="";
        if (!empty($events)){
            foreach ($events as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $feature_image=$prog['feature_image']??'https://www.heslb.go.tz/assets/css/assets_22/images/new.svg';
                $date = date("F d, Y", strtotime($prog['start_date']));

                $expire  = $prog['end_date']??$date;
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="event-new">';
                }
                $location =$prog['location']??"";
                $events_list.=<<<atta
            <li class="event-item">
                <div class="event-flex">
                    <img src="$feature_image" alt="event" class="event-img">
                    <div>
                        <a href="/events/detail/$id" class="event-title">{$name} - {$location}</a>
                        <div class="event-meta">
                            <i class="fa fa-clock-o"></i>
                            <span>{$date}</span>
                            $img
                        </div>
                    </div>
                </div>
                <hr class="event-divider">
            </li>
atta;
            }
        }

        return <<<intro
<div class="intro-section">
    <div class="intro-container">
        <div class="intro-grid">

            <!-- CEO's Remarks -->
            <div class="intro-col">
                <h3 class="intro-heading">AMUCTA's Remarks</h3>
                <div class="remarks">
                    <img src="/assets/images/asantemungu.jpg" alt="CEO" class="remarks-img">
                    <div>
                        <p class="remarks-text">
                            The Archbishop Mihayo University College of Tabora (AMUCTA) is a constituent college of St. Augustine University of Tanzania (SAUT). AMUCTA is an independent higher-learning institution governed by the Board of Trustees and the University Council under the Catholic Bishops of Tanzania (Tanzania Episcopal Conference).
                        </p>
                        <a href="/about" class="link-primary">Read more</a>
                    </div>
                </div>
            </div>

            <!-- News & Events -->
            <div class="intro-col">
                <h3 class="intro-heading">News & Events</h3>
                <ul class="events-list">
                    $events_list
                    <li>
                        <a href="/events/all" class="link-primary">Read More</a>
                    </li>
                </ul>
            </div>

            <!-- Shortcut Links -->
            <div class="intro-col">
                <h3 class="intro-heading">Shortcut Links</h3>
                <ul class="shortcuts">
                    <li>
                        <a href="https://oas.amucta.ac.tz" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Confirm Application
                        </a>
                        <a href="/assets/files/AMUCTA_JOIN_INSTRUCTION.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Joining Instruction 2025/2026
                        </a>
                        <a href="https://amucta.ac.tz/assets/files/Prospectus_2022-2023.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Prospectus
                        </a>
                        <a href="/assets/files/Fee_structure_2025-2026.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Fee Structure 2025-2026
                        </a>                      
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
intro;
    }

}